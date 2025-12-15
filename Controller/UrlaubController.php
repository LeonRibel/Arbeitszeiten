<?php

namespace App\Controller;

use App\Enum\UrlaubsStatus;
use App\Repositorys\ArbeiterHinzuRepository;
use App\Repositorys\ArbeiterRepository;
use App\Repositorys\UrlaubRepository;
use App\View\ViewHelper;
use DateTime;
use App\Validator\DatetimeValidator;
use RuntimeException; 



class UrlaubController
{
    private UrlaubRepository $repository;
    private ArbeiterHinzuRepository $arbeiterRepo;
    private ViewHelper $viewHelper;



    public function __construct()
    {

        $this->arbeiterRepo = new ArbeiterHinzuRepository();
        $this->repository = new UrlaubRepository();
        $this->viewHelper = new ViewHelper();
    }
    public function Urlaub()
    {
        $spalten = ['Urlaub_id', 'Vorname', 'urlaub_start', 'urlaub_ende', 'status', 'Tage'];
        $order = isset($_GET['order']) && in_array($_GET['order'], $spalten) ? $_GET['order'] : 'Urlaub_id';
        $sort = isset($_GET['sort']) && in_array(strtolower($_GET['sort']), ['asc', 'desc']) ? strtolower($_GET['sort']) : 'asc';

        $seite = isset($_GET['seite']) && is_numeric($_GET['seite']) ? (int)$_GET['seite'] : 1;
        $limit = 10;
        $offset = ($seite - 1) * $limit;

        $urlaub = $this->repository->fetchAllUrlaub($order, $sort, $limit, $offset);
        $urlaubgeplant = $this->repository->fetchAllesUrlaub();
        $gesamtEintraege = $this->repository->countAlleUrlaube();
        $seitenAnzahl = ceil($gesamtEintraege / $limit);

        $arbeiter = $this->arbeiterRepo->getArbeiterById($_SESSION['user']);

        $urlaubsanspruchGesamt = $arbeiter['Urlaubstage'];
        $urlaubsGeplant = $urlaubsanspruchGesamt;

        foreach($urlaubgeplant as $urlaubseintrag) {
             if ($urlaubseintrag['status'] == UrlaubsStatus::ANGEFRAGT->value||$urlaubseintrag['status'] == UrlaubsStatus::GENEHMIGT->value){
                $urlaubsGeplant -= $urlaubseintrag['Tage'];
            }

            if ($urlaubseintrag['status'] == UrlaubsStatus::GENEHMIGT->value){
                $urlaubsanspruchGesamt -= $urlaubseintrag['Tage'];
            }
        }

        return $this->viewHelper->render('Urlaub', [
            'urlaub' => $urlaub,
            'order' => $order,
            'sort' => $sort,
            'seite' => $seite,
            'seitenAnzahl' => $seitenAnzahl,
            'urlaubsanspruchGesamt' => $urlaubsanspruchGesamt,
            'urlaubsGeplant' => $urlaubsGeplant
        ]);
    }

    public function bearbeiten()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $vorname = (int)($_SESSION['user'] ?? 0);
                $id = $_POST['id'] ?? null;

                $validator = new DatetimeValidator();
                try {
                    [$start, $ende] = $validator->datevalidate('urlaub_start', 'urlaub_ende');
                }   catch(RuntimeException $e) {
                    $this->viewHelper->flash($e->getMessage());
                    header('Location: /Urlaub');
                    exit;
                } 
                $errors = $validator->getErrors();

                if (empty($errors) && $vorname) {
                    if ($id) {
                        $this->repository->urlaubBearbeiten($id, $start, $ende);
                    } else {
                        $this->repository->addUrlaub($vorname, $start, $ende, UrlaubsStatus::ANGEFRAGT);
                    }
                    header('Location: /Urlaub');
                    exit;
                }
            }

        $vorhandene_daten = [];
        if (!empty($_GET['id'])) {
            $urlaub = $this->repository->fetchUrlaubById((int)$_GET['id']);
            if ($urlaub) {
                $vorhandene_daten = $urlaub;
            }
        }
        return $this->viewHelper->render('updateUrlaub', [
            'vorhandene_daten' => $vorhandene_daten
        ]);
    }

    public function genehmigen()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->repository->statusAendern($id, UrlaubsStatus::GENEHMIGT);
        }
        header('Location: /Urlaub');
        exit;
    }

    public function ablehnen()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->repository->statusAendern($id, UrlaubsStatus::ABGELEHNT);
        }
        header('Location: /Urlaub');
        exit;
    }
}
