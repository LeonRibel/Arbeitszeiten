<?php

namespace App\ValueObject;

class ArbeitsWoche {
    public array $arbeitsTage;
    public function __construct(Arbeitstag ...$arbeitstag) {
        $this->arbeitsTage = $arbeitstag;
    }
}