<?php

namespace App\Repositorys;

use PDO;

class ArbeitszeitenRepository
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

    public function fetchArbeitszeiten(string $order, string $sort, int $limit, int $offset,?int $kalenderwoche = null)
    {
       if ($kalenderwoche === null) {
        $kalenderwoche = date('W');
    }
       

        $sql ='SELECT Arbeitszeiten.id, Start_von, Ende_bis, Aufgaben, Vorname, Nachname FROM Arbeitszeiten LEFT JOIN Arbeiter ON Arbeitszeiten.Arbeiter_id = Arbeiter.id WHERE WEEK(Start_von, 1) = :kw ORDER BY ' . $order . ' ' . $sort . ' LIMIT ' . $limit . ' OFFSET ' . $offset;

        $stmt =$this->db->prepare($sql);
        $stmt->execute([
        'kw' => $kalenderwoche
    ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

        
    }
    public function countArbeitszeiten(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM arbeitszeiten");
        return (int)$stmt->fetchColumn();
    }

    public function update(string $start_von, string $ende_bis, string $aufgaben, int $arbeiter_id, int $id)
    {

        $stmt = $this->db->prepare("UPDATE arbeitszeiten SET Start_von = :start, Ende_bis = :ende, Aufgaben = :aufgaben, Arbeiter_id =:arbeiter_id WHERE id =:id");
        $update = $stmt->execute(['start' => $start_von, 'ende' => $ende_bis, 'aufgaben' => $aufgaben, 'arbeiter_id' => $arbeiter_id, 'id' => $id]);
    }

    public function insert(string $Start_von, string $Ende_bis, string $aufgaben, int $arbeiter_id)
    {
        $stmt = $this->db->prepare("INSERT INTO arbeitszeiten (Start_von, Ende_bis, Aufgaben, Arbeiter_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$Start_von, $Ende_bis, $aufgaben, $arbeiter_id]);
    }


    public function fetchbyId(int $id)
    {
        $stmt = $this->db->prepare("SELECT Start_von,Ende_bis,Aufgaben FROM Arbeitszeiten WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
        #$vorhandene_daten = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteZeit(int $id)
    {
        $stmt = $this->db->prepare("DELETE FROM Arbeitszeiten WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function fetchAll()
    {
        $stmt = $this->db->prepare('
            SELECT Arbeitszeiten.id, Start_von, Ende_bis, Aufgaben, Vorname, Nachname 
            FROM Arbeitszeiten 
            LEFT JOIN Arbeiter ON Arbeitszeiten.Arbeiter_id = Arbeiter.id');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

