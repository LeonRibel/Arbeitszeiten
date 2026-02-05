<?php

namespace App\Controller;

use App\Repositorys\UeberstundenRepository;
use App\ValueObject\ArbeitsJahr;
use App\ValueObject\ArbeitsJahrCollection;
use App\ValueObject\Arbeitstag;
use App\View\ViewHelper;
use DateTime;

class UeberstundenController
{
    private $repository;

    private ViewHelper $viewHelper;

    public function __construct()
    {
        $this->repository = new UeberstundenRepository();
        $this->viewHelper = new ViewHelper();
    }


    public function auslesen(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $id = $_SESSION['user'] ?? null;

            $arbeitszeiten = $this->repository->fetchArbeitszeitenById($id);

            foreach ($arbeitszeiten as $eintrag) {
                $eintrag['Start_von'] = new \DateTime($eintrag['Start_von']);
                $eintrag['Ende_bis'] = new \DateTime($eintrag['Ende_bis']);
            }
        }
    }

    public function arbeitstageZaehlen()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = $_SESSION['user'] ?? null;
            $Start_von = $_GET['Start_von'] ?? null;
            $Ende_bis = $_GET['Ende_bis'] ?? null;
        }

        $arbeitszeiten = $this->repository->arbeitstageZaehlen($id, $Start_von, $Ende_bis,);

        $Start_von = new \DateTime($_GET['Start_von']);
        $Ende_bis = new \DateTime($_GET['Ende_bis']);

        return $this->viewHelper->render("Ueberstunden", ['arbeitszeiten' => $arbeitszeiten]);
    }

    public function Ueberstunden()
    {
        $id = $_SESSION['user'] ?? null;

        $arbeitszeiten = [];
        $wochen = [];
        $ueberstundenProMonat = [];
        $ueberstundenProJahr = [];
        $wochenStunden = 0;

        if (!$id) {
            throw new \Exception('Not Logged In');
        }

        $stunden = $this->repository->fetchArbeitszeitenById($id);

        $arbeitszeiten = ArbeitsJahrCollection::fromArray($stunden);

        return $this->viewHelper->render(
            "Ueberstunden",
            [
                'arbeitszeiten' => $arbeitszeiten,
                'ueberstundenProMonat' => $ueberstundenProMonat,
                'ueberstundenProJahr' => $ueberstundenProJahr,
                'wochen' => $wochen,
            ]
        );
    }
}
