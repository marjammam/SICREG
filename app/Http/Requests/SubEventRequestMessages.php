<?php

namespace App\Http\Requests;

trait SubEventRequestMessages
{
    public function messages(): array
    {
        return [
            'subevent-name.required' => 'El nombre es obligatorio.',
            'subevent-name.string' => 'El nombre debe ser una cadena de texto.',
            'subevent-name.max' => 'El nombre no puede tener más de 100 caracteres.',
            'subevent-name.not_regex' => 'El nombre no puede estar en blanco.',

            'subevent-type.required' => 'El tipo es obligatorio.',
            'subevent-type.string' => 'El tipo debe ser una cadena de texto.',
            'subevent-type.in' => 'El tipo debe ser uno de los siguientes valores: Delegados, Congreso.',

            'subevent-date.required' => 'La fecha es obligatoria.',
            'subevent-date.date' => 'La fecha debe ser una fecha válida.',
            'subevent-date.after_or_equal' => 'La fecha debe ser hoy o una fecha futura.',

            'subevent-time1.required' => 'La hora de inicio es obligatoria.',
            'subevent-time1.date_format' => 'La hora de inicio debe ser una hora válida.',

            'subevent-time2.required' => 'La hora de finalización es obligatoria.',
            'subevent-time2.date_format' => 'La hora de finalización debe ser una hora válida.',

            'state.required' => 'El estado es obligatorio.',
            'state.string' => 'El estado debe ser una cadena de texto.',
            'state.in' => 'El estado debe ser uno de los siguientes valores: Activo, Inactivo, Finalizado.',

            'eventId.required' => 'El ID de evento es obligatorio.',
            'eventId.integer' => 'El ID de evento debe ser de tipo entero.',
            'eventId.min' => 'El ID de evento debe ser válido.',
        ];
    }
}
