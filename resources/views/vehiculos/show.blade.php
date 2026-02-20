@extends('layouts.app')
@section('title', $vehiculo->descripcion_completa . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Detalle del Vehículo</h1>
    <div class="vx-page-actions">
        @can('update', $vehiculo)
            <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>
        @endcan
        <a href="{{ route('vehiculos.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>
<div style="max-width: 750px;">
    <div class="vx-card">
        <div class="vx-card-header">
            <h3><i class="bi bi-truck" style="color: var(--vx-primary); margin-right: 8px;"></i>{{ $vehiculo->descripcion_completa }}</h3>
        </div>
        <div class="vx-card-body">
            <div class="vx-info-row"><div class="vx-info-label">ID</div><div class="vx-info-value">{{ $vehiculo->id }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Chasis (VIN)</div><div class="vx-info-value"><span class="vx-badge vx-badge-gray" style="font-family: var(--vx-font-mono); letter-spacing: 0.5px;">{{ $vehiculo->chasis }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Modelo</div><div class="vx-info-value" style="font-weight: 600;">{{ $vehiculo->modelo }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Versión</div><div class="vx-info-value">{{ $vehiculo->version }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Color Externo</div><div class="vx-info-value">{{ $vehiculo->color_externo }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Color Interno</div><div class="vx-info-value">{{ $vehiculo->color_interno }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Empresa</div><div class="vx-info-value">{{ $vehiculo->empresa->nombre }} <span class="vx-badge vx-badge-gray">{{ $vehiculo->empresa->abreviatura }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Creado</div><div class="vx-info-value">{{ $vehiculo->created_at->format('d/m/Y H:i') }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Actualizado</div><div class="vx-info-value">{{ $vehiculo->updated_at->format('d/m/Y H:i') }}</div></div>
        </div>
    </div>
</div>
@endsection
