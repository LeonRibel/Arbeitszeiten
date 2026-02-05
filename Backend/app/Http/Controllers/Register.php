<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\Arbeiter;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Register extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        // Create the user with validated data
        $arbeiter = User::create([
            'vorname' => $request->vorname,
            'nachname' => $request->nachname,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // Log them in
        Auth::login($arbeiter);

        // Return JSON response or redirect
        return response()->json([
            'success' => true,
            'message' => 'Registrierung erfolgreich'
        ]);
    }
}
