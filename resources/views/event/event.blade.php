@extends('layouts.header')

@push('styles')
    <style>
        .event-container {
            margin-top: 80px;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            height: 60vh;
        }

        .login-box {
            align-self: flex-start;
        }

        .buttons {
            display: flex;
            justify-content: space-around;
        }

        .buttons button {
            width: 30%;
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

        .icon-btn {
            background: transparent;
            cursor: pointer;
            font-size: 2rem;
        }

        .icon-btn:hover {
            font-size: 2.5rem;
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

            selectedEvent = eventData;
        }

        function sendData(e) {
            e.preventDefault();

            const form = document.getElementById('event-form');
            const formData = new FormData(form);

            let url = '{{url("eventos")}}';

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
            <h3>Registro de Evento</h3>

            <label for="name">Nombre del evento:</label>
            <input id="name" type="text" name="name" placeholder="Ingrese el nombre del evento">

            <label for="description">Descripci&oacute;n</label>
            <textarea id="description" name="description" placeholder="Ingrese la descripción del evento"></textarea>

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
                @include('event.list', ['event' => $event])
            @endforeach
        </div>
    </div>
</div>

@endsection
