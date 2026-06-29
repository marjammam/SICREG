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

            <label>Usuario:</label>
            <div class="input-wrap">
                <i class="ti ti-user" aria-hidden="true"></i>
                <input id="username" name="username" type="text" placeholder=" Ingrese su usuario">
            </div>

            <label>Contraseña:</label>
            <div class="input-wrap">
                <i class="ti ti-lock" aria-hidden="true"></i>
                <input id="password" name="password" type="password" placeholder=" Ingrese su contraseña">
            </div>

            <button class="btn-ingresar" type="submit">Ingresar</button>
        </form>
    </div>
</div>

@endsection
