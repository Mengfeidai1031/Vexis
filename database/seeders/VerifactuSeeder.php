<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Factura;
use App\Models\Venta;
use App\Models\Verifactu;

class VerifactuSeeder extends Seeder
{
    public function run(): void
    {
        // Create facturas from existing ventas if none exist
        if (Factura::count() === 0) {
            $ventas = Venta::with(['cliente', 'empresa', 'centro', 'marca'])->get();
            $seq = 1;

            foreach ($ventas as $venta) {
                $subtotal = (float) $venta->precio_final;
                // Determine tax: IGIC 7% for Canarias (CP 35xxx/38xxx), IVA 21% otherwise
                $empresa = $venta->empresa;
                $cp = $empresa?->codigo_postal ?? '';
                $isCanarias = str_starts_with($cp, '35') || str_starts_with($cp, '38');
                $ivaPct = $isCanarias ? 7 : 21;
                $ivaImporte = round($subtotal * $ivaPct / 100, 2);
                $total = round($subtotal + $ivaImporte, 2);

                $estados = ['emitida', 'pagada', 'pagada', 'emitida', 'pagada', 'vencida', 'pagada', 'emitida'];
                $estado = $estados[$seq - 1] ?? 'emitida';

                Factura::create([
                    'codigo_factura' => 'FAC-' . date('Ym') . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT),
                    'venta_id' => $venta->id,
                    'cliente_id' => $venta->cliente_id,
                    'empresa_id' => $venta->empresa_id,
                    'centro_id' => $venta->centro_id,
                    'marca_id' => $venta->marca_id,
                    'emisor_id' => $venta->vendedor_id,
                    'fecha_factura' => $venta->fecha_venta,
                    'fecha_vencimiento' => $venta->fecha_venta->addDays(30),
                    'concepto' => 'Venta de vehículo ' . ($venta->vehiculo?->modelo ?? ''),
                    'subtotal' => $subtotal,
                    'iva_porcentaje' => $ivaPct,
                    'iva_importe' => $ivaImporte,
                    'total' => $total,
                    'estado' => $estado,
                ]);
                $seq++;
            }
        }

        // Create Verifactu records for existing facturas
        if (Verifactu::count() === 0) {
            $facturas = Factura::with(['empresa', 'cliente'])->orderBy('fecha_factura')->get();
            $hashAnterior = null;
            $seq = 1;

            $estadosCiclo = ['validado', 'validado', 'enviado', 'validado', 'registrado', 'validado', 'rechazado', 'validado'];

            foreach ($facturas as $i => $factura) {
                $hash = Verifactu::generateHash($factura, $hashAnterior);
                $estado = $estadosCiclo[$i] ?? 'registrado';

                $respuestaAeat = null;
                if ($estado === 'validado') {
                    $respuestaAeat = [
                        'codigo' => 'CSV-' . strtoupper(substr(md5("verifactu-{$factura->id}"), 0, 12)),
                        'fecha_validacion' => $factura->fecha_factura->addDays(1)->format('Y-m-d H:i:s'),
                        'resultado' => 'Aceptado',
                    ];
                } elseif ($estado === 'rechazado') {
                    $respuestaAeat = [
                        'codigo_error' => 'ERR-4021',
                        'descripcion' => 'NIF del emisor no coincide con el registrado en AEAT',
                        'fecha_rechazo' => $factura->fecha_factura->addDays(2)->format('Y-m-d H:i:s'),
                    ];
                }

                Verifactu::create([
                    'codigo_registro' => 'VRF-' . date('Ym') . '-' . str_pad($seq, 5, '0', STR_PAD_LEFT),
                    'factura_id' => $factura->id,
                    'hash_registro' => $hash,
                    'hash_anterior' => $hashAnterior,
                    'fecha_registro' => $factura->fecha_factura->addHours(rand(1, 8)),
                    'estado' => $estado,
                    'tipo_operacion' => 'emision',
                    'nif_emisor' => $factura->empresa?->cif,
                    'nombre_emisor' => $factura->empresa?->nombre,
                    'importe_total' => $factura->total,
                    'respuesta_aeat' => $respuestaAeat,
                ]);

                $hashAnterior = $hash;
                $seq++;
            }
        }
    }
}
