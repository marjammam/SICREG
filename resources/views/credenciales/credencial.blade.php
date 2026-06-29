@extends('layouts.header')

@section('content')
<link rel="stylesheet" href="{{ asset('css/credencial.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qz-tray/qz-tray.js"></script>

<div class="contenedor">
<!-- COLUMNA FORMULARIO -->
<div class="formulario">
    <!-- BUSCADOR -->
    <div class="buscador">
        <input type="text" id="buscar_ci" placeholder="Escriba aquí su carnet de identidad"
         onkeypress="if(event.key === 'Enter') buscarPersona()">
        <button class="btn-buscar"onclick="buscarPersona()"><i class="fas fa-search"></i></button>
    </div>

      <!-- CAMPOS -->
    <label>NOMBRE(S):</label>
    <input type="text" id="nombre" disabled>
    <label>APELLIDO(S):</label>
    <input type="text" id="apellidos">
    <label>CARNET DE IDENTIDAD:</label>
    <input type="text" id="ci" disabled>
    <label>TIPO DE INSTITUCION:</label>
    <div class="radio">
        <label class="radio-label">
            <input type="radio" name="tipo_institucion" value="ue" id="ue"> U.E.
        </label>
        <label class="radio-label">
            <input type="radio" name="tipo_institucion" value="sindicato" id="sindicato"> SINDICATO
        </label>
    </div>
    <input type="text" id="institucion" oninput="this.value = this.value.toUpperCase()">
    <label>DISTRITO:</label>
    <input type="text" id="distrito" oninput="this.value = this.value.toUpperCase()">
    <label>SUBIR IMAGEN:</label>
    <div class="upload">
    <input type="file" id="fotoInput" accept="image/png, image/jpeg">
    </div>

<button class="btn-generar"onclick="generarCredencial()">GENERAR CREDENCIAL</button>
<button class="btn-cancelar" onclick="cancelarFormulario()">CANCELAR</button>


</div>


<!-- COLUMNA CREDENCIAL -->
<div class="credenciales">

<div class="cards">
<!-- FRENTE -->
<div class="credencial-box" id="credencial-frente">
    <div class="credencial frente">
        <img src="/image/FRENTE.png" class="bg">
        <div class="foto" id="fotoCredencial"></div>
        <div id="etiqueta-sindicato" style="display:none;">SINDICATO</div>
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
        <img src="/image/ATRAS.png" class="bg-atras">
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
        <button class="btn-pdf" onclick="impresionImgF()"> Descargar Imagen</button>
        <button class="btn-imp" onclick="imprimirFrente()"> Impresion Directa</button>
        
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
        <button class="btn-pdf" onclick="impresionImgA()"> Descargar Imagen</button>
        <button class="btn-imp" onclick="imprimirAtras()"> Impresion Directa</button>
    </div>
  </div>
</div>




<script>
let personaActualId = null;

//Funcion buscar persona por CI y cargar datos en formulario
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

//funcion generar credencial, cargar datos en credencial, generar QR

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

    /* cambiar texto U.E. o Cargo + etiqueta SINDICATO debajo de foto */
    let etiquetaSindicato = document.getElementById("etiqueta-sindicato");
        if(tipo === "sindicato"){
            document.getElementById("labelInstitucion").innerText = "Cargo:";
            // Mostrar etiqueta SINDICATO debajo de la foto
            if(etiquetaSindicato) etiquetaSindicato.style.display = "block";
            document.querySelector(".datos").style.top = "255px";
        } else {
            document.getElementById("labelInstitucion").innerText = "U.E.:";
            // Ocultar etiqueta si no es sindicato
            if(etiquetaSindicato) etiquetaSindicato.style.display = "none";
            document.querySelector(".datos").style.top = "250px";
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


    document.querySelectorAll('input[name="tipo_institucion"]').forEach(function(radio){
        radio.addEventListener('change', function(){
            if(this.value === "sindicato"){
                document.getElementById("institucion").value = "";
                document.getElementById("institucion").placeholder = "Ingrese cargo...";
            } else {
                document.getElementById("institucion").value = "";
                document.getElementById("institucion").placeholder = "Ingrese institución...";
            }
        });
    });
}

