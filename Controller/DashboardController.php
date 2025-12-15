<?php

namespace App\Controller;

use App\Repositorys\ArbeitszeitenRepository;
use App\View\ViewHelper;
use App\Validator\Textvalidator;
use App\Validator\DatetimeValidator;
use RuntimeException;





class DashboardController
{
    private ArbeitszeitenRepository $repository;
    private ViewHelper $viewHelper;

    public function __construct()
    {
        $this->repository = new ArbeitszeitenRepository();
        $this->viewHelper = new ViewHelper();
    }

    public function index()
    {
        $spalten = ['id', 'Start_von', 'Ende_bis', 'Aufgaben', 'Vorname', 'Nachname'];
        $order = (isset($_GET['order']) && in_array($_GET['order'], $spalten)) ? $_GET['order'] : 'id';
        $sort = (isset($_GET['sort']) && in_array($_GET['sort'], ['asc', 'desc'])) ? $_GET['sort'] : 'ASC';

        $kalenderwoche = isset($_GET['kw']) ? (int)$_GET['kw'] : (int)date('W');
        $kalenderwoche = max(1, min(53, $kalenderwoche));

        $naechsteKW = ($kalenderwoche === 53) ? 1 : $kalenderwoche + 1;
        $vorherigeKW = ($kalenderwoche === 1) ? 53 : $kalenderwoche - 1;

        $seite = (isset($_GET['seite']) && is_numeric($_GET['seite'])) ? (int)$_GET['seite'] : 1;
        $naechsteSeite = $seite + 1;
        $vorherSeite = max(1, $seite - 1);

        $limit = 10;
        $offset = ($seite - 1) * $limit;

        $arbeitszeiten = $this->repository->fetchArbeitszeiten($order, $sort, $limit, $offset, $kalenderwoche);

        $wochentage = [
            1 => 'Montag',
            2 => 'Dienstag',
            3 => 'Mittwoch',
            4 => 'Donnerstag',
            5 => 'Freitag',
            6 => 'Samstag',
            7 => 'Sonntag'
        ];

        $arbeitszeitenNachTag = [];
        $letztesEndeProTag = [];

        foreach ($arbeitszeiten as $arbeitszeit) {
            $tagNummer = date('N', strtotime($arbeitszeit['Start_von']));
            $tag = $wochentage[$tagNummer];

            //Berechnung zeit des tages
            $start = strtotime($arbeitszeit['Start_von']);
            $ende = strtotime($arbeitszeit['Ende_bis']);
            $zeitInSekunden = $ende - $start;
            $ZeitStunden = floor($zeitInSekunden / 3600);
            $ZeitMinuten = floor(($zeitInSekunden % 3600) / 60);
            $arbeitszeit['ZeitGesamt'] = ($ZeitStunden > 0 ? $ZeitStunden . 'h ' : '') . $ZeitMinuten . 'min';
            $arbeitszeit['IstPause'] = false;

            // Pausen-Zeile einfügen
            if (isset($letztesEndeProTag[$tag]) && $start > $letztesEndeProTag[$tag]) {
                $pauseInSekunden = $start - $letztesEndeProTag[$tag];
                $pauseStunden = floor($pauseInSekunden / 3600);
                $pauseMinuten = floor(($pauseInSekunden % 3600) / 60);
                $pauseText = ($pauseStunden > 0 ? $pauseStunden . 'h ' : '') . $pauseMinuten . 'min';

                $arbeitszeitenNachTag[$tag][] = [
                    'IstPause' => true,
                    'PauseText' => $pauseText
                ];
            }

            $letztesEndeProTag[$tag] = $ende;
            $arbeitszeitenNachTag[$tag][] = $arbeitszeit;
        }

        $gesamtZeitInSekunden = 0;
        foreach (['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag'] as $tag) {
            if (!empty($arbeitszeitenNachTag[$tag])) {
                foreach ($arbeitszeitenNachTag[$tag] as $arbeitszeit) {
                    $start = strtotime($arbeitszeit['Start_von']);
                    $ende = strtotime($arbeitszeit['Ende_bis']);
                    $gesamtZeitInSekunden += ($ende - $start);
                }
            }
        }

        $gesamtStunden = floor($gesamtZeitInSekunden / 3600);
        $gesamtMinuten = floor(($gesamtZeitInSekunden % 3600) / 60);
        $ZeitGesamt = ($gesamtStunden > 0 ? $gesamtStunden . 'h ' : '') . $gesamtMinuten . 'min';

        $gesamtEintraege = $this->repository->countArbeitszeiten();
        $seitenAnzahl = ceil($gesamtEintraege / $limit);

        return $this->viewHelper->render('dashboard', [
            'order' => $order,
            'sort' => $sort,
            'vorherSeite' => $vorherSeite,
            'naechsteSeite' => $naechsteSeite,
            'seitenAnzahl' => $seitenAnzahl,
            'arbeitszeitenNachTag' => $arbeitszeitenNachTag,
            'kalenderwoche' => $kalenderwoche,
            'vorherigeKW' => $vorherigeKW,
            'naechsteKW' => $naechsteKW,
            'gesamtZeitInSekunden' => $gesamtZeitInSekunden,
            'ZeitGesamt' => $ZeitGesamt
        ]);
    }

  public function update()
{
    $vorhandene_daten = [];
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $start_von = $_POST['Start_von'] ?? '';
        $ende_bis  = $_POST['Ende_bis'] ?? '';
        $aufgaben  = $_POST['Aufgaben'] ?? '';
        $arbeiter_id = $_SESSION['user'] ?? null;
        $id = $_POST['id'] ?? null;

        $textValidator = new Textvalidator();
        $datetimeValidator = new DatetimeValidator();

        try {
            $textValidator->validate('Aufgaben', 3, 255);
        } catch (RuntimeException $e) {
            $errors[] = $e->getMessage();
            $this->viewHelper->flash($e->getMessage());
        }

        try {
            [$startDate, $endeDate] = $datetimeValidator->datevalidate('Start_von', 'Ende_bis');
        } catch (RuntimeException $e) {
            $errors[] = $e->getMessage();
            $this->viewHelper->flash($e->getMessage());
        }

        $vorhandene_daten = [
            'Start_von' => $start_von,
            'Ende_bis'  => $ende_bis,
            'Aufgaben'  => $aufgaben
        ];

        if (empty($errors) && $arbeiter_id) {
            if ($id) {
                $this->repository->update($start_von, $ende_bis, $aufgaben, $arbeiter_id, (int)$id);
            } else {
                $this->repository->insert($start_von, $ende_bis, $aufgaben, $arbeiter_id);
            }
            header('Location: /');
            exit;
        }
    } else {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = (int)$_GET['id'];
            $arbeitszeit = $this->repository->fetchbyId($id);
            $vorhandene_daten = $arbeitszeit ?: [];
        } else {
            $vordefiniertesDatum = $_GET['datum'] ?? date('Y-m-d');
            $vorhandene_daten = [
                'Start_von' => $vordefiniertesDatum,
                'Ende_bis'  => $vordefiniertesDatum,
                'Aufgaben'  => ''
            ];
        }
    }

    return $this->viewHelper->render('update', [
        'vorhandene_daten' => $vorhandene_daten,
        'errors' => $errors
    ]);
}

    public function delete()
    {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = (int)$_GET['id'];
            $this->repository->deleteZeit($id);
        }

        header('Location: /Arbeitszeiten');
    }
}
