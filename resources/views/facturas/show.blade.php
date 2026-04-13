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
<div style="max-width:800px;">
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
        <div class="vx-card-header"><h4><i class="bi bi-calculator" style="margin-right:6px;"></i> Desglose Económico</h4></div>
        <div class="vx-card-body">
            <table style="width:100%;border-collapse:collapse;font-size:13px;max-width:600px;">
                @if($factura->venta)
                <tr style="border-bottom:1px solid var(--vx-border);">
                    <td style="padding:8px 0;font-weight:600;">Precio venta ({{ $factura->venta->vehiculo?->modelo ?? '—' }})</td>
                    <td style="padding:8px 0;text-align:right;font-family:var(--vx-font-mono);">{{ number_format($factura->venta->precio_venta, 2, ',', '.') }} €</td>
                </tr>
                @if($factura->venta->descuento > 0)
                <tr style="border-bottom:1px solid var(--vx-border);">
                    <td style="padding:8px 0;color:var(--vx-danger);">Descuento general</td>
                    <td style="padding:8px 0;text-align:right;font-family:var(--vx-font-mono);color:var(--vx-danger);">-{{ number_format($factura->venta->descuento, 2, ',', '.') }} €</td>
                </tr>
                @endif
                @foreach($factura->venta->conceptos->where('tipo', 'extra') as $extra)
                <tr style="border-bottom:1px solid var(--vx-border);">
                    <td style="padding:8px 0;color:var(--vx-success);"><i class="bi bi-plus-circle" style="margin-right:4px;"></i> {{ $extra->descripcion }}</td>
                    <td style="padding:8px 0;text-align:right;font-family:var(--vx-font-mono);color:var(--vx-success);">+{{ number_format($extra->importe, 2, ',', '.') }} €</td>
                </tr>
                @endforeach
                @foreach($factura->venta->conceptos->where('tipo', 'descuento') as $desc)
                <tr style="border-bottom:1px solid var(--vx-border);">
                    <td style="padding:8px 0;color:var(--vx-danger);"><i class="bi bi-dash-circle" style="margin-right:4px;"></i> {{ $desc->descripcion }}</td>
                    <td style="padding:8px 0;text-align:right;font-family:var(--vx-font-mono);color:var(--vx-danger);">-{{ number_format($desc->importe, 2, ',', '.') }} €</td>
                </tr>
                @endforeach
                @endif
                <tr style="border-bottom:2px solid var(--vx-border);background:var(--vx-bg);">
                    <td style="padding:10px 0;font-weight:700;">Subtotal</td>
                    <td style="padding:10px 0;text-align:right;font-family:var(--vx-font-mono);font-weight:700;">{{ number_format($factura->subtotal, 2, ',', '.') }} €</td>
                </tr>
                <tr style="border-bottom:1px solid var(--vx-border);">
                    <td style="padding:8px 0;">{{ $factura->venta?->impuesto_nombre ?? 'IVA' }} ({{ number_format($factura->iva_porcentaje, 0) }}%)</td>
                    <td style="padding:8px 0;text-align:right;font-family:var(--vx-font-mono);">{{ number_format($factura->iva_importe, 2, ',', '.') }} €</td>
                </tr>
                <tr style="background:var(--vx-bg);">
                    <td style="padding:12px 0;font-weight:800;font-size:15px;">TOTAL</td>
                    <td style="padding:12px 0;text-align:right;font-family:var(--vx-font-mono);font-weight:800;font-size:18px;color:var(--vx-primary);">{{ number_format($factura->total, 2, ',', '.') }} €</td>
                </tr>
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
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4>Observaciones</h4></div>
        <div class="vx-card-body"><p style="margin:0;">{{ $factura->observaciones }}</p></div>
    </div>
    @endif

    {{-- Verifactu status --}}
    @php $verifactuRegistro = \App\Models\Verifactu::where('factura_id', $factura->id)->whereNotIn('estado', ['anulado'])->orderByDesc('id')->first(); @endphp
    @if($verifactuRegistro)
    <div class="vx-card">
        <div class="vx-card-header"><h4><i class="bi bi-shield-check"></i> Registro Verifactu</h4></div>
        <div class="vx-card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:12px;">
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Código Registro</p>
                    <p style="font-family:var(--vx-font-mono);font-weight:700;margin:2px 0;"><a href="{{ route('verifactu.show', $verifactuRegistro) }}" style="color:var(--vx-primary);">{{ $verifactuRegistro->codigo_registro }}</a></p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Estado AEAT</p>
                    <p style="margin:2px 0;">@switch($verifactuRegistro->estado)
                        @case('registrado')<span class="vx-badge" style="background:#e3f2fd;color:#1565c0;">Registrado</span>@break
                        @case('enviado')<span class="vx-badge vx-badge-info">Enviado</span>@break
                        @case('aceptado')<span class="vx-badge vx-badge-success">Aceptado</span>@break
                        @case('aceptado_errores')<span class="vx-badge vx-badge-warning">Aceptado c/errores</span>@break
                        @case('rechazado')<span class="vx-badge vx-badge-danger">Rechazado</span>@break
                    @endswitch</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Huella SHA-256</p>
                    <p style="font-size:9px;font-family:var(--vx-font-mono);color:var(--vx-text-muted);margin:2px 0;word-break:break-all;">{{ substr($verifactuRegistro->hash_registro, 0, 32) }}...</p>
                </div>
                <div>
                    <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">CSV AEAT</p>
                    <p style="font-family:var(--vx-font-mono);font-weight:700;color:var(--vx-success);margin:2px 0;">{{ $verifactuRegistro->csv_aeat ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
