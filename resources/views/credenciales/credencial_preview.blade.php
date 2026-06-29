@extends('layouts.header')

@section('content')

<link rel="stylesheet" href="{{ asset('css/credencial_masivo.css') }}">

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<div class="barra-top">
    <a href="{{ url()->previous() }}" class="btn btn-back" style="text-decoration: none">
        <i class="fa-solid fa-arrow-left"></i>
        <span>VOLVER</span>
    </a>
    <a onclick="imprimirPDF()" class="btn btn-print">
        <i class="fa fa-print"></i>
        <span>IMPRIMIR</span>
    </a>
</div>

<div id="hoja" class="page">

    @foreach($personas as $p)

    <div class="credencial">

        <img src="{{ asset('image/CREDENCIAL.png') }}" class="fondo">

        <img src="{{ asset('image/logoF.png') }}" class="logo">

        <div class="titulo">{{ $evento }}</div>
        <div class="qr" id="qr-{{ $p->idPersona }}"></div>

        <div class="datos">

            <div class="nombre">{{ $p->nombre }} {{ $p->apellidos }}</div>

            <div class="info">
            <p> {{ $p->tipoInstitucion }}</p>
            <p> {{ $p->distrito }}</p>
            </div>

        </div>
    </div>
    @endforeach

</div>

<script>

document.addEventListener("DOMContentLoaded", function() {
    @foreach($personas as $p)
    // ✅ QR con nombre completo, CI, distrito y U.E.
    new QRCode(document.getElementById("qr-{{ $p->idPersona }}"), {
        text: [
            "CI: {{ $p->ci }}",
            "NOMBRE: {{ $p->nombre }} {{ $p->apellidos }}",
            "DISTRITO: {{ $p->distrito }}",
            "U.E.: {{ $p->tipoInstitucion }}"
        ].join("\n"),
        width: 125,   // ✅ más pequeño para que quepan los datos abajo
        height: 125,
        correctLevel: QRCode.CorrectLevel.M
    });
    @endforeach
});

// ✅ Imprimir directo sin html2canvas — mantiene tamaño PVC exacto
function imprimirPDF() {
    window.print();
}



</script>

@endsection