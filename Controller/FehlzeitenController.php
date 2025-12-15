<?php

namespace App\Controller;

use App\Enum\FehlzeitenStatus;
use App\Repositorys\ArbeiterHinzuRepository;
use App\Repositorys\FehlzeitenRepository;
use App\View\ViewHelper;
use DateTime;
use App\Validator\Filevalidator;
use App\Validator\DatetimeValidator;
use RuntimeException;

class FehlzeitenController
{
    private FehlzeitenRepository $repository;
    private ArbeiterHinzuRepository $arbeiterRepo;
    private ViewHelper $viewHelper;

    public function __construct()
    {
        $this->arbeiterRepo = new ArbeiterHinzuRepository();
        $this->repository = new FehlzeitenRepository();
        $this->viewHelper = new ViewHelper();
    }

    public function Fehlzeiten()
    {
        $spalten = ['Fehlzeiten_id', 'Vorname', 'Krankheit_start', 'Krankheit_ende', 'status', 'Tage'];
        $order = $_GET['order'] ?? 'Fehlzeiten_id';
        $order = in_array($order, $spalten, true) ? $order : 'Fehlzeiten_id';

        $sort = strtolower($_GET['sort'] ?? 'asc');
        $sort = in_array($sort, ['asc', 'desc'], true) ? $sort : 'asc';

        $seite = isset($_GET['seite']) && is_numeric($_GET['seite']) ? (int)$_GET['seite'] : 1;
        $limit = 10;
        $offset = ($seite - 1) * $limit;

        $fehlzeiten = $this->repository->fetchAllFehlzeiten($order, $sort, $limit, $offset);
        $gesamtEintraege = $this->repository->countAlleFehlzeiten();
        $seitenAnzahl = ceil($gesamtEintraege / $limit);


        $monate = [
            0 => 'Januar',
            1 => 'Februar',
            2 => 'März',
            3 => 'April',
            4 => 'Mai',
            5 => 'Juni',
            6 => 'Juli',
            7 => 'August',
            8 => 'September',
            9 => 'Oktober',
            10 => 'November',
            11 => 'Dezember',
        ];

        $fehlzeitenProMonat = [];

        foreach ($fehlzeiten as &$eintrag) {
            $start = new DateTime($eintrag['Krankheit_start']);
            $ende  = new DateTime($eintrag['Krankheit_ende']);
            $tage = $this->repository->berechneFehlzeiten($start, $ende);
            $eintrag['Tage'] = $tage;

            $monat = (int)$start->format('n');
            if (!isset($fehlzeitenProMonat[$monat])) {
                $fehlzeitenProMonat[$monat] = 0;
            }
            $fehlzeitenProMonat[$monat] += $tage;
        }
        unset($eintrag);


        foreach ($fehlzeiten as &$eintrag) {
            $start = new DateTime($eintrag['Krankheit_start']);
            $ende  = new DateTime($eintrag['Krankheit_ende']);
            $eintrag['Tage'] = $this->repository->berechneFehlzeiten($start, $ende);
        }
        unset($eintrag);




        return $this->viewHelper->render('Fehlzeiten', [
            'fehlzeiten' => $fehlzeiten,
            'order' => $order,
            'sort' => $sort,
            'seite' => $seite,
            'seitenAnzahl' => $seitenAnzahl,
            'fehlzeitenProMonat' => $fehlzeitenProMonat,
            ''
        ]);
    }


    public function Monat()
    {
        $monat = isset($_GET['monat']) ? (int)$_GET['monat'] : 0;

        if ($monat < 1 || $monat > 12) {
            header('Location: /Fehlzeiten');
            exit;
        }

        $alleFehlzeiten = $this->repository->fetchAlleFehlzeitenOhneLimit();

        $fehlzeitenDesMonats = [];
        foreach ($alleFehlzeiten as $eintrag) {
            $start = new DateTime($eintrag['Krankheit_start']);
            if ((int)$start->format('n') === $monat) {
                $eintrag['Tage'] = $this->repository->berechneFehlzeiten($start, new DateTime($eintrag['Krankheit_ende']));
                $fehlzeitenDesMonats[] = $eintrag;
            }
        }

        return $this->viewHelper->render('FehlzeitenMonat', [
            'fehlzeiten' => $fehlzeitenDesMonats,
            'monat' => $monat,
        ]);
    }

    public function bearbeiten()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mitarbeiterId = (int)($_SESSION['user'] ?? 0);
            $id = $_POST['id'] ?? null;

            $validator = new DatetimeValidator();
            try {
                [$start, $ende] = $validator->datevalidate('Krankheit_start', 'Krankheit_ende');
            } catch (RuntimeException $e) {
                $this->viewHelper->flash($e->getMessage());
                header('Location: /Fehlzeiten');
                exit;
            }
            $errors = $validator->getErrors();

            if (empty($errors) && $mitarbeiterId) {
                if ($id) {
                    $this->repository->FehltageBearbeiten($id, $start, $ende);
                } else {
                    $this->repository->addFehltag($mitarbeiterId, $start, $ende);
                }
                header('Location: /Fehlzeiten');
                exit;
            }
        }

        $vorhandene_daten = [];
        if (!empty($_GET['id'])) {
            $fehltag = $this->repository->FetchFehlTageById((int)$_GET['id']);
            if ($fehltag) {
                $vorhandene_daten = $fehltag;
            }
        }

        return $this->viewHelper->render('updateFehlzeiten', [
            'vorhandene_daten' => $vorhandene_daten,
        ]);
    }

    public function genehmigen()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->repository->statusAendern($id, FehlzeitenStatus::GENEHMIGT);
        }
        header('Location: /Fehlzeiten');
        exit;
    }

    public function ablehnen()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->repository->statusAendern($id, FehlzeitenStatus::ABGELEHNT);
        }
        header('Location: /Fehlzeiten');
        exit;
    }


    public function upload()
    {
        $id = (int)($_GET['id'] ?? 0);
        $fehlzeit = $this->repository->FetchFehlTageById($id);

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new Filevalidator();

            if ($validator->validate('datei', ['application/pdf', 'image/png'])) {
                $neuerDateiname = time() . '_' . $_FILES['datei']['name'];
                $ziel = __DIR__ . '/../uploads/' . $neuerDateiname;
                move_uploaded_file($_FILES['datei']['tmp_name'], $ziel);
                $this->repository->statusAendern($id, \App\Enum\FehlzeitenStatus::ABGELEHNT);
            } else {
                $errors = $validator->getErrors();
                $this->viewHelper->flash($errors, 'warning');
            }
        }

        return $this->viewHelper->render('upload', [
            'fehlzeit' => $fehlzeit,
            'id' => $id,
            'errors' => $errors
        ]);
    }
}
 // insert in file tabelle
            // dateiname = insert['id'] . pdf