@extends('layouts.app')
@section('title', $empresa->nombre . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">{{ $empresa->nombre }}</h1>
    <div class="vx-page-actions">
        @can('editar empresas')<a href="{{ route('empresas.edit', $empresa) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>@endcan
        <a href="{{ route('empresas.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>
<div style="max-width:700px;">
    <div class="vx-card">
        <div class="vx-card-header"><h3><i class="bi bi-building" style="color:var(--vx-primary);margin-right:8px;"></i>{{ $empresa->nombre }} <span class="vx-badge vx-badge-primary">{{ $empresa->abreviatura }}</span></h3></div>
        <div class="vx-card-body">
            <div class="vx-info-row"><div class="vx-info-label">ID</div><div class="vx-info-value">{{ $empresa->id }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Nombre</div><div class="vx-info-value" style="font-weight:600;">{{ $empresa->nombre }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Abreviatura</div><div class="vx-info-value"><span class="vx-badge vx-badge-primary">{{ $empresa->abreviatura }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">CIF</div><div class="vx-info-value"><span class="vx-badge vx-badge-gray" style="font-family:var(--vx-font-mono);">{{ $empresa->cif }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Domicilio</div><div class="vx-info-value">{{ $empresa->domicilio }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Código Postal</div><div class="vx-info-value">{{ $empresa->codigo_postal ?? '—' }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Teléfono</div><div class="vx-info-value">{{ $empresa->telefono }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Centros</div><div class="vx-info-value"><span class="vx-badge vx-badge-info">{{ $empresa->centros_count }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Usuarios</div><div class="vx-info-value"><span class="vx-badge vx-badge-info">{{ $empresa->users_count }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Creado</div><div class="vx-info-value">{{ $empresa->created_at->format('d/m/Y H:i') }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Actualizado</div><div class="vx-info-value">{{ $empresa->updated_at->format('d/m/Y H:i') }}</div></div>
        </div>
    </div>
</div>
@endsection
