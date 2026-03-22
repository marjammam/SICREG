@extends('layouts.header')

@section('content')

<div class="login-container">
    <div class="login-box">
        <form
            action="/login"
            method="POST"
        >
            @csrf
            <div class="user-icon">
                <i class="fa-solid fa-user-circle"></i>
            </div>

            <h3>Iniciar Sesión</h3>

            <label>Usuario</label>
            <input id="username" name="username" type="text" placeholder="Ingrese su usuario">

            <label>Contraseña</label>
            <input id="password" name="password" type="password" placeholder="Ingrese su contraseña">

            <button class="btn-ingresar" type="submit">Ingresar</button>
        </form>
    </div>
</div>

@endsection
