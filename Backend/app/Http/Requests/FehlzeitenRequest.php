<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FehlzeitenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ggf. mit Policies einschränken
    }

    public function rules(): array
    {
        $rules = [];

        // Wenn POST oder PUT → Store / Update
        if ($this->isMethod('post') || $this->isMethod('put')) {
            $rules = [
                'krankheit_start' => ['required', 'date'],
                'krankheit_ende'  => ['required', 'date', 'after_or_equal:krankheit_start'],
            ];
        }

        // Wenn Datei hochgeladen wird → Upload
        if ($this->hasFile('datei')) {
            $rules['datei'] = ['required', 'file', 'mimes:pdf,png', 'max:2048'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'krankheit_start.required' => 'Startdatum ist erforderlich.',
            'krankheit_start.date'     => 'Startdatum muss ein gültiges Datum sein.',
            'krankheit_ende.required'  => 'Enddatum ist erforderlich.',
            'krankheit_ende.date'      => 'Enddatum muss ein gültiges Datum sein.',
            'krankheit_ende.after_or_equal' => 'Enddatum darf nicht vor dem Startdatum liegen.',

            'datei.required' => 'Es muss eine Datei hochgeladen werden.',
            'datei.file'     => 'Die Datei ist ungültig.',
            'datei.mimes'    => 'Nur PDF oder PNG sind erlaubt.',
            'datei.max'      => 'Die Datei darf maximal 2MB groß sein.',
        ];
    }
}
