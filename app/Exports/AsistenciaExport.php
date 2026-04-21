<?php

namespace App\Exports;

use App\Models\Asistencia;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AsistenciaExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $subEventId;

    public function __construct(int $subEventId)
    {
        $this->subEventId = $subEventId;
    }

    public function query()
    {
        $query = Asistencia::query();

        $query->with(['persona', 'subevento', 'subevento.event'])
            ->where('Subevento_idSubevento', $this->subEventId)
            ->orderBy('fechahoraIngreso', 'desc');

        return $query;
    }

    public function map($asistencia): array
    {
        return [
            $asistencia->fechahoraIngreso,
            $asistencia->fechahoraSalida,
            $asistencia->persona->ci,
            $asistencia->persona->nombre,
            $asistencia->persona->apellidos,
            $asistencia->persona->tipoInstitucion,
            $asistencia->persona->distrito,
            $asistencia->subevento->nombreSE,
            $asistencia->subevento->tipoEvento,
            $asistencia->subevento->event->nombreE,
            $asistencia->subevento->event->descripcionE,
            $asistencia->subevento->event->fechaInicioE,
        ];
    }

    public function headings(): array
    {
        return [
            'Fecha de Ingreso',
            'Fecha de Salida',
            'CI',
            'Nombre',
            'Apellidos',
            'Institución',
            'Distrito',
            'Subevento',
            'Tipo de Subevento',
            'Evento',
            'Descripción',
            'Fecha de Evento',
        ];
    }
}
