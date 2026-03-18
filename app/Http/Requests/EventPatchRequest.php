<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventPatchRequest extends FormRequest
{
    use EventRequestMessages;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:100', 'not_regex:/^\s*$/'],
            'description' => ['sometimes', 'nullable', 'string', 'max:255', 'not_regex:/^\s*$/'],
            'event-date1' => ['sometimes', 'date'],
            'event-date2' => ['sometimes', 'nullable', 'date'],
            'state' => ['sometimes', 'string', 'in:Activo,Inactivo,Finalizado'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $date1 = $this->input('event-date1');
            $date2 = $this->input('event-date2');

            if ($date1 && $date2 && strtotime($date1) > strtotime($date2)) {
                $validator->errors()->add('event-date1', 'La fecha inicial debe ser menor o igual a la fecha final.');
                $validator->errors()->add('event-date2', 'La fecha final debe ser mayor o igual a la fecha inicial.');
            }
        });
    }
}
