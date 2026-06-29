@extends('layouts.header')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/cliente.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
   

    <div class="tabla-clientes">

        <div class="barra-superior">
            <form class="buscador" method="POST" action="/cliente">
                @csrf
                <input type="text" name="nombre" placeholder="Escriba aquí el nombre del cliente">
                <button class="btn-buscar" type="submit"><i class="fas fa-search"></i></button>
            </form>
            <div class="acciones-superior">
                <button class="btn-registrar"onclick="abrirModal()"><i class="fa fa-plus"></i> Registrar</button>
                <button class="btn-imprimir" onclick="Credenciales()"> Generar Credenciales</button>
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
                    @forelse ($personas as $i => $p)
                        <tr data-id="{{ $p->idPersona }}" data-nombre="{{ $p->nombre }}"
                            data-apellido="{{ $p->apellidos }}"data-ci="{{ $p->ci }}">
                            <td><input type="checkbox" class="check-item" value="{{ $p->idPersona }}"></td>
                            <td>{{ $personas->firstItem() + $i }}</td>
                            <td>{{ $p->ci }}</td>
                            <td>{{ $p->nombre }}</td>
                            <td>{{ $p->apellidos }}</td>
                            <td>{{ $p->distrito }}</td>
                            <td>{{ $p->tipoInstitucion }}</td>
                            <td class="acciones">
                                <i class="fa-solid fa-pen-to-square btn-accion btn-editar" onclick="edit(event, {{ $p }})"></i>
                                <i class="fa-solid fa-trash btn-accion btn-eliminar" onclick="deleteById(event, {{ $p->idPersona }})"></i>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 20px;">No hay personas registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $personas->links() }}
        </div>
    </div>

    <div id="modalCliente" class="modal">
        <div class="modal-card">
            <div class="modal-header">
                <h2>Registrar Nuevo Profesor</h2>
                <button class="cerrar" onclick="cerrarModal()">✕</button>
            </div>
            <form
                id="formCliente"
                class="form-cliente"
                action="{{ old('personaId') ? '/cliente/' . old('personaId') : '/cliente/store' }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf
                <input
                    type="hidden"
                    id="form-method"
                    name="_method"
                    value="PATCH"
                    {{ old('personaId') ? '' : 'disabled' }}
                >
                <input
                    type="hidden"
                    id="personaId"
                    name="personaId"
                    value="{{ old('personaId') }}"
                    {{ old('personaId') ? '' : 'disabled' }}
                >

                <div class="grid-form">
                    <div class="campo">
                        <label>C.I.</label>
                        <input
                            id="ci"
                            type="text"
                            name="ci"
                            placeholder="Carnet de identidad"
                            value="{{ old('ci') }}"
                            oninput="this.value = this.value.toUpperCase()"
                            class="@error('ci') is-invalid @enderror"
                        >
                        @error('ci')
                            <div class="alert-msg">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="campo">
                        <label>Nombres</label>
                        <input
                            id="nombre"
                            type="text"
                            name="nombre"
                            placeholder="Nombres"
                            value="{{ old('nombre') }}"
                            oninput="this.value = this.value.toUpperCase()"
                            class="@error('nombre') is-invalid @enderror"
                        >
                        @error('nombre')
                            <div class="alert-msg">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="campo">
                        <label>Apellidos</label>
                        <input
                            id="apellidos"
                            type="text"
                            name="apellidos"
                            placeholder="Apellidos"
                            value="{{ old('apellidos') }}"
                            oninput="this.value = this.value.toUpperCase()"
                            class="@error('apellidos') is-invalid @enderror"
                        >
                        @error('apellidos')
                            <div class="alert-msg">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="campo">
                        <label>Distrito</label>
                        <select
                            id="distrito"
                            name="distrito"
                            onchange="toggleOtroDistrito(this.value)"
                            class="@error('distrito') is-invalid @enderror"
                        >

                        <option value="">Seleccionar</option>
                            <option value="ANZALDO" {{ old('distrito') == 'ANZALDO' ? 'selected' : '' }}>ANZALDO</option>
                            <option value="ARANI" {{ old('distrito') == 'ARANI' ? 'selected' : '' }}>ARANI</option>
                            <option value="ARBIETO" {{ old('distrito') == 'ARBIETO' ? 'selected' : '' }}>ARBIETO</option>
                            <option value="ARQUE" {{ old('distrito') == 'ARQUE' ? 'selected' : '' }}>ARQUE</option>
                            <option value="AYOPAYA" {{ old('distrito') == 'AYOPAYA' ? 'selected' : '' }}>AYOPAYA</option>
                            <option value="CAPINOTA" {{ old('distrito') == 'CAPINOTA' ? 'selected' : '' }}>CAPINOTA</option>
                            <option value="CHIMORE" {{ old('distrito') == 'CHIMORE' ? 'selected' : '' }}>CHIMORE</option>
                            <option value="CLIZA" {{ old('distrito') == 'CLIZA' ? 'selected' : '' }}>CLIZA</option>
                            <option value="COCHABAMBA 1" {{ old('distrito') == 'COCHABAMBA 1' ? 'selected' : '' }}>COCHABAMBA 1</option>
                            <option value="COCHABAMBA 2" {{ old('distrito') == 'COCHABAMBA 2' ? 'selected' : '' }}>COCHABAMBA 2</option>
                            <option value="COLCAPIRHUA" {{ old('distrito') == 'COLCAPIRHUA' ? 'selected' : '' }}>COLCAPIRHUA</option>
                            <option value="COLOMI" {{ old('distrito') == 'COLOMI' ? 'selected' : '' }}>COLOMI</option>
                            <option value="ENTRE RIOS" {{ old('distrito') == 'ENTRE RIOS' ? 'selected' : '' }}>ENTRE RIOS</option>
                            <option value="MIZQUE" {{ old('distrito') == 'MIZQUE' ? 'selected' : '' }}>MIZQUE</option>
                            <option value="MOROCHATA" {{ old('distrito') == 'MOROCHATA' ? 'selected' : '' }}>MOROCHATA</option>
                            <option value="OMEREQUE" {{ old('distrito') == 'OMEREQUE' ? 'selected' : '' }}>OMEREQUE</option>
                            <option value="PASORAPA" {{ old('distrito') == 'PASORAPA' ? 'selected' : '' }}>PASORAPA</option>
                            <option value="POJO" {{ old('distrito') == 'POJO' ? 'selected' : '' }}>POJO</option>
                            <option value="PUERTO VILLARROEL" {{ old('distrito') == 'PUERTOVILLARROEL' ? 'selected' : '' }}>PUERTO VILLARROEL</option>
                            <option value="PUNATA" {{ old('distrito') == 'PUNATA' ? 'selected' : '' }}>PUNATA</option>
                            <option value="QUILLACOLLO" {{ old('distrito') == 'QUILLACOLLO' ? 'selected' : '' }}>QUILLACOLLO</option>
                            <option value="SACABA" {{ old('distrito') == 'SACABA' ? 'selected' : '' }}>SACABA</option>
                            <option value="SAN BENITO" {{ old('distrito') == 'SAN BENITO' ? 'selected' : '' }}>SAN BENITO</option>
                            <option value="SANTIVANIEZ" {{ old('distrito') == 'SANTIVANIEZ' ? 'selected' : '' }}>SANTIVANIEZ</option>
                            <option value="SHINAHOTA" {{ old('distrito') == 'SHINAHOTA' ? 'selected' : '' }}>SHINAHOTA</option>
                            <option value="SIPE SIPE" {{ old('distrito') == 'SIPE SIPE' ? 'selected' : '' }}>SIPE SIPE</option>
                            <option value="TAPACARI" {{ old('distrito') == 'TAPACARI' ? 'selected' : '' }}>TAPACARI</option>
                            <option value="TARATA" {{ old('distrito') == 'TARATA' ? 'selected' : '' }}>TARATA</option>
                            <option value="TIQUIPAYA" {{ old('distrito') == 'TIQUIPAYA' ? 'selected' : '' }}>TIQUIPAYA</option>
                            <option value="TIRAQUE" {{ old('distrito') == 'TIRAQUE' ? 'selected' : '' }}>TIRAQUE</option>
                            <option value="TOKO" {{ old('distrito') == 'TOKO' ? 'selected' : '' }}>TOKO</option>
                            <option value="TOLATA" {{ old('distrito') == 'TOLATA' ? 'selected' : '' }}>TOLATA</option>
                            <option value="TOTORA" {{ old('distrito') == 'TOTORA' ? 'selected' : '' }}>TOTORA</option>
                            <option value="VILA VILA" {{ old('distrito') == 'VILA VILA' ? 'selected' : '' }}>VILA VILA</option>
                            <option value="VILLA RIVERO" {{ old('distrito') == 'VILLA RIVERO' ? 'selected' : '' }}>VILLA RIVERO</option>
                            <option value="VILLA TUNARI" {{ old('distrito') == 'VILLA TUNARI' ? 'selected' : '' }}>VILLA TUNARI</option>
                            <option value="VINTO" {{ old('distrito') == 'VINTO' ? 'selected' : '' }}>VINTO</option>
                            <option value="OTRO" {{ old('distrito') == 'OTRO' ? 'selected' : '' }}>OTRO</option>
                        </select>

                        {{-- Campo que aparece solo si se selecciona OTRO --}}
                        <div id="campo-otro-distrito" style="display: {{ old('distrito') == 'OTRO' ? 'block' : 'none' }}; margin-top: 8px;">
                            <input
                                type="text"
                                id="distrito_otro"
                                name="distrito_otro"
                                placeholder="Ingrese el distrito"
                                value="{{ old('distrito_otro') }}"
                                style="text-transform: uppercase;"
                                oninput="this.value = this.value.toUpperCase()"
                                class="@error('distrito_otro') is-invalid @enderror"
                            />
                            @error('distrito_otro')
                                <div class="alert-msg">{{ $message }}</div>
                            @enderror
                        </div>


                        @error('distrito')
                            <div class="alert-msg">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="campo">
                        <label>Unidad Educativa</label>
                        <input
                            id="tipoInstitucion"
                            type="text"
                            name="tipoInstitucion"
                            placeholder="Unidad Educativa"
                            value="{{ old('tipoInstitucion') }}"
                            oninput="this.value = this.value.toUpperCase()"
                            class="@error('tipoInstitucion') is-invalid @enderror"
                        >
                        @error('tipoInstitucion')
                            <div class="alert-msg">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="campo">
                        <label>Foto</label>
                        <input
                            id="foto"
                            type="file"
                            name="foto"
                            value="{{ old('foto') }}"
                            class="@error('foto') is-invalid @enderror"
                        >
                        @error('foto')
                            <div class="alert-msg">{{ $message }}</div>
                        @enderror
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
                @foreach ($eventos as $e)
                    <option value="{{ $e->nombreE }}"> {{ $e->nombreE }} </option>
                @endforeach
            </select>
            <button class="btn-cancelareve" onclick="cerrarModalEvento()">Cancelar</button>
            <button class="btn-modaleve" onclick="confirmarEvento()">Aceptar</button>
        </div>
    </div>



    <script>
    function toggleOtroDistrito(value) {
        const campoOtro = document.getElementById('campo-otro-distrito');
        const inputOtro = document.getElementById('distrito_otro');

        if (value === 'OTRO') {
            campoOtro.style.display = 'block';
            inputOtro.required = true;
        } else {
            campoOtro.style.display = 'none';
            inputOtro.required = false;
            inputOtro.value = '';
        }
    }

        function cerrarModalEvento() {
            document.getElementById("modalEvento").style.display = "none";
        }
        let personasSeleccionadas = [];

        function Credenciales() {
            personasSeleccionadas = [];
            document.querySelectorAll(".check-item:checked").forEach(el => {
                personasSeleccionadas.push(el.value);
            });
            if (personasSeleccionadas.length === 0) {
                alert("Seleccione personas");
                return;
            }
            document.getElementById("modalEvento").style.display = "block";
        }

        function confirmarEvento() {
            let evento = document.getElementById("evento").value;
            if (evento === "") {
                alert("Seleccione evento");
                return;
            }
            document.getElementById("modalEvento").style.display = "none";
            enviarPreview(evento);
        }

        function enviarPreview(evento) {
            let form = document.createElement("form");
            form.method = "POST";
            form.action = "/credenciales/preview";
            /* TOKEN */
            let token = document.createElement("input");
            token.type = "hidden";
            token.name = "_token";
            token.value = "{{ csrf_token() }}";
            form.appendChild(token);

            /* IDS */
            let ids = document.createElement("input");
            ids.type = "hidden";
            ids.name = "personas";
            ids.value = JSON.stringify(personasSeleccionadas);
            form.appendChild(ids);

            /* EVENTO */
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
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                const modal = document.getElementById('modalCliente');
                modal.style.display = 'flex';
            @endif
        });

        /*modal registro cliente*/
        function abrirModal() {
            document.getElementById("modalCliente").style.display = "flex";
        }

        function cerrarModal() {
            document.getElementById("modalCliente").style.display = "none";

            const form = document.getElementById('formCliente');
            const formMethod = document.getElementById('form-method');
            const personaIdInput = document.getElementById('personaId');

            form.querySelectorAll('.alert-msg').forEach(alert => alert.remove());

            form.querySelectorAll('.is-invalid').forEach(element => {
                element.classList.remove('is-invalid');
            });

            document.getElementById('ci').value = null;
            document.getElementById('nombre').value = null;
            document.getElementById('apellidos').value = null;
            document.getElementById('distrito').value = 'Seleccionar';
            document.getElementById('tipoInstitucion').value = null;
            document.getElementById('foto').value = null;

            form.action = '/cliente/store';
            formMethod.disabled = true;
            personaIdInput.value = null;
            personaIdInput.disabled = true;
        }

        /* cerrar haciendo clic fuera */

        window.onclick = function(e) {
            let modal = document.getElementById("modalCliente");
            if (e.target === modal) {
                cerrarModal();
            }
        }

        function edit(e, personaData) {
            e.preventDefault();

            const form = document.getElementById('formCliente');
            const formMethod = document.getElementById('form-method');
            const personaIdInput = document.getElementById('personaId');

            form.action = `/cliente/${personaData.idPersona}`;
            formMethod.disabled = false;
            personaIdInput.value = personaData.idPersona;
            personaIdInput.disabled = false;

            document.getElementById('ci').value = personaData.ci;
            document.getElementById('nombre').value = personaData.nombre;
            document.getElementById('apellidos').value = personaData.apellidos;
            document.getElementById('distrito').value = personaData.distrito;
            document.getElementById('tipoInstitucion').value = personaData.tipoInstitucion;

            abrirModal();
        }



        function deleteById(e, personaId) {
            e.preventDefault();

            confirmarEliminar(function () {
                console.log('Ejecutando delete con id:', personaId);
                const url = `{{ url("cliente") }}/${personaId}`;
                const formData = new FormData();
                formData.append('_method', 'DELETE');
                formData.append('_token', '{{ csrf_token() }}');

                fetch(url, {
                    method: 'post',
                    credentials: 'same-origin',
                    body: formData,
                })
                .then((response) => {
                    console.log('Response status:', response.status);
                    if (response.redirected) {
                        window.location.href = response.url;
                    }
                    return response;
                })
                .catch((error) => {
                    console.log(error);
                });
            });
        }

      /*  function deleteById(e, personaId) {
            e.preventDefault();

            const url = `{{ url("cliente") }}/${personaId}`;
            const formData = new FormData();
            formData.append('_method', 'DELETE');
            formData.append('_token', '{{ csrf_token() }}');

            fetch(url, {
                method: 'post',
                credentials: 'same-origin',
                body: formData,
            })
            .then((response) => {
                if (response.redirected) {
                    window.location.href = response.url;
                }

                return response;
            })
            .catch((error) => {
                console.log(error);
            });
        }*/
    </script>
@endsection
