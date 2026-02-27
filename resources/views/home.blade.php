@extends('layouts.header')

@section('content')

<div class="login-container">
    <div class="login-box">
    <form>
        <div class="user-icon">
            <i class="fa-solid fa-user-circle"></i>
        </div>

        <h3>Iniciar Sesión</h3>

        <label>Usuario</label>
        <input type="text" placeholder="Ingrese su usuario">

        <label>Contraseña</label>
        <input type="password" placeholder="Ingrese su contraseña">
        
        <button type="submit">Ingresar</button>
    </form>
    </div>
</div>

@endsection