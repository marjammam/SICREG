<?php

namespace App\Http\Requests;

trait PersonaRequestMessages
{
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 60 caracteres.',

            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.string' => 'Los apellidos deben ser una cadena de texto.',
            'apellidos.max' => 'Los apellidos no pueden tener más de 60 caracteres.',

            'ci.required' => 'El CI es obligatorio.',
            'ci.string' => 'El CI debe ser una cadena de texto.',
            'ci.max' => 'El CI no puede tener más de 20 caracteres.',
            'ci.unique' => 'El CI ya está registrado.',

            'tipoInstitucion.required' => 'El tipo de institución es obligatorio.',
            'tipoInstitucion.string' => 'El tipo de institución debe ser una cadena de texto.',
            'tipoInstitucion.max' => 'El tipo de institución no puede tener más de 100 caracteres.',

            'distrito.required' => 'El distrito es obligatorio.',
            'distrito.not_in' => 'Debe seleccionar un distrito válido.',

            'foto.image' => 'La foto debe ser una imagen.',
            'foto.mimes' => 'La foto debe ser un archivo de tipo: jpg, png, jpeg.',
            'foto.max' => 'La foto no puede superar los 2048 KB.',
        ];
    }
}