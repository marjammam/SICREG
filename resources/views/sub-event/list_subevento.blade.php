@extends('layouts.header')


@section('content')
<style>
        /* ============================
        CONTENEDOR PRINCIPAL
        ============================ */
        .lista-container {
            padding: 20px;
            background: #f4f4f4;
            min-height: calc(100vh - 80px);
        }
    
        /* ============================
        HEADER
        ============================ */
        .lista-header {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            align-items: center;
            justify-content: space-between;
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;

        }

        .lista-header-col {
            display: flex;
            align-items: center;
        }

        .lista-header-col:nth-child(2) {
            justify-content: center;
        }

        .lista-header-col:nth-child(3) {
            justify-content: flex-end;
        }

        .lista-header h2 {
            margin: 0;
            font-size: 1.1rem;
            color: #850B0B;
            text-align: center;
            white-space: nowrap;
        }


        .btn-exportar {
            background: #2e7d32;
            color: white;
            padding: 8px 14px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }

        .btn-exportar:hover {
            background: #1c4d1e;
            color: white;
        }
        /* ============================
        BUSCADOR
        ============================ */
        .buscador {
            display: flex;
            align-items: stretch;
            margin-bottom: 8px;
        }

        .buscador input {
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

        .btn-buscar {
            height: 38px;
            width: 40px;
            border: 1px solid #ccc;
            border-radius: 0 4px 4px 0;
            background: #eee;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .btn-buscar:hover {
            background: #ddd;
        }

     /* Caja de la tabla */
        .subevent-table-box {
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px 25px;
            overflow-x: auto;
        }

        .subevent-table-box table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .subevent-table-box thead tr {
            background-color: #333232;
            color: white;
        }

        .subevent-table-box th {
            padding: 12px 15px;
            text-align: center;
            font-weight: 600;
        }

        .subevent-table-box td {
            padding: 10px 15px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }
        .text-center {
            text-align: center;
        }

        .subevent-table-box tbody tr:hover {
            background-color: #fdf0f0;
        }

        .element-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        thead {
            background: #666666;
            color: white;
        }

        thead th {
            text-align: left;
            padding: 12px 16px;
            text-transform: uppercase;
        }

        tbody {
            background: white;
        }

        tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #e6e6e6;
        }


        /* ACCIONES */
        /* Botones de acción */
        .btn-accion {
            cursor: pointer;
            font-size: 1rem;
            padding: 6px 8px;
            border-radius: 6px;
            border: 0.5px solid;
            transition: background-color 0.2s, transform 0.1s;
        }


        .btn-editar {
            color: #151617;
            background-color: #cfd0d2;
            border-color: #4c4d4e;
            margin-right: 6px;
        }

        .btn-editar:hover {
            background-color: #a4a6a7;
        }


        .btn-accion:active {
            transform: scale(0.95);
        }

        .element-actions {
            text-align: center;
            white-space: nowrap;
        }

</style>    

<div class="lista-container">
    <div class="lista-header">
        <div class="lista-header-col">
        <div class="buscador">
            <input type="text" id="buscar" placeholder="Escriba aquí el nombre del subevento, evento"
               value="{{ $buscar ?? '' }}" onkeypress="if(event.key==='Enter') buscar()">
            <button class="btn-buscar" onclick="buscar()">
                <i class="fas fa-search"></i>
            </button>
        </div>
        </div>
        <div class="lista-header-col">
            <h2>LISTA DE SUBEVENTOS REGISTRADOS</h2>
        </div>

        <div class="lista-header-col" style="justify-content: flex-end;">
            <a href="{{ route('subeventos.exportar') }} " class="btn-exportar">
                <i class="fa-solid fa-print"></i> EXPORTAR EXCEL
            </a>
        </div>
    </div>


    <div class="subevent-table-box">
       <table>
            <thead>
                <tr>
                    <th>EVENTO</th>
                    <th>TIPO DE EVENTO</th>
                    <th>SUBEVENTO</th>
                    <th>FECHA</th>
                    <th>HORA INICIO</th>
                    <th>No ASISTENTES</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>

            @forelse ($subEvents as $subEvent)
            <tr>
                <td>{{ $subEvent->event->nombreE ?? '-' }}</td>
                <td class="text-center">{{ $subEvent->event->tipoEvento ?? '-' }}</td>
                <td>{{ $subEvent->nombreSE }}</td>
                <td class="text-center">{{ date('d/m/Y', strtotime($subEvent->fechaSE)) }}</td>
                <td class="text-center">{{ date('H:i', strtotime($subEvent->horaInicio)) }}</td>
                <td class="text-center">{{ $subEvent->nro_asistencia }}</td>
                <td>
                    <div class="element-actions">
                           
                           <a href="{{ route('asistencia.index', ['id' => $subEvent->idSubevento]) }}">
                            <i class="fa-solid fa-play btn-accion btn-editar"></i>
                            </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px;">No hay subeventos registrados.</td>
            </tr>
            @endforelse

            </tbody>
        </table>
    </div>

</div>
  
</div>

@push('scripts')
<script>
    function buscar() {
        const valor = document.getElementById('buscar').value;
        const url = new URL(window.location.href);
        url.searchParams.set('buscar', valor);
        window.location.href = url.toString();
    }
</script>
@endpush

@endsection