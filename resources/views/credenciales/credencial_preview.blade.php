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

<img src="{{ asset('image/credencialM.png') }}" class="fondo">

<img src="{{ asset('image/logoF.png') }}" class="logo">

<div class="titulo">{{ $evento }}</div>
<div class="qr" id="qr-{{ $p->idPersona }}"></div>

<div class="datos">

<div class="nombre">
{{ $p->nombre }} {{ $p->apellidos }}
</div>

<div class="info">
<p> {{ $p->tipoInstitucion }}</p>
<p> {{ $p->distrito }}</p>
<p><b>C.I.:</b> {{ $p->ci }}</p>
</div>

</div>

</div>

@endforeach

</div>

<script>
    document.addEventListener("DOMContentLoaded", function(){
    @foreach($personas as $p)
    new QRCode(document.getElementById("qr-{{ $p->idPersona }}"), {
        text: "CI: {{ $p->ci }}",
        width: 120,
        height: 120
    });

    @endforeach

    });

    async function esperarQR(){

    return new Promise(resolve=>{
    setTimeout(resolve, 500); // espera a que renderice QR
    });

    }
    async function imprimirPDF(){

    await esperarQR();

    const { jsPDF } = window.jspdf;

    let hoja=document.getElementById("hoja");

    let canvas=await html2canvas(hoja,{scale:2});

    let img=canvas.toDataURL("image/png");

    let pdf=new jsPDF('p','mm','a4');

    pdf.addImage(img,'PNG',0,0,210,297);

    let blobUrl = pdf.output('bloburl');

    window.open(blobUrl);

    }
    

</script>

@endsection