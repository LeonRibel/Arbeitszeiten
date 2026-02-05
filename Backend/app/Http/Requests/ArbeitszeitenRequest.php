<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArbeitszeitenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start' => ['required', 'date'],
            'ende' => ['required', 'date'],
            'aufgaben' => ['required', 'string', 'min:3', 'max:255'],
            'kunde_id' => ['nullable', 'integer', 'exists:kunden,id']
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [

            'start.required' => 'Startzeit ist erforderlich.',
            'start.date' => 'Startzeit muss ein gültiges Datum sein.',
            'ende.required' => 'Endzeit ist erforderlich.',
            'ende.date' => 'Endzeit muss ein gültiges Datum sein.',
            'aufgaben.required' => 'Aufgabenfeld darf nicht leer sein.',
            'aufgaben.min' => 'Aufgaben müssen mindestens 3 Zeichen haben.',
            'aufgaben.max' => 'Aufgaben dürfen maximal 255 Zeichen haben.',
            'kunde_id.integer' => 'Kunden-ID muss eine Zahl sein.',
            'kunde_id.exists' => 'Der ausgewählte Kunde existiert nicht.',
        ];
    }

    /**
     * Get the data to be validated from the request.
     * Ensures we read from JSON body.
     *
     * @return array
     */
    public function validationData(): array
    {
        return $this->json()->all();
    }
}
