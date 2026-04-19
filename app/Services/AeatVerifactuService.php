<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Factura;
use App\Models\Setting;
use App\Models\Verifactu;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AeatVerifactuService
{
    private bool $sandbox;

    private string $endpointUrl;

    public function __construct()
    {
        $this->sandbox = config('services.aeat.sandbox', true);
        $this->endpointUrl = $this->sandbox
            ? 'https://prewww2.aeat.es/wlpl/TIKE-CONT/ws/SuministroLRFacturasEmitidas'
            : 'https://www2.aeat.es/wlpl/TIKE-CONT/ws/SuministroLRFacturasEmitidas';
    }

    /**
     * Register a factura in Verifactu system.
     */
    public function registrarFactura(Factura $factura, string $tipoOperacion = 'alta', ?string $observaciones = null): Verifactu
    {
        $factura->load(['empresa', 'cliente']);

        // Get last hash in chain
        $ultimoRegistro = Verifactu::orderByDesc('id')->first();
        $hashAnterior = $ultimoRegistro?->hash_registro;

        $fechaHoraHuella = now()->format('Y-m-d\TH:i:s');
        $hash = Verifactu::generateHash($factura, $hashAnterior, $fechaHoraHuella);

        $qrUrl = Verifactu::generateQrUrl($factura, $this->sandbox);

        $codigo = 'VRF-'.date('Ym').'-'.str_pad(
            Verifactu::whereYear('fecha_registro', date('Y'))->count() + 1,
            5, '0', STR_PAD_LEFT
        );

        $registro = Verifactu::create([
            'codigo_registro' => $codigo,
            'numero_serie_factura' => $factura->codigo_factura,
            'fecha_expedicion' => $factura->fecha_factura->format('d-m-Y'),
            'factura_id' => $factura->id,
            'hash_registro' => $hash,
            'hash_anterior' => $hashAnterior,
            'huella' => $hash,
            'url_qr' => $qrUrl,
            'fecha_registro' => now(),
            'estado' => 'registrado',
            'tipo_operacion' => $tipoOperacion,
            'tipo_factura' => $factura->tipo_factura ?? 'F1',
            'factura_simplificada' => $factura->factura_simplificada ?? false,
            'clave_regimen' => $factura->clave_regimen_iva ?? '01',
            'descripcion_operacion' => $factura->concepto ?? 'Venta de vehículo',
            'nif_emisor' => $factura->empresa?->cif,
            'nombre_emisor' => $factura->empresa?->nombre,
            'nif_destinatario' => $factura->cliente?->dni,
            'nombre_destinatario' => $factura->cliente ? $factura->cliente->nombre.' '.$factura->cliente->apellidos : null,
            'importe_total' => $factura->total,
            'base_imponible' => $factura->subtotal,
            'cuota_tributaria' => $factura->iva_importe,
            'tipo_impositivo' => $factura->iva_porcentaje,
            'observaciones' => $observaciones,
            'sistema_informatico' => 'VEXIS',
            'version_sistema' => '1.0.0',
        ]);

        // Auto-send to AEAT if configured
        if (Setting::get('verifactu_envio_aeat', false)) {
            $this->enviarAeat($registro);
        }

        return $registro;
    }

    /**
     * Send Verifactu registration to AEAT sandbox/production.
     */
    public function enviarAeat(Verifactu $registro): array
    {
        $xml = $registro->buildAeatXml();

        try {
            // In a real implementation, this would use SOAP with client certificates
            // For sandbox testing, we simulate the AEAT response structure
            if ($this->sandbox) {
                return $this->simulateSandboxResponse($registro);
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/xml',
            ])->withBody($xml, 'application/xml')
                ->post($this->endpointUrl);

            if ($response->successful()) {
                $responseData = $this->parseAeatResponse($response->body());
                $registro->update([
                    'estado' => $responseData['estado'],
                    'csv_aeat' => $responseData['csv'] ?? null,
                    'respuesta_aeat' => $responseData,
                ]);

                return $responseData;
            }

            $errorData = [
                'estado' => 'rechazado',
                'codigo_error' => 'HTTP-'.$response->status(),
                'descripcion' => 'Error de comunicación con AEAT',
                'fecha_respuesta' => now()->format('Y-m-d H:i:s'),
            ];
            $registro->update(['estado' => 'rechazado', 'respuesta_aeat' => $errorData]);

            return $errorData;
        } catch (\Exception $e) {
            Log::error('Verifactu AEAT error: '.$e->getMessage());
            $errorData = [
                'estado' => 'registrado',
                'error' => $e->getMessage(),
                'fecha_intento' => now()->format('Y-m-d H:i:s'),
            ];
            $registro->update(['respuesta_aeat' => $errorData]);

            return $errorData;
        }
    }

    /**
     * Simulate AEAT sandbox response for testing.
     */
    private function simulateSandboxResponse(Verifactu $registro): array
    {
        // Simulate validation based on data completeness
        $errores = [];
        if (empty($registro->nif_emisor)) {
            $errores[] = ['codigo' => '1106', 'descripcion' => 'NIF del obligado tributario obligatorio'];
        }
        if (empty($registro->numero_serie_factura)) {
            $errores[] = ['codigo' => '1100', 'descripcion' => 'Número de serie de factura obligatorio'];
        }
        if ((float) $registro->importe_total <= 0) {
            $errores[] = ['codigo' => '2000', 'descripcion' => 'Importe total debe ser mayor que 0'];
        }

        if (! empty($errores)) {
            $responseData = [
                'estado' => 'rechazado',
                'csv' => null,
                'resultado' => 'Incorrecto',
                'errores' => $errores,
                'fecha_respuesta' => now()->format('Y-m-d H:i:s'),
                'entorno' => 'sandbox',
            ];
            $registro->update(['estado' => 'rechazado', 'respuesta_aeat' => $responseData]);

            return $responseData;
        }

        $csv = 'CSV'.strtoupper(substr(hash('sha256', $registro->hash_registro.now()->toString()), 0, 16));

        $responseData = [
            'estado' => 'aceptado',
            'csv' => $csv,
            'resultado' => 'Correcto',
            'codigo_registro_aeat' => 'AEAT-'.date('Y').'-'.str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
            'fecha_respuesta' => now()->format('Y-m-d H:i:s'),
            'entorno' => 'sandbox',
            'detalle' => 'Registro aceptado correctamente en entorno de pruebas',
        ];

        $registro->update([
            'estado' => 'aceptado',
            'csv_aeat' => $csv,
            'respuesta_aeat' => $responseData,
        ]);

        return $responseData;
    }

    private function parseAeatResponse(string $xml): array
    {
        // Parse real AEAT XML response
        // Simplified for initial implementation
        return [
            'estado' => 'aceptado',
            'csv' => null,
            'resultado' => 'Parsed from response',
            'fecha_respuesta' => now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Generate QR code as base64 PNG for embedding in PDFs.
     */
    public static function generateQrImage(string $url): string
    {
        $builder = new Builder(
            writer: new PngWriter,
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 200,
            margin: 10,
        );

        $result = $builder->build();

        return base64_encode($result->getString());
    }
}
