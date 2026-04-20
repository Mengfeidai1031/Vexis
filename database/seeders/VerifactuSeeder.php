<?php

namespace Database\Seeders;

use App\Models\Factura;
use App\Models\Venta;
use App\Models\Verifactu;
use Illuminate\Database\Seeder;

class VerifactuSeeder extends Seeder
{
    public function run(): void
    {
        if (Factura::count() === 0) {
            $ventas = Venta::with(['cliente', 'empresa', 'centro', 'marca'])
                ->orderBy('fecha_venta')
                ->get();
            $seq = 1;

            foreach ($ventas as $venta) {
                $subtotal = (float) $venta->precio_final;
                $empresa = $venta->empresa;
                $cp = $empresa?->codigo_postal ?? '';
                $isCanarias = str_starts_with($cp, '35') || str_starts_with($cp, '38');
                $ivaPct = $isCanarias ? 7 : 21;
                $ivaImporte = round($subtotal * $ivaPct / 100, 2);
                $total = round($subtotal + $ivaImporte, 2);

                $estados = ['emitida', 'pagada', 'pagada', 'pagada', 'vencida', 'pagada', 'anulada', 'pagada'];
                $estado = $estados[($seq - 1) % count($estados)];

                Factura::create([
                    'codigo_factura' => 'FAC-'.$venta->fecha_venta->format('Ym').'-'.str_pad((string) $seq, 4, '0', STR_PAD_LEFT),
                    'numero_serie' => 'A',
                    'venta_id' => $venta->id,
                    'cliente_id' => $venta->cliente_id,
                    'empresa_id' => $venta->empresa_id,
                    'centro_id' => $venta->centro_id,
                    'marca_id' => $venta->marca_id,
                    'emisor_id' => $venta->vendedor_id,
                    'fecha_factura' => $venta->fecha_venta,
                    'fecha_vencimiento' => $venta->fecha_venta->copy()->addDays(30),
                    'concepto' => 'Venta de vehículo '.($venta->vehiculo?->modelo ?? ''),
                    'subtotal' => $subtotal,
                    'iva_porcentaje' => $ivaPct,
                    'iva_importe' => $ivaImporte,
                    'total' => $total,
                    'estado' => $estado,
                    'tipo_factura' => 'F1',
                    'clave_regimen_iva' => $isCanarias ? '08' : '01',
                ]);
                $seq++;
            }
        }

        if (Verifactu::count() === 0) {
            $facturas = Factura::with(['empresa', 'cliente'])
                ->orderBy('fecha_factura')
                ->orderBy('id')
                ->get();
            $hashAnterior = null;
            $seq = 1;

            $estadosCiclo = ['aceptado', 'aceptado', 'aceptado', 'enviado', 'registrado', 'aceptado', 'rechazado', 'aceptado', 'aceptado_errores'];

            foreach ($facturas as $i => $factura) {
                $hash = Verifactu::generateHash($factura, $hashAnterior);
                $estado = $estadosCiclo[$i % count($estadosCiclo)];
                $respuestaAeat = match ($estado) {
                    'aceptado' => [
                        'codigo' => 'CSV-'.strtoupper(substr(md5("verifactu-{$factura->id}"), 0, 12)),
                        'fecha_validacion' => $factura->fecha_factura->copy()->addDays(1)->format('Y-m-d H:i:s'),
                        'resultado' => 'Aceptado',
                    ],
                    'rechazado' => [
                        'codigo_error' => 'ERR-4021',
                        'descripcion' => 'NIF del emisor no coincide con el registrado en AEAT',
                        'fecha_rechazo' => $factura->fecha_factura->copy()->addDays(2)->format('Y-m-d H:i:s'),
                    ],
                    default => null,
                };

                Verifactu::create([
                    'codigo_registro' => 'VRF-'.$factura->fecha_factura->format('Ym').'-'.str_pad((string) $seq, 5, '0', STR_PAD_LEFT),
                    'numero_serie_factura' => $factura->codigo_factura,
                    'fecha_expedicion' => $factura->fecha_factura->format('d-m-Y'),
                    'factura_id' => $factura->id,
                    'hash_registro' => $hash,
                    'hash_anterior' => $hashAnterior,
                    'fecha_registro' => $factura->fecha_factura->copy()->addHours(rand(1, 8)),
                    'estado' => $estado,
                    'tipo_operacion' => 'alta',
                    'tipo_factura' => $factura->tipo_factura ?? 'F1',
                    'clave_regimen' => $factura->clave_regimen_iva ?? '01',
                    'descripcion_operacion' => 'Venta de vehículo',
                    'nif_emisor' => $factura->empresa?->cif,
                    'nombre_emisor' => $factura->empresa?->nombre,
                    'nif_destinatario' => $factura->cliente?->dni,
                    'nombre_destinatario' => $factura->cliente ? $factura->cliente->nombre.' '.$factura->cliente->apellidos : null,
                    'importe_total' => $factura->total,
                    'base_imponible' => $factura->subtotal,
                    'cuota_tributaria' => $factura->iva_importe,
                    'tipo_impositivo' => $factura->iva_porcentaje,
                    'respuesta_aeat' => $respuestaAeat,
                ]);

                $hashAnterior = $hash;
                $seq++;
            }
        }
    }
}