//Registrar credencial en la base de datos
function registrarCredencial(){
    let tipo = document.querySelector('input[name="tipo_institucion"]:checked');
    
    // verificar que haya una persona buscada
    if(!personaActualId){
        console.error('No hay persona seleccionada');
        return;
    }
    // verificar que haya tipo seleccionado
    if(!tipo){
        console.error('No hay tipo de institución seleccionado');
        return;
    }
    let datosRegistro = {
        Persona_idPersona: personaActualId,
        carnet:           document.getElementById("ci").value,
        tipo_institucion: tipo.value,
        cargo:            tipo.value === "sindicato" 
                          ? document.getElementById("institucion").value 
                          : null
    };
    fetch('/credencial-persona', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(datosRegistro)
    })

    .then(response => response.json())
    .then(data => {
        if(data.success){
            console.log('Credencial registrada correctamente');
        }
    })
    .catch(error => console.error('Error al registrar credencial:', error));
}



//funcion limpiar formulario y credencial
function cancelarFormulario(){
    // Limpiar buscador
    document.getElementById("buscar_ci").value = "";

    // Limpiar inputs
    document.getElementById("nombre").value = "";
    document.getElementById("apellidos").value = "";
    document.getElementById("ci").value = "";
    document.getElementById("institucion").value = "";
    document.getElementById("distrito").value = "";

    // Limpiar radio
    document.querySelectorAll('input[name="tipo_institucion"]').forEach(r => r.checked = false);

    // Limpiar foto
    document.getElementById("fotoInput").value = "";

    // Limpiar credencial generada
    document.getElementById("cred-nombre").innerText = "";
    document.getElementById("cred-apellidos").innerText = "";
    document.getElementById("cred-ci").innerText = "";
    document.getElementById("cred-institucion").innerText = "";
    document.getElementById("cred-distrito").innerText = "";
    document.getElementById("fotoCredencial").style.backgroundImage = "url('/image/perfil.png')";
    document.getElementById("qrCredencial").innerHTML = "";

    // Ocultar etiqueta sindicato
    let etiqueta = document.getElementById("etiqueta-sindicato");
    if(etiqueta) etiqueta.style.display = "none";

    // Resetear label
    document.getElementById("labelInstitucion").innerText = "U.E.:";
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




//funcion para impresion por imagen

async function impresionImgF() {
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
    registrarCredencial();
    cerrarModal();
}

async function impresionImgA() {
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

    cerrarModalA();
}




//Funcion impresion directa 

async function imprimirFrente() {
    let credencial = document.querySelector("#credencial-frente .credencial");
    if (!credencial) {
        alert("Error: No se encontró el diseño de la credencial");
        return;
    }

    // ✅ Misma captura que usas para descargar
    let canvas = await html2canvas(credencial, {
        scale: 4,
        useCORS: true,
        backgroundColor: null
    });

    imprimirCanvasPVC(canvas);
    cerrarModal();
}

async function imprimirAtras() {
    let credencial = document.querySelector("#credencial-atras .credencial");
    if (!credencial) {
        alert("Error: No se encontró el diseño de la credencial");
        return;
    }

    await new Promise(r => setTimeout(r, 500));

    let canvas = await html2canvas(credencial, {
        scale: 4,
        useCORS: true,
        backgroundColor: null
    });

    if (canvas.toDataURL("image/png").length < 10000) {
        alert("❌ Error: credencial vacía");
        return;
    }

    imprimirCanvasPVC(canvas);
    cerrarModalA();
}

// ✅ Imprime ajustado a tarjeta PVC vertical 54x86mm
function imprimirCanvasPVC(canvas) {
    let imgData = canvas.toDataURL("image/png");

    let iframe = document.getElementById('iframe-print');
    if (iframe) document.body.removeChild(iframe);

    iframe = document.createElement('iframe');
    iframe.id = 'iframe-print';
    iframe.style.cssText = "position:fixed; top:-10000px; left:-10000px; width:0; height:0; border:none;";
    document.body.appendChild(iframe);

    let doc = iframe.contentWindow.document;
    doc.open();
    doc.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                @page {
                    size: 54mm 86mm portrait; /* ✅ PVC vertical */
                    margin: 0;
                }
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                html, body {
                    width: 54mm;
                    height: 86mm;
                    overflow: hidden;
                    background: transparent;
                }
                img {
                    width: 54mm;
                    height: 86mm;
                    object-fit: fill;  /* ✅ ocupa exactamente la tarjeta */
                    display: block;
                }
            </style>
        </head>
        <body>
            <img src="${imgData}" />
        </body>
        </html>
    `);
    doc.close();

    iframe.contentWindow.onload = function() {
        setTimeout(() => {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
        }, 500);
    };
}




</script>

@endsection