@extends('layouts.app')
@section('title', $factura->codigo_factura . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Factura: {{ $factura->codigo_factura }}</h1>
    <div class="vx-page-actions">
        <a href="{{ route('facturas.generatePdf', $factura) }}" class="vx-btn vx-btn-danger"><i class="bi bi-file-earmark-pdf"></i> Generar PDF</a>
        @can('editar facturas')<a href="{{ route('facturas.edit', $factura) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>@endcan
        <a href="{{ route('facturas.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>
<div style="max-width:900px;">
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4>Datos de la Factura</h4></div>
        <div class="vx-card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Código</p>
                    <p style="font-weight:700;font-family:var(--vx-font-mono);margin:2px 0 12px;">{{ $factura->codigo_factura }}</p>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Cliente</p>
                    <p style="font-weight:600;margin:2px 0 12px;">{{ $factura->cliente ? $factura->cliente->nombre . ' ' . $factura->cliente->apellidos : '—' }}</p>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Empresa</p>
                    <p style="margin:2px 0 12px;">{{ $factura->empresa?->nombre ?? '—' }}</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Marca</p>
                    <p style="margin:2px 0 12px;">@if($factura->marca)@php $logoSlug = Str::lower($factura->marca->nombre); @endphp<span class="vx-badge" style="background:{{ $factura->marca->color }}20;color:{{ $factura->marca->color }};display:inline-flex;align-items:center;gap:4px;">@if(file_exists(storage_path("app/public/logos/{$logoSlug}.png")))<img src="{{ asset("storage/logos/{$logoSlug}.png") }}" alt="" style="height:14px;">@endif{{ $factura->marca->nombre }}</span>@else — @endif</p>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Centro</p>
                    <p style="margin:2px 0 12px;">{{ $factura->centro?->nombre ?? '—' }}</p>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Venta vinculada</p>
                    <p style="margin:2px 0 12px;">@if($factura->venta)<a href="{{ route('ventas.show', $factura->venta) }}" style="color:var(--vx-primary);">{{ $factura->venta->codigo_venta }}</a>@else — @endif</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Estado</p>
                    <p style="margin:2px 0 12px;">@switch($factura->estado) @case('emitida')<span class="vx-badge vx-badge-info">Emitida</span>@break @case('pagada')<span class="vx-badge vx-badge-success">Pagada</span>@break @case('vencida')<span class="vx-badge vx-badge-warning">Vencida</span>@break @case('anulada')<span class="vx-badge vx-badge-danger">Anulada</span>@break @endswitch</p>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Fecha Factura</p>
                    <p style="margin:2px 0 12px;">{{ $factura->fecha_factura->format('d/m/Y') }}</p>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Fecha Vencimiento</p>
                    <p style="margin:2px 0 12px;">{{ $factura->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4>Importes</h4></div>
        <div class="vx-card-body">
            <table class="vx-table" style="max-width:400px;">
                <tr><td style="font-weight:600;">Subtotal</td><td style="text-align:right;font-family:var(--vx-font-mono);">{{ number_format($factura->subtotal, 2) }} €</td></tr>
                <tr><td style="font-weight:600;">IVA ({{ number_format($factura->iva_porcentaje, 0) }}%)</td><td style="text-align:right;font-family:var(--vx-font-mono);">{{ number_format($factura->iva_importe, 2) }} €</td></tr>
                <tr style="border-top:2px solid var(--vx-border);"><td style="font-weight:800;font-size:14px;">TOTAL</td><td style="text-align:right;font-family:var(--vx-font-mono);font-weight:800;font-size:16px;color:var(--vx-primary);">{{ number_format($factura->total, 2) }} €</td></tr>
            </table>
        </div>
    </div>
    @if($factura->concepto)
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4>Concepto</h4></div>
        <div class="vx-card-body"><p style="margin:0;">{{ $factura->concepto }}</p></div>
    </div>
    @endif
    @if($factura->observaciones)
    <div class="vx-card">
        <div class="vx-card-header"><h4>Observaciones</h4></div>
        <div class="vx-card-body"><p style="margin:0;">{{ $factura->observaciones }}</p></div>
    </div>
    @endif
</div>
@endsection
