<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'cliente_id' => 'nullable|exists:clientes,id',
            'empresa_id' => 'required|exists:empresas,id',
            'centro_id' => 'required|exists:centros,id',
            'marca_id' => 'nullable|exists:marcas,id',
            'precio_venta' => 'required|numeric|min:0',
            'descuento' => 'nullable|numeric|min:0',
            'forma_pago' => 'required|in:contado,financiado,leasing,renting',
            'estado' => 'required|in:reservada,pendiente_entrega,entregada,cancelada',
            'fecha_venta' => 'required|date',
            'fecha_entrega' => 'nullable|date',
            'observaciones' => 'nullable|string|max:2000',
            'conceptos.*.tipo' => 'required_with:conceptos|in:extra,descuento',
            'conceptos.*.descripcion' => 'required_with:conceptos|string|max:255',
            'conceptos.*.importe' => 'required_with:conceptos|numeric|min:0',
        ];
    }
}
