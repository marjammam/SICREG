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
        select {
            box-sizing: border-box;
            width: 90%;
            padding: 10px;
            margin: 10px 0px 20px 0;
        }


        .btn-guardar {
            width: 50%;
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
            width: 50%;
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


            width: 60%;
            display: flex;
            flex-direction: column;
            height: 80vh;
            background: white;           /* 👈 fondo blanco como el formulario */
            border-radius: 10px;         /* 👈 bordes redondeados */
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1); /* 👈 sombra como el formulario */
            padding: 20px;               /* 👈 espacio interno */
            box-sizing: border-box;
        }

        .list h3 {
            align-self: center;
            color: #850B0B;
            margin-bottom: 15px;
        }

        .list-events {
            flex-grow: 1;
            overflow-y: auto;
            max-height: 70vh;
            padding: 10px;
            padding-right: 15px;
            box-sizing: border-box;
        }

        /* scroll personalizado */
        .list-events::-webkit-scrollbar {
            width: 6px;
        }

        .list-events::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .list-events::-webkit-scrollbar-thumb {
            background: #850B0B;
            border-radius: 10px;
        }

        .list-events::-webkit-scrollbar-thumb:hover {
            background: #6a0909;
        }


        .element {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f6f2f2;
            padding: 10px 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
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
            
            const formTitle = document.getElementById('form-title');

            if (formTitle) {
                formTitle.textContent = 'Registro de Evento';
            }

            form.querySelectorAll('.alert-msg').forEach(alert => alert.remove());

            form.querySelectorAll('.is-invalid').forEach(element => {
                element.classList.remove('is-invalid');
            });

            document.getElementById('name').value = null;
            document.getElementById('subevent-type').value = 'Delegados';
            document.getElementById('state').value = 'Activo';
            document.getElementById('event-date1').value = '{{ date("Y-m-d") }}';
            document.getElementById('event-date2').value = '{{ date("Y-m-d") }}';

            form.action = '/eventos';
            formMethod.disabled = true;
            eventIdInput.value = null;
            eventIdInput.disabled = true;
        }

        function edit(e, eventData) {
            e.preventDefault();

            const form = document.getElementById('event-form');
            const formMethod = document.getElementById('form-method');
            const eventIdInput = document.getElementById('eventId');
            const formTitle = document.getElementById('form-title');

            if (formTitle) {
                formTitle.textContent = 'Editar Evento';
            }

            form.action = `/eventos/${eventData.idEvento}`;
            formMethod.disabled = false;
            eventIdInput.value = eventData.idEvento;
            eventIdInput.disabled = false;

            document.getElementById('name').value = eventData.nombreE;
            document.getElementById('subevent-type').value = eventData.tipoEvento;
            document.getElementById('state').value = eventData.estadoE;
            document.getElementById('event-date1').value = eventData.fechaInicioE;
            document.getElementById('event-date2').value = eventData.fechaFinE;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const eventIdInput = document.getElementById('eventId');
            const formTitle = document.getElementById('form-title');
            if (eventIdInput && eventIdInput.value) {
                if (formTitle) formTitle.textContent = 'Editar Evento';
            } else {
                if (formTitle) formTitle.textContent = 'Registro de Evento';
            }
        });
    </script>
@endpush

@section('content')

<div class="event-container">
    <div class="div-container">
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
                {{ old('eventId') ? '' : 'disabled' }}
            >
            <h3 id="form-title" style="color: #656061;">Registro de Evento</h3>

            <label for="name">Nombre del evento:</label>
            <input
                id="name"
                type="text"
                name="name"
                placeholder="Ingrese el nombre del evento"
                value="{{ old('name') }}"
                oninput="this.value = this.value.toUpperCase()"
                class="@error('name') is-invalid @enderror"
            >
            @error('name')
                <div class="alert-msg">{{ $message }}</div>
            @enderror

            <div>
                <label for="subevent-type">Tipo de evento:</label>
                    <select
                        id="subevent-type"
                        name="subevent-type"
                        class="@error('subevent-type') is-invalid @enderror"
                    >
                        <option value="Delegados" {{ old('subevent-type') == 'Delegados' ? 'selected' : '' }}>Delegados</option>
                        <option value="Congreso" {{ old('subevent-type') == 'Congreso' ? 'selected' : '' }}>Congreso</option>
                        <option value="Talleres" {{ old('subevent-type') == 'Talleres' ? 'selected' : '' }}>Talleres</option>
                        <option value="Conferencias" {{ old('subevent-type') == 'Conferencias' ? 'selected' : '' }}>Conferencias</option>
                        <option value="Otros" {{ old('subevent-type') == 'Otros' ? 'selected' : '' }}>Otros</option>
                    </select>
                    @error('subevent-type')
                        <div class="alert-msg">{{ $message }}</div>
                    @enderror
            </div>

            <div class="event-row-controls">
                <div>
                    <label for="event-date1">Fecha inicio:</label>
                    <input
                        id="event-date1"
                        type="date"
                        name="event-date1"
                        value="{{ old('event-date1', date('Y-m-d')) }}"
                        class="@error('event-date1') is-invalid @enderror"
                    >
                    @error('event-date1')
                        <div class="alert-msg">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="event-date2">Fecha fin:</label>
                    <input
                        id="event-date2"
                        type="date"
                        name="event-date2"
                        value="{{ old('event-date2', date('Y-m-d')) }}"
                        class="@error('event-date2') is-invalid @enderror"
                    >
                    @error('event-date2')
                        <div class="alert-msg">{{ $message }}</div>
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
                <div class="alert-msg">{{ $message }}</div>
            @enderror

            <div class="buttons">
                <button class="btn-guardar submit-btn" type="submit">Guardar</button>
                <button class="clean-btn" onclick="clean(event)">Limpiar</button>
            </div>
        </form>
    </div>

    <div class="list">
        <h3>EVENTOS RESGISTRADOS</h3>

        <div class="list-events">
            @forelse ($events as $event)
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
            @empty
            <div style="text-align: center; padding: 20px;">
                No hay eventos registrados.
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
