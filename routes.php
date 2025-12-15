<?php

use App\Controller\ArbeiterHinzuController;
use App\Controller\DashboardController;
use App\Controller\LogoutController;
use App\Controller\UrlaubController;
use App\Controller\PasswortVergessenController;
use App\Controller\UeberstundenController;
use App\Controller\FehlzeitenController;
use App\Controller\MeinProfilController;


return [

    'guest' => [
        'Passwort/Zuruecksetzen/senden' => [PasswortVergessenController::class, 'tokenanfragen'],
        'Passwort/Vergessen/senden' => [PasswortVergessenController::class, 'senden'],
        'Passwort/Zuruecksetzen' => [PasswortVergessenController::class, 'tokenPruefen'],
        'Passwort/Vergessen' => [PasswortVergessenController::class, 'vergessen'],

        'Logout' => [LogoutController::class, 'index'],

    ],
    'auth' => [
        'Mitarbeiter/update' => [ArbeiterHinzuController::class, 'addArbeiter'],
        'Mitarbeiter' => [ArbeiterHinzuController::class, 'mitarbeiter'],

        'Arbeitszeiten/update' => [DashboardController::class, 'update'],
        'Arbeitszeiten/delete' => [DashboardController::class, 'delete'],
        'Arbeitszeiten' => [DashboardController::class, 'index'],

        'Urlaub/genehmigen' => [UrlaubController::class, 'genehmigen'],
        'Urlaub/ablehnen' => [UrlaubController::class, 'ablehnen'],
        'Urlaub/update'       => [UrlaubController::class, 'bearbeiten'],
        'Urlaub' => [UrlaubController::class, 'Urlaub'],

        'Ueberstunden'=> [UeberstundenController::class, 'Ueberstunden'],

        'Fehlzeiten/upload' => [FehlzeitenController::class, 'upload'],
        'Fehlzeiten/update' => [FehlzeitenController::class, 'bearbeiten'],
        'Fehlzeiten' => [FehlzeitenController::class, 'Fehlzeiten'],

         'MeinProfil/update'=> [MeinProfilController::class, 'Profilupdate'],
         'MeinProfil'=> [MeinProfilController::class, 'Profilinfo'],

        

        '' => [DashboardController::class, 'index'], 
    ]
];
