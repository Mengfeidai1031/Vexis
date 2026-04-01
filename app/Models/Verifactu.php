<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Verifactu extends Model
{
    protected $table = 'verifactus';

    protected $fillable = [
        'codigo_registro', 'numero_serie_factura', 'fecha_expedicion',
        'factura_id', 'hash_registro', 'hash_anterior', 'huella', 'url_qr', 'csv_aeat',
        'fecha_registro', 'estado', 'tipo_operacion', 'tipo_factura', 'factura_simplificada',
        'clave_regimen', 'descripcion_operacion',
        'nif_emisor', 'nombre_emisor', 'nif_destinatario', 'nombre_destinatario',
        'importe_total', 'base_imponible', 'cuota_tributaria', 'tipo_impositivo',
        'respuesta_aeat', 'observaciones',
        'id_factura_rectificada', 'sistema_informatico', 'version_sistema',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
        'importe_total' => 'decimal:2',
        'base_imponible' => 'decimal:2',
        'cuota_tributaria' => 'decimal:2',
        'tipo_impositivo' => 'decimal:2',
        'respuesta_aeat' => 'array',
        'factura_simplificada' => 'boolean',
    ];

    // Estados del registro según ciclo de vida AEAT
    public static array $estados = [
        'registrado' => 'Registrado',        // Generado en sistema local
        'enviado' => 'Enviado a AEAT',        // Enviado al sandbox/producción
        'aceptado' => 'Aceptado',             // AEAT acepta el registro
        'aceptado_errores' => 'Aceptado con errores', // AEAT acepta con advertencias
        'rechazado' => 'Rechazado',           // AEAT rechaza el registro
        'anulado' => 'Anulado',               // Registro anulado
    ];

    public static array $tiposOperacion = [
        'alta' => 'Alta (Emisión)',
        'anulacion' => 'Anulación',
    ];

    // Tipos de factura según SII/Verifactu
    public static array $tiposFactura = [
        'F1' => 'F1 - Factura completa (art. 6, 7.2 y 7.3 del RD 1619/2012)',
        'F2' => 'F2 - Factura simplificada (art. 6.1.d y 7.1 del RD 1619/2012)',
        'F3' => 'F3 - Factura emitida en sustitución de simplificadas',
        'R1' => 'R1 - Factura rectificativa (art. 80.1, 80.2 y art. 80.6)',
        'R2' => 'R2 - Factura rectificativa (art. 80.3)',
        'R3' => 'R3 - Factura rectificativa (art. 80.4)',
        'R4' => 'R4 - Factura rectificativa (resto)',
        'R5' => 'R5 - Factura rectificativa en facturas simplificadas',
    ];

    // Claves de régimen IVA según Verifactu
    public static array $clavesRegimen = [
        '01' => '01 - Régimen general',
        '02' => '02 - Exportación',
        '03' => '03 - Bienes usados, objetos de arte',
        '04' => '04 - Régimen especial de oro de inversión',
        '05' => '05 - Régimen especial de agencias de viaje',
        '06' => '06 - Régimen especial de grupos de entidades (IVA)',
        '07' => '07 - Régimen especial del criterio de caja',
        '08' => '08 - Operaciones sujetas a IGIC/IPSI',
        '09' => '09 - Facturación de prestaciones de servicios de agencias de viaje',
        '14' => '14 - Factura con IVA pendiente de devengo (certificaciones obra)',
        '15' => '15 - Factura con IVA pendiente de devengo (tracto sucesivo)',
    ];

    public function factura(): BelongsTo { return $this->belongsTo(Factura::class); }

    /**
     * Generate hash according to Real Decreto 1007/2023 art. 12.
     * Huella = SHA-256(IDFactura + FechaExpedición + TipoFactura + CuotaTotal + ImporteTotal + Huella anterior + FechaHoraHuella)
     */
    public static function generateHash(Factura $factura, ?string $hashAnterior = null, ?string $fechaHoraHuella = null): string
    {
        $fechaHoraHuella = $fechaHoraHuella ?? now()->format('Y-m-d\TH:i:s');

        $data = implode('&', [
            'IDEmisorFactura=' . ($factura->empresa?->cif ?? ''),
            'NumSerieFactura=' . $factura->codigo_factura,
            'FechaExpedicionFactura=' . $factura->fecha_factura->format('d-m-Y'),
            'TipoFactura=' . ($factura->tipo_factura ?? 'F1'),
            'CuotaTotal=' . number_format((float) $factura->iva_importe, 2, '.', ''),
            'ImporteTotal=' . number_format((float) $factura->total, 2, '.', ''),
            'Huella=' . ($hashAnterior ?? ''),
            'FechaHoraHuella=' . $fechaHoraHuella,
        ]);

        return hash('sha256', $data);
    }

    /**
     * Generate QR code URL for AEAT verification per Orden HAC/1177/2024.
     * Format: https://prewww2.aeat.es/wlpl/TIKE-CONT/ValidarQR?nif=X&numserie=X&fecha=X&importe=X
     */
    public static function generateQrUrl(Factura $factura, bool $sandbox = true): string
    {
        $baseUrl = $sandbox
            ? 'https://prewww2.aeat.es/wlpl/TIKE-CONT/ValidarQR'
            : 'https://www2.aeat.es/wlpl/TIKE-CONT/ValidarQR';

        $params = http_build_query([
            'nif' => $factura->empresa?->cif ?? '',
            'numserie' => $factura->codigo_factura,
            'fecha' => $factura->fecha_factura->format('d-m-Y'),
            'importe' => number_format((float) $factura->total, 2, '.', ''),
        ]);

        return $baseUrl . '?' . $params;
    }

    /**
     * Build XML payload for AEAT SuministroLR (alta de facturas emitidas).
     */
    public function buildAeatXml(): string
    {
        $factura = $this->factura;
        $empresa = $factura?->empresa;
        $cliente = $factura?->cliente;

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siiLR="https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/SusrtroLRFacturasEmitidas.xsd">' . "\n";
        $xml .= '<soapenv:Body>' . "\n";
        $xml .= '<siiLR:RegistroFactura>' . "\n";

        // Cabecera
        $xml .= '  <Cabecera>' . "\n";
        $xml .= '    <ObligadoEmision>' . "\n";
        $xml .= '      <NombreRazon>' . htmlspecialchars($empresa?->nombre ?? '') . '</NombreRazon>' . "\n";
        $xml .= '      <NIF>' . htmlspecialchars($empresa?->cif ?? '') . '</NIF>' . "\n";
        $xml .= '    </ObligadoEmision>' . "\n";
        $xml .= '  </Cabecera>' . "\n";

        // Registro alta factura
        $xml .= '  <RegistroAlta>' . "\n";
        $xml .= '    <IDFactura>' . "\n";
        $xml .= '      <IDEmisorFactura>' . htmlspecialchars($empresa?->cif ?? '') . '</IDEmisorFactura>' . "\n";
        $xml .= '      <NumSerieFactura>' . htmlspecialchars($factura?->codigo_factura ?? '') . '</NumSerieFactura>' . "\n";
        $xml .= '      <FechaExpedicionFactura>' . ($factura?->fecha_factura?->format('d-m-Y') ?? '') . '</FechaExpedicionFactura>' . "\n";
        $xml .= '    </IDFactura>' . "\n";
        $xml .= '    <NombreRazonEmisor>' . htmlspecialchars($empresa?->nombre ?? '') . '</NombreRazonEmisor>' . "\n";
        $xml .= '    <TipoFactura>' . htmlspecialchars($this->tipo_factura) . '</TipoFactura>' . "\n";
        $xml .= '    <ClaveRegimenIvaEsp>' . htmlspecialchars($this->clave_regimen) . '</ClaveRegimenIvaEsp>' . "\n";
        $xml .= '    <DescripcionOperacion>' . htmlspecialchars($this->descripcion_operacion ?? 'Venta') . '</DescripcionOperacion>' . "\n";

        // Destinatario
        if ($cliente) {
            $xml .= '    <Destinatarios>' . "\n";
            $xml .= '      <IDDestinatario>' . "\n";
            $xml .= '        <NombreRazon>' . htmlspecialchars($cliente->nombre . ' ' . $cliente->apellidos) . '</NombreRazon>' . "\n";
            $xml .= '        <NIF>' . htmlspecialchars($cliente->dni ?? '') . '</NIF>' . "\n";
            $xml .= '      </IDDestinatario>' . "\n";
            $xml .= '    </Destinatarios>' . "\n";
        }

        // Desglose
        $xml .= '    <Desglose>' . "\n";
        $xml .= '      <DetalleDesglose>' . "\n";
        $xml .= '        <ClaveImpuesto>01</ClaveImpuesto>' . "\n"; // 01=IVA
        $xml .= '        <TipoImpositivo>' . number_format((float) $this->tipo_impositivo, 2, '.', '') . '</TipoImpositivo>' . "\n";
        $xml .= '        <BaseImponible>' . number_format((float) $this->base_imponible, 2, '.', '') . '</BaseImponible>' . "\n";
        $xml .= '        <CuotaRepercutida>' . number_format((float) $this->cuota_tributaria, 2, '.', '') . '</CuotaRepercutida>' . "\n";
        $xml .= '      </DetalleDesglose>' . "\n";
        $xml .= '    </Desglose>' . "\n";

        $xml .= '    <CuotaTotal>' . number_format((float) $this->cuota_tributaria, 2, '.', '') . '</CuotaTotal>' . "\n";
        $xml .= '    <ImporteTotal>' . number_format((float) $this->importe_total, 2, '.', '') . '</ImporteTotal>' . "\n";

        // Huella
        $xml .= '    <Encadenamiento>' . "\n";
        if ($this->hash_anterior) {
            $xml .= '      <RegistroAnterior>' . "\n";
            $xml .= '        <Huella>' . $this->hash_anterior . '</Huella>' . "\n";
            $xml .= '      </RegistroAnterior>' . "\n";
        } else {
            $xml .= '      <PrimerRegistro>S</PrimerRegistro>' . "\n";
        }
        $xml .= '    </Encadenamiento>' . "\n";

        $xml .= '    <SistemaInformatico>' . "\n";
        $xml .= '      <NombreSistema>' . htmlspecialchars($this->sistema_informatico) . '</NombreSistema>' . "\n";
        $xml .= '      <Version>' . htmlspecialchars($this->version_sistema) . '</Version>' . "\n";
        $xml .= '      <NombreDesarrollador>Meng Fei Dai</NombreDesarrollador>' . "\n";
        $xml .= '      <NIF>00000000T</NIF>' . "\n"; // Developer NIF placeholder
        $xml .= '    </SistemaInformatico>' . "\n";

        $xml .= '    <FechaHoraHuella>' . $this->fecha_registro->format('Y-m-d\TH:i:s') . '</FechaHoraHuella>' . "\n";
        $xml .= '    <Huella>' . $this->hash_registro . '</Huella>' . "\n";

        $xml .= '  </RegistroAlta>' . "\n";
        $xml .= '</siiLR:RegistroFactura>' . "\n";
        $xml .= '</soapenv:Body>' . "\n";
        $xml .= '</soapenv:Envelope>';

        return $xml;
    }
}
