<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
       // Auth::attempt() automatically logs the user in if credentials are correct
       if (Auth::attempt($request->validated())) {
            $token = $request->user()->createToken('logintoken');
            // Regenerate session to prevent session fixation attacks


            // Session cookie is automatically set via Set-Cookie header
            // No need to return a token - stateful API handles it
            return response()->json([
                'success' => true,
                'message' => 'Login erfolgreich',
                'user' => Auth::user(), // Optionally return user data
                'token' => $token->plainTextToken
            ]);
       }

       return response()->json([
            'success' => false,
            'message' => 'Ungültige Eingabedaten'
       ], 401);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'vorname' => $request->vorname,
            'nachname' => $request->nachname,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $token = $user->createToken('logintoken');

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Registrierung erfolgreich',
            'user' => $user,
            'token' => $token->plainTextToken
        ], 201);
    }
}