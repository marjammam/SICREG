@extends('layouts.header')


@section('content')
<link rel="stylesheet" href="{{ asset('css/credencial-lista.css') }}">
<div class="lista-container">

    <div class="lista-header">
        <div class="lista-header-col">
            <div class="buscador">
                <input type="text" id="buscar_ci" placeholder="Escriba aquí su carnet de identidad" value="{{ $buscar ?? '' }}"
                    onkeypress="if(event.key==='Enter') buscarCI()">
                <button class="btn-buscar" onclick="buscarCI()"> <i class="fas fa-search"></i></button>
            </div>
        </div>

        <div class="lista-header-col">
            <h2>LISTA DE CREDENCIALES EMITIDAS</h2>
        </div>

        <div class="lista-header-col" style="justify-content: flex-end;">
            <a href="{{ route('credenciales.excel') }}" class="btn-exportar">
                <i class="fa-solid fa-print"></i> EXPORTAR EXCEL
            </a>
        </div>
    </div>

    <div class="lista-wrapper">
        <table class="lista-tabla">
            <thead>
                <tr>
                    <th>#</th>
                    <th>CARNET</th>
                    <th>NOMBRE</th>
                    <th>APELLIDOS</th>
                    <th>TIPO</th>
                    <th>INSTITUCIÓN / CARGO</th>
                    <th>DISTRITO</th>
                    <th>IMPRESIONES</th>
                    <th>ÚLTIMA EMISIÓN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($credenciales as $c)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $c->carnet }}</td>
                    <td>{{ $c->persona->nombre ?? '-' }}</td>
                    <td>{{ $c->persona->apellidos ?? '-' }}</td>
                    <td>
                        @if($c->tipo_institucion === 'sindicato')
                            <span class="badge-sindicato">SINDICATO</span>
                        @else
                            <span class="badge-ue">U.E.</span>
                        @endif
                    </td>
                    <td>{{ $c->cargo ?? $c->persona->tipoInstitucion ?? '-' }}</td>
                    <td>{{ $c->persona->distrito ?? '-' }}</td>
                    <td class="td-impresiones">{{ $c->total_impresiones }}</td>
                    <td>{{ \Carbon\Carbon::parse($c->ultima_emision)->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="sin-datos">No hay credenciales registradas aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
  
</div>

@push('scripts')
<script>
    function buscarCI() {
        const valor = document.getElementById('buscar_ci').value;
        const url = new URL(window.location.href);
        url.searchParams.set('buscar', valor);
        window.location.href = url.toString();
    }
</script>
@endpush

@endsection