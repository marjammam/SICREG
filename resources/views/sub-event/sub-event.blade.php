@extends('layouts.header')

@push('styles')
    <style>
        .subevent-container {
            margin-top: 2%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            width: 100%;
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

        .search {
            display: flex;
            align-items: center;
            width: 60%;
        }

        .search input {
            background: #d9d9d9;
            color: #656061;
            border: none;
            outline: none;
            margin: unset;
        }

        .search button {
            background: #e6e6e6;
            color: black;
            border: none;
        }

        table {
            width: 80%;
            border-collapse: collapse;
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

        .element-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .icon-btn {
            background: transparent;
            cursor: pointer;
        }

        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
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

            form.reset();
            form.action = '/eventos';
            formMethod.disabled = true;
            subeventIdInput.value = null;
            subeventIdInput.disable = true;
            modal.classList.add('hidden');
        }

        function edit(e, subeventData) {
            e.preventDefault();

            const form = document.getElementById('subevent-form');
            const formMethod = document.getElementById('form-method');
            const subeventIdInput = document.getElementById('subeventId');

            form.action = `/subeventos/${subeventData.idSubevento}`;
            formMethod.disabled = false;
            subeventIdInput.value = subeventData.idSubevento;
            subeventIdInput.disable = false;

            document.getElementById('subevent-name').value = subeventData.nombreSE;
            document.getElementById('subevent-type').value = subeventData.tipoEvento;
            document.getElementById('subevent-date').value = subeventData.fechaSE;
            document.getElementById('subevent-time1').value = subeventData.horaInicio;
            document.getElementById('subevent-time2').value = subeventData.horaFin;
            document.getElementById('subevent-state').value = subeventData.estadoSE;

            openModal(e, 'subevent-modal');
        }

        function deleteById(e, subeventId) {
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
        }
    </script>
@endpush

@section('content')

<div class="subevent-container">
    <h3>Registro de Subeventos</h3>

    <div class="top-actions">
        <div class="search">
            <input type="search" placeholder="Escriba aquí el nombre del subevento">
            <button>
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>

        <button onclick="openModal(event, 'subevent-modal')">
            <i class="fa-solid fa-circle-plus"></i>
            <span>REGISTRAR</span>
        </button>
    </div>

    <table>
        <thead>
            <tr>
                <th>NOMBRE</th>
                <th>TIPO EVENTO</th>
                <th>FECHA INICIO</th>
                <th>ESTADO</th>
                <th>&nbsp;</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($subEvents as $subEvent)
                <tr>
                    <td>{{ $subEvent->nombreSE }}</td>
                    <td>{{ $subEvent->tipoEvento }}</td>
                    <td>{{ date('d/m/Y', strtotime($subEvent->fechaSE)) }}</td>
                    <td>{{ $subEvent->estadoSE }}</td>
                    <td>
                        <div class="element-actions">
                            <i class="fa-solid fa-pen-to-square icon-btn", onclick="edit(event, {{ $subEvent }})"></i>
                            <i class="fa-solid fa-trash icon-btn" onclick="deleteById(event, {{ $subEvent->idSubevento }})"></i>
                            <i class="fa-solid fa-play icon-btn"></i>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="subevent-modal" class="modal hidden">
    <div class="login-box">
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
            >
            <input
                type="hidden"
                id="eventId"
                name="eventId"
                value="{{ $eventId }}"
            >
            <h3 style="color: #656061;">Registro de Subevento</h3>

            <label for="subevent-name">Nombre del subevento:</label>
            <input
                id="subevent-name"
                type="text"
                name="subevent-name"
                placeholder="Ingrese el nombre del subevento"
                value="{{ old('subevent-name') }}"
                class="@error('subevent-name') is-invalid @enderror"
            >
            @error('subevent-name')
                <div class="alert">{{ $message }}</div>
            @enderror

            <div class="subevent-row-controls">
                <div>
                    <label for="subevent-type">Tipo de evento:</label>
                    <select
                        id="subevent-type"
                        name="subevent-type"
                        class="@error('subevent-type') is-invalid @enderror"
                    >
                        <option value="Delegados" {{ old('subevent-type') == 'Delegados' ? 'selected' : '' }}>Delegados</option>
                        <option value="Congreso" {{ old('subevent-type') == 'Congreso' ? 'selected' : '' }}>Congreso</option>
                    </select>
                    @error('subevent-type')
                        <div class="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="subevent-date">Fecha:</label>
                    <input
                        id="subevent-date"
                        type="date"
                        name="subevent-date"
                        value="{{ old('subevent-date') }}"
                        class="@error('subevent-date') is-invalid @enderror"
                    >
                    @error('subevent-date')
                        <div class="alert">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="subevent-row-controls">
                <div>
                    <label for="subevent-time1">Hora de inicio:</label>
                    <input
                        id="subevent-time1"
                        type="time"
                        name="subevent-time1"
                        value="{{ old('subevent-time1') }}"
                        class="@error('subevent-time1') is-invalid @enderror"
                    >
                    @error('subevent-time1')
                        <div class="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="subevent-time2">Hora de Finalizaci&oacute;n:</label>
                    <input
                        id="subevent-time2"
                        type="time"
                        name="subevent-time2"
                        value="{{ old('subevent-time2') }}"
                        class="@error('subevent-time2') is-invalid @enderror"
                    >
                    @error('subevent-time2')
                        <div class="alert">{{ $message }}</div>
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
                <div class="alert">{{ $message }}</div>
            @enderror

            <div class="subevent-form-buttons">
                <button type="submit">Guardar</button>
                <button class="clean-btn" onclick="closeModal(event, 'subevent-modal')">Cancelar</button>
            </div>
        </form>
    </div>
</div>

@endsection
