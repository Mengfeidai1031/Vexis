<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFacturaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'venta_id' => 'required|exists:ventas,id',
            'cliente_id' => 'nullable|exists:clientes,id',
            'empresa_id' => 'required|exists:empresas,id',
            'centro_id' => 'required|exists:centros,id',
            'marca_id' => 'nullable|exists:marcas,id',
            'fecha_factura' => 'required|date',
            'fecha_vencimiento' => 'nullable|date',
            'concepto' => 'nullable|string|max:500',
            'estado' => 'required|in:emitida,pagada,vencida,anulada',
            'observaciones' => 'nullable|string|max:2000',
        ];
    }
}
