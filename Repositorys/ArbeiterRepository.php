<?php

namespace App\Repositorys;

use PDO;

class ArbeiterRepository
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

        public function findeNachUser(string $username)
        {
            $stmt = $this->db->prepare("SELECT id, Vorname, Nachname, password FROM Arbeiter WHERE username = :username");
            $stmt->execute(['username'=>$username]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
}