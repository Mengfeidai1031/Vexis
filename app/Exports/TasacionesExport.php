<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Tasacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TasacionesExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Tasacion::with(['cliente', 'empresa', 'marca', 'tasador'])
            ->orderByDesc('fecha_tasacion')->get();
    }

    public function headings(): array
    {
        return ['Código', 'Marca Vehículo', 'Modelo', 'Año', 'Matrícula', 'Kilometraje', 'Combustible', 'Estado Vehículo', 'Valor Estimado', 'Valor Final', 'Estado', 'Cliente', 'Empresa', 'Fecha', 'Tasador'];
    }

    public function map($tasacion): array
    {
        return [
            $tasacion->codigo_tasacion,
            $tasacion->vehiculo_marca ?? '',
            $tasacion->vehiculo_modelo ?? '',
            $tasacion->vehiculo_anio ?? '',
            $tasacion->matricula ?? '',
            $tasacion->kilometraje ?? 0,
            $tasacion->combustible ?? '',
            Tasacion::$estadosVehiculo[$tasacion->estado_vehiculo] ?? $tasacion->estado_vehiculo ?? '',
            $tasacion->valor_estimado ?? 0,
            $tasacion->valor_final ?? 0,
            Tasacion::$estados[$tasacion->estado] ?? $tasacion->estado,
            $tasacion->cliente ? $tasacion->cliente->nombre.' '.$tasacion->cliente->apellidos : '',
            $tasacion->empresa?->nombre ?? '',
            $tasacion->fecha_tasacion?->format('d/m/Y') ?? '',
            $tasacion->tasador?->name ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true, 'size' => 12]]];
    }
}
