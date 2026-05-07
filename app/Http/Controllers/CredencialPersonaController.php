<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CredencialPersona;

class CredencialPersonaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'codigoQR' => 'required|string',
            'Persona_idPersona' => 'required|integer|exists:persona,idPersona'
        ]);

        $credencial = CredencialPersona::create([
            'codigoQR' => $request->codigoQR,
            'Persona_idPersona' => $request->Persona_idPersona,
            'fechaEmision' => now()
        ]);

        return response()->json(['success' => true, 'credencial' => $credencial]);
    }
}

