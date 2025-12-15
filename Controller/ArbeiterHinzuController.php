<?php

namespace App\Controller;

use App\Repositorys\ArbeiterHinzuRepository;
use App\View\ViewHelper;
use App\Validator\Textvalidator;
use RuntimeException;

class ArbeiterHinzuController
{
    private ArbeiterHinzuRepository $repository;
    private ViewHelper $viewHelper;

    public function __construct()
    {
        $this->repository = new ArbeiterHinzuRepository();
        $this->viewHelper = new ViewHelper();
    }

    public function mitarbeiter()
    {
        $spalten = ['id', 'Vorname', 'Nachname', 'username'];
        $order = isset($_GET['order']) && in_array($_GET['order'], $spalten) ? $_GET['order'] : 'id';
        $sort = isset($_GET['sort']) && in_array(strtolower($_GET['sort']), ['asc', 'desc']) ? strtolower($_GET['sort']) : 'asc';

        $seite = isset($_GET['seite']) && is_numeric($_GET['seite']) ? (int)$_GET['seite'] : 1;
        $limit = 10;
        $offset = ($seite - 1) * $limit;

        $arbeiter = $this->repository->fetchArbeiter($order, $sort, $limit, $offset);
        $gesamtEintraege = $this->repository->countArbeiter();
        $seitenAnzahl = ceil($gesamtEintraege / $limit);

        return $this->viewHelper->render('mitarbeiter', [
            'Arbeiter' => $arbeiter,
            'order' => $order,
            'sort' => $sort,
            'seite' => $seite,
            'seitenAnzahl' => $seitenAnzahl
        ]);
    }

    public function addArbeiter()
    {
        $vorhandene_daten = [];
        $errors = [];

        $textValidator = new Textvalidator();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $vorname = $_POST['Vorname'] ?? '';
            $nachname = $_POST['Nachname'] ?? '';
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? null;

            try {
                $vorname = $textValidator->validate('Vorname', 2, 100);
            } catch (RuntimeException $e) {
                $errors[] = $e->getMessage();
                $this->viewHelper->flash($e->getMessage());
            }

            try {
                $nachname = $textValidator->validate('Nachname', 2, 100);
            } catch (RuntimeException $e) {
                $errors[] = $e->getMessage();
                $this->viewHelper->flash($e->getMessage());
            }

            try {
                $username = $textValidator->validate('username', 3, 50);
            } catch (RuntimeException $e) {
                $errors[] = $e->getMessage();
                $this->viewHelper->flash($e->getMessage());
            }

            if ($password !== null && $password !== '') {
                try {
                    $password = $textValidator->validate('password', 3, 255);
                } catch (RuntimeException $e) {
                    $errors[] = $e->getMessage();
                    $this->viewHelper->flash($e->getMessage());
                }
            } else {
                $password = null;
            }


            $vorhandene_daten = [
                'Vorname' => $vorname,
                'Nachname' => $nachname,
                'username' => $username,
            ];

            if (empty($errors)) {
                if ($id) {
                    $this->repository->updateArbeiter((int)$id, $vorname, $nachname, $username, $password);
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Mitarbeiter erfolgreich aktualisiert']);
                    exit;
                } else {
                    if ($password === null) {
                        $errors[] = 'Passwort darf nicht leer sein';
                        $this->viewHelper->flash('Passwort darf nicht leer sein');
                    } else {
                        $this->repository->addArbeiter($vorname, $nachname, $username, $password);
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'message' => 'Mitarbeiter erfolgreich hinzugefügt']);
                        exit;
                    }
                }

                if (empty($errors)) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true]);
                    exit;
                }
            }

            // Bei Fehlern JSON zurückgeben wenn POST-Request
            if (!empty($errors)) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => implode(', ', $errors), 'errors' => $errors]);
                exit;
            }
        } else {
            if (!empty($_GET['id'])) {
                $arbeiter = $this->repository->getArbeiterById((int)$_GET['id']);
                $vorhandene_daten = $arbeiter ?: [];
                $vorhandene_daten['password'] = '';
            }
        }

        return $this->viewHelper->render('MitarbeiterHinzufugen', [
            'vorhandene_daten' => $vorhandene_daten,
            'errors' => $errors
        ]);
    }
    public function deleteArbeiter(int $id)
    {
        $this->repository->deleteArbeiter($id);
        header('Location: ?action=mitarbeiter');
        exit;
    }
}
