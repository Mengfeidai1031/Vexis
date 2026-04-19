<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VentasExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Venta::with(['vehiculo', 'cliente', 'empresa', 'centro', 'marca', 'vendedor'])
            ->orderByDesc('fecha_venta')->get();
    }

    public function headings(): array
    {
        return ['Código', 'Vehículo', 'Cliente', 'Marca', 'Empresa', 'Centro', 'Precio Venta', 'Descuento', 'Precio Final', 'Subtotal', 'Impuesto', 'Imp. %', 'Imp. €', 'Total', 'Forma Pago', 'Estado', 'Fecha Venta', 'Fecha Entrega', 'Vendedor'];
    }

    public function map($venta): array
    {
        return [
            $venta->codigo_venta,
            $venta->vehiculo?->modelo ?? '',
            $venta->cliente ? $venta->cliente->nombre.' '.$venta->cliente->apellidos : '',
            $venta->marca?->nombre ?? '',
            $venta->empresa?->nombre ?? '',
            $venta->centro?->nombre ?? '',
            $venta->precio_venta ?? 0,
            $venta->descuento ?? 0,
            $venta->precio_final ?? 0,
            $venta->subtotal ?? 0,
            $venta->impuesto_nombre ?? '',
            $venta->impuesto_porcentaje ?? 0,
            $venta->impuesto_importe ?? 0,
            $venta->total ?? 0,
            Venta::$formasPago[$venta->forma_pago] ?? $venta->forma_pago,
            Venta::$estados[$venta->estado] ?? $venta->estado,
            $venta->fecha_venta?->format('d/m/Y') ?? '',
            $venta->fecha_entrega?->format('d/m/Y') ?? '',
            $venta->vendedor?->name ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true, 'size' => 12]]];
    }
}
