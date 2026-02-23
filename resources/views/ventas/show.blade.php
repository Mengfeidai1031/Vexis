@extends('layouts.app')
@section('title', $venta->codigo_venta . ' - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">{{ $venta->codigo_venta }}</h1><div class="vx-page-actions">@can('editar ventas')<a href="{{ route('ventas.edit', $venta) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>@endcan <a href="{{ route('ventas.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div></div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;max-width:900px;">
    <div class="vx-card"><div class="vx-card-header"><h4>Datos de la Venta</h4></div><div class="vx-card-body">
        <div class="vx-info-row"><div class="vx-info-label">Código</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);font-weight:700;">{{ $venta->codigo_venta }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Estado</div><div class="vx-info-value">@switch($venta->estado) @case('reservada')<span class="vx-badge vx-badge-warning">Reservada</span>@break @case('pendiente_entrega')<span class="vx-badge vx-badge-info">Pte. Entrega</span>@break @case('entregada')<span class="vx-badge vx-badge-success">Entregada</span>@break @case('cancelada')<span class="vx-badge vx-badge-danger">Cancelada</span>@break @endswitch</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Precio venta</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);">{{ number_format($venta->precio_venta, 2) }} €</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Descuento</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);color:var(--vx-danger);">-{{ number_format($venta->descuento, 2) }} €</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Precio final</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);font-weight:800;font-size:18px;color:var(--vx-success);">{{ number_format($venta->precio_final, 2) }} €</div></div>
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
@endsection
