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
        let selectedEvent = null;

        function clean(e) {
            e.preventDefault();
            document.getElementById('event-form').reset();

            selectedEvent = null;
        }

        function edit(e, eventData) {
            e.preventDefault();

            document.getElementById('name').value = eventData.nombreE;
            document.getElementById('description').value = eventData.descripcionE;
            document.getElementById('state').value = eventData.estadoE;
            document.getElementById('event-date1').value = eventData.fechaInicioE;
            document.getElementById('event-date2').value = eventData.fechaFinE;

            selectedEvent = eventData;
        }

        function sendData(e) {
            e.preventDefault();

            const form = document.getElementById('event-form');
            const formData = new FormData(form);

            let url = '{{ url("eventos") }}';

            if (selectedEvent) {
                if (!selectedEvent.idEvento) {
                    return;
                }

                url = url + `/${selectedEvent.idEvento}`;

                formData.append('_method', 'PATCH');
            }

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

<div class="event-container">
    <div class="login-box">
        <form id="event-form">
            @csrf
            <h3 style="color: #656061;">Registro de Evento</h3>

            <label for="name">Nombre del evento:</label>
            <input id="name" type="text" name="name" placeholder="Ingrese el nombre del evento">

            <label for="description">Descripci&oacute;n:</label>
            <textarea id="description" name="description" placeholder="Ingrese la descripción del evento"></textarea>

            <div class="event-row-controls">
                <div>
                    <label for="event-date1">Fecha de inicio:</label>
                    <input id="event-date1" type="date" name="event-date1">
                </div>
                <div>
                    <label for="event-date2">Fecha de Finalizaci&oacute;n:</label>
                    <input id="event-date2" type="date" name="event-date2">
                </div>
            </div>

            <label for="state">Estado:</label>
            <select id="state" name="state">
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
                <option value="Finalizado">Finalizado</option>
            </select>

            <div class="buttons">
                <button class="submit-btn" onclick="sendData(event)">Guardar</button>
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
