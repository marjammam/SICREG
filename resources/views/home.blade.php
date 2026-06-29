@extends('layouts.header')

@section('content')
@extends('layouts.header')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">


<div class="login-container">
    <div class="login-box">
        <form
            action="/login"
            method="POST"
        >
            @csrf
            <div class="user-icon">
                <i class="ti ti-user"></i>
            </div>
            <h3>Iniciar Sesión</h3>

            @error('username')
                <div class="error-alert">
                    <span>{{ $message }}</span>
                </div>
            @enderror

            <label for="username">Usuario</label>
            <input id="username" name="username" type="text" placeholder="Ingrese su usuario" value="{{ old('username') }}">

            <label for="password">Contraseña</label>
            <div class="password-container">
                <input id="password" name="password" type="password" placeholder="Ingrese su contraseña">
                <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
            </div>

            <button class="btn-ingresar" type="submit">Ingresar</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }
    });
</script>
@endpush
