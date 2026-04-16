<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Event;
class PersonaController extends Controller
{
    public function index()
    {
        $personas = Persona::paginate(50);
       // return view('cliente.cliente', compact('personas'));
        $eventos = Event::all(); 
         return view('cliente.cliente', compact('personas','eventos'));
    }

    public function buscar($ci)
    {
        $persona = Persona::where('ci', $ci)->first();
        return response()->json($persona);
    }
    public function store(Request $request)
    {
        $request->validate([
        'nombre' => 'required',
        'apellidos' => 'required',
        'ci' => 'required|unique:persona,ci',
        'tipoInstitucion' => 'required',
        'distrito' => 'required',
        'foto' => 'image|mimes:jpg,png,jpeg|max:2048',
        ]);
        $nombreFoto = null;
        if($request->hasFile('foto')){
        $nombreFoto = time().'.'.$request->foto->extension();
        $request->foto->move(public_path('fotos'),$nombreFoto);
        }
        Persona::create([
        'nombre' => $request->nombre,
        'apellidos' => $request->apellidos,
        'ci' => $request->ci,
        'tipoInstitucion' => $request->tipoInstitucion,
        'distrito' => $request->distrito,
        'foto' => $nombreFoto
        ]);
        return redirect()->back()->with('success','Registrado');
    }
}

