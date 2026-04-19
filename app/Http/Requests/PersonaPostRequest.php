<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonaPostRequest extends FormRequest
{
    use PersonaRequestMessages;
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:60'],
            'apellidos' => ['required', 'string', 'max:60'],
            'ci' => ['required', 'string', 'max:20', 'unique:persona,ci'],
            'tipoInstitucion' => ['required', 'string', 'max:100'],
            'distrito' => ['required', 'not_in:Seleccionar'],
            'foto' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ];
    }
}
