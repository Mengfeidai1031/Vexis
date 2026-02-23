@extends('layouts.app')
@section('title', $stock->nombre_pieza . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">{{ $stock->nombre_pieza }}</h1>
    <div class="vx-page-actions">
        @can('editar stocks')<a href="{{ route('stocks.edit', $stock) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>@endcan
        <a href="{{ route('stocks.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>
<div style="max-width:700px;">
    <div class="vx-card"><div class="vx-card-body">
        <div class="vx-info-row"><div class="vx-info-label">Referencia</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);">{{ $stock->referencia }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Pieza</div><div class="vx-info-value" style="font-weight:600;">{{ $stock->nombre_pieza }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Descripción</div><div class="vx-info-value">{{ $stock->descripcion ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Marca pieza</div><div class="vx-info-value">{{ $stock->marca_pieza ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Cantidad</div><div class="vx-info-value">@if($stock->isBajoStock())<span class="vx-badge vx-badge-danger">{{ $stock->cantidad }} ⚠️ Bajo stock</span>@else<span class="vx-badge vx-badge-success">{{ $stock->cantidad }}</span>@endif</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Stock mínimo</div><div class="vx-info-value">{{ $stock->stock_minimo }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Precio unitario</div><div class="vx-info-value" style="font-weight:700;">{{ number_format($stock->precio_unitario, 2) }} €</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Ubicación almacén</div><div class="vx-info-value">{{ $stock->ubicacion_almacen ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Almacén</div><div class="vx-info-value">{{ $stock->almacen->nombre ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Empresa</div><div class="vx-info-value">{{ $stock->empresa->nombre ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Centro</div><div class="vx-info-value">{{ $stock->centro->nombre ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Estado</div><div class="vx-info-value">@if($stock->activo)<span class="vx-badge vx-badge-success">Activo</span>@else<span class="vx-badge vx-badge-gray">Inactivo</span>@endif</div></div>
    </div></div>
</div>
@endsection
