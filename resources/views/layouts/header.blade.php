<!DOCTYPE html>
<html>
<head>
    <title>SICREG</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @stack('styles')
    @stack('scripts')
</head>
<body>

<header>
    <div class="logo-area">
        <!-- Aquí irá tu logo -->
        <img src="{{ asset('image/logoF.png') }}" alt="Logo">
        <h2>SICREG</h2>
    </div>

    <nav>
        @auth
            @if(auth()->user()->rol === 'ADMINISTRADOR')
                <a href="{{ route('eventos') }}">Evento</a>
                <a href="{{ route('credencial') }}">Credencial</a>
                <a href="{{ route('cliente') }}">Clientes</a>
                <a href="{{ route('usuarios') }}">Usuarios</a>
            @elseif(auth()->user()->rol === 'MODERADOR')
                <a href="{{ route('eventos') }}">Evento</a>
                <a href="{{ route('credencial') }}">Credencial</a>
                <a href="{{ route('cliente') }}">Clientes</a>
            @endif

            <a onclick="openLogoutModal(event)" style="cursor: pointer;">Cerrar sesión</a>
        @endauth

        @guest
            <a href="{{ route('login') }}">Iniciar Sesión</a>
        @endguest
    </nav>
</header>

@auth
<div id="logout-modal" class="modal-logout hidden-logout">
    <div class="logout-box">
        <h3 style="color: #656061; margin-bottom: 25px;">¿Confirmas que quieres cerrar sesión?</h3>
        <div style="display: flex; justify-content: space-evenly; gap: 5px;">
            <button class="clean-btn" onclick="closeLogoutModal(event)">Cancelar</button>
            <button class="btn-ingresar" onclick="confirmLogout(event)">Cerrar sesión</button>
        </div>
    </div>
</div>
@endauth

<main>
    @yield('content')
</main>

</body>

<script>
    function openLogoutModal(e) {
        e.preventDefault();
        const modal = document.getElementById('logout-modal');
        if (modal) {
            modal.classList.remove('hidden-logout');
        }
    }

    function closeLogoutModal(e) {
        if (e) e.preventDefault();
        const modal = document.getElementById('logout-modal');
        if (modal) {
            modal.classList.add('hidden-logout');
        }
    }

    function confirmLogout(e) {
        if (e) e.preventDefault();

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ url("logout") }}', {
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
</html>
