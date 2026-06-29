<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Exports\CredencialPersonasExport;
use Maatwebsite\Excel\Facades\Excel;  
use App\Models\Credencial;
use Illuminate\Support\Facades\DB;


class CredencialController extends Controller
{
    public function preview(Request $request)
    {
        $ids = json_decode($request->personas);
        $personas = Persona::whereIn('idPersona', $ids)->get();
        $evento = $request->evento;
        return view('credenciales.credencial_preview', compact('personas','evento'));
    }

    // Guardar credencial al imprimir
    public function store(Request $request)
    {
        $request->validate([
            'Persona_idPersona' => 'required|integer',
            'carnet'  => 'required|string',
            'tipo_institucion'  => 'required|string',
            'cargo'             => 'nullable|string'
        ]);

        $credencial = Credencial::create([
            'Persona_idPersona' => $request->Persona_idPersona,
            'carnet' => $request->carnet,
            'tipo_institucion' => $request->tipo_institucion,
            'cargo' => $request->cargo,
            'fecha_emision' => now()
        ]);

        return response()->json(['success' => true, 'credencial' => $credencial]);
    }

    // Listar todas las credenciales con datos de persona
   public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $query = Credencial::with('persona')
            ->select('carnet', 'tipo_institucion', 'cargo', 'Persona_idPersona',
                    DB::raw('COUNT(*) as total_impresiones'),
                    DB::raw('MAX(fecha_emision) as ultima_emision'))
            ->groupBy('Persona_idPersona', 'carnet', 'tipo_institucion', 'cargo');

        if ($buscar) {
            $query->whereHas('persona', function($q) use ($buscar) {
                $q->where('ci', 'like', '%' . $buscar . '%');
            });
        }

        $credenciales = $query->get();

        return view('credenciales.lista', compact('credenciales', 'buscar'));
    }
    // Exportar a Excel
    public function exportToExcel()
    {
        return Excel::download(new CredencialPersonasExport, 'Credenciales_Emitidas_' . date('Y-m-d') . '.xlsx');
    }


}
