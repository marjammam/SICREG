<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubEventPatchRequest extends FormRequest
{
    use SubEventRequestMessages;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subevent-name' => ['sometimes', 'string', 'max:100', 'not_regex:/^\s*$/'],
            'subevent-type' => ['sometimes', 'string', 'in:Delegados,Congreso'],
            'subevent-date' => ['sometimes', 'date'],
            'subevent-time1' => ['sometimes', 'date_format:H:i,H:i:s'],
            'subevent-time2' => ['sometimes', 'date_format:H:i,H:i:s'],
            'subevent-state' => ['sometimes', 'string', 'in:Activo,Inactivo,Finalizado'],
            'eventId' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
