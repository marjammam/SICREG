@extends('layouts.header')

@section('content')
<link rel="stylesheet" href="{{ asset('css/cliente.css') }}">

<div class="tabla-clientes">

<div class="barra-superior">

    <div class="buscador">
    <input type="text" placeholder="Escriba aquí el nombre del cliente">
    <button class="btn-buscar"><i class="fas fa-search"></i></button>
    </div>
    <div class="acciones-superior">
    <button class="btn-registrar"onclick="abrirModal()"><i class="fa fa-plus"></i>  Registrar</button>
    <button class="btn-imprimir" onclick="Credenciales()"><i class="fa fa-print"></i>  Imprimir</button>
    </div>

</div>

<div class="tabla-scroll">
<table>
<thead>
<tr>
<th><input type="checkbox" id="checkAll"></th>
<th>Nro</th>
<th>C.I.</th>
<th>Nombres</th>
<th>Apellidos</th>
<th>Distrito</th>
<th>Unidad Educativa</th>
<th></th>
</tr>
</thead>

<tbody>
@foreach($personas as $i => $p)
<tr>
<td>
<input type="checkbox" class="check-item"value="{{ $p->idPersona }}">
</td>
<td>{{ $i + 1 }}</td>

<td>{{ $p->ci }}</td>

<td>{{ $p->nombre }}</td>

<td>{{ $p->apellidos }}</td>

<td>{{ $p->distrito }}</td>

<td>{{ $p->tipoInstitucion }}</td>
<td class="acciones">
<span class="editar">✏️</span>
<span class="eliminar">🗑</span>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
</div>

<div id="modalCliente" class="modal">

<div class="modal-card">

<div class="modal-header">
<h2>Registrar Cliente</h2>
<button class="cerrar" onclick="cerrarModal()">✕</button>
</div>

<form class="form-cliente">

<div class="grid-form">

<div class="campo">
<label>C.I.</label>
<input type="text" placeholder="Carnet de identidad">
</div>


<div class="campo">
<label>Nombres</label>
<input type="text">
</div>

<div class="campo">
<label>Apellidos</label>
<input type="text">
</div>

<div class="campo">
<label>Distrito</label>
<select>
<option>Seleccionar</option>
<option>1</option>
<option>2</option>
</select>
</div>

<div class="campo">
<label>Unidad Educativa</label>
<input type="text">
</div>

<div class="campo">
<label>Foto</label>
<input type="file">
</div>

</div>

<div class="acciones-modal">
<button type="submit" class="btn-guardar">Guardar</button>
<button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
</div>

</form>

</div>
</div>



<div id="modalEvento" class="modaleve">

<div class="modal-contenidoeve">

<h3>Seleccionar Evento</h3>

<select id="evento">
<option value="">Seleccione evento</option>
<option value="Congreso">Congreso</option>
<option value="Asamblea">Asamblea</option>
<option value="Reunion">Reunion</option>
</select>
<button class="btn-modaleve" onclick="confirmarEvento()">Generar Credenciales</button>

<script>

let personasSeleccionadas = [];

/* abrir modal */

function Credenciales(){

personasSeleccionadas = [];

document.querySelectorAll(".check-item:checked").forEach(function(el){

personasSeleccionadas.push(el.value);

});

if(personasSeleccionadas.length === 0){

alert("Seleccione personas");

return;

}

document.getElementById("modalEvento").style.display = "block";

}

/* confirmar evento */

function confirmarEvento(){

let evento = document.getElementById("evento").value;

if(evento === ""){

alert("Seleccione evento");

return;

}

/* cerrar modal */

document.getElementById("modalEvento").style.display = "none";

/* generar PDF */

generarCredenciales(evento);

}

/* enviar al servidor */

function generarCredenciales(evento){

let form = document.createElement("form");

form.method = "POST";
form.action = "/credenciales/imprimir";
form.target = "_blank";

/* token */

let token = document.createElement("input");

token.type = "hidden";
token.name = "_token";
token.value = "{{ csrf_token() }}";

form.appendChild(token);

/* personas */

let ids = document.createElement("input");

ids.type = "hidden";
ids.name = "personas";
ids.value = JSON.stringify(personasSeleccionadas);

form.appendChild(ids);

/* evento */

let ev = document.createElement("input");

ev.type = "hidden";
ev.name = "evento";
ev.value = evento;

form.appendChild(ev);

document.body.appendChild(form);

form.submit();

}

</script>

<script>


/*modal registro cliente*/
function abrirModal(){
document.getElementById("modalCliente").style.display="flex";
}

function cerrarModal(){
document.getElementById("modalCliente").style.display="none";
}

/* cerrar haciendo clic fuera */

window.onclick = function(e){
let modal = document.getElementById("modalCliente");
if(e.target === modal){
modal.style.display="none";
}
}

</script>
@endsection