@extends('layouts.header')

@section('content')
<style>
    .v-asist-container {
        padding: 20px;
        background-color: #f4f4f4;
        min-height: calc(100vh - 80px); /* Ajusta según el alto de tu header */
    }

    .v-asist-barra {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .v-asist-buscador {
        display: flex;
        flex: 1;
        max-width: 500px;
    }

    .v-asist-input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px 0 0 4px;
        outline: none;
    }

    .v-asist-btn-buscar {
        padding: 25px 25px;
        background: #333;
        color: white;
        border: none;
        border-radius: 0 4px 4px 0;
        cursor: pointer;


        height:40px;
        width:40px;
        border:1px solid #ccc;
        cursor:pointer;
        box-sizing:border-box;
        display:flex;
        align-items:center;
        justify-content:center;

        box-sizing:border-box;
        margin:0;
    }

    .v-asist-acciones .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-left: 10px;
        font-weight: bold;
    }

    .v-asist-btn-add { background-color: #6c757d; color: white; }
    .v-asist-btn-print { background-color: #28a745; color: white; }

    .v-asist-wrapper-tabla {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .v-asist-tabla {
        width: 100%;
        border-collapse: collapse;
    }

    .v-asist-tabla thead {
        background-color: #8f8b88; /* Color guinda */
        color: white;
    }

    .v-asist-tabla th, .v-asist-tabla td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .v-asist-badge {
        background: #d4edda;
        color: #260ae0;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.85em;
    }

    .v-asist-delete {
        color: #dc3545;
        cursor: pointer;
        font-size: 1.2em;
    }
</style>

<div class="v-asist-container">
    <div class="v-asist-barra">
        <div class="v-asist-buscador">
            <input type="text" id="input-scanner" class="v-asist-input" placeholder="Escanee el QR o busque por nombre..." autocomplete="off">
            <button class="v-asist-btn-buscar"><i class="fas fa-search"></i></button>
        </div>
        <div class="v-asist-acciones">
            <button class="btn v-asist-btn-add"><i class="fa fa-plus"></i> Agregar</button>
            <button class="btn v-asist-btn-print" onclick="exportToExcel(event)">Exportar Asistencia</button>
        </div>
    </div>

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
            <tbody>
                @forelse ($asistencias as $index => $asist)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $asist->persona->ci }}</td>
                    <td>{{ $asist->persona->nombre }} {{ $asist->persona->apellidos }}</td>
                    <td>{{ $asist->persona->distrito ?? 'S/D' }}</td>
                    <td>{{ date('H:i:s', strtotime($asist->fechahoraIngreso)) }}</td>
                    <td><span class="v-asist-badge">{{ $asist->estadoR }}</span></td>
                    <td>
                        <span class="v-asist-delete" onclick="quitarAsistencia({{ $asist->idregistroAsistencia }})">🗑</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">No hay asistencias registradas para este subevento aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modal-asistencia" class="modal" style="display:none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; margin: 15% auto; padding: 20px; width: 400px; border-radius: 8px;">
        <h3>Confirmar Asistencia</h3>
        <div id="info-cliente"></div>
        <div style="margin-top: 20px; text-align: right;">
            <button id="btn-aceptar-asistencia" class="btn v-asist-btn-print">Aceptar</button>
            <button onclick="cerrarModal()" class="btn v-asist-btn-add">Cancelar</button>
        </div>
    </div>
</div>

<script>
let procesando = false;

document.addEventListener('DOMContentLoaded', function() {
    const scannerInput = document.getElementById('input-scanner');
    const btnAceptar = document.getElementById('btn-aceptar-asistencia');

    scannerInput.focus();
    document.addEventListener('click', () => scannerInput.focus());

    scannerInput.addEventListener('keydown', async function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();

            if (procesando) return;

            let textoQR = this.value.trim();
            console.log("Escaneado:", textoQR);

            let match = textoQR.match(/CI:?\s*(\d+)/i);

            let ci = null;

            if (match && match[1]) {
                ci = match[1];
            } else {
                let backupMatch = textoQR.match(/\d{7,8}/);
                if (backupMatch) {
                    ci = backupMatch[0];
                }
            }

            if (ci) {
                await procesarEscaneo(ci);
                this.value = ""; // 🔥 SOLO limpia después de procesar
            } else {
                alert("Código no reconocido: " + textoQR);
            }
        }
    });

    btnAceptar.onclick = async function() {
        const ci = this.dataset.ci;

        try {
            const response = await fetch("{{ route('asistencia.registrar') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    ci: ci,
                    subevento_id: "{{ $subevento->idSubevento }}"
                })
            });

            const result = await response.json();

            if (result.status === 'success') {
                location.reload();
            } else {
                alert(result.message);
            }

        } catch (error) {
            console.error(error);
        }
    };
});


function abrirModal() {
    document.getElementById('modal-asistencia').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modal-asistencia').style.display = 'none';
}

async function procesarEscaneo(ci) {
    console.log("Verificando CI en el servidor: " + ci);

    try {
        const response = await fetch(`/buscar-cliente/${ci}`);
        console.log("URL:", `/buscar-cliente/${ci}`);
        // Si el servidor responde con error (500, 404, etc)
        if (!response.ok) {
            const errorText = await response.text();
            console.error("Error del servidor:", errorText);
            alert("Error en el servidor al buscar el cliente. Revisa los logs de Laravel.");
            return;
        }

        const data = await response.json();

        if (data.status === 'success') {
            document.getElementById('info-cliente').innerHTML = `
                <h4>${data.cliente.nombres} ${data.cliente.apellidos}</h4>
                <p><strong>CI:</strong> ${data.cliente.ci}</p>
            `;

            document.getElementById('btn-aceptar-asistencia').dataset.ci = ci;
            abrirModal();
        } else {
            alert("El cliente con CI " + ci + " no existe en la base de datos.");
        }
    } catch (error) {
        console.error("Error crítico en la petición:", error);
        alert("No se pudo procesar la respuesta del servidor. Revisa la consola.");
    }
}

function exportToExcel(e) {
    e.preventDefault();

    const exportUrl = `/asistencia/exportar/{{ $subevento->idSubevento }}`;

    window.location.href = exportUrl;
}
</script>
@endsection
