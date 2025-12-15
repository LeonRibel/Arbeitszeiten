<?php

namespace App\ValueObject;

class ArbeitsJahrCollection{
    /**
     * @param ArbeitsJahr[] $jahre
     */
    public function __construct(public array $jahre) {
    }

    public static function fromArray(array $data): self {
        $monate = [];
        $jahre = [];

        $wochen = [];
        $tage = [];
        
        foreach ($data as $arbeitsZeitEintrag) {

            $dateToday = new \DateTime($arbeitsZeitEintrag['tag']);
            $jahr = $dateToday->format("Y");
            $monat = $dateToday->format("m");
            $tag   = $dateToday->format("d");

            $arbeitszeiten[$jahr][$monat][$tag][] = $arbeitsZeitEintrag;
        }

        foreach ($arbeitszeiten as $jahr => $monate) {
            foreach ($monate as $monat => $tage) {
                $arbeitsTage = [];
                foreach ($tage as $eintraege) {
                    $eintragTag = $eintraege[0]['tag'];
                    $arbeitsTage[$eintragTag] = new Arbeitstag($eintraege);
                }

                $monate[$monat] = new ArbeitsMonat($arbeitsTage);
            }
            $jahre[$jahr] = new ArbeitsJahr($monate);
        }

        return new self($jahre);
    }
}