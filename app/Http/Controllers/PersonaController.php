<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonaPatchRequest;
use App\Http\Requests\PersonaPostRequest;
use App\Models\Persona;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PersonaController extends Controller
{
    public function index(Request $request)
    {
        $query = Persona::where('estadoP', 'ACTIVO'); 

        if ($request->isMethod('post')) {
            $nombre = $request->input('nombre');

            if ($nombre) {
                $query->where(function ($q) use ($nombre) {
                    $q->where('nombre', 'like', '%' . $nombre . '%')
                        ->orWhere('apellidos', 'like', '%' . $nombre . '%')
                        ->orWhere('ci', 'like', '%' . $nombre . '%');
                });
            }
        }

        $personas = $query->paginate(50);
        $eventos = Event::all();
        return view('cliente.cliente', compact('personas', 'eventos'));
    }

    public function buscar($ci)
    {
        $persona = Persona::where('ci', $ci)->first();
        return response()->json($persona);
    }
    public function store(PersonaPostRequest $request)
    {
        $nombreFoto = null;
        if ($request->hasFile('foto')) {
            $nombreFoto = time() . '.' . $request->foto->extension();
            $request->foto->move(storage_path('app/private/fotos'), $nombreFoto);
        }
        Persona::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'ci' => $request->ci,
            'tipoInstitucion' => $request->tipoInstitucion,
            'distrito' => $request->distrito === 'OTRO' ? strtoupper($request->distrito_otro) : $request->distrito,
            'foto' => $nombreFoto,
            'estadoP' => 'ACTIVO'
        ]);
        return redirect('cliente');
    }

    public function update(int $personaId, PersonaPatchRequest $request)
    {
        $persona = Persona::findOrFail($personaId);
        $nombreFoto = $persona->foto;

        if ($request->hasFile('foto')) {
            $nombreFoto = time() . '.' . $request->foto->extension();
            $request->foto->move(storage_path('app/private/fotos'), $nombreFoto);

            if ($persona->foto) {
                $this->deleteFile(storage_path('app/private/fotos/' . $persona->foto));
            }
        }

        $persona->update([
            'nombre' => $request->input('nombre', $persona->nombre),
            'apellidos' => $request->input('apellidos', $persona->apellidos),
            'ci' => $request->input('ci', $persona->ci),
            'tipoInstitucion' => $request->input('tipoInstitucion', $persona->tipoInstitucion),
            'distrito' => $request->input('distrito', $persona->distrito),
            'foto' => $nombreFoto,
        ]);

        return redirect('cliente');
    }

    private function deleteFile(string $path)
    {
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    public function delete(int $personaId)
    {

        $persona = Persona::findOrFail($personaId);
        $persona->update(['estadoP' => 'INACTIVO']);
        return redirect('cliente');
       /* $persona = Persona::findOrFail($personaId);
        if ($persona->foto) {
            $this->deleteFile(storage_path('app/private/fotos/' . $persona->foto));
        }
        $persona->delete();
        return redirect('cliente');*/
    }

    public function obtenerFoto($filename)
    {
        $path = storage_path('app/private/fotos/' . $filename);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        return response($file, 200)->header("Content-Type", $type);
    }
}
