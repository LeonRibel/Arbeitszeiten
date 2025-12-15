<?php

namespace App\Controller;

use App\Repositorys\PasswortVergessenRepository;
use App\View\ViewHelper;
use App\Service\Mail;
use DateTime;

class PasswortVergessenController
{
    private $repository;
    private Mail $mail;
    private ViewHelper $viewHelper;

    public function __construct()
    {
        $this->repository = new PasswortVergessenRepository();
        $this->mail = new Mail();
        $this->viewHelper = new ViewHelper();
    }

    #Token wird angefragt
    public function vergessen()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Nur POST erlaubt']);
            return;
        }

        // JSON-Body auslesen
        $input = json_decode(file_get_contents('php://input'), true);
        $username = $input['username'] ?? null;

        header('Content-Type: application/json');

        if (!$username) {
            http_response_code(400);
            echo json_encode(['message' => 'Username fehlt']);
            return;
        }

        // Benutzer aus DB holen
        $user = $this->repository->fetchUserByUsername($username);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['message' => 'User nicht gefunden']);
            return;
        }

        // Token generieren
        $token = bin2hex(random_bytes(16));
        $dt = new \DateTime();
        $dt->setTimestamp(time() + 3600);

        $this->repository->speicherToken($user['id'], $token, $dt);

        // E-Mail senden
        try {
            $this->mail->sendPasswordReset($user['email'], $user['username'], $token);
            echo json_encode(['message' => 'Token erfolgreich versendet']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'E-Mail konnte nicht versendet werden: ' . $e->getMessage()]);
        }
    }



    #Token wird geprüft
    public function tokenpruefen()
    {
        $token = $_GET['token'] ?? '';

        $record = $this->repository->fetchToken($token);

        #wenn Token falsch ist
        if (!$record) {
            $this->viewHelper->flash('Token existiert nicht', 'error');
            return $this->viewHelper->render("resetPassword", []);
        }

        #Überprüfung ob der Token noch gültig ist
        $abgelaufen = new DateTime($record["laeuft_ab_am"]);
        $jetzt = new DateTime();
        if ($abgelaufen < $jetzt) {
            $this->viewHelper->flash('Token ist abgelaufen', 'error');
            return $this->viewHelper->render("resetPassword", []);
        }

        $neuesPasswort = '';
        $passwortBestaetigen = '';
        if ($_SERVER['REQUEST_METHOD'] != "POST") {
            return $this->viewHelper->render("resetPassword", []);
        }


        $neuesPasswort = $_POST["password"] ?? '';
        $passwortBestaetigen = $_POST["password_bestaetigen"] ?? '';

        if (!$neuesPasswort || $neuesPasswort !== $passwortBestaetigen) {
            $this->viewHelper->flash('Passwort stimmt nicht überein oder wird bereits genutzt', 'error');
            return $this->viewHelper->render("resetPassword", []);
        } else {
            $user = $this->repository->fetchUserByUsernameById($record['mitarbeiter_id']);
            $username = $user['username'];
            try {
                $this->repository->neuesPasswort($record['mitarbeiter_id'], $neuesPasswort, $username);
            } catch (\Exception $e) {
                $this->viewHelper->flash('Fehler:' . $e->getMessage(), 'error');
            }
            $this->viewHelper->flash('Passwort update', 'success');
            header("Location: http://localhost:5173/settings");
            $this->repository->deleteToken($record["id"]);
            die();
        }
    }

    public function vergessenVonSettings()
    {
        session_start();
        $userId = $_SESSION['user_id'] ?? null;

        header('Content-Type: application/json');

        if (!$userId) {
            http_response_code(401);
            echo json_encode(['message' => 'Nicht angemeldet']);
            return;
        }

        $user = $this->repository->fetchUserByUsernameById($userId);

        if (!$user || empty($user['email'])) {
            http_response_code(404);
            echo json_encode(['message' => 'Keine E-Mail-Adresse gefunden']);
            return;
        }

        $token = bin2hex(random_bytes(16));
        $dt = new DateTime();
        $dt->setTimestamp(time() + 3600);
        $this->repository->speicherToken($user['id'], $token, $dt);

        try {
            $this->mail->sendPasswordReset($user['email'], $user['username'], $token);
            echo json_encode(['message' => 'Token erfolgreich versendet']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'E-Mail konnte nicht versendet werden: ' . $e->getMessage()]);
        }
    }
}
