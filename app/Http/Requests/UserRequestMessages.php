<?php

namespace App\Http\Requests;

trait UserRequestMessages
{
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 60 caracteres.',
            'name.not_regex' => 'El nombre no puede estar en blanco.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.max' => 'El correo electrónico no puede tener más de 60 caracteres.',
            'email.not_regex' => 'El correo electrónico no puede estar en blanco.',

            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.alpha_dash' => 'El nombre de usuario solo puede contener letras, números, guiones y guiones bajos.',
            'username.max' => 'El nombre de usuario no puede tener más de 45 caracteres.',
            'username.not_regex' => 'El nombre de usuario no puede estar en blanco.',

            'role.required' => 'El rol es obligatorio.',
            'role.string' => 'El rol debe ser una cadena de texto.',
            'role.in' => 'El rol debe ser uno de los siguientes valores: Administrador, Moderador, Usuario.',

            'state.required' => 'El estado es obligatorio.',
            'state.string' => 'El estado debe ser una cadena de texto.',
            'state.in' => 'El estado debe ser uno de los siguientes valores: ACTIVO, INACTIVO.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ];
    }
}
