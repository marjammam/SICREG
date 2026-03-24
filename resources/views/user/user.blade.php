@extends('layouts.header')

@push('styles')
    <style>
        .user-container {
            margin-top: 2%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            width: 100%;
        }

        .user-container button {
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

        .row-controls {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .row-controls > div {
            width: 45%;
        }

        .form-buttons {
            display: flex;
            justify-content: space-evenly;
        }

        .form-buttons button {
            width: auto;
            border-radius: 7px;
        }

        .hidden {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="user-container">
        <h3>Usuarios del Sistema</h3>

        <div class="top-actions">
            <div class="search">
                <input type="search">
                <button class="btn-ingresar">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>

            <button class="btn-ingresar" onclick="openModal(event, 'user-modal')">
                <i class="fa-solid fa-circle-plus"></i>
                <span>REGISTRAR</span>
            </button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>NOMBRE</th>
                    <th>EMAIL</th>
                    <th>ROL</th>
                    <th>ESTADO</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->nombreApellido }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->rol }}</td>
                        <td>{{ $user->estado }}</td>
                        <td>
                            <div class="element-actions">
                                <i class="fa-solid fa-pen-to-square icon-btn", onclick="edit(event, {{ $user }})"></i>
                                <!--i class="fa-solid fa-trash icon-btn" onclick="deleteById(event, {{ $user->idUsuario }})"></i>
                                <i class="fa-solid fa-play icon-btn"></i-->
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="user-modal" class="modal hidden">
        <div class="login-box">
            <a style="float: right; cursor: pointer;" onclick="closeModal(event, 'user-modal')">
                <i class="fa-solid fa-circle-xmark"></i>
            </a>

            <form
                id="user-form"
                action="{{ old('userId') ? '/usuarios/' . old('userId') : '/usuarios' }}"
                method="POST"
            >
                @csrf
                <input
                    type="hidden"
                    id="form-method"
                    name="_method"
                    value="PATCH"
                    {{ old('userId') ? '' : 'disabled' }}
                >
                <input
                    type="hidden"
                    id="userId"
                    name="userId"
                    value="{{ old('userId') }}"
                    {{ old('userId') ? '' : 'disabled' }}
                >
                <h3 style="color: #656061;">Registro de Usuario</h3>

                <label for="name">Nombre:</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    placeholder="Ingrese el nombre completo"
                    value="{{ old('name') }}"
                    class="@error('name') is-invalid @enderror"
                >
                @error('name')
                    <div class="alert">{{ $message }}</div>
                @enderror

                <label for="email">Correo electr&oacute;nico:</label>
                <input
                    id="email"
                    type="text"
                    name="email"
                    placeholder="Ingrese el correo electr&oacute;nico"
                    value="{{ old('email') }}"
                    class="@error('email') is-invalid @enderror"
                >
                @error('email')
                    <div class="alert">{{ $message }}</div>
                @enderror

                <label for="username">Usuario:</label>
                <input
                    id="username"
                    type="text"
                    name="username"
                    placeholder="Ingrese el usuario"
                    value="{{ old('username') }}"
                    class="@error('username') is-invalid @enderror"
                >
                @error('username')
                    <div class="alert">{{ $message }}</div>
                @enderror

                <div class="row-controls">
                    <div>
                        <label for="role">Rol:</label>
                        <select
                            id="role"
                            name="role"
                            class="@error('role') is-invalid @enderror"
                        >
                            <option value="ADMINISTRADOR" {{ old('role') == 'ADMINISTRADOR' ? 'selected' : '' }}>ADMINISTRADOR</option>
                            <option value="MODERADOR" {{ old('role') == 'MODERADOR' ? 'selected' : '' }}>MODERADOR</option>
                            <option value="INVITADO" {{ old('role') == 'INVITADO' ? 'selected' : '' }}>INVITADO</option>
                        </select>
                        @error('role')
                            <div class="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="state">Estado:</label>
                        <select
                            id="state"
                            name="state"
                            class="@error('state') is-invalid @enderror"
                        >
                            <option value="ACTIVO" {{ old('state') == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                            <option value="INACTIVO" {{ old('state') == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                        </select>
                        @error('state')
                            <div class="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <span id="enablePFbtn" style="display: {{ old('userId') ? 'flex' : 'none' }}; gap: 5px;">
                    <input
                        type="checkbox"
                        style="width: auto; margin: 0;"
                        onclick="enablePasswordFields(event)"
                    >
                    Cambiar contrase&ntilde;a
                </span>

                <label for="password">Contrase&ntilde;a:</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Ingrese la contrase&ntilde;a"
                    value="{{ old('password') }}"
                    class="@error('password') is-invalid @enderror"
                >
                @error('password')
                    <div class="alert">{{ $message }}</div>
                @enderror

                <label for="password_confirmation">Confirmar contrase&ntilde;a:</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    placeholder="Confirme la contrase&ntilde;a"
                    value="{{ old('password_confirmation') }}"
                    class="@error('password_confirmation') is-invalid @enderror"
                >
                @error('password_confirmation')
                    <div class="alert">{{ $message }}</div>
                @enderror

                <div class="form-buttons">
                    <button class="btn-ingresar" type="submit">Guardar</button>
                    <button class="clean-btn" onclick="closeModal(event, 'user-modal')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if ($errors->any())
                const modal = document.getElementById('user-modal');
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

            const form = document.getElementById('user-form');
            const formMethod = document.getElementById('form-method');
            const userIdInput = document.getElementById('userId');
            const modal = document.getElementById(modalName);
            const enablePFInput = document.getElementById('enablePFbtn');

            form.reset();
            form.action = '/usuarios';
            formMethod.disabled = true;
            userIdInput.value = null;
            userIdInput.disabled = true;
            modal.classList.add('hidden');
            enablePFInput.style.display = 'none';
        }

        function edit(e, userData) {
            e.preventDefault();

            const form = document.getElementById('user-form');
            const formMethod = document.getElementById('form-method');
            const userIdInput = document.getElementById('userId');
            const enablePFInput = document.getElementById('enablePFbtn');

            form.action = `/usuarios/${userData.idUsuario}`;
            formMethod.disabled = false;
            userIdInput.value = userData.idUsuario;
            userIdInput.disabled = false;
            enablePFInput.style.display = 'flex';

            document.getElementById('name').value = userData.nombreApellido;
            document.getElementById('email').value = userData.email;
            document.getElementById('username').value = userData.usuario;
            document.getElementById('role').value = userData.rol;
            document.getElementById('state').value = userData.estado;
            document.getElementById('password').disabled = true;
            document.getElementById('password_confirmation').disabled = true;

            openModal(e, 'user-modal');
        }

        function enablePasswordFields(e) {
            const enablePFcb = e.target;
            const enabled = enablePFcb.checked;

            document.getElementById('password').disabled = !enabled;
            document.getElementById('password_confirmation').disabled = !enabled;
        }
    </script>
@endsection
