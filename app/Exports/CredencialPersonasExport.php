<?php

namespace App\Exports;

use App\Models\Persona;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CredencialPersonasExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Persona::has('credencialPersonas')->get();
    }

    public function headings(): array
    {
        return [
            'CI',
            'Nombre',
            'Apellidos',
            'Tipo Institución',
            'Distrito'
        ];
    }

    public function map($persona): array
    {
        return [
            $persona->ci,
            $persona->nombre,
            $persona->apellidos,
            $persona->tipoInstitucion,
            $persona->distrito
        ];
    }
}
