<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfilRequest extends FormRequest
{
    // Wer darf diese Anfrage durchführen?
    public function authorize(): bool
    {
        return Auth::check(); // nur eingeloggte Benutzer
    }

    // Validierungsregeln
    public function rules(): array
    {
        return [
            'vorname' => 'required|string|max:255',
            'nachname' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . Auth::id(),
        ];
    }

    // Optional: benutzerdefinierte Fehlermeldungen
    public function messages(): array
    {
        return [
            'vorname.required' => 'Bitte gib deinen Vornamen ein.',
            'nachname.required' => 'Bitte gib deinen Nachnamen ein.',
            'email.email' => 'Bitte gib eine gültige E-Mail-Adresse ein.',
            'email.unique' => 'Diese E-Mail-Adresse ist bereits vergeben.',
        ];
    }
}