<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClientesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Cliente::with('empresa')->orderBy('apellidos')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Nombre', 'Apellidos', 'DNI', 'Email', 'Teléfono', 'Domicilio', 'Código Postal', 'Empresa'];
    }

    public function map($cliente): array
    {
        return [
            $cliente->id,
            $cliente->nombre ?? '',
            $cliente->apellidos ?? '',
            $cliente->dni ?? '',
            $cliente->email ?? '',
            $cliente->telefono ?? '',
            $cliente->domicilio ?? '',
            $cliente->codigo_postal ?? '',
            $cliente->empresa?->nombre ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true, 'size' => 12]]];
    }
}
