<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UrlaubRequest extends FormRequest
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
            'start' => 'required|date',
            'ende' => 'required|date|after_or_equal:start',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'start.required' => 'Das Startdatum ist erforderlich.',
            'start.date' => 'Das Startdatum muss ein gültiges Datum sein.',
            'ende.required' => 'Das Enddatum ist erforderlich.',
            'ende.date' => 'Das Enddatum muss ein gültiges Datum sein.',
            'ende.after_or_equal' => 'Das Enddatum muss nach oder am selben Tag wie das Startdatum liegen.',
        ];
    }
}
