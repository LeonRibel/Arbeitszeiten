<?php

namespace App\Repositorys;

use PDO;

class ProfilRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO(
            "mysql:host=localhost;dbname=Arbeitszeiterfassung",
            "LeonRibel",
            "Test123",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public function getProfilMitDetailsById(int $id): array
    {
        $stmt = $this->db->prepare("
            SELECT id, Vorname, Nachname, username, Urlaubstage
            FROM Arbeiter
            WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function getUrlaubstage(int $id): array
    {
        $stmt = $this->db->prepare(
            "SELECT SUM(Tage) as Tage
            FROM Urlaub
            WHERE Mitarbeiter_id = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function getFehlzeiten(int $id): array
    {
        $stmt = $this->db->prepare(
            "SELECT SUM(Tage) as Fehltage 
            FROM Fehlzeiten
            WHERE Mitarbeiter_id = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }


    public function updateName(int $id, string $Vorname, string $Nachname): bool
    {
        $stmt = $this->db->prepare("
            UPDATE Arbeiter
            SET Vorname = :vorname, Nachname = :nachname
            WHERE id = :id
        ");
        return $stmt->execute([
            'vorname' => $Vorname,
            'nachname' => $Nachname,
            'id' => $id
        ]);
    }
}
