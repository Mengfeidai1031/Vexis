@extends('layouts.app')
@section('title', $verifactu->codigo_registro . ' - Verifactu - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Registro: {{ $verifactu->codigo_registro }}</h1>
    <div class="vx-page-actions">
        <a href="{{ route('verifactu.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>

<div style="max-width:900px;">
    {{-- Estado y tipo --}}
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4><i class="bi bi-shield-check"></i> Información del Registro</h4></div>
        <div class="vx-card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Código Registro</p>
                    <p style="font-weight:700;font-family:var(--vx-font-mono);margin:2px 0 12px;">{{ $verifactu->codigo_registro }}</p>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Tipo Operación</p>
                    <p style="margin:2px 0 12px;">@switch($verifactu->tipo_operacion) @case('emision')<span class="vx-badge vx-badge-info">Emisión</span>@break @case('anulacion')<span class="vx-badge vx-badge-danger">Anulación</span>@break @case('rectificacion')<span class="vx-badge vx-badge-warning">Rectificación</span>@break @endswitch</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Estado</p>
                    <p style="margin:2px 0 12px;">@switch($verifactu->estado) @case('registrado')<span class="vx-badge" style="background:#e3f2fd;color:#1565c0;">Registrado</span>@break @case('enviado')<span class="vx-badge vx-badge-info">Enviado</span>@break @case('validado')<span class="vx-badge vx-badge-success">Validado</span>@break @case('rechazado')<span class="vx-badge vx-badge-danger">Rechazado</span>@break @case('anulado')<span class="vx-badge" style="background:#eee;color:#666;">Anulado</span>@break @endswitch</p>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Fecha Registro</p>
                    <p style="margin:2px 0 12px;">{{ $verifactu->fecha_registro->format('d/m/Y H:i:s') }}</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Importe Total</p>
                    <p style="font-weight:800;font-size:18px;font-family:var(--vx-font-mono);color:var(--vx-primary);margin:2px 0 12px;">{{ number_format($verifactu->importe_total, 2) }} €</p>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Emisor</p>
                    <p style="margin:2px 0 12px;">{{ $verifactu->nombre_emisor ?? '—' }} <span style="font-size:10px;color:var(--vx-text-muted);">({{ $verifactu->nif_emisor ?? '—' }})</span></p>
                </div>
            </div>

            {{-- Change state --}}
            @can('editar verifactu')
            <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--vx-border);display:flex;gap:8px;align-items:center;">
                <span style="font-size:12px;font-weight:600;margin-right:8px;">Cambiar estado:</span>
                <form action="{{ route('verifactu.cambiarEstado', $verifactu) }}" method="POST" style="display:flex;gap:6px;">@csrf @method('PUT')
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
        <div class="vx-card-header"><h4><i class="bi bi-link-45deg"></i> Cadena de Hashes (SHA-256)</h4></div>
        <div class="vx-card-body">
            <div style="display:grid;grid-template-columns:1fr;gap:12px;">
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Hash Anterior</p>
                    <p style="font-family:var(--vx-font-mono);font-size:11px;word-break:break-all;background:var(--vx-bg);padding:8px;border-radius:6px;margin:4px 0;">
                        @if($verifactu->hash_anterior)
                        {{ $verifactu->hash_anterior }}
                        @if($anterior) <a href="{{ route('verifactu.show', $anterior) }}" style="font-size:10px;color:var(--vx-primary);margin-left:6px;"><i class="bi bi-arrow-up-left"></i> {{ $anterior->codigo_registro }}</a>@endif
                        @else <span style="color:var(--vx-text-muted);">GENESIS (primer registro)</span> @endif
                    </p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Hash Actual</p>
                    <p style="font-family:var(--vx-font-mono);font-size:11px;word-break:break-all;background:var(--vx-success-bg, #e8f5e9);padding:8px;border-radius:6px;margin:4px 0;font-weight:700;color:var(--vx-success, #2e7d32);">
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
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Cliente</p>
                    <p style="margin:2px 0 12px;">{{ $verifactu->factura->cliente ? $verifactu->factura->cliente->nombre . ' ' . $verifactu->factura->cliente->apellidos : '—' }}</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Total Factura</p>
                    <p style="margin:2px 0 12px;font-family:var(--vx-font-mono);font-weight:700;">{{ number_format($verifactu->factura->total, 2) }} €</p>
                </div>
            </div>
            @else
            <p style="color:var(--vx-text-muted);">Factura no disponible.</p>
            @endif
        </div>
    </div>

    {{-- AEAT Response --}}
    @if($verifactu->respuesta_aeat)
    <div class="vx-card">
        <div class="vx-card-header"><h4><i class="bi bi-building"></i> Respuesta AEAT</h4></div>
        <div class="vx-card-body">
            <pre style="background:var(--vx-bg);padding:12px;border-radius:6px;font-size:11px;font-family:var(--vx-font-mono);margin:0;overflow-x:auto;">{{ json_encode($verifactu->respuesta_aeat, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
    </div>
    @endif
</div>
@endsection
