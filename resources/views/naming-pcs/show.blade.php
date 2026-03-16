@extends('layouts.app')
@section('title', $namingPc->nombre_equipo . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">{{ $namingPc->nombre_equipo }}</h1>
    <div class="vx-page-actions">
        @can('editar naming-pcs')<a href="{{ route('naming-pcs.edit', $namingPc) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>@endcan
        <a href="{{ route('naming-pcs.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>
<div style="max-width:700px;">
    <div class="vx-card">
        <div class="vx-card-header"><h3><i class="bi bi-pc-display" style="color:var(--vx-primary);margin-right:8px;"></i>{{ $namingPc->nombre_equipo }}</h3></div>
        <div class="vx-card-body">
            <div class="vx-info-row"><div class="vx-info-label">Tipo</div><div class="vx-info-value"><span class="vx-badge vx-badge-info">{{ $namingPc->tipo }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Estado</div><div class="vx-info-value">@if($namingPc->activo)<span class="vx-badge vx-badge-success">Activo</span>@else<span class="vx-badge vx-badge-gray">Inactivo</span>@endif</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Empresa</div><div class="vx-info-value">{{ $namingPc->empresa->nombre ?? '—' }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Centro</div><div class="vx-info-value">{{ $namingPc->centro->nombre ?? '—' }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Ubicación</div><div class="vx-info-value">{{ $namingPc->ubicacion ?? '—' }}</div></div>
            
            <div class="vx-info-row"><div class="vx-info-label">Dirección IP</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);">{{ $namingPc->direccion_ip ?? '—' }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Dirección MAC</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);">{{ $namingPc->direccion_mac ?? '—' }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Sistema Operativo</div><div class="vx-info-value">{{ $namingPc->sistema_operativo ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Versión SO</div><div class="vx-info-value">{{ $namingPc->version_so ?? '—' }}</div></div>
            @if($namingPc->observaciones)
            <div class="vx-info-row"><div class="vx-info-label">Observaciones</div><div class="vx-info-value">{{ $namingPc->observaciones }}</div></div>
            @endif
            <div class="vx-info-row"><div class="vx-info-label">Creado</div><div class="vx-info-value">{{ $namingPc->created_at->format('d/m/Y H:i') }}</div></div>
        </div>
    </div>
</div>
@endsection
