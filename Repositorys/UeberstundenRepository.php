<?php

namespace App\Repositorys;

use DateTime;
use PDO;

class UeberstundenRepository
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

    public function fetchArbeitszeitenById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT DATE(Start_von) AS tag, Start_von, Ende_bis FROM Arbeitszeiten WHERE Arbeiter_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    }

    public function countArbeitszeiten(int $arbeiterId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            DATE(Start_von) AS tag,
            SEC_TO_TIME(
                SUM(TIME_TO_SEC(TIMEDIFF(Ende_bis, Start_von)))
            ) AS arbeitszeit
        FROM arbeitszeiten
        WHERE Arbeiter_id = ?
        GROUP BY DATE(Start_von)
        ORDER BY DATE(Start_von)
    ");

        $stmt->execute([$arbeiterId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function arbeitstageZaehlen(int $arbeiterId, string $startDatum, string $endDatum)
    {
        $stmt = $this->db->prepare("
        SELECT DATE(Start_von) AS tag
        FROM arbeitszeiten
        WHERE Arbeiter_id = ?
          AND DATE(Start_von) BETWEEN ? AND ?
    ");
        $stmt->execute([$arbeiterId, $startDatum, $endDatum]);
        $tage = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return ($tage);
    }
}
