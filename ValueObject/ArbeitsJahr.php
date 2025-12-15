<?php

namespace App\ValueObject;

class ArbeitsJahr {
    /**
     * @param ArbeitsMonat[] $monate
     */
    public function __construct(public array $monate) {
        $this->monate = $monate;
    }

    public function getKalenderWochen() {}

    public function getGearbeiteteStunden() {
        $stunden = 0;
        foreach ($this->monate as $key => $value) {
            $stunden += $value->getGearbeiteteStunden();
        }
        return $stunden;
    }


    public function getUeberstunden() {
         $stunden = 0;
        foreach ($this->monate as $key => $value) {
            $stunden += $value->getUeberstunden();
        }
        return $stunden;
    }

    public function GetJahresWarnung(float $limit=1068) {
        $errors = [];

        if ($this->getGearbeiteteStunden() > $limit){
             $errors[] = 'ihr jährliches Pensum liegt über dem legalen Wert';
        }
        return implode('<br>', $errors);
    
    }
}