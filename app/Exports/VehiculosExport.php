<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Vehiculo;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VehiculosExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $userEmpresaId = Auth::user()?->empresa_id;
        
        $query = Vehiculo::with('empresa');
        
        if ($userEmpresaId) {
            $query->where('empresa_id', $userEmpresaId);
        }
        
        return $query->orderBy('modelo', 'asc')
            ->orderBy('version', 'asc')
            ->get();
    }

    /**
     * Define los encabezados de las columnas
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Chasis',
            'Modelo',
            'Versión',
            'Color Externo',
            'Color Interno',
            'Empresa',
            'CIF Empresa',
        ];
    }

    /**
     * Mapea cada fila de datos
     *
     * @param Vehiculo $vehiculo
     * @return array
     */
    public function map($vehiculo): array
    {
        return [
            $vehiculo->id,
            $vehiculo->chasis ?? '',
            $vehiculo->modelo ?? '',
            $vehiculo->version ?? '',
            $vehiculo->color_externo ?? '',
            $vehiculo->color_interno ?? '',
            $vehiculo->empresa ? $vehiculo->empresa->nombre : '',
            $vehiculo->empresa ? $vehiculo->empresa->cif : '',
        ];
    }

    /**
     * Aplica estilos a la hoja de cálculo
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
