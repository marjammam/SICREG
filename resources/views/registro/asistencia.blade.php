@extends('layouts.header')

@section('content')
<style>
    .v-asist-container {
        padding: 20px;
        background-color: #f4f4f4;
        min-height: calc(100vh - 80px);
    }

    /* ── Barra superior ── */
    .v-asist-barra {
        background: white;
        border-radius: 10px;
        padding: 18px 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        margin-bottom: 20px;
    }

    .v-asist-barra h2 {
        text-align: center;
        margin: 0 0 16px 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: #2c2c2c;
        border-bottom: 2px solid #8B0000;
        padding-bottom: 10px;
    }

    .v-asist-fila {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .v-asist-inputs {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }

    .v-asist-grupo {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .v-asist-grupo label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #656061;
        text-transform: uppercase;
    }

    .v-asist-grupo .input-fila {
        display: flex;
        align-items: stretch;
        margin-bottom: 8px;
    }

    .v-asist-input {
            flex: 1;
            height: 38px;
            width:300px;
            padding: 0 10px;
            border: 1px solid #ccc;
            border-right: none;
            border-radius: 4px 0 0 4px;
            font-size: 13px;
            box-sizing: border-box;
            margin: 0;
    }

    .v-asist-btn-buscar {
      
        background: #555;
        color: white;
        border: none;
        border-radius: 0 4px 4px 0;
        cursor: pointer;
        font-size: 0.95rem;
        transition: background 0.2s;
        height: 38px;
        width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .v-asist-btn-add {
        height: 38px;
        background-color: #6c757d;
        color: white;
        border: none;
        border-radius: 0 6px 6px 0;
        cursor: pointer;
        font-weight: bold;
        font-size: 0.85rem;
    }

    .v-asist-btn-add:hover  { background-color: #5a6268; }
    .v-asist-btn-print      { background-color: #28a745; color: white; border: none;
                               border-radius: 6px; padding: 9px 18px; cursor: pointer;
                               font-weight: bold;  height: 40px; font-size: 0.9rem; }
    .v-asist-btn-print:hover { background-color: #218838; }

    /* ── Notificación ── */
    .v-asist-notif {
        display: none;
        padding: 10px 16px;
        border-radius: 6px;
        margin-bottom: 14px;
        font-weight: 600;
        font-size: 0.95rem;
        text-align: center;
    }
    .v-asist-notif.success  { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .v-asist-notif.error    { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .v-asist-notif.duplicado { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }

    /* ── Tabla ── */
    .v-asist-wrapper-tabla {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .v-asist-tabla { width: 100%; border-collapse: collapse; }
    .v-asist-tabla thead { background-color: #8f8b88; color: white; }
    .v-asist-tabla th, .v-asist-tabla td {
        padding: 12px; text-align: left; border-bottom: 1px solid #eee;
    }

    .v-asist-badge {
        background: #d4edda; color: #260ae0;
        padding: 5px 10px; border-radius: 20px; font-size: 0.85em;
    }

    .btn-accion {
        cursor: pointer; font-size: 1rem; padding: 6px 8px;
        border-radius: 6px; border: 0.5px solid;
        transition: background-color 0.2s, transform 0.1s;
    }
    .btn-eliminar { color: #A32D2D; background-color: #FCEBEB; border-color: #F09595; }
    .btn-eliminar:hover { background-color: #F7C1C1; }
    .btn-accion:active { transform: scale(0.95); }
</style>

<div class="v-asist-container">

    {{-- ── BARRA SUPERIOR ── --}}
    <div class="v-asist-barra">
        <h2>LISTA DE ASISTENCIA — {{ $subevento->nombreSE }}</h2>

        <div class="v-asist-fila">
            <div class="v-asist-inputs">

                {{-- Input oculto para lector QR --}}
                <input type="text" id="input-scanner"
                       style="position:absolute; opacity:0; width:1px; height:1px; pointer-events:none;"
                       autocomplete="off">

                {{-- Lector QR (visual informativo) --}}
                <div class="v-asist-grupo">
                    <label><i class="fas fa-qrcode"></i> Lector QR</label>
                    <div class="input-fila">
                        <input type="text" id="input-qr-display" class="v-asist-input"
                               placeholder="Apunte el lector aquí..."
                               readonly
                               style="background:#f9f9f9; color:#888; cursor:default;">
                        <div class="v-asist-btn-buscar" style="cursor:default;">
                            <i class="fas fa-qrcode"></i>
                        </div>
                    </div>
                </div>

                {{-- Ingreso manual por CI --}}
                <div class="v-asist-grupo">
                    <label><i class="fas fa-keyboard"></i> Ingreso manual</label>
                    <div class="input-fila">
                        <input type="text" id="ci-manual" class="v-asist-input"
                               placeholder="Ingresar CI..."
                               onkeypress="if(event.key==='Enter') registrarManual()">
                        <button class="v-asist-btn-add" onclick="registrarManual()">
                            <i class="fas fa-user-plus"></i> Registrar
                        </button>
                    </div>
                </div>

            </div>

            {{-- Exportar --}}
            <button class="v-asist-btn-print" onclick="exportToExcel(event)">
                <i class="fa-solid fa-file-excel"></i> Exportar
            </button>
        </div>
    </div>

    {{-- ── NOTIFICACIÓN ── --}}
    <div id="v-asist-notif" class="v-asist-notif"></div>

    {{-- ── TABLA ── --}}
    <div class="v-asist-wrapper-tabla">
        <table class="v-asist-tabla">
            <thead>
                <tr>
                    <th>Nro</th>
                    <th>C.I.</th>
                    <th>Nombre Completo</th>
                    <th>Distrito</th>
                    <th>Hora Ingreso</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-body">
                @forelse ($asistencias as $index => $asist)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $asist->persona->ci }}</td>
                    <td>{{ $asist->persona->nombre }} {{ $asist->persona->apellidos }}</td>
                    <td>{{ $asist->persona->distrito ?? 'S/D' }}</td>
                    <td>{{ date('H:i:s', strtotime($asist->fechahoraIngreso)) }}</td>
                    <td><span class="v-asist-badge">{{ $asist->estadoR }}</span></td>
                    <td>
                        <i class="fa-solid fa-trash btn-accion btn-eliminar"
                           onclick="quitarAsistencia(event, {{ $asist->idregistroAsistencia }})"></i>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:20px;">
                        No hay asistencias registradas para este subevento aún.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script>
let procesando = false;
let notifTimer = null;

document.addEventListener('DOMContentLoaded', function() {
    const scannerInput = document.getElementById('input-scanner');

    if (scannerInput) {
        scannerInput.focus();

        // Mantener foco en el scanner salvo que se haga click en inputs visibles
        document.addEventListener('click', function(e) {
            const id  = e.target.id;
            const tag = e.target.tagName.toLowerCase();
            if (id !== 'ci-manual' && tag !== 'button') {
                scannerInput.focus();
            }
        });

        // ✅ Lector QR — registra directo
        scannerInput.addEventListener('keydown', async function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (procesando) return;

                let textoQR = this.value.trim();
                let ci      = null;

                let match = textoQR.match(/CI:?\s*(\d+)/i);
                if (match && match[1]) {
                    ci = match[1];
                } else {
                    let backup = textoQR.match(/\d{7,8}/);
                    if (backup) ci = backup[0];
                }

                // Mostrar brevemente en el display del QR
                const display = document.getElementById('input-qr-display');
                if (display) display.value = textoQR;

                if (ci) {
                    await registrarAsistencia(ci);
                } else {
                    mostrarNotif('Código QR no reconocido: ' + textoQR, 'error');
                }

                this.value = '';
                setTimeout(() => { if (display) display.value = ''; }, 2000);
            }
        });
    }
});

// ✅ Registro manual
async function registrarManual() {
    const input = document.getElementById('ci-manual');
    const ci    = input ? input.value.trim() : null;

    if (!ci) { mostrarNotif('Ingrese un CI válido', 'error'); return; }

    await registrarAsistencia(ci);
    if (input) input.value = '';
}

// ✅ Función central de registro
async function registrarAsistencia(ci) {
    if (procesando) return;
    procesando = true;

    try {
        const response = await fetch("{{ route('asistencia.registrar') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                ci:           ci,
                subevento_id: "{{ $subevento->idSubevento }}"
            })
        });

        const result = await response.json();

        if (result.status === 'success') {
            mostrarNotif('✅ ' + result.message, 'success');
            setTimeout(() => location.reload(), 1200); // recarga tras mostrar mensaje
        } else if (result.status === 'duplicado') {
            mostrarNotif('⚠️ ' + result.message, 'duplicado');
        } else {
            mostrarNotif('❌ ' + result.message, 'error');
        }

    } catch (error) {
        console.error('Error:', error);
        mostrarNotif('❌ Error de conexión. Intente nuevamente.', 'error');
    } finally {
        procesando = false;
    }
}

// ✅ Notificación temporal
function mostrarNotif(mensaje, tipo) {
    const notif = document.getElementById('v-asist-notif');
    notif.textContent    = mensaje;
    notif.className      = 'v-asist-notif ' + tipo;
    notif.style.display  = 'block';

    if (notifTimer) clearTimeout(notifTimer);
    notifTimer = setTimeout(() => { notif.style.display = 'none'; }, 4000);
}

function exportToExcel(e) {
    e.preventDefault();
    window.location.href = `/asistencia/exportar/{{ $subevento->idSubevento }}`;
}

function quitarAsistencia(e, asistenciaId) {
    e.preventDefault();
    const url      = `{{ url("asistencia") }}/${asistenciaId}`;
    const formData = new FormData();
    formData.append('_method', 'DELETE');
    formData.append('_token', '{{ csrf_token() }}');

    fetch(url, { method: 'post', credentials: 'same-origin', body: formData })
        .then(response => { if (response.redirected) window.location.href = response.url; })
        .catch(error => console.log(error));
}
</script>
@endsection