<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Factura;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FacturasExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Factura::with(['venta', 'cliente', 'empresa', 'centro', 'marca', 'emisor'])
            ->orderByDesc('fecha_factura')->get();
    }

    public function headings(): array
    {
        return ['Código', 'Venta', 'Cliente', 'Marca', 'Empresa', 'Centro', 'Concepto', 'Subtotal', 'IVA %', 'IVA Importe', 'Total', 'Estado', 'Fecha Factura', 'Fecha Vencimiento', 'Emisor'];
    }

    public function map($factura): array
    {
        return [
            $factura->codigo_factura,
            $factura->venta?->codigo_venta ?? '',
            $factura->cliente ? $factura->cliente->nombre.' '.$factura->cliente->apellidos : '',
            $factura->marca?->nombre ?? '',
            $factura->empresa?->nombre ?? '',
            $factura->centro?->nombre ?? '',
            $factura->concepto ?? '',
            $factura->subtotal ?? 0,
            $factura->iva_porcentaje ?? 21,
            $factura->iva_importe ?? 0,
            $factura->total ?? 0,
            Factura::$estados[$factura->estado] ?? $factura->estado,
            $factura->fecha_factura?->format('d/m/Y') ?? '',
            $factura->fecha_vencimiento?->format('d/m/Y') ?? '',
            $factura->emisor?->name ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true, 'size' => 12]]];
    }
}
