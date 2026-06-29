<?php

namespace App\Http\Controllers;

use App\Exports\AsistenciaExport;
use Illuminate\Http\Request;
use App\Models\Persona; // Ajusta según tu modelo de clientes
use App\Models\Asistencia;
use App\Models\SubEvent; // Ajusta según tu modelo de subeventos
use Illuminate\Support\Facades\Auth;

class AsistenciaController extends Controller {
    public function index(int $id, Request $request)
    {
        // 1. Buscamos el subevento
        $subevento = SubEvent::findOrFail($id);

        // 2. Usamos el nombre correcto del Modelo: Asistencia
        $query = Asistencia::where('Subevento_idSubevento', $id)
                        ->with('persona')
                        ->orderBy('fechahoraIngreso', 'desc');

        if ($request->isMethod('post')) {
            $searchTerm = $request->input('search');
            if ($searchTerm) {
                $query->whereHas('persona', function ($q) use ($searchTerm) {
                    $q->where('nombre', 'like', '%' . $searchTerm . '%')
                      ->orWhere('apellidos', 'like', '%' . $searchTerm . '%')
                      ->orWhere('ci', 'like', '%' . $searchTerm . '%');
                });
            }
        }

        $asistencias = $query->get();

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
            $persona = Persona::where('ci', $request->ci)->first();

            if (!$persona) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Persona no encontrada con CI: ' . $request->ci
                ]);
            }

            // ✅ Duplicado por subevento completo, no solo por día
            $existe = Asistencia::where('Persona_idPersona', $persona->idPersona)
                ->where('Subevento_idSubevento', $request->subevento_id)
                ->exists();

            if ($existe) {
                return response()->json([
                    'status'  => 'duplicado',
                    'message' => $persona->nombre . ' ' . $persona->apellidos . ' ya está registrado en este subevento.'
                ]);
            }

            Asistencia::create([
                'codigoQRleido'         => $request->ci,
                'fechahoraIngreso'      => now(),
                'estadoR'               => 'INGRESO',
                'Subevento_idSubevento' => $request->subevento_id,
                'Persona_idPersona'     => $persona->idPersona,
                'Usuario_idUsuario'     => Auth::id() ?? 1
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Asistencia registrada: ' . $persona->nombre . ' ' . $persona->apellidos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Error en servidor: ' . $e->getMessage()
            ], 500);
        }
    }


    public function exportToExcel(int $subEventId)
    {
        $subevent = SubEvent::findOrFail($subEventId);

        return (new AsistenciaExport($subEventId))->download('registro_asistencia_' . $subevent->nombreSE . '.xlsx');
    }

    public function delete(int $asistenciaId)
    {
        $asistencia = Asistencia::findOrFail($asistenciaId);

        $asistencia->delete();

        return redirect()->back();
    }

    // En AsistenciaController.php

    public function buscarPorCI(Request $request)
    {
        try {
            $ci = $request->input('ci');

            if (!$ci) {
                return response()->json(['error' => 'Ingrese un carnet de identidad'], 400);
            }

            $persona = Persona::where('ci', $ci)->first();

            if (!$persona) {
                return response()->json(['error' => 'No se encontró ninguna persona con ese carnet'], 404);
            }

            $asistencias = Asistencia::where('Persona_idPersona', $persona->idPersona)
                ->with(['subevento', 'subevento.event'])
                ->orderBy('fechahoraIngreso', 'desc')
                ->get();

            return response()->json([
                'persona' => [
                    'ci'              => $persona->ci,
                    'nombre_completo' => $persona->nombre . ' ' . $persona->apellidos,
                ],
                'total_asistencias' => $asistencias->count(), 
                'asistencias' => $asistencias->map(function ($a) {
                    return [
                        'evento'    => optional($a->subevento)->event->nombreE ?? ' ',
                        'subevento' => optional($a->subevento)->nombreSE        ?? ' ',
                        'fecha'     => optional($a->subevento)->fechaSE
                                        ? \Carbon\Carbon::parse($a->subevento->fechaSE)->format('d/m/Y')
                                        : '—',
                        'hora'      => optional($a->fechahoraIngreso)->format('H:i:s') ?? ' ',
                    ];
                }),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error'   => $e->getMessage(),
                'linea'   => $e->getLine(),
                'archivo' => class_basename($e->getFile()),
            ], 500);
        }
    }
    public function verificar()
    {
        return view('registro.verificarAsistencia'); // o la vista que uses
    }
}
