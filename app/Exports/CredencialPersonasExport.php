<?php


namespace App\Exports;

use App\Models\Credencial;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class CredencialPersonasExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithCustomStartCell
{
    public function collection()
    {
        return Credencial::with('persona')
            ->select(
                'carnet',
                'tipo_institucion',
                'cargo',
                'Persona_idPersona',
                DB::raw('COUNT(*) as total_impresiones'),
                DB::raw('MAX(fecha_emision) as ultima_emision')
            )
            ->groupBy(
                'Persona_idPersona',
                'carnet',
                'tipo_institucion',
                'cargo'
            )
            ->get();
    }

    // La tabla comenzará desde la fila 3
    public function startCell(): string
    {
        return 'A3';
    }

    // Encabezados
    public function headings(): array
    {
        return [
            '#',
            'CARNET',
            'NOMBRE',
            'APELLIDOS',
            'TIPO',
            'INSTITUCIÓN / CARGO',
            'DISTRITO',
            'IMPRESIONES',
            'ÚLTIMA EMISIÓN'
        ];
    }

    // Datos
    public function map($c): array
    {
        static $i = 0;
        $i++;

        return [
            $i,

            $c->carnet,

            $c->persona->nombre ?? '-',

            $c->persona->apellidos ?? '-',

            $c->tipo_institucion === 'sindicato'
                ? 'SINDICATO'
                : 'U.E.',

            $c->cargo ?? $c->persona->tipoInstitucion ?? '-',

            $c->persona->distrito ?? '-',

            $c->total_impresiones,

            \Carbon\Carbon::parse($c->ultima_emision)
                ->format('d/m/Y H:i')
        ];
    }

    // Estilos Excel
    public function styles(Worksheet $sheet)
    {
        // TITULO
        $sheet->setCellValue('A1', 'LISTA DE IMPRESIONES EMITIDAS');

        // Combinar celdas del título
        $sheet->mergeCells('A1:I1');

        // Altura título
        $sheet->getRowDimension(1)->setRowHeight(30);

        return [

            // ESTILO TITULO
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

            // ENCABEZADOS TABLA
            3 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }
}