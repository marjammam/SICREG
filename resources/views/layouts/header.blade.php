<!DOCTYPE html>
<html>
<head>
    <title>SICREG</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @stack('scripts')
</head>
<body>

<header>
    <div class="logo-area">
        <img src="{{ asset('image/logoF.png') }}" alt="Logo">
        <h2>SICREG</h2>
    </div>

    {{-- Botón hamburguesa solo visible en móvil --}}
    <button class="menu-toggle" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
    </button>

    <nav id="nav-menu">
        @auth
            @if(auth()->user()->rol === 'ADMINISTRADOR')
                <div class="nav-dropdown">
                    <a href="">Evento ▾</a>
                    <div class="dropdown-menu">
                        <a href="{{ route('eventos') }}">Eventos</a>
                        <a href="{{ route('subeventos.listas') }}">Subeventos</a>
                    </div>
                </div>
                <div class="nav-dropdown">
                    <a href="">Credencial ▾</a>
                    <div class="dropdown-menu">
                        <a href="{{ route('credencial') }}">Credencial</a>
                        <a href="{{ route('credenciales.lista') }} ">Credencial Emitidas</a>
                    </div>
                </div>
                <div class="nav-dropdown">
                    <a href="">Profesores ▾</a>
                    <div class="dropdown-menu">
                        <a href="{{ route('cliente') }}">Profesores</a>
                        <a href="{{ route('asistencia.verificar') }}">Verificar Asistencia</a>
                    </div>
                </div>
                <a href="{{ route('usuarios') }}">Usuarios</a>
            @elseif(auth()->user()->rol === 'MODERADOR')
                <a href="{{ route('eventos') }}">Subeventos</a>
                <a href="{{ route('credencial') }}">Credencial</a>
                <a href="{{ route('cliente') }}">Clientes</a>
            @endif
            <a onclick="logout(event)">Cerrar sesión</a>
        @endauth

        @guest
            <a href="{{ route('login') }}">Iniciar Sesión</a>
        @endguest
    </nav>

</header>

<main>
    @yield('content')
</main>


{{-- ===== MODAL ELIMINAR GLOBAL ===== --}}
<div id="modal-eliminar" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:2rem; max-width:380px; width:90%; border:0.5px solid #D3D1C7;">
        <h3 style="margin:0 0 0.5rem; font-size:18px; font-weight:500;">¿Está seguro de eliminar?</h3>
        <p style="margin:0 0 1.5rem; color:#5F5E5A; font-size:15px;">Esta acción cambiará el estado del registro a <strong>inactivo</strong>.</p>
        <div style="display:flex; justify-content:flex-end; gap:10px;">
            <button onclick="cerrarModalEliminar()"
                style="padding:8px 20px; border-radius:6px; border:0.5px solid #B4B2A9; background:transparent; cursor:pointer; font-size:14px;">
                Cancelar
            </button>
            <button id="btn-confirmar-eliminar"
                style="padding:8px 20px; border-radius:6px; background:#FCEBEB; color:#A32D2D; border:0.5px solid #F09595; cursor:pointer; font-size:14px; font-weight:500;">
                Sí, eliminar
            </button>
        </div>
    </div>
</div>

</body>

<script>

function toggleMenu() {
    const nav = document.getElementById('nav-menu');
    nav.classList.toggle('open');
}

// dropdown con click en móvil
document.querySelectorAll('.nav-dropdown > a').forEach(function(link) {
    link.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            e.preventDefault();
            const dropdown = this.closest('.nav-dropdown');
            dropdown.classList.toggle('open');
        }
    });
});

// ===== MODAL ELIMINAR =====
   function confirmarEliminar(callback) {
    const modal = document.getElementById('modal-eliminar');
    modal.style.display = 'flex';

    // 👇 clonar el botón para eliminar eventos anteriores
    const btnAnterior = document.getElementById('btn-confirmar-eliminar');
    const btnNuevo = btnAnterior.cloneNode(true);
    btnAnterior.parentNode.replaceChild(btnNuevo, btnAnterior);

    // 👇 asignar el nuevo callback directamente
    btnNuevo.addEventListener('click', function () {
        cerrarModalEliminar();
        callback(); // 👈 usar callback directo, no _eliminarCallback
    });
    }

    function cerrarModalEliminar() {
        document.getElementById('modal-eliminar').style.display = 'none';
    }

    document.getElementById('modal-eliminar').addEventListener('click', function (e) {
        if (e.target === this) cerrarModalEliminar();
    });



    function logout(e) {
        e.preventDefault();

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
@stack('scripts')
</html>
