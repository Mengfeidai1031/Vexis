<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Stock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StocksExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Stock::with(['almacen', 'empresa', 'centro'])
            ->orderBy('nombre_pieza')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Referencia', 'Nombre Pieza', 'Cantidad', 'Stock Mínimo', 'Precio Unitario', 'Almacén', 'Empresa', 'Centro', 'Estado'];
    }

    public function map($stock): array
    {
        return [
            $stock->id,
            $stock->referencia ?? '',
            $stock->nombre_pieza ?? '',
            $stock->cantidad ?? 0,
            $stock->stock_minimo ?? 0,
            $stock->precio_unitario ?? 0,
            $stock->almacen?->nombre ?? '',
            $stock->empresa?->nombre ?? '',
            $stock->centro?->nombre ?? '',
            $stock->cantidad <= $stock->stock_minimo ? 'BAJO STOCK' : 'OK',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true, 'size' => 12]]];
    }
}
