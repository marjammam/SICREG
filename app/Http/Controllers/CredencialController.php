<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Persona;

class CredencialController extends Controller
{
    public function imprimirMasivo(Request $request)
    {

    $ids=json_decode($request->personas);

    $personas=Persona::whereIn('idPersona',$ids)->get();

    $evento=$request->evento;

    foreach($personas as $p){

    $datosQR=
    "CI: ".$p->ci."\n".
    "Nombre: ".$p->nombre." ".$p->apellido."\n".
    "Evento: ".$evento;

    $p->qr="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=".urlencode($datosQR);

    }

    $pdf=Pdf::loadView('pdf.credenciales_masivo',[
    'personas'=>$personas,
    'evento'=>$evento
    ])->setPaper('letter');

    return $pdf->stream("credenciales.pdf");

    }
    

}
