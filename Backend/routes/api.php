<?php

use App\Http\Controllers\UeberstundenController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArbeitszeitenController;
use App\Http\Controllers\FehlzeitenController;
use App\Http\Controllers\UrlaubController;
use App\Http\Controllers\MeinProfilController;
use App\Http\Controllers\MitarbeiterController;
use App\Http\Controllers\ProjektController;
use App\Http\Controllers\KundenController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Authentication Routes - statefulApi() handles session without CSRF
Route::post('/login', [LoginController::class, 'login'])
    ->middleware('throttle:10,1')
    ->name('login');

Route::post('/register', [LoginController::class, 'register'])
    ->middleware('throttle:5,1')
    ->name('register');




Route::middleware('auth:sanctum')->group(function () {

    //Mein Pofil Routes
    Route::get('/MeinProfil', [MeinProfilController::class, 'index']);

    //Arbeitszeiten Routes
    Route::post('/arbeitszeiten/timer/start', [ArbeitszeitenController::class, 'startTimer']);
    Route::post('/arbeitszeiten/timer/{id}/stop', [ArbeitszeitenController::class, 'stopTimer']);
    Route::get('/arbeitszeiten/timer/running', [ArbeitszeitenController::class, 'getTimer']);
    
    Route::get('/arbeitszeiten', [ArbeitszeitenController::class, 'index']);
    Route::post('/arbeitszeiten', [ArbeitszeitenController::class, 'store']);
    Route::get('/arbeitszeiten/{id}', [ArbeitszeitenController::class, 'show']);
    Route::put('/arbeitszeiten/{id}', [ArbeitszeitenController::class, 'update']);
    Route::delete('/arbeitszeiten/{id}', [ArbeitszeitenController::class, 'destroy']);
    

    // Fehlzeiten Routes
    Route::get('/fehlzeiten', [FehlzeitenController::class, 'index']);
    Route::get('/fehlzeiten/monat/{monat}', [FehlzeitenController::class, 'monat']);
    Route::get('/fehlzeiten/{id}', [FehlzeitenController::class, 'show']);
    Route::post('/fehlzeiten', [FehlzeitenController::class, 'store']);
    Route::put('/fehlzeiten/{id}', [FehlzeitenController::class, 'update']);
    Route::post('/fehlzeiten/{id}/upload', [FehlzeitenController::class, 'upload']);

    //Urlaub Routes
    Route::get('/urlaub', [UrlaubController::class, 'index']);
    Route::post('/urlaub', [UrlaubController::class, 'store']);
    Route::get('/urlaub/{id}', [UrlaubController::class, 'show']);
    Route::put('/urlaub/{id}', [UrlaubController::class, 'update']);
    Route::delete('/urlaub/{id}', [UrlaubController::class, 'destroy']);

    //Mitarbeiter Routes
    Route::get('/mitarbeiter', [MitarbeiterController::class, 'mitarbeiter']);

    //Ueberstunden Routes
    Route::get('/Ueberstunden', [UeberstundenController::class, 'index']);

    //Projekt Routes
    Route::get('/projekte', [ProjektController::class, 'index']);
    Route::post('/projekte', [ProjektController::class, 'store']);
    Route::get('/projekte/{id}', [ProjektController::class, 'show']);
    Route::put('/projekte/{id}', [ProjektController::class, 'update']);
    Route::delete('/projekte/{id}', action: [ProjektController::class, 'destroy']);

    //Kunden Routes
    Route::get('/kunden', [KundenController::class, 'index']);
    Route::post('/kunden', [KundenController::class, 'store']);
    Route::get('/kunden/{id}', [KundenController::class, 'show']);
    Route::put('/kunden/{id}', [KundenController::class, 'update']);
    Route::delete('/kunden/{id}', [KundenController::class, 'destroy']);
});