<?php

namespace App\Repositorys;

use DateTime;
use PDO;

class PasswortVergessenRepository
{
    private $db;
    public function __construct()
    {
        $this->db = $this->connect();
    }

    function connect()
    {
        $hostname = 'localhost';
        $username = 'LeonRibel';
        $datapassword = 'Test123';

        $db = new PDO("mysql:host=localhost;dbname=Arbeitszeiterfassung", $username, $datapassword);
        return $db;
    }

    public function fetchUserByUsernameById(int $id)
    {
        $stmt = $this->db->prepare("SELECT id, username, email FROM Arbeiter WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    #User mit Username finden
    public function fetchUserByUsername(string $username)
    {
        $stmt = $this->db->prepare("SELECT username, id, email FROM Arbeiter WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(mode: PDO::FETCH_ASSOC);
        return $user;
    }

    #Token speichern
    public function speicherToken(int $mitarbeiter_id, string $token, DateTime $läuft_ab_am): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO Token (mitarbeiter_id, token, laeuft_ab_am) VALUES (:mitarbeiter_id, :token, :expiring)"
        );
        return $stmt->execute(['mitarbeiter_id' => $mitarbeiter_id, 'token'=> $token, 'expiring'=> $läuft_ab_am->format("Y-m-d H:i:s")]);
    }

    #Token abfragen
    public function fetchToken(string $token)
    {
        $stmt = $this->db->prepare("SELECT * FROM Token WHERE token = ?");
        $stmt->execute([$token]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    #Token löschen
    public function deleteToken(int $id)
    {
        $stmt = $this->db->prepare("DELETE FROM Token WHERE id = ?");
        $stmt->execute([$id]);
    }

    #Token neues Passwort
    public function neuesPasswort(int $mitarbeiter_id, string $password, string $username) 
    {

        $hashedPassword = crypt($password, $username);


        $stmt = $this->db->prepare("UPDATE  Arbeiter SET password = ? WHERE id = ?");
        if(!$stmt->execute([$hashedPassword,$mitarbeiter_id])) {
            throw new \Exception(json_encode($stmt->errorInfo()));
        }

    }
}
