<?php

namespace App\Repositorys;

use App\Enum\UrlaubsStatus;
use DateTime;
use PDO;

class UrlaubRepository
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

        $db = new PDO("mysql:host=$hostname;dbname=Arbeitszeiterfassung", $username, $datapassword);
        return $db;
    }

    public function fetchAllUrlaub(string $order, string $sort, int $limit, int $offset)
    {
        $stmt = $this->db->prepare('
             SELECT Urlaub.*, Arbeiter.Vorname, Arbeiter.Nachname 
             FROM Urlaub 
             JOIN Arbeiter ON Urlaub.Mitarbeiter_id = Arbeiter.id
             ORDER BY ' . $order . ' ' . $sort . '
             LIMIT ' . $limit . ' OFFSET ' . $offset);

        $stmt->execute([]);
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    }

    public function fetchallesUrlaub()
{
    
    $stmt = $this->db->prepare('SELECT Tage, status FROM Urlaub WHERE status = "angefragt" OR status = "genehmigt"');
    $stmt->execute([]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}


    public function countAlleUrlaube()
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM Urlaub");
        return $stmt->fetchColumn();
    }

    public function berechneUrlaubstage(DateTime $start, DateTime $ende)
    {
        $interval = $start->diff($ende);
        return (int)$interval->format('%a') + 1;
    }

    public function addUrlaub(int $Mitarbeiter_id, DateTime $urlaub_start, DateTime $urlaub_ende, UrlaubsStatus $status = UrlaubsStatus::ANGEFRAGT)
    {
        $tage = $this->berechneUrlaubstage($urlaub_start, $urlaub_ende);
        $stmt = $this->db->prepare("INSERT INTO Urlaub (Mitarbeiter_id, Urlaub_start, Urlaub_ende, Status, Tage) VALUES (:mitarbeiter_id, :start, :ende, :status, :tage)");
        $result = $stmt->execute([
            'mitarbeiter_id' => $Mitarbeiter_id,
            'start' => $urlaub_start->format('Y-m-d H:i:s'),
            'ende' => $urlaub_ende->format('Y-m-d H:i:s'),
            'status' => $status->value,
            'tage'  => $tage
        ]);
    }

    public function urlaubBearbeiten(int $Urlaub_id, DateTime $neuer_start, DateTime $neues_ende)
    {
        $tage = $this->berechneUrlaubstage($neuer_start, $neues_ende);
        $stmt = $this->db->prepare("UPDATE Urlaub SET Urlaub_start =:start, Urlaub_ende =:ende, Tage =:tage WHERE Urlaub_id =:id");
        $stmt->execute([
            'start' => $neuer_start->format('Y-m-d H:i:s'),
            'ende'  => $neues_ende->format('Y-m-d H:i:s'),
            'tage'  => $tage,
            'id'    => $Urlaub_id,
        ]);
    }

    public function fetchUrlaubById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM Urlaub WHERE Urlaub_id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }



/*
    
    public function restUrlaubGeplant($Mitarbeiter_id)
    {
        $stmt = $this->db->prepare("SELECT Urlaubstage FROM Arbeiter WHERE Mitarbeiter_id = :id AND (Status = :status1 OR Status = :status2)");
            $stmt->execute([
            'id' => $Mitarbeiter_id,
            'status1' => UrlaubsStatus::ANGEFRAGT,
            'status2' => UrlaubsStatus::GENEHMIGT
            ]);

    }
    

    
    public function restUrlaubgGenehmigt($Mitarbeiter_id)
    {
        $stmt = $this->db->prepare("SELECT Urlaubstage FROM Arbeiter WHERE Mitarbeiter_id =:id AND Status =: status");
        $stmt->execute(['id' => $Mitarbeiter_id, 'status' => UrlaubsStatus::GENEHMIGT]);
    }
    
*/
    public function statusAendern(int $Urlaub_id, UrlaubsStatus $neuer_status)
    {
        $stmt = $this->db->prepare("UPDATE Urlaub SET Status =:status WHERE Urlaub_id =:id");
        $stmt->execute(['status' => $neuer_status->value, 'id' => $Urlaub_id]);
    }

}
