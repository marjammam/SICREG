<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Persona;

class CredencialController extends Controller
{
    public function preview(Request $request)
    {
        $ids = json_decode($request->personas);

        $personas = Persona::whereIn('idPersona', $ids)->get();

        $evento = $request->evento;

        return view('credenciales.credencial_preview', compact('personas','evento'));
    }

}
