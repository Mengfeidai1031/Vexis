@extends('layouts.app')
@section('title', $verifactu->codigo_registro . ' - Verifactu - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">@if(file_exists(storage_path('app/public/logos/verifactu.png')))<img src="{{ asset('storage/logos/verifactu.png') }}" alt="Verifactu" style="height:22px;vertical-align:middle;margin-right:6px;">@endif Registro: {{ $verifactu->codigo_registro }}</h1>
    <div class="vx-page-actions">
        <a href="{{ route('verifactu.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
        <a href="{{ route('verifactu.descargarXml', $verifactu) }}" class="vx-btn vx-btn-secondary"><i class="bi bi-filetype-xml"></i> Descargar XML</a>
        @if(in_array($verifactu->estado, ['registrado', 'rechazado']))
        <form action="{{ route('verifactu.enviarAeat', $verifactu) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Enviar este registro a AEAT (sandbox)?')">@csrf
            <button type="submit" class="vx-btn vx-btn-danger"><i class="bi bi-send"></i> Enviar a AEAT</button>
        </form>
        @endif
    </div>
</div>

<div style="max-width:960px;">
    {{-- Estado y tipo --}}
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4><i class="bi bi-shield-check"></i> Información del Registro</h4></div>
        <div class="vx-card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Código Registro</p>
                    <p style="font-weight:700;font-family:var(--vx-font-mono);margin:2px 0 12px;">{{ $verifactu->codigo_registro }}</p>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Tipo Operación</p>
                    <p style="margin:2px 0 12px;">@if($verifactu->tipo_operacion === 'alta')<span class="vx-badge vx-badge-info">Alta (Emisión)</span>@else<span class="vx-badge vx-badge-danger">Anulación</span>@endif</p>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Tipo Factura</p>
                    <p style="margin:2px 0 12px;font-size:12px;">{{ \App\Models\Verifactu::$tiposFactura[$verifactu->tipo_factura] ?? $verifactu->tipo_factura }}</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Estado AEAT</p>
                    <p style="margin:2px 0 12px;">@switch($verifactu->estado)
                        @case('registrado')<span class="vx-badge" style="background:#e3f2fd;color:#1565c0;">Registrado</span>@break
                        @case('enviado')<span class="vx-badge vx-badge-info">Enviado a AEAT</span>@break
                        @case('aceptado')<span class="vx-badge vx-badge-success">Aceptado</span>@break
                        @case('aceptado_errores')<span class="vx-badge vx-badge-warning">Aceptado con errores</span>@break
                        @case('rechazado')<span class="vx-badge vx-badge-danger">Rechazado</span>@break
                        @case('anulado')<span class="vx-badge" style="background:#eee;color:#666;">Anulado</span>@break
                    @endswitch</p>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Fecha Registro</p>
                    <p style="margin:2px 0 12px;">{{ $verifactu->fecha_registro->format('d/m/Y H:i:s') }}</p>
                    @if($verifactu->csv_aeat)
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">CSV AEAT</p>
                    <p style="margin:2px 0 12px;font-family:var(--vx-font-mono);font-weight:700;color:var(--vx-success);">{{ $verifactu->csv_aeat }}</p>
                    @endif
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Importe Total</p>
                    <p style="font-weight:800;font-size:18px;font-family:var(--vx-font-mono);color:var(--vx-primary);margin:2px 0 12px;">{{ number_format($verifactu->importe_total, 2) }} €</p>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Emisor</p>
                    <p style="margin:2px 0 4px;">{{ $verifactu->nombre_emisor ?? '—' }}</p>
                    <p style="font-size:10px;color:var(--vx-text-muted);margin:0 0 12px;">NIF: {{ $verifactu->nif_emisor ?? '—' }}</p>
                </div>
            </div>

            {{-- Fiscal details --}}
            <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--vx-border);display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:12px;">
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Base Imponible</p>
                    <p style="font-family:var(--vx-font-mono);font-weight:700;margin:2px 0;">{{ number_format($verifactu->base_imponible, 2) }} €</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Cuota Tributaria</p>
                    <p style="font-family:var(--vx-font-mono);font-weight:700;margin:2px 0;">{{ number_format($verifactu->cuota_tributaria, 2) }} €</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Tipo Impositivo</p>
                    <p style="font-family:var(--vx-font-mono);font-weight:700;margin:2px 0;">{{ number_format($verifactu->tipo_impositivo, 0) }}%</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Clave Régimen</p>
                    <p style="font-size:12px;margin:2px 0;">{{ \App\Models\Verifactu::$clavesRegimen[$verifactu->clave_regimen] ?? $verifactu->clave_regimen }}</p>
                </div>
            </div>

            {{-- Destinatario --}}
            @if($verifactu->nif_destinatario || $verifactu->nombre_destinatario)
            <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--vx-border);display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Destinatario</p>
                    <p style="margin:2px 0;">{{ $verifactu->nombre_destinatario ?? '—' }}</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">NIF Destinatario</p>
                    <p style="font-family:var(--vx-font-mono);margin:2px 0;">{{ $verifactu->nif_destinatario ?? '—' }}</p>
                </div>
            </div>
            @endif

            {{-- QR Code --}}
            @if($verifactu->url_qr)
            <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--vx-border);">
                <p style="font-size:11px;color:var(--vx-text-muted);margin:0 0 8px;">Código QR Verificación AEAT</p>
                <div style="display:flex;align-items:center;gap:16px;">
                    <img src="data:image/png;base64,{{ \App\Services\AeatVerifactuService::generateQrImage($verifactu->url_qr) }}" alt="QR Verifactu" style="width:120px;height:120px;border:1px solid var(--vx-border);border-radius:4px;">
                    <div style="font-size:10px;color:var(--vx-text-muted);word-break:break-all;">
                        <p style="margin:0 0 4px;font-weight:600;">URL de verificación:</p>
                        <a href="{{ $verifactu->url_qr }}" target="_blank" style="color:var(--vx-primary);">{{ $verifactu->url_qr }}</a>
                    </div>
                </div>
            </div>
            @endif

            {{-- Change state (admin) --}}
            @can('editar verifactu')
            <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--vx-border);display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                <span style="font-size:12px;font-weight:600;margin-right:8px;">Cambiar estado manual:</span>
                <form action="{{ route('verifactu.cambiarEstado', $verifactu) }}" method="POST" style="display:flex;gap:6px;flex-wrap:wrap;">@csrf @method('PUT')
                    @foreach(\App\Models\Verifactu::$estados as $k => $v)
                    @if($k !== $verifactu->estado)
                    <button type="submit" name="estado" value="{{ $k }}" class="vx-btn vx-btn-secondary" style="padding:4px 10px;font-size:11px;">{{ $v }}</button>
                    @endif
                    @endforeach
                </form>
            </div>
            @endcan
        </div>
    </div>

    {{-- Hash chain --}}
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4><i class="bi bi-link-45deg"></i> Cadena de Hashes (SHA-256) — RD 1007/2023 art. 12</h4></div>
        <div class="vx-card-body">
            <div style="display:grid;grid-template-columns:1fr;gap:12px;">
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Hash Anterior</p>
                    <p style="font-family:var(--vx-font-mono);font-size:11px;word-break:break-all;background:var(--vx-bg);padding:8px;border-radius:6px;margin:4px 0;">
                        @if($verifactu->hash_anterior)
                        {{ $verifactu->hash_anterior }}
                        @if($anterior) <a href="{{ route('verifactu.show', $anterior) }}" style="font-size:10px;color:var(--vx-primary);margin-left:6px;"><i class="bi bi-arrow-up-left"></i> {{ $anterior->codigo_registro }}</a>@endif
                        @else <span style="color:var(--vx-text-muted);">GENESIS (primer registro de la cadena)</span> @endif
                    </p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Hash Actual (Huella)</p>
                    <p style="font-family:var(--vx-font-mono);font-size:11px;word-break:break-all;background:#e8f5e9;padding:8px;border-radius:6px;margin:4px 0;font-weight:700;color:#2e7d32;">
                        {{ $verifactu->hash_registro }}
                    </p>
                </div>
                @if($siguiente)
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Siguiente en cadena</p>
                    <p style="font-family:var(--vx-font-mono);font-size:11px;margin:4px 0;">
                        <a href="{{ route('verifactu.show', $siguiente) }}" style="color:var(--vx-primary);"><i class="bi bi-arrow-down-right"></i> {{ $siguiente->codigo_registro }}</a> — {{ substr($siguiente->hash_registro, 0, 24) }}...
                    </p>
                </div>
                @endif
            </div>
            <div style="margin-top:12px;padding:8px 12px;background:var(--vx-bg);border-radius:6px;font-size:10px;color:var(--vx-text-muted);">
                <i class="bi bi-info-circle"></i> Formato huella: SHA-256(IDEmisorFactura&NumSerieFactura&FechaExpedicionFactura&TipoFactura&CuotaTotal&ImporteTotal&Huella&FechaHoraHuella)
            </div>
        </div>
    </div>

    {{-- Factura info --}}
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4><i class="bi bi-receipt"></i> Factura Asociada</h4></div>
        <div class="vx-card-body">
            @if($verifactu->factura)
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Código Factura</p>
                    <p style="margin:2px 0 12px;"><a href="{{ route('facturas.show', $verifactu->factura) }}" style="color:var(--vx-primary);font-weight:700;">{{ $verifactu->factura->codigo_factura }}</a></p>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Nº Serie Factura</p>
                    <p style="margin:2px 0 12px;font-family:var(--vx-font-mono);">{{ $verifactu->numero_serie_factura ?? '—' }}</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Cliente</p>
                    <p style="margin:2px 0 12px;">{{ $verifactu->factura->cliente ? $verifactu->factura->cliente->nombre . ' ' . $verifactu->factura->cliente->apellidos : '—' }}</p>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Fecha Expedición</p>
                    <p style="margin:2px 0 12px;">{{ $verifactu->fecha_expedicion ?? '—' }}</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Total Factura</p>
                    <p style="margin:2px 0 12px;font-family:var(--vx-font-mono);font-weight:700;">{{ number_format($verifactu->factura->total, 2) }} €</p>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Empresa</p>
                    <p style="margin:2px 0 12px;">{{ $verifactu->factura->empresa?->nombre ?? '—' }}</p>
                </div>
            </div>
            @else
            <p style="color:var(--vx-text-muted);">Factura no disponible.</p>
            @endif
        </div>
    </div>

    {{-- Sistema informatico --}}
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4><i class="bi bi-cpu"></i> Sistema Informático</h4></div>
        <div class="vx-card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Nombre Sistema</p>
                    <p style="font-weight:700;margin:2px 0;">{{ $verifactu->sistema_informatico ?? 'VEXIS' }}</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Versión</p>
                    <p style="font-family:var(--vx-font-mono);margin:2px 0;">{{ $verifactu->version_sistema ?? '1.0.0' }}</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Descripción Operación</p>
                    <p style="margin:2px 0;">{{ $verifactu->descripcion_operacion ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- AEAT Response --}}
    @if($verifactu->respuesta_aeat)
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4><i class="bi bi-building"></i> Respuesta AEAT</h4></div>
        <div class="vx-card-body">
            @if(isset($verifactu->respuesta_aeat['entorno']) && $verifactu->respuesta_aeat['entorno'] === 'sandbox')
            <div style="background:#fff3e0;padding:8px 12px;border-radius:6px;font-size:11px;color:#e65100;margin-bottom:12px;">
                <i class="bi bi-exclamation-triangle"></i> Respuesta del entorno <strong>SANDBOX</strong> (pruebas). No es una validación real de AEAT.
            </div>
            @endif
            <pre style="background:var(--vx-bg);padding:12px;border-radius:6px;font-size:11px;font-family:var(--vx-font-mono);margin:0;overflow-x:auto;white-space:pre-wrap;">{{ json_encode($verifactu->respuesta_aeat, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
    </div>
    @endif

    {{-- XML preview --}}
    <div class="vx-card">
        <div class="vx-card-header"><h4><i class="bi bi-filetype-xml"></i> XML AEAT (SuministroLR)</h4></div>
        <div class="vx-card-body">
            <pre style="background:#1e1e1e;color:#d4d4d4;padding:16px;border-radius:6px;font-size:10px;font-family:var(--vx-font-mono);margin:0;overflow-x:auto;white-space:pre-wrap;max-height:400px;">{{ $xml }}</pre>
        </div>
    </div>
</div>
@endsection
