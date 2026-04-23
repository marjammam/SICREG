<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonaPatchRequest extends FormRequest
{
    use PersonaRequestMessages;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['sometimes', 'string', 'max:60'],
            'apellidos' => ['sometimes', 'string', 'max:60'],
            'ci' => [
                'sometimes',
                'integer',
                'digits_between:0,20',
                Rule::unique('persona', 'ci')->ignore($this->route('personaId'), 'idPersona'),
            ],
            'tipoInstitucion' => ['sometimes', 'string', 'max:100'],
            'distrito' => ['sometimes', 'not_in:Seleccionar'],
            'foto' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ];
    }
}
