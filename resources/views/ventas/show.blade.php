@extends('layouts.app')
@section('title', $venta->codigo_venta . ' - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">{{ $venta->codigo_venta }}</h1><div class="vx-page-actions">@can('crear facturas')<a href="{{ route('facturas.create', ['venta_id' => $venta->id]) }}" class="vx-btn vx-btn-success"><i class="bi bi-receipt"></i> Crear Factura</a>@endcan @can('editar ventas')<a href="{{ route('ventas.edit', $venta) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>@endcan <a href="{{ route('ventas.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div></div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;max-width:950px;">
    <div class="vx-card"><div class="vx-card-header"><h4>Datos de la Venta</h4></div><div class="vx-card-body">
        <div class="vx-info-row"><div class="vx-info-label">Código</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);font-weight:700;">{{ $venta->codigo_venta }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Estado</div><div class="vx-info-value">@switch($venta->estado) @case('reservada')<span class="vx-badge vx-badge-warning">Reservada</span>@break @case('pendiente_entrega')<span class="vx-badge vx-badge-info">Pte. Entrega</span>@break @case('entregada')<span class="vx-badge vx-badge-success">Entregada</span>@break @case('cancelada')<span class="vx-badge vx-badge-danger">Cancelada</span>@break @endswitch</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Forma de pago</div><div class="vx-info-value">{{ \App\Models\Venta::$formasPago[$venta->forma_pago] ?? $venta->forma_pago }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Fecha venta</div><div class="vx-info-value">{{ $venta->fecha_venta->format('d/m/Y') }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Fecha entrega</div><div class="vx-info-value">{{ $venta->fecha_entrega?->format('d/m/Y') ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Vendedor</div><div class="vx-info-value">{{ $venta->vendedor->nombre_completo ?? '—' }}</div></div>
    </div></div>

    <div class="vx-card"><div class="vx-card-header"><h4>Vehículo y Cliente</h4></div><div class="vx-card-body">
        <div class="vx-info-row"><div class="vx-info-label">Vehículo</div><div class="vx-info-value" style="font-weight:600;">{{ $venta->vehiculo->modelo ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Marca</div><div class="vx-info-value">@if($venta->marca)<span class="vx-badge" style="background:{{ $venta->marca->color }}20;color:{{ $venta->marca->color }};">{{ $venta->marca->nombre }}</span>@endif</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Cliente</div><div class="vx-info-value">{{ $venta->cliente ? $venta->cliente->nombre . ' ' . $venta->cliente->apellidos : '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Empresa</div><div class="vx-info-value">{{ $venta->empresa->nombre ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Centro</div><div class="vx-info-value">{{ $venta->centro->nombre ?? '—' }}</div></div>
        @if($venta->observaciones)<div class="vx-info-row"><div class="vx-info-label">Observaciones</div><div class="vx-info-value">{{ $venta->observaciones }}</div></div>@endif
    </div></div>
</div>

{{-- Desglose económico --}}
<div class="vx-card" style="max-width:950px;margin-top:16px;">
    <div class="vx-card-header"><h4><i class="bi bi-calculator" style="margin-right:6px;"></i> Desglose Económico</h4></div>
    <div class="vx-card-body">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <tr style="border-bottom:1px solid var(--vx-border);">
                <td style="padding:8px 0;font-weight:600;">Precio venta</td>
                <td style="padding:8px 0;text-align:right;font-family:var(--vx-font-mono);">{{ number_format($venta->precio_venta, 2, ',', '.') }} €</td>
            </tr>
            @if($venta->descuento > 0)
            <tr style="border-bottom:1px solid var(--vx-border);">
                <td style="padding:8px 0;color:var(--vx-danger);">Descuento general</td>
                <td style="padding:8px 0;text-align:right;font-family:var(--vx-font-mono);color:var(--vx-danger);">-{{ number_format($venta->descuento, 2, ',', '.') }} €</td>
            </tr>
            @endif
            @foreach($venta->conceptos->where('tipo', 'extra') as $extra)
            <tr style="border-bottom:1px solid var(--vx-border);">
                <td style="padding:8px 0;color:var(--vx-success);"><i class="bi bi-plus-circle" style="margin-right:4px;"></i> {{ $extra->descripcion }}</td>
                <td style="padding:8px 0;text-align:right;font-family:var(--vx-font-mono);color:var(--vx-success);">+{{ number_format($extra->importe, 2, ',', '.') }} €</td>
            </tr>
            @endforeach
            @foreach($venta->conceptos->where('tipo', 'descuento') as $desc)
            <tr style="border-bottom:1px solid var(--vx-border);">
                <td style="padding:8px 0;color:var(--vx-danger);"><i class="bi bi-dash-circle" style="margin-right:4px;"></i> {{ $desc->descripcion }}</td>
                <td style="padding:8px 0;text-align:right;font-family:var(--vx-font-mono);color:var(--vx-danger);">-{{ number_format($desc->importe, 2, ',', '.') }} €</td>
            </tr>
            @endforeach
            <tr style="border-bottom:2px solid var(--vx-border);background:var(--vx-bg);">
                <td style="padding:10px 0;font-weight:700;">Subtotal</td>
                <td style="padding:10px 0;text-align:right;font-family:var(--vx-font-mono);font-weight:700;">{{ number_format($venta->subtotal ?? $venta->precio_final, 2, ',', '.') }} €</td>
            </tr>
            <tr style="border-bottom:1px solid var(--vx-border);">
                <td style="padding:8px 0;">{{ $venta->impuesto_nombre ?? 'IGIC' }} ({{ number_format($venta->impuesto_porcentaje ?? 7, 0) }}%)</td>
                <td style="padding:8px 0;text-align:right;font-family:var(--vx-font-mono);">{{ number_format($venta->impuesto_importe ?? 0, 2, ',', '.') }} €</td>
            </tr>
            <tr style="background:var(--vx-bg);">
                <td style="padding:12px 0;font-weight:800;font-size:15px;">TOTAL</td>
                <td style="padding:12px 0;text-align:right;font-family:var(--vx-font-mono);font-weight:800;font-size:18px;color:var(--vx-success);">{{ number_format($venta->total ?? $venta->precio_final, 2, ',', '.') }} €</td>
            </tr>
        </table>
    </div>
</div>
@endsection
