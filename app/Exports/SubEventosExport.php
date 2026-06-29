<?php

namespace App\Exports;

use App\Models\SubEvent;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class SubEventosExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithCustomStartCell
{
    public function collection()
    {
        return SubEvent::with('event')
            ->withCount(['asistencias as nro_asistencia'])
            ->orderBy('fechaSE', 'desc')
            ->get();
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function headings(): array
    {
        return [
            '#',
            'EVENTO',
            'TIPO DE EVENTO',
            'SUBEVENTO',
            'FECHA',
            'HORA INICIO',
            'No ASISTENTES',
        ];
    }

    public function map($s): array
    {
        static $i = 0;
        $i++;

        return [
            $i,
            $s->event->nombreE ?? '-',
            $s->event->tipoEvento ?? '-',
            $s->nombreSE,
            \Carbon\Carbon::parse($s->fechaSE)->format('d/m/Y'),
            \Carbon\Carbon::parse($s->horaInicio)->format('H:i'),
            $s->nro_asistencia,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setCellValue('A1', 'LISTA DE SUBEVENTOS REGISTRADOS');
        $sheet->mergeCells('A1:G1');
        $sheet->getRowDimension(1)->setRowHeight(30);

        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                ],
            ],
            3 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }
}