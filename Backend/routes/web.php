<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ArbeitszeitenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Authentication Routes
Route::post('/login', [LoginController::class, 'login'])
    ->middleware( 'throttle:10,1')
    ->name('login');

Route::post('/logout', [LoginController::class, 'logout']);


Route::get('/user', function (Request $request) {
    if (Auth::check()) {
        return response()->json([
            'authenticated' => true,
            'user' => $request->user()
        ]);
    }

    return response()->json([
        'authenticated' => false,
        'user' => null
    ]);
});


