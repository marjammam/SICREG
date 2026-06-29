@extends('layouts.header')

@push('styles')
    <style>
        .div-container {
            background: white;
            padding: 40px;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        label {
            display: block;
            text-align: left;
            margin-top: 10px;
            font-weight: bold;
            color: #850B0B
        }


        input,
        textarea,
        select {
            box-sizing: border-box;
            width: 90%;
            padding: 10px;
            margin: 10px 0px 20px 0;
        }


        textarea {
            field-sizing: content;
            min-height: 3rem;
            resize: vertical;
        }


        .btn-guardar {
            width: 60%;
            padding: 10px;
            background-color: #850B0B;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-guardar:hover {
            background-color: #5f0808;
        }

        .clean-btn {
            width: 60%;
            padding: 10px;
            background-color: #656061;
            color: white;
            border: none;
            cursor: pointer;
        }

        .clean-btn:hover {
            background-color: #454142;
        }

        .is-invalid {
            border-color: red !important;
            outline: none;
            margin-bottom: 0;
        }

        .alert-msg {
            margin: 0;
            padding: 0;
            font-size: 0.8rem;
            color: red;
            align-self: flex-start;
        }



        .subevent-container {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Caja del título + buscador + botón */
        .subevent-header-box {
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .subevent-header-box h3 {
        font-size: 18px;
        font-weight: bold;
        color: black;
         text-align: center;
         margin: 0;
        }
        .subevent-container button {
            width: auto;
        }

        .top-actions {
            width: 80%;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .top-actions * {
            font-size: 16px;
        }

        .top-actions > button {
            background: #adadad;
            color: black;
            border-radius: 7px;
        }

        
      
        /* Caja de la tabla */
        .subevent-table-box {
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px 25px;
            overflow-x: auto;
        }

        .subevent-table-box table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .subevent-table-box thead tr {
            background-color: #b30d0d;
            color: white;
        }

        .subevent-table-box th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
        }

        .subevent-table-box td {
            padding: 10px 15px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }

        .subevent-table-box tbody tr:hover {
            background-color: #fdf0f0;
        }

        .element-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        thead {
            background: #af3e3e;
            color: white;
        }

        thead th {
            text-align: left;
            padding: 12px 16px;
            text-transform: uppercase;
        }

        tbody {
            background: white;
        }

        tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #e6e6e6;
        }



        /* ACCIONES */
        /* Botones de acción */
        .btn-accion {
            cursor: pointer;
            font-size: 1rem;
            padding: 6px 8px;
            border-radius: 6px;
            border: 0.5px solid;
            transition: background-color 0.2s, transform 0.1s;
        }

         .btn-ingresar {
            color: white;
            background-color: #185FA5;
            border-radius: 6px;
        }

         .btn-ingresar:hover {
            background-color: #0d4278;
        }

        .btn-editar {
            color: #a58618;
            background-color: #fbf6e6;
            border-color: #dad549;
            margin-right: 6px;
        }
        

        .btn-editar:hover {
            background-color: #f4e2b5;
        }
        .btn-play {
            color: #282829;
            background-color: #c6cace;
            border-color: #4b4b4b;
            margin-right: 6px;
        }
        

        .btn-play:hover {
            background-color: #a0a2a4;
        }

        .btn-eliminar {
            color: #A32D2D;
            background-color: #FCEBEB;
            border-color: #F09595;
        }

        .btn-eliminar:hover {
            background-color: #F7C1C1;
        }

        .btn-accion:active {
            transform: scale(0.95);
        }

        .element-actions {
            text-align: center;
            white-space: nowrap;
        }

        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
            box-sizing: border-box;
        }

        .modal .login-box {
            box-sizing: border-box;
            max-height: 90vh;
            overflow-y: auto;
            width: 100%;
            max-width: 430px;
        }

        .subevent-row-controls {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .subevent-row-controls > div {
            width: 45%;
        }

        .subevent-form-buttons {
            display: flex;
            justify-content: space-evenly;
        }

        .subevent-form-buttons button {
            width: auto;
            border-radius: 7px;
        }

        .hidden {
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if ($errors->any())
                const modal = document.getElementById('subevent-modal');
                modal.classList.remove('hidden');
            @endif

            const subeventIdInput = document.getElementById('subeventId');
            const modalTitle = document.getElementById('modal-title');
            if (subeventIdInput && subeventIdInput.value) {
                if (modalTitle) modalTitle.textContent = 'Editar Subevento';
            } else {
                if (modalTitle) modalTitle.textContent = 'Registro de Subevento';
            }
        });

        function openModal(e, modalName) {
            e.preventDefault();

            const modal = document.getElementById(modalName);

            modal.classList.remove('hidden');
        }

        function closeModal(e, modalName) {
            e.preventDefault();

            const form = document.getElementById('subevent-form');
            const formMethod = document.getElementById('form-method');
            const subeventIdInput = document.getElementById('subeventId');
            const modal = document.getElementById(modalName);
            const modalTitle = document.getElementById('modal-title');

            if (modalTitle) {
                modalTitle.textContent = 'Registro de Subevento';
            }

            form.querySelectorAll('.alert-msg').forEach(alert => alert.remove());

            form.querySelectorAll('.is-invalid').forEach(element => {
                element.classList.remove('is-invalid');
            });

            document.getElementById('subevent-name').value = null;
            document.getElementById('description').value = null;
            document.getElementById('subevent-date').value = '{{ date("Y-m-d") }}';
            document.getElementById('subevent-time1').value = '{{ date("H:i") }}';
            document.getElementById('subevent-time2').value = '{{ date("H:i") }}';
            document.getElementById('subevent-state').value = 'Activo';

            form.action = '/eventos';
            formMethod.disabled = true;
            subeventIdInput.value = null;
            subeventIdInput.disabled = true;
            modal.classList.add('hidden');
        }

        function edit(e, subeventData) {
            e.preventDefault();

            const form = document.getElementById('subevent-form');
            const formMethod = document.getElementById('form-method');
            const subeventIdInput = document.getElementById('subeventId');
            const modalTitle = document.getElementById('modal-title');

            if (modalTitle) {
                modalTitle.textContent = 'Editar Subevento';
            }

            form.action = `/subeventos/${subeventData.idSubevento}`;
            formMethod.disabled = false;
            subeventIdInput.value = subeventData.idSubevento;
            subeventIdInput.disabled = false;

            document.getElementById('subevent-name').value = subeventData.nombreSE;
            document.getElementById('description').value = subeventData.descripcionSE;
            document.getElementById('subevent-date').value = subeventData.fechaSE;
            document.getElementById('subevent-time1').value = subeventData.horaInicio;
            document.getElementById('subevent-time2').value = subeventData.horaFin;
            document.getElementById('subevent-state').value = subeventData.estadoSE;

            openModal(e, 'subevent-modal');
        }

         function deleteById(e, subeventId) {
            e.preventDefault();

            confirmarEliminar(function () {
                console.log('Ejecutando delete con id:', subeventId);
                const url = `{{ url("subeventos") }}/${subeventId}`;
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

       /* function deleteById(e, subeventId) {
            e.preventDefault();

            const url = `{{ url("subeventos") }}/${subeventId}`;
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
@endpush

@section('content')


<div class="subevent-container">
    <div class="subevent-header-box">
        <div style="flex: 1;">
            <h3 style="text-align: center;">REGISTRO DE SUBEVENTOS</h3>
        
            <p style="margin:4px 0 0; font-size:0.9rem;"><span style="color:#656061;">Evento: </span>{{ $evento->nombreE }}</p>
            <p style="margin:4px 0 0; font-size:0.9rem;"><span style="color:#656061;">Tipo de Evento: </span>{{ $evento->tipoEvento }}</p>
        </div>

        <button class="btn-ingresar" onclick="openModal(event, 'subevent-modal')">
            <i class="fa-solid fa-circle-plus"></i>
            <span>REGISTRAR</span>
        </button>
    </div>  

    <div class="subevent-table-box">
        <table>
            <thead>
                <tr>
                    <th>NOMBRE</th>
                    <th>FECHA INICIO</th>
                    <th>HORA INICIO</th>
                    <th>HORA FIN</th>
                    <th>ESTADO</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($subEvents as $subEvent)
                <tr>
                    <td>{{ $subEvent->nombreSE }}</td>
                    <td>{{ date('d/m/Y', strtotime($subEvent->fechaSE)) }}</td>
                    <td>{{ date($subEvent->horaInicio) }}</td>
                    <td>{{ date($subEvent->horaFin) }}</td>
                    <td>{{ $subEvent->estadoSE }}</td>
                    <td>
                        <div class="element-actions">
                            <i class="fa-solid fa-pen-to-square btn-accion btn-editar" onclick="edit(event, {{ $subEvent }})"></i>
                            <i class="fa-solid fa-trash btn-accion btn-eliminar" onclick="deleteById(event, {{ $subEvent->idSubevento }})"></i>
                           <a href="{{ route('asistencia.index', ['id' => $subEvent->idSubevento]) }}"><i class="fa-solid fa-play btn-accion btn-play"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">No hay subeventos registradas para este evento.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>    
</div>

<div id="subevent-modal" class="modal hidden">
    <div class="div-container">
        <a style="float: right; cursor: pointer;" onclick="closeModal(event, 'subevent-modal')">
            <i class="fa-solid fa-circle-xmark"></i>
        </a>

        <form
            id="subevent-form"
            action="{{ old('subeventId') ? '/subeventos/' . old('subeventId') : '/subeventos' }}"
            method="POST"
        >
            @csrf
            <input
                type="hidden"
                id="form-method"
                name="_method"
                value="PATCH"
                {{ old('subeventId') ? '' : 'disabled' }}
            >
            <input
                type="hidden"
                id="subeventId"
                name="subeventId"
                value="{{ old('subeventId') }}"
                {{ old('subeventId') ? '' : 'disabled' }}
            >
            <input
                type="hidden"
                id="eventId"
                name="eventId"
                value="{{ $eventId }}"
            >
            <h3 id="modal-title" style="color: #656061;">Registro de Subevento</h3>

            <label for="subevent-name">Nombre del subevento:</label>
            <input
                id="subevent-name"
                type="text"
                name="subevent-name"
                placeholder="Ingrese el nombre del subevento"
                oninput="this.value = this.value.toUpperCase()"
                value="{{ old('subevent-name') }}"
                class="@error('subevent-name') is-invalid @enderror"
            >
            @error('subevent-name')
                <div class="alert-msg">{{ $message }}</div>
            @enderror

            <label for="description">Descripci&oacute;n:</label>
                <textarea
                    id="description"
                    name="description"
                    placeholder="Ingrese la descripción del evento"
                    class="@error('description') is-invalid @enderror"
                >{{ old('description') }}</textarea>
                @error('description')
                    <div class="alert-msg">{{ $message }}</div>
                @enderror
                
            <div>
                    <label for="subevent-date">Fecha:</label>
                    <input
                        id="subevent-date"
                        type="date"
                        name="subevent-date"
                        value="{{ old('subevent-date', date('Y-m-d')) }}"
                        class="@error('subevent-date') is-invalid @enderror"
                    >
                    @error('subevent-date')
                        <div class="alert-msg">{{ $message }}</div>
                    @enderror
            </div>

            <div class="subevent-row-controls">
                <div>
                    <label for="subevent-time1">Hora Inicio:</label>
                    <input
                        id="subevent-time1"
                        type="time"
                        name="subevent-time1"
                        value="{{ old('subevent-time1', date('H:i')) }}"
                        class="@error('subevent-time1') is-invalid @enderror"
                    >
                    @error('subevent-time1')
                        <div class="alert-msg">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="subevent-time2">Hora Fin:</label>
                    <input
                        id="subevent-time2"
                        type="time"
                        name="subevent-time2"
                        value="{{ old('subevent-time2', date('H:i')) }}"
                        class="@error('subevent-time2') is-invalid @enderror"
                    >
                    @error('subevent-time2')
                        <div class="alert-msg">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <label for="subevent-state">Estado:</label>
            <select
                id="subevent-state"
                name="subevent-state"
                class="@error('subevent-state') is-invalid @enderror"
            >
                <option value="Activo" {{ old('subevent-state') == 'Activo' ? 'selected' : '' }}>Activo</option>
                <option value="Inactivo" {{ old('subevent-state') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                <option value="Finalizado" {{ old('subevent-state') == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
            </select>
            @error('subevent-state')
                <div class="alert-msg">{{ $message }}</div>
            @enderror

            <div class="subevent-form-buttons">
                <button class="btn-guardar" type="submit">Guardar</button>
                <button class="clean-btn" onclick="closeModal(event, 'subevent-modal')">Cancelar</button>
            </div>
        </form>
    </div>
</div>

@endsection
