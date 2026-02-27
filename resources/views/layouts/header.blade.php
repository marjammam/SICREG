<!DOCTYPE html>
<html>
<head>
    <title>SICREG</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header>
    <div class="logo-area">
        <!-- Aquí irá tu logo -->
        <img src="{{ asset('image/logoF.png') }}" alt="Logo">
        <h2>SICREG</h2>
    </div>

    <nav>
        <a href="#">Inicio</a>
        <a href="#">Evento</a>
        <a href="#">Credencial</a>
        <a href="#">Iniciar Sesión</a>
    </nav>
</header>

@yield('content')

</body>
</html>