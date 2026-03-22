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
            'name' => ['required', 'string', 'max:60', 'not_regex:/^\s*$/'],
            'email' => ['required', 'email', 'max:60', 'not_regex:/^\s*$/'],
            'username' => ['required', 'alpha_dash', 'max:45', 'not_regex:/^\s*$/'],
            'role' => ['required', 'string', 'in:Administrador,Moderador,Usuario'],
            'state' => ['required', 'string', 'in:ACTIVO,INACTIVO'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
        ];
    }
}
