@extends('layouts.app')
@section('title', $almacen->nombre . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">{{ $almacen->nombre }}</h1>
    <div class="vx-page-actions">
        @can('editar almacenes')<a href="{{ route('almacenes.edit', $almacen) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>@endcan
        <a href="{{ route('almacenes.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>
<div style="max-width:700px;">
    <div class="vx-card">
        <div class="vx-card-header"><h3><i class="bi bi-boxes" style="color:var(--vx-primary);margin-right:8px;"></i>{{ $almacen->nombre }} <span class="vx-badge vx-badge-primary" style="font-family:var(--vx-font-mono);">{{ $almacen->codigo }}</span></h3></div>
        <div class="vx-card-body">
            <div class="vx-info-row"><div class="vx-info-label">Código</div><div class="vx-info-value"><span class="vx-badge vx-badge-primary" style="font-family:var(--vx-font-mono);">{{ $almacen->codigo }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Estado</div><div class="vx-info-value">@if($almacen->activo)<span class="vx-badge vx-badge-success">Activo</span>@else<span class="vx-badge vx-badge-gray">Inactivo</span>@endif</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Domicilio</div><div class="vx-info-value">{{ $almacen->domicilio }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Localidad</div><div class="vx-info-value">{{ $almacen->localidad ?? '—' }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">CP</div><div class="vx-info-value">{{ $almacen->codigo_postal ?? '—' }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Isla</div><div class="vx-info-value"><span class="vx-badge vx-badge-info">{{ $almacen->isla ?? '—' }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Teléfono</div><div class="vx-info-value">{{ $almacen->telefono ?? '—' }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Empresa</div><div class="vx-info-value">{{ $almacen->empresa->nombre ?? '—' }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Centro</div><div class="vx-info-value">{{ $almacen->centro->nombre ?? '—' }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Stock</div><div class="vx-info-value"><span class="vx-badge vx-badge-warning">{{ $almacen->stocks_count }} registros</span></div></div>
            @if($almacen->observaciones)
            <div class="vx-info-row"><div class="vx-info-label">Observaciones</div><div class="vx-info-value">{{ $almacen->observaciones }}</div></div>
            @endif
            <div class="vx-info-row"><div class="vx-info-label">Creado</div><div class="vx-info-value">{{ $almacen->created_at->format('d/m/Y H:i') }}</div></div>
        </div>
    </div>
</div>
@endsection
