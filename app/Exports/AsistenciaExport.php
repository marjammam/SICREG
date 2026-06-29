<?php

namespace App\Exports;

use App\Models\Asistencia;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell; 
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AsistenciaExport implements FromQuery, WithMapping, WithHeadings, WithStyles, WithCustomStartCell
{
    use Exportable;

    protected $subEventId;

    public function __construct(int $subEventId)
    {
        $this->subEventId = $subEventId;
    }

    public function query()
    {
        return Asistencia::query()
            ->with(['persona', 'subevento', 'subevento.event'])
            ->where('Subevento_idSubevento', $this->subEventId)
            ->orderBy('fechahoraIngreso', 'desc');
    }

    // ✅ WithCustomStartCell usa startCell()
    public function startCell(): string
    {
        return 'A3'; // fila 3 = encabezados, fila 4 en adelante = datos
    }

    public function headings(): array
    {
        return [
            '#',
            'CI',
            'Nombre',
            'Apellidos',
            'Institución',
            'Distrito',
            'Evento',
            'Tipo de Subevento',
            'Subevento',
            'Fecha de Ingreso',
            'Hora de Ingreso',
        ];
    }

    public function map($asistencia): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $asistencia->persona->ci,
            $asistencia->persona->nombre,
            $asistencia->persona->apellidos,
            $asistencia->persona->tipoInstitucion ?? '—',
            $asistencia->persona->distrito        ?? '—',
            $asistencia->subevento->event->nombreE,
            $asistencia->subevento->event->tipoEvento    ?? '—',
            $asistencia->subevento->nombreSE     ?? '—',
            optional($asistencia->fechahoraIngreso)->format('d/m/Y') ?? '—',
            optional($asistencia->fechahoraIngreso)->format('H:i:s')  ?? '—',
        ];
       
    }

    public function styles(Worksheet $sheet)
    {
        $ultimaFila = $sheet->getHighestRow();

        // ✅ Título en A1
        $sheet->setCellValue('A1', 'LISTA DE ASISTENCIA');
        $sheet->mergeCells('A1:K1');
        $sheet->getRowDimension(1)->setRowHeight(30);

        // ✅ Fila 2 vacía como separador visual
        $sheet->getRowDimension(2)->setRowHeight(8);
    
        // ✅ Bordes en encabezados y datos (A3 en adelante)
        $sheet->getStyle("A3:K{$ultimaFila}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // ✅ Ancho automático
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [
            // Título fila 1
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical'   => 'center',
                ],
            ],
            // Encabezados fila 3
            3 => [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => 'center',
                ],
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    ],
                ],
            ],
        ];
    }
}