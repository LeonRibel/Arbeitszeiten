<?php

namespace App\ValueObject;

class Arbeitstag {
    public function __construct(public array $arbeitszeiten) {

    }

    public function addEintrag(array $eintrag) {
        $this->arbeitszeiten[] = $eintrag;
    }

    public function getTag() {
        return $this->arbeitszeiten[0]['tag'];
    }

    public function getArbeitsStunden(): float {

        $gesamtStunden = 0;
        /** @var  */
        foreach ($this->arbeitszeiten as $eintrag) {
            $start = new \DateTime($eintrag['Start_von']);
            $ende = new \DateTime($eintrag['Ende_bis']);

            $diff = $start->diff($ende);
            $stundenHeute = $diff->h;
            $minutenHeute = $diff->i;
            $sekundenHeute = $diff->s;

            $gesamtStunden += $stundenHeute + ($minutenHeute / 60) + ($sekundenHeute / 3600);
        }

        return round($gesamtStunden, 2);;

    }

    public function getUeberstunden() {
        $uebersekunden = 0;
        /** @var  */
        foreach ($this->arbeitszeiten as $eintrag) {
            $start = new \DateTime($eintrag['Start_von']);
            $ende = new \DateTime($eintrag['Ende_bis']);

            $diff = $start->diff($ende);
            $stundenHeute = $diff->h;
            $minutenHeute = $diff->i;
            $sekundenHeute = $diff->s;

            $rundeteMinuten = round($minutenHeute / 15) * 15;

            $uebersekunden += ($stundenHeute * 3600) + ($rundeteMinuten * 60) + $sekundenHeute ;
        }

        return ($uebersekunden - (8 * 3600)) / 3600;
    }

    public function getWarnungen() {
        $errors = [];

        if($this->isMoreThan10Hours()) {
            $errors[] = 'Achtung: Arbeitszeit über 10 Std';
        }
        if($this->isSundayWork()) {
            $errors[] = 'Achtung es wurde am Sonntag gearbeitet';
        }

        if($this->isNachtSchicht()) {
            $errors[] = 'Nachtschicht erkannt';
        }
            


        if($this->isNachtSchicht() && $this->getArbeitsStunden() > 8) {
            $errors[] = 'böse böse nachtschit';
        }

        return implode('<br>', $errors);
    }

    public function isMoreThan10Hours(): bool {
        return $this->getArbeitsStunden() > 10;
    }

    public function isSundayWork(): bool {
         $dateToday = new \DateTime($this->getTag());

        return $dateToday->format("w") == 0;
    }

    public function isNachtSchicht(): bool {
        $gesamtnachtstunden = 0;
        foreach ($this->arbeitszeiten as $key => $eintrag) {
            $start = new \DateTime($eintrag['Start_von']);
            $ende = new \DateTime($eintrag['Ende_bis']);


            $NachtSchichtStart = new \DateTime($start->format('Y-m-d') . ' 23:00:00');
            $NachtSchichtEnde = new \DateTime($start->format('Y-m-d') . ' 06:00:00');
            $NachtSchichtEnde->modify('+1 day');

            $nachtstunden = 0;
            if ($start < $NachtSchichtEnde && $ende > $NachtSchichtStart) 
            {

                $spaetererStart = max($start, $NachtSchichtStart);
                $frueheresEnde = min($ende, $NachtSchichtEnde);
                $nachtstunden = ($frueheresEnde->getTimestamp() - $spaetererStart->getTimestamp()) / 3600;
            }

            $gesamtnachtstunden += $nachtstunden;
        }

        return $gesamtnachtstunden > 2;
    }

}