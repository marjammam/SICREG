@extends('layouts.header')

@section('content')
<link rel="stylesheet" href="{{ asset('css/credencial.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<div class="contenedor">

<!-- COLUMNA FORMULARIO -->
<div class="formulario">

    <div class="buscador">
    <input type="text"  id="buscar_ci" placeholder="Escriba aquí su carnet de identidad">
    <button class="btn-buscar"onclick="buscarPersona()"><i class="fas fa-search"></i></button>
    </div>

    <label>NOMBRE(S):</label>
    <input type="text" id="nombre" disabled>

    <label>APELLIDO(S):</label>
    <input type="text" id="apellidos" disabled>

    <label>CARNET DE IDENTIDAD:</label>
    <input type="text" id="ci" disabled>

    <label>TIPO DE INSTITUCION:</label>

    <div class="radio">
    <input type="radio" name="tipo_institucion" value="ue"id="ue">U.E.
    <input type="radio" name="tipo_institucion" value="sindicato" id="sindicato">SINDICATO
    </div>

    <input type="text" id="institucion">

    
    <label>DISTRITO:</label>
    <input type="text" id="distrito">

    <label>SUBIR IMAGEN:</label>

    <div class="upload">
    <input type="file" id="fotoInput" accept="image/png, image/jpeg">
    </div>

<button class="btn-generar"onclick="generarCredencial()">GENERAR CREDENCIAL</button>
<button class="btn-cancelar">CANCELAR</button>

</div>

<!-- COLUMNA CREDENCIAL -->
<div class="credenciales">

<h2>Registro Credencial</h2>

<div class="cards">

<!-- FRENTE -->
<div class="credencial-box" id="credencial-frente">

    <div class="credencial frente">

        <img src="{{ asset('image/perfil.png') }}" class="foto" id="fotoCredencial">

        <div class="datos">
        <p><b>Nombre(s):</b> <span id="cred-nombre"></span></p>
        <p><b>Apellido(s):</b> <span id="cred-apellidos"></span></p>
        <p><b>C.I.:</b> <span id="cred-ci"></span></p>
        <p><b id="labelInstitucion">U.E.:</b> <span id="cred-institucion"></span></p>
        <p><b>Distrito:</b> <span id="cred-distrito"></span></p>
        </div>

    </div>
       <br>
    <button class="btn-print" onclick="pdfFrente()">IMPRIMIR</button>

</div>


<!-- ATRAS -->
<div class="credencial-box"id="credencial-atras">
    <div class="credencial atras">
        <div class="titulo">
        Secretaria de Organizacion y<br>
        Vinculacion Sindical
        </div>
        <div class="qr" id="qrCredencial"></div>
    </div>
    <br>
    <button class="btn-print" onclick="pdfAtras()">IMPRIMIR</button>
</div>

</div>
</div>
<script>

    function buscarPersona(){

    let ci = document.getElementById("buscar_ci").value;

    fetch("/buscar-persona/" + ci)
    .then(response => response.json())
    .then(data => {

    if(!data){
    alert("Persona no encontrada");
    return;
    }

    document.getElementById("nombre").value = data.nombre;
    document.getElementById("apellidos").value = data.apellidos;
    document.getElementById("ci").value = data.ci;
    document.getElementById("institucion").value = data.tipoInstitucion;
    document.getElementById("distrito").value = data.distrito;

    /* marcar radio automáticamente */

    document.getElementById("ue").checked = true;

    })
    .catch(error => console.error(error));

    }

</script>
<script>

function generarCredencial(){

let nombre = document.getElementById("nombre").value;
let apellidos = document.getElementById("apellidos").value;
let ci = document.getElementById("ci").value;
let institucion = document.getElementById("institucion").value;
let distrito = document.getElementById("distrito").value;

let tipo = document.querySelector('input[name="tipo_institucion"]:checked').value;

/* cargar datos en credencial */

document.getElementById("cred-nombre").innerText = nombre;
document.getElementById("cred-apellidos").innerText = apellidos;
document.getElementById("cred-ci").innerText = ci;
document.getElementById("cred-institucion").innerText = institucion;
document.getElementById("cred-distrito").innerText = distrito;

/* cambiar texto U.E. o Cargo */

if(tipo === "sindicato"){
document.getElementById("labelInstitucion").innerText = "Cargo:";
}else{
document.getElementById("labelInstitucion").innerText = "U.E.:";
}
/* cargar foto en credencial */
let inputFoto = document.getElementById("fotoInput");

if(inputFoto.files && inputFoto.files[0]){

let lector = new FileReader();

lector.onload = function(e){

document.getElementById("fotoCredencial").src = e.target.result;

}

lector.readAsDataURL(inputFoto.files[0]);

}

/* generar QR */

let datosQR =
"CI: " + ci + "\n" +
"Nombre: " + nombre + " " + apellidos + "\n" +
"Institucion: " + institucion + "\n" +
"Distrito: " + distrito;

/* limpiar QR anterior */

document.getElementById("qrCredencial").innerHTML = "";

new QRCode(document.getElementById("qrCredencial"),{
text: datosQR,
width:150,
height:150
});
}
/* Funciones para generar PDF para imprimir */
async function pdfFrente(){

const { jsPDF } = window.jspdf;

let credencial=document.querySelector("#credencial-frente .credencial");

let canvas=await html2canvas(credencial,{
scale:3
});

let img=canvas.toDataURL("image/png");

let pdf=new jsPDF({
orientation:"portrait",
unit:"mm",
format:[54,85.6]
});

pdf.addImage(img,"PNG",0,0,54,85.6);

pdf.save("credencial_frente.pdf");

}

async function pdfAtras(){

const { jsPDF } = window.jspdf;

let credencial=document.querySelector("#credencial-atras .credencial");

let canvas=await html2canvas(credencial,{
scale:3
});

let img=canvas.toDataURL("image/png");

let pdf=new jsPDF({
orientation:"portrait",
unit:"mm",
format:[54,85.6]
});

pdf.addImage(img,"PNG",0,0,54,85.6);

pdf.save("credencial_atras.pdf");

}

</script>

@endsection