@extends('layouts.header')

@section('content')
<link rel="stylesheet" href="{{ asset('css/credencial.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qz-tray/qz-tray.js"></script>

<div style="text-align: right; margin-right: 10px;">
    <a href="{{ route('credenciales.exportar') }}" class="btn-generar" style="text-decoration: none; display: inline-block; text-align: center; width: fit-content;">EXPORTAR EXCEL</a>
</div>

<div class="contenedor">
<!-- COLUMNA FORMULARIO -->
<div class="formulario">
    <div class="buscador">
    <input type="text" id="buscar_ci" placeholder="Escriba aquí su carnet de identidad"
    onkeypress="if(event.key === 'Enter') buscarPersona()">
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
        <img src="/image/FRENTE.png" class="bg">
        <div class="foto" id="fotoCredencial"></div>
        <!--<img src="{{ asset('image/perfil.png') }}" class="foto" id="fotoCredencial">-->
        <div class="datos">
        <p><b>Nombre(s):</b> <span id="cred-nombre"></span></p>
        <p><b>Apellido(s):</b> <span id="cred-apellidos"></span></p>
        <p><b>C.I.:</b> <span id="cred-ci"></span></p>
        <p><b id="labelInstitucion">U.E.:</b> <span id="cred-institucion"></span></p>
        <p><b>Distrito:</b> <span id="cred-distrito"></span></p>
        </div>
    </div>
       <br>
    <button class="btn-print" onclick="abrirImprimir()">IMPRIMIR</button>
</div>



<div class="credencial-box" id="credencial-atras">
    <div class="credencial atras">
        <div class="contenido-superior">
            <div class="titulo"> 
                <br>Secretaría de Organización y<br>
                Vinculación Sindical
            </div>
            <div class="qr" id="qrCredencial"></div>
        </div>

        <div class="seccion-firmas">
            <div class="firma-col">
                <img src="image/ejecutivo_firma.png" class="img-firma">
                <p class="nombre-firma">Prof. Wilson Velasquez Pinto</p>
                <p class="cargo-firma">EJECUTIVO GENERAL <br>
                                        F.D.T.E.U.C.</p>
               
                
            </div>
            <div class="firma-col">
                <img src="image/secretario_firma.png" class="img-firma">
                  <p class="nombre-firma">Prof. Luis Villarroel Castellon</p>
                <p class="cargo-firma">STRIA. DE ORGANIZACIÓN Y VINCULACIÓN SINDICAL</p>
               
            </div>
        </div>
    </div>
    <br>
    <button class="btn-print" onclick="abrirImprimirA()">IMPRIMIR</button>
</div>

<!-- Modal para impresion-->
<div id="modalImprimir" class="modal">
  <div class="modal-content">
    <!-- BOTÓN X -->
    <span class="close" onclick="cerrarModal()">×</span>
    <h3>Imprimir Credencial</h3>
    <div class="opciones">
        <button class="btn-imp" onclick="imprimirFrente()"> Impresora Directa</button>
        <button class="btn-pdf" onclick="pdfFrente()"> Descargar Imagen</button>
    </div>
  </div>
</div>

<!-- Modal para impresion-->
<div id="modalImprimirA" class="modal">
  <div class="modal-content">
    <!-- BOTÓN X -->
    <span class="close" onclick="cerrarModalA()">×</span>
    <h3>Imprimir Credencial</h3>
    <div class="opciones">
        <button class="btn-imp" onclick="imprimirAtras()"> Impresora directa</button>
        <button class="btn-pdf" onclick="pdfAtras()"> Descargar PDF</button>
    </div>
  </div>
</div>




<script>
let personaActualId = null;

function buscarPersona(){
    let ci = document.getElementById("buscar_ci").value.trim();
    if(ci === ""){
        alert("Ingrese un CI");
        return;
    }
    fetch("/buscar-persona/" + ci)
    .then(response => response.json())
    .then(data => {
        if(!data){
            alert("Persona no encontrada");
            return;
        }
        personaActualId = data.idPersona;

        document.getElementById("nombre").value = data.nombre;
        document.getElementById("apellidos").value = data.apellidos;
        document.getElementById("ci").value = data.ci;
        document.getElementById("institucion").value = data.tipoInstitucion;
        document.getElementById("distrito").value = data.distrito;
        document.getElementById("ue").checked = true;

        let fotoCredencial = document.getElementById("fotoCredencial");

        if (data.foto) {
            fetch(`/cliente/fotos/${data.foto}`)
                .then(response => {
                    if (response.ok) {
                        fotoCredencial.style.backgroundImage = `url('/cliente/fotos/${data.foto}')`;
                    } else {
                        fotoCredencial.style.backgroundImage = `url('/image/perfil.png')`;
                    }
                })
                .catch(error => {
                    console.error(error);
                    fotoCredencial.style.backgroundImage = `url('/image/perfil.png')`;
                });
        } else {
            fotoCredencial.style.backgroundImage = `url('/image/perfil.png')`;
        }
    })
    .catch(error => console.error(error));
}
document.getElementById("buscar_ci").addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
        buscarPersona();
    }
});



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
document.getElementById("fotoCredencial").style.backgroundImage = `url(${e.target.result})`;
/*document.getElementById("fotoCredencial").src = e.target.result;*/
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

fetch('/credencial-persona', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
        codigoQR: datosQR,
        Persona_idPersona: personaActualId
    })
})
.then(response => response.json())
.then(data => {
    if(data.success) {
        console.log('Credencial registrada correctamente en CredencialPersona');
    }
})
.catch(error => console.error('Error al registrar credencial:', error));
}
</script>

<script>

/*ABRIR MODAL DE IMPRESIÓN*/
function abrirImprimir(){
    document.getElementById("modalImprimir").style.display = "flex";
}
function cerrarModal(){
    document.getElementById("modalImprimir").style.display = "none";
}

// cerrar haciendo clic afuera
window.onclick = function(e){
    let modal = document.getElementById("modalImprimir");
    if(e.target === modal){
        modal.style.display = "none";
    }
}

function abrirImprimirA(){
    document.getElementById("modalImprimirA").style.display = "flex";
}
function cerrarModalA(){
    document.getElementById("modalImprimirA").style.display = "none";
}

// cerrar haciendo clic afuera
window.onclick = function(e){
    let modal = document.getElementById("modalImprimirA");
    if(e.target === modal){
        modal.style.display = "none";
    }
}


async function conectarQZ(){
    if(!qz.websocket.isActive()){
        try{
            await qz.websocket.connect();
            console.log("✅ QZ conectado");
        }catch(err){
            console.error("❌ Error QZ:", err);
        }
    }
}

async function imprimirFrente() {

    await conectarQZ();

    let original = document.querySelector("#credencial-frente .credencial");
    if (!original) {
        alert("No se encontró la credencial");
        return;
    }

    // === CLONAR ELEMENTO ===
    let clone = original.cloneNode(true);

    // Contenedor oculto
    let container = document.createElement("div");
    container.style.position = "fixed";
    container.style.top = "-10000px";
    container.style.left = "-10000px";
    container.style.background = "#fff";

    // === TAMAÑO REAL PVC EN PX (300 DPI) ===
    clone.style.width = "638px";
    clone.style.height = "1011px";
    clone.style.transform = "scale(1)";
    clone.style.transformOrigin = "top left";

    container.appendChild(clone);
    document.body.appendChild(container);

    await new Promise(r => setTimeout(r, 300));

    // Captura GRANDE
    let canvas = await html2canvas(clone, {
        scale: 1,
        useCORS: true,
        backgroundColor: "#ffffff"
    });

    document.body.removeChild(container);

    let base64 = canvas.toDataURL("image/png").split(',')[1];

    // === QZ CONFIG EN PX (A4) ===
    let config = qz.configs.create("Epson L8050 Series", {
        size: { width: 2480, height: 3508 }, // A4 en px (300 DPI)
        units: "px",
        density: 300
    });

    let data = [{
        type: 'image',
        format: 'base64',
        data: base64,
        options: {
            x: 50,   // margen izquierdo
            y: 50    // margen superior
        }
    }];

    try {
        await qz.print(config, data);
        console.log("✅ IMPRESIÓN PERFECTA");
    } catch (err) {
        console.error(err);
        alert("Error: " + err);
    }
}







async function pdfFrente() {
    let credencial = document.querySelector("#credencial-frente .credencial");
    
    // 1. Obtener el CI del usuario desde el DOM
    // Ajusta el selector '#ci-usuario' al que estés usando en tu HTML
    let ciElement = document.querySelector("#cred-ci"); 
    let ciValue = ciElement ? ciElement.innerText.trim() : "credencial";

    if (!credencial) {
        alert("Error: No se encontró el diseño de la credencial");
        return;
    }

    let canvas = await html2canvas(credencial, {
        scale: 4, 
        useCORS: true,
        backgroundColor: null 
    });

    let imgData = canvas.toDataURL("image/png");

    let link = document.createElement('a');
    
    // 2. Aplicamos el nombre dinámico: CI + _frente.png
    link.download = `${ciValue}_frente.png`; 
    
    link.href = imgData;
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}







async function imprimirAtras(){
let credencial = document.querySelector("#credencial-atras .credencial");
if(!credencial){
    alert("Error: no se encontró la credencial");
    return;
}
await new Promise(resolve => setTimeout(resolve, 500));
let canvas = await html2canvas(credencial, {
    scale:2,
    useCORS: true,
    backgroundColor: "#ffffff"
});
let base64Full = canvas.toDataURL("image/png");
if(base64Full.length < 10000){
    alert("❌ Error: credencial vacía");
    return;
}
let base64 = base64Full.split(',')[1];
let config = qz.configs.create("Epson L8050 Series", {
    size: { width: 54, height: 85.6, units: "mm" },
    margins: 0,
    scaleContent: true,
    density: 300
});
let data = [{
    type: 'image',
    format: 'base64',
    data: base64
}];
await qz.print(config, data);

}



async function pdfAtras() {
    let credencial = document.querySelector("#credencial-atras .credencial");
    
    // 1. Obtener el CI del usuario desde el DOM
    // Asegúrate de que el elemento que contiene el CI tenga el id="ci-usuario"
    let ciElement = document.querySelector("#cred-ci"); 
    let ciValue = ciElement ? ciElement.innerText.trim() : "credencial";

    if (!credencial) {
        alert("Error: No se encontró la parte posterior de la credencial");
        return;
    }

    // Esperar renderizado para asegurar que el QR y las firmas carguen bien
    await new Promise(resolve => setTimeout(resolve, 500));

    // Generar el canvas con escala 3 para mejor nitidez en PVC
    let canvas = await html2canvas(credencial, {
        scale: 3, 
        useCORS: true,
        backgroundColor: "#ffffff"
    });

    // Convertir a base64
    let imgData = canvas.toDataURL("image/png");

    // Crear el proceso de descarga
    let link = document.createElement('a');
    
    // 2. Nombre dinámico aumentado: CI + _atras.png
    link.download = `${ciValue}_atras.png`; 
    
    link.href = imgData;
    
    // Ejecutar descarga
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    console.log(`✅ Imagen trasera descargada como: ${ciValue}_atras.png`);
}

</script>

@endsection