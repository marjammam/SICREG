<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubEventPostRequest extends FormRequest
{
    use SubEventRequestMessages;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subevent-name' => ['required', 'string', 'max:100', 'not_regex:/^\s*$/'],
            'description' => ['nullable', 'string', 'max:255', 'not_regex:/^\s*$/'],
            'subevent-date' => ['required', 'date'],
            'subevent-time1' => ['required', 'date_format:H:i,H:i:s'],
            'subevent-time2' => ['required', 'date_format:H:i,H:i:s'],
            'subevent-state' => ['required', 'string', 'in:Activo,Inactivo,Finalizado'],
            'eventId' => ['required', 'integer', 'min:1'],
        ];
    }
}
