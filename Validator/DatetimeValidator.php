<?php

namespace App\Validator;

use DateTime;
use Exception;
use RuntimeException;

class DatetimeValidator{

    private array $errors = [];

    public function datestart(DateTime $start, DateTime $ende): void
    {
        if($start > $ende){
            $this->errors[] = "Das Startdatum darf nicht nach dem Enddatum liegen.";
        }
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function validateRequired(DateTime $start, DateTime $ende): void
    {
        if (!$start || !$ende) {
            $this->errors[] = "Bitte Start- und Enddatum angeben.";
        }
    }

    public function tryDate(string $datum): ?DateTime
    {
        try {
            return new DateTime($datum);
        } catch(Exception $e) {
            $this->errors[] = "Ungültiges Datum $datum. Bitte ein korrektes Datum auswählen.";
        }

        return null;
    }

    public function datevalidate(string $start, string $ende): array{
        if(!isset($_POST[$start]) || empty($_POST[$start] )) {
            $this->errors[] = 'Startdatum nicht angegeben';
             throw new RuntimeException('Bitte Start- und Enddatum angeben.');
        }
        if(!isset($_POST[$ende])  || empty($_POST[$ende] ) ) {
            $this->errors[] = 'Enddatum nicht angegeben';
            throw new RuntimeException('Bitte Start- und Enddatum angeben.');
        }

        $startDate = $this->tryDate($_POST[$start]);
        $endeDate = $this->tryDate($_POST[$ende]);

        if(!$startDate || !$endeDate) {
             throw new RuntimeException('Ungültiges Datum. Bitte ein korrektes Datum auswählen.');
        }

        $this->datestart($startDate,$endeDate);

        if (count($this->errors) !== 0) {
            throw new RuntimeException('Das Startdatum darf nicht nach dem Enddatum liegen.');
        }

        return [$startDate, $endeDate];
    }
   
}