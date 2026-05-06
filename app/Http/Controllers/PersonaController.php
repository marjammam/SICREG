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
        $query = Persona::query();

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
            $request->foto->move(public_path('fotos'), $nombreFoto);
        }
        Persona::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'ci' => $request->ci,
            'tipoInstitucion' => $request->tipoInstitucion,
            'distrito' => $request->distrito,
            'foto' => $nombreFoto
        ]);
        return redirect('cliente');
    }

    public function update(int $personaId, PersonaPatchRequest $request)
    {
        $persona = Persona::findOrFail($personaId);
        $nombreFoto = $persona->foto;

        if ($request->hasFile('foto')) {
            $nombreFoto = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('fotos'), $nombreFoto);

            if ($persona->foto) {
                $this->deleteFile(public_path('fotos/' . $persona->foto));
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

        if ($persona->foto) {
            $this->deleteFile(public_path('fotos/' . $persona->foto));
        }

        $persona->delete();

        return redirect('cliente');
    }
}
