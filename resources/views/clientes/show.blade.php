@extends('layouts.app')
@section('title', $cliente->nombre_completo . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Detalle del Cliente</h1>
    <div class="vx-page-actions">
        @can('update', $cliente)
            <a href="{{ route('clientes.edit', $cliente) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>
        @endcan
        <a href="{{ route('clientes.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>
<div style="max-width: 700px;">
    <div class="vx-card">
        <div class="vx-card-header">
            <h3><i class="bi bi-person-lines-fill" style="color: var(--vx-primary); margin-right: 8px;"></i>{{ $cliente->nombre_completo }}</h3>
        </div>
        <div class="vx-card-body">
            <div class="vx-info-row"><div class="vx-info-label">ID</div><div class="vx-info-value">{{ $cliente->id }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Nombre</div><div class="vx-info-value">{{ $cliente->nombre }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Apellidos</div><div class="vx-info-value">{{ $cliente->apellidos }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">DNI</div><div class="vx-info-value"><span class="vx-badge vx-badge-gray" style="font-family: var(--vx-font-mono);">{{ $cliente->dni }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Empresa</div><div class="vx-info-value">{{ $cliente->empresa->nombre }} <span class="vx-badge vx-badge-gray">{{ $cliente->empresa->abreviatura }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Domicilio</div><div class="vx-info-value">{{ $cliente->domicilio }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Código Postal</div><div class="vx-info-value">{{ $cliente->codigo_postal }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Creado</div><div class="vx-info-value">{{ $cliente->created_at->format('d/m/Y H:i') }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Actualizado</div><div class="vx-info-value">{{ $cliente->updated_at->format('d/m/Y H:i') }}</div></div>
        </div>
    </div>
</div>
@endsection
