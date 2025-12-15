<?php

namespace App\Controller;

use App\ValueObject\Session;

use App\Repositorys\ProfilRepository;

class MeinProfilController
{

    private ProfilRepository $repository;

    public function __construct()
    {
        $this->repository = new ProfilRepository();
    }

    public function Profilinfo()
    {
        $profil = $this->repository->getProfilMitDetailsById(Session::user());

        $urlaub = $this->repository->getUrlaubstage(Session::user());
        $genommeneTage = (int)($urlaub['Tage']);

        $profil['Resturlaub'] = $profil['Urlaubstage'] - $genommeneTage;

        $Fehltage = $this->repository->getFehlzeiten(Session::user());
        $profil['Fehltage'] = (int)($Fehltage['Fehltage'] ?? 0);

        $user = array_merge($profil, $urlaub);

        $viewHelper = new \App\View\ViewHelper();
        echo $viewHelper->render('MeinProfil', ['profil' => [$user], 'user' => $user]);
    }

    public function Profilupdate()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $Vorname = $_POST['Vorname'] ?? '';
            $Nachname = $_POST['Nachname'] ?? '';
            $Username = $_POST['username'] ?? '';

            if ($Vorname && $Nachname && $Username) {
                $this->repository->updateName(Session::user(), $Vorname, $Nachname);
            }

            header("Location: MeinProfil");
            exit;
        }

        $user = $this->repository->getProfilMitDetailsById(Session::user());
        $viewHelper = new \App\View\ViewHelper();
        echo $viewHelper->render('Profilupdate', ['user' => $user]);
    }
}
