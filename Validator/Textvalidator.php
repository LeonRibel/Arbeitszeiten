<?php

namespace App\Validator;

use RuntimeException;

class Textvalidator
{

    private array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }
    public function validate(string $feldName, int $min = 1, int $max = 255): string
    {
        if (!isset($_POST[$feldName])) {
            $this->errors[] = "Das Feld '$feldName' wurde nicht übermittelt.";
            throw new RuntimeException("Fehler im Feld '$feldName'");
        }

        $wert = trim($_POST[$feldName]);
        if ($wert === '') {
            throw new RuntimeException('Bitte Aufgaben eingeben.');
        }

        if (mb_strlen($wert) < $min) {
            throw new RuntimeException('Eingabe zu kurz.');
        }

        if (mb_strlen($wert) > $max) {
            throw new RuntimeException('Eingabe zu lang.');
        }

        if (count($this->errors) !== 0) {
            throw new RuntimeException('Das Startdatum darf nicht nach dem Enddatum liegen.');
        }

        return $wert;
    }

    
}
