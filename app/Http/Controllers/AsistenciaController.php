<?php

namespace App\Http\Controllers;

use App\Exports\AsistenciaExport;
use Illuminate\Http\Request;
use App\Models\Persona; // Ajusta según tu modelo de clientes
use App\Models\Asistencia;
use App\Models\SubEvent; // Ajusta según tu modelo de subeventos
use Illuminate\Support\Facades\Auth;

class AsistenciaController extends Controller {
    public function index($id)
    {
        // 1. Buscamos el subevento
        $subevento = SubEvent::findOrFail($id);

        // 2. Usamos el nombre correcto del Modelo: Asistencia
        $asistencias = Asistencia::where('Subevento_idSubevento', $id)
                        ->with('persona')
                        ->orderBy('fechahoraIngreso', 'desc')
                        ->get();

        // 3. Retornamos la vista
        return view('registro.asistencia', compact('subevento', 'asistencias'));

    }

    public function buscarCliente($ci)
    {
        try {
            // Busca a la persona por CI
            // Nota: Ajusta 'Persona' y 'ci' según tus nombres de modelo y columna
            $persona = Persona::where('ci', $ci)->first();

            if ($persona) {
                return response()->json([
                    'status' => 'success',
                    'cliente' => [
                        'nombres' => $persona->nombre,
                        'apellidos' => $persona->apellidos,
                        'ci' => $persona->ci,
                        'tipoInstitucion' => $persona->tipoInstitucion // Asegúrate de que este campo exista
                    ]
                ]);
            }

            return response()->json(['status' => 'error', 'message' => 'No encontrado'], 404);

        } catch (\Exception $e) {
            // Esto ayudará a que el error 500 te dé más info en los logs
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function registrar(Request $request)
    {
        try {
            // 1. Buscar persona por CI
            $persona = Persona::where('ci', $request->ci)->first();

            if (!$persona) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Persona no encontrada'
                ]);
            }

            // 2. Validar si ya existe registro (evitar duplicados)
            $existe = Asistencia::where('Persona_idPersona', $persona->idPersona)
                ->where('Subevento_idSubevento', $request->subevento_id)
                ->whereDate('fechahoraIngreso', now()->toDateString())
                ->exists();

            if ($existe) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ya registró asistencia hoy'
                ]);
            }

            // 3. Registrar asistencia
            Asistencia::create([
                'codigoQRleido' => $request->ci,
                'fechahoraIngreso' => now(),
                'estadoR' => 'INGRESO', // puedes ajustar según tu lógica
                'Subevento_idSubevento' => $request->subevento_id,
                'Persona_idPersona' => $persona->idPersona,
                'Usuario_idUsuario' => Auth::id() ?? 1 // opcional
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Asistencia registrada'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error en servidor',
                'error' => $e->getMessage()
            ]);
        }
    }


    public function exportToExcel(int $subEventId)
    {
        $subevent = SubEvent::findOrFail($subEventId);

        return (new AsistenciaExport($subEventId))->download('registro_asistencia_' . $subevent->nombreSE . '.xlsx');
    }
}
