@extends('layouts.header')

@push('styles')
    <style>
        .event-container {
            margin-top: 2%;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            height: 60vh;
        }

        .login-box {
            align-self: flex-start;
        }

        .event-row-controls {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .event-row-controls > div {
            width: 45%;
        }

        .buttons {
            display: flex;
            justify-content: space-around;
        }

        .buttons button {
            width: 30%;
            border-radius: 7px;
        }

        .list {
            width: 50%;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .list h3 {
            align-self: center;
            color: #850B0B;
        }

        .list-events {
            flex-grow: 1;
            overflow-y: auto;
            padding: 10px;
            box-sizing: border-box;
        }

        .element {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 10px 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 10px;
            min-height: 60px;
        }

        .info {
            display: flex;
            flex-direction: column;
        }

        .info h4 {
            margin: 10px 0px 5px;
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .actions a,
        .actions a:visited {
            color: black;
        }

        .icon-btn {
            background: transparent;
            cursor: pointer;
            font-size: 2rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function clean(e) {
            e.preventDefault();

            const form = document.getElementById('event-form');
            const formMethod = document.getElementById('form-method');
            const eventIdInput = document.getElementById('eventId');

            form.reset();
            form.action = '/eventos';
            formMethod.disabled = true;
            eventIdInput.value = null;
            eventIdInput.disable = true;
        }

        function edit(e, eventData) {
            e.preventDefault();

            const form = document.getElementById('event-form');
            const formMethod = document.getElementById('form-method');
            const eventIdInput = document.getElementById('eventId');

            form.action = `/eventos/${eventData.idEvento}`;
            formMethod.disabled = false;
            eventIdInput.value = eventData.idEvento;
            eventIdInput.disable = false;

            document.getElementById('name').value = eventData.nombreE;
            document.getElementById('description').value = eventData.descripcionE;
            document.getElementById('state').value = eventData.estadoE;
            document.getElementById('event-date1').value = eventData.fechaInicioE;
            document.getElementById('event-date2').value = eventData.fechaFinE;
        }
    </script>
@endpush

@section('content')

<div class="event-container">
    <div class="login-box">
        <form
            id="event-form"
            action="{{ old('eventId') ? '/eventos/' . old('eventId') : '/eventos' }}"
            method="POST"
        >
            @csrf
            <input
                type="hidden"
                id="form-method"
                name="_method"
                value="PATCH"
                {{ old('eventId') ? '' : 'disabled' }}
            >
            <input
                type="hidden"
                id="eventId"
                name="eventId"
                value="{{ old('eventId') }}"
            >
            <h3 style="color: #656061;">Registro de Evento</h3>

            <label for="name">Nombre del evento:</label>
            <input
                id="name"
                type="text"
                name="name"
                placeholder="Ingrese el nombre del evento"
                value="{{ old('name') }}"
                class="@error('name') is-invalid @enderror"
            >
            @error('name')
                <div class="alert">{{ $message }}</div>
            @enderror

            <label for="description">Descripci&oacute;n:</label>
            <textarea
                id="description"
                name="description"
                placeholder="Ingrese la descripción del evento"
                class="@error('description') is-invalid @enderror"
            >{{ old('description') }}</textarea>
            @error('description')
                <div class="alert">{{ $message }}</div>
            @enderror

            <div class="event-row-controls">
                <div>
                    <label for="event-date1">Fecha de inicio:</label>
                    <input
                        id="event-date1"
                        type="date"
                        name="event-date1"
                        value="{{ old('event-date1') }}"
                        class="@error('event-date1') is-invalid @enderror"
                    >
                    @error('event-date1')
                        <div class="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="event-date2">Fecha de Finalizaci&oacute;n:</label>
                    <input
                        id="event-date2"
                        type="date"
                        name="event-date2"
                        value="{{ old('event-date2') }}"
                        class="@error('event-date2') is-invalid @enderror"
                    >
                    @error('event-date2')
                        <div class="alert">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <label for="state">Estado:</label>
            <select
                id="state"
                name="state"
                class="@error('state') is-invalid @enderror"
            >
                <option value="Activo" {{ old('state') == 'Activo' ? 'selected' : '' }}>Activo</option>
                <option value="Inactivo" {{ old('state') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                <option value="Finalizado" {{ old('state') == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
            </select>
            @error('state')
                <div class="alert">{{ $message }}</div>
            @enderror

            <div class="buttons">
                <button class="submit-btn" type="submit">Guardar</button>
                <button class="clean-btn" onclick="clean(event)">Limpiar</button>
            </div>
        </form>
    </div>

    <div class="list">
        <h3>Eventos Registrados</h3>

        <div class="list-events">
            @foreach ($events as $event)
                <div class="element" id="{{ $event->idEvento }}">
                    <div class="info">
                        <h4 class="title">{{ $event->nombreE }}</h4>
                        <span class="date">{{ date('d/m/Y', strtotime($event->fechaInicioE)) }}</span>
                        <span class="state">Estado: {{ $event->estadoE }}</span>
                    </div>

                    <div class="actions">
                        <i class="fa-solid fa-pen-to-square icon-btn" onclick="edit(event, {{ $event }})"></i>
                        <a href="{{ route('subeventos.evento', ['eventId' => $event->idEvento]) }}">
                            <i class="fa-solid fa-play icon-btn"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
