<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserPatchRequest extends FormRequest
{
    use UserRequestMessages;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:60', 'not_regex:/^\s*$/', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/'],
            'email' => ['sometimes', 'email', 'max:60', 'not_regex:/^\s*$/'],
            'username' => ['sometimes', 'max:45', 'not_regex:/^\s*$/', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ_]+$/'],
            'role' => ['sometimes', 'string', 'in:ADMINISTRADOR,MODERADOR,INVITADO'],
            'state' => ['sometimes', 'string', 'in:ACTIVO,INACTIVO'],
            'password' => [
                'sometimes',
                'confirmed',
                Password::min(8),
            ],
        ];
    }
}
