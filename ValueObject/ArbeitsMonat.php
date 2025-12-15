<?php

namespace App\ValueObject;

class ArbeitsMonat
{
    /**
     * @param Arbeitstag[] $tage
     */
    public function __construct(public array $tage) {}

    

    public function getGearbeiteteStunden()
    {
        $stunden = 0;
        foreach ($this->tage as $key => $value) {
            $stunden += $value->getArbeitsStunden();
        }
        return $stunden;
    }

    public function getUeberstunden()
    {
        $stunden = 0;
        foreach ($this->tage as $key => $value) {
            $stunden += $value->getUeberstunden();
        }
        return $stunden;
    }

    public function getMonatsWarnung(float $limit = 149):string{
        $errors = [];

        if ($this->getGearbeiteteStunden() > $limit){
             $errors[] = 'ihr monatliches Pensum liegt über dem legalen Wert';
        }
        return implode('<br>', $errors);
    }
}
