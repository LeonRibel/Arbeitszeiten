<?php

namespace App\Repositorys;

use PDO;

class ArbeiterHinzuRepository
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

    public function fetchArbeiter(string $order, string $sort, int $limit, int $offset)
    {
        $zählerQuery = $this->db->query('SELECT COUNT(*) FROM Arbeiter');
        $gesamt = $zählerQuery->fetchColumn();
        $ArbeiterQuery = $this->db->query('SELECT Arbeiter.id, Vorname, Nachname, username FROM Arbeiter ORDER BY ' . $order . ' ' . $sort . ' LIMIT ' . $limit . ' OFFSET ' . $offset);
        $Arbeiter = $ArbeiterQuery->fetchAll(PDO::FETCH_ASSOC);
        return $Arbeiter;
    }

    public function countArbeiter(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM Arbeiter");
        return (int)$stmt->fetchColumn();
    }

    public function getArbeiterById(int $id)
    {
        $stmt = $this->db->prepare("SELECT id, Vorname, Nachname, username, Urlaubstage FROM Arbeiter WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function updateArbeiter(int $id, string $vorname, string $nachname, string $username, ?string $password)
    {   
       if ($password === null) {
        $stmt = $this->db->prepare("
            UPDATE Arbeiter 
            SET Vorname = :vorname, Nachname = :nachname, username = :username
            WHERE id = :id
        ");
        $stmt->execute([
            'id' => $id,
            'vorname' => $vorname,
            'nachname' => $nachname,
            'username' => $username
        ]);
    } else {
        $hashedPassword = crypt($password, $username);
        $stmt = $this->db->prepare("
            UPDATE Arbeiter 
            SET Vorname = :vorname, Nachname= :nachname, username = :username, password = :password
            WHERE id = :id
        ");
        $stmt->execute([
            'id' => $id,
            'vorname' => $vorname,
            'nachname' => $nachname,
            'username' => $username,
            'password'=> $hashedPassword
        ]);
    }
}
    public function addArbeiter(string $vorname, string $nachname, string $username, string $password)
    {   

        $hashedPassword = crypt($password, $username);
        $stmt = $this->db->prepare("INSERT INTO Arbeiter(Vorname, Nachname, username, password) VALUES (:Vorname, :Nachname, :username, :password)");
        $stmt->execute([
            'Vorname' => $vorname,
            'Nachname' => $nachname,
            'username' => $username,
            'password'=> $hashedPassword

        ]);
        return $this->db->lastInsertId();
    }

    public function deleteArbeiter(int $id)
    {
        $stmt = $this->db->prepare("DELETE FROM Arbeiter WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
