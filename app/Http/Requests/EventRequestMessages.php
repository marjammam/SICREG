<?php

namespace App\Http\Requests;

trait EventRequestMessages
{
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 100 caracteres.',
            'name.not_regex' => 'El nombre no puede estar en blanco.',

            'description.string' => 'La descripción debe ser una cadena de texto.',
            'description.max' => 'La descripción no puede tener más de 255 caracteres.',
            'description.not_regex' => 'La descripción no puede estar en blanco.',

            'event-date1.required' => 'La fecha es obligatoria.',
            'event-date1.date' => 'La fecha debe ser una fecha válida.',
            'event-date1.after_or_equal' => 'La fecha debe ser hoy o una fecha futura.',

            'event-date2.date' => 'La fecha debe ser una fecha válida.',
            'event-date2.after_or_equal' => 'La fecha debe ser hoy o una fecha futura.',

            'state.required' => 'El estado es obligatorio.',
            'state.string' => 'El estado debe ser una cadena de texto.',
            'state.in' => 'El estado debe ser uno de los siguientes valores: Activo, Inactivo, Finalizado.',
        ];
    }
}