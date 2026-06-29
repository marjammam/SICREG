@extends('layouts.header')

@section('content')
<link rel="stylesheet" href="{{ asset('css/credencial-lista.css') }}">

<style>
/* ── Cabecera ── */
.verificar-header {
    background: #fff;
    border-radius: 10px;
    padding: 20px 28px 16px;
    margin-bottom: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.verificar-titulo {
    text-align: center;
    font-size: 1.4rem;
    font-weight: 700;
    color: #2c2c2c;
    margin: 0 0 18px 0;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #8B0000;
    padding-bottom: 10px;
}

.verificar-fila {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
}

/* IZQUIERDA */
.verificar-datos {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.verificar-datos p {
    margin: 0;
    font-size: 0.95rem;
    color: #2c2c2c;
}

.etiqueta {
    color: #656061;
    font-weight: 600;
}

/* DERECHA */
.verificar-acciones {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
}

.buscador {
    display: flex;
    align-items: center;
    gap: 6px;
}

.buscador input {
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 0.9rem;
    width: 260px;
}

.btn-buscar {
    padding: 8px 14px;
    background: #8B0000;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.95rem;
    transition: background 0.2s;
}

.btn-buscar:hover { background: #6a0000; }
.btn-exportar {
    padding: 8px 18px;
    background: #28a745;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 600;
    transition: background 0.2s;
}

.btn-exportar:hover { background: #218838; }

/* ── ESTILOS DE IMPRESIÓN ── */

@media print {

    @page {
        size: A4 portrait;
        margin: 10mm;
    }

    /* ✅ Ocultar elementos específicos en lugar de body > * */
    nav, header, .navbar, .sidebar,
    .no-print {
        display: none !important;
    }

    body {
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
    }

    /* ✅ Contenedores del layout de Laravel */
    .container, main, #app, #content {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        box-shadow: none !important;
    }

    /* ✅ Mostrar explícitamente lo que necesitas */
    .lista-container {
        display: block !important;
        box-shadow: none !important;
        padding: 0 !important;
        width: 100% !important;
    }

    .verificar-header {
        display: block !important;
        box-shadow: none !important;
        padding: 0 0 12px 0 !important;
        border-bottom: 1px solid #ccc !important;
    }

    .verificar-datos {
        display: block !important;
    }

    .verificar-fila {
        display: block !important;
    }

    .lista-wrapper {
        display: block !important;
    }

    .lista-tabla {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    .lista-tabla th,
    .lista-tabla td {
        border: 1px solid #999 !important;
        padding: 6px 10px !important;
        font-size: 0.85rem !important;
    }
}
</style>

<div class="lista-container">
  {{-- CABECERA VERIFICAR ASISTENCIA --}}
    <div class="verificar-header">

        {{-- TÍTULO CENTRADO --}}
        <h2 class="verificar-titulo">EVENTOS ASISTIDOS</h2>

        {{-- FILA: datos personales | buscador + botón --}}
        <div class="verificar-fila">

            {{-- IZQUIERDA: datos personales --}}
            <div class="verificar-datos" id="seccion-imprimible">
                <p><span class="etiqueta">C.I.:</span> <strong id="info-ci"> </strong></p>
                <p><span class="etiqueta">NOMBRE COMPLETO:</span> <strong id="info-nombre"> </strong></p>
                <p><span class="etiqueta">CANTIDAD DE ASISTENCIAS:</span> <strong id="info-asistencias"> </strong></p>
            </div>

            {{-- DERECHA: buscador + botón imprimir --}}
            <div class="verificar-acciones no-print">
                <div class="buscador">
                    <input type="text" id="buscar_ci"
                        placeholder="Escriba el carnet de identidad"
                        onkeypress="if(event.key==='Enter') buscarCI()">
                    <button class="btn-buscar" onclick="buscarCI()">
                        <i class="fas fa-search"></i>
                    </button>
                    <button class="btn-exportar" onclick="window.print()">
                    <i class="fa-solid fa-print"></i> IMPRIMIR
                    </button>
               
                </div>
                
            </div>

        </div>
    </div>

    {{-- MENSAJE DE ERROR --}}
    <div id="mensaje-error" class="no-print"
        style="display:none; color:red; text-align:center; margin:8px 0; font-weight:bold;">
    </div>

    {{-- TABLA --}}
    <div class="lista-wrapper">
        <table class="lista-tabla">
            <thead>
                <tr>
                    <th>#</th>
                    <th>EVENTO</th>
                    <th>SUBEVENTO</th>
                    <th>FECHA</th>
                    <th>HORA</th>
                </tr>
            </thead>
            <tbody id="tabla-body">
                <tr>
                    <td colspan="5" class="sin-datos">
                        Ingrese un carnet para ver las asistencias.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function buscarCI() {
    const ci    = document.getElementById('buscar_ci').value.trim();
    const error = document.getElementById('mensaje-error');
    const tbody = document.getElementById('tabla-body');

    error.style.display = 'none';

    if (!ci) {
        error.textContent   = 'Por favor ingrese un carnet de identidad.';
        error.style.display = 'block';
        return;
    }

    tbody.innerHTML = `<tr><td colspan="5" class="sin-datos">Buscando...</td></tr>`;

    fetch(`/asistencia/buscar-ci?ci=${encodeURIComponent(ci)}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => {
        // ✅ Verificar si la respuesta es OK antes de parsear
        if (!res.ok) throw new Error('Error HTTP: ' + res.status);
        return res.json();
    })
    .then(data => {
        console.log('Respuesta del servidor:', data); // ✅ Ver en consola exactamente qué llega

        if (data.error) {
            document.getElementById('info-ci').textContent          = ' ';
            document.getElementById('info-nombre').textContent      = ' ';
            document.getElementById('info-asistencias').textContent = ' ';
            tbody.innerHTML = `<tr><td colspan="5" class="sin-datos">${data.error}</td></tr>`;
            return;
        }

        // ✅ Llenar datos persona
        document.getElementById('info-ci').textContent          = data.persona.ci;
        document.getElementById('info-nombre').textContent      = data.persona.nombre_completo;
        document.getElementById('info-asistencias').textContent = data.total_asistencias;

        // ✅ Llenar tabla
        if (!data.asistencias || data.asistencias.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="sin-datos">
                                   Esta persona no tiene asistencias registradas.
                               </td></tr>`;
            return;
        }

        tbody.innerHTML = data.asistencias.map((a, i) => `
            <tr>
                <td>${i + 1}</td>
                <td>${a.evento}</td>
                <td>${a.subevento}</td>
                <td>${a.fecha}</td>
                <td>${a.hora}</td>
            </tr>
        `).join('');
    })
    .catch(err => {
        console.error('Error fetch:', err); // ✅ Ver error exacto en consola
        error.textContent   = 'Error de conexión. Intente nuevamente.';
        error.style.display = 'block';
        tbody.innerHTML     = `<tr><td colspan="5" class="sin-datos">Error al cargar datos.</td></tr>`;
    });
}
</script>
@endsection