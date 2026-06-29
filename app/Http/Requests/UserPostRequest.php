<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserPostRequest extends FormRequest
{
    use UserRequestMessages;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:60', 'not_regex:/^\s*$/', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/'],
            'email' => ['required', 'email', 'max:60', 'not_regex:/^\s*$/'],
            'username' => ['required', 'max:45', 'not_regex:/^\s*$/', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ_]+$/'],
            'role' => ['required', 'string', 'in:ADMINISTRADOR,MODERADOR,INVITADO'],
            'state' => ['required', 'string', 'in:ACTIVO,INACTIVO'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
        ];
    }
}
