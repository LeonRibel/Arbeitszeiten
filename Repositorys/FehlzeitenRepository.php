<?php

namespace App\Repositorys;

use App\Enum\FehlzeitenStatus;
use DateTime;
use PDO;

class FehlzeitenRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = $this->connect();
    }

    private function connect(): PDO
    {
        $hostname = 'localhost';
        $username = 'LeonRibel';
        $datapassword = 'Test123';

        return new PDO("mysql:host=$hostname;dbname=Arbeitszeiterfassung", $username, $datapassword, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }


    public function fetchAlleFehlzeitenOhneLimit(): array
    {
        $stmt = $this->db->prepare("
        SELECT Fehlzeiten.*, Arbeiter.Vorname, Arbeiter.Nachname 
        FROM Fehlzeiten 
        JOIN Arbeiter ON Fehlzeiten.Mitarbeiter_id = Arbeiter.id
        ORDER BY Krankheit_start ASC
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchAllFehlzeiten(string $order, string $sort, int $limit, int $offset): array
    {
        $stmt = $this->db->prepare("
            SELECT Fehlzeiten.*, Arbeiter.Vorname, Arbeiter.Nachname 
            FROM Fehlzeiten 
            JOIN Arbeiter ON Fehlzeiten.Mitarbeiter_id = Arbeiter.id
            ORDER BY $order $sort
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAlleFehlzeiten(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM Fehlzeiten");
        return (int)$stmt->fetchColumn();
    }

    public function berechneFehlzeiten(DateTime $start, DateTime $ende): int
    {
        $interval = $start->diff($ende);
        return (int)$interval->format('%a') + 1;
    }

    public function addFehltag(int $Mitarbeiter_id, DateTime $Krankheit_start, DateTime $Krankheit_ende, FehlzeitenStatus $status = FehlzeitenStatus::GENEHMIGT): void
    {
        $tage = $this->berechneFehlzeiten($Krankheit_start, $Krankheit_ende);
        $stmt = $this->db->prepare("
            INSERT INTO Fehlzeiten (Mitarbeiter_id, Krankheit_start, Krankheit_ende, Status, Tage) 
            VALUES (:mitarbeiter_id, :start, :ende, :status, :tage)
        ");
        $stmt->execute([
            'mitarbeiter_id' => $Mitarbeiter_id,
            'start' => $Krankheit_start->format('Y-m-d H:i:s'),
            'ende' => $Krankheit_ende->format('Y-m-d H:i:s'),
            'status' => $status->value,
            'tage'  => $tage
        ]);
    }

    public function FehltageBearbeiten(int $Fehlzeiten_id, DateTime $neuer_start, DateTime $neues_ende): void
    {
        $stmt = $this->db->prepare("
            UPDATE Fehlzeiten 
            SET Krankheit_start = :start, Krankheit_ende = :ende 
            WHERE Fehlzeiten_id = :id
        ");
        $stmt->execute([
            'start' => $neuer_start->format('Y-m-d H:i:s'),
            'ende'  => $neues_ende->format('Y-m-d H:i:s'),
            'id'    => $Fehlzeiten_id,
        ]);
    }

    public function FetchFehlTageById(int $id): ?array
    {
        $stmt = $this->db->prepare("
        SELECT 
            Fehlzeiten.*, 
            Arbeiter.Vorname, 
            Arbeiter.Nachname
        FROM Fehlzeiten
        JOIN Arbeiter 
            ON Fehlzeiten.Mitarbeiter_id = Arbeiter.id
        WHERE Fehlzeiten.Fehlzeiten_id = :id
    ");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function statusAendern(int $Fehlzeiten_id, FehlzeitenStatus $neuer_status): void
{
    $stmt = $this->db->prepare("
        UPDATE Fehlzeiten SET Status = :status WHERE Fehlzeiten_id = :id
    ");
    $stmt->execute([
        'status' => $neuer_status->value,
        'id' => $Fehlzeiten_id
    ]);
}
}
