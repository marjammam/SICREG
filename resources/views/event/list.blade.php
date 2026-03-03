<div class="element" id="{{ $event->idEvento }}">
    <div class="info">
        <h4 class="title">{{ $event->nombreE }}</h4>
        <span class="date">{{ date('d/m/Y', strtotime($event->fechaInicioE)) }}</span>
        <span class="state">Estado: {{ $event->estadoE }}</span>
    </div>

    <div class="actions">
        <i class="fa-solid fa-pen-to-square icon-btn" onclick="edit(event, {{ $event }})"></i>
        <i class="fa-solid fa-play icon-btn"></i>
    </div>
</div>
