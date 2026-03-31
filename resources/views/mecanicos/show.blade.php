@extends('layouts.app')
@section('title', $mecanico->nombre_completo . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">{{ $mecanico->nombre_completo }}</h1>
    <div class="vx-page-actions">@can('editar mecanicos')<a href="{{ route('mecanicos.edit', $mecanico) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>@endcan <a href="{{ route('mecanicos.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;max-width:900px;">
    <div class="vx-card"><div class="vx-card-header"><h4>Información</h4></div><div class="vx-card-body">
        <div class="vx-info-row"><div class="vx-info-label">Nombre</div><div class="vx-info-value">{{ $mecanico->nombre }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Apellidos</div><div class="vx-info-value">{{ $mecanico->apellidos }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Especialidad</div><div class="vx-info-value">{{ $mecanico->especialidad ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Taller</div><div class="vx-info-value">@if($mecanico->taller)<a href="{{ route('talleres.show', $mecanico->taller) }}">{{ $mecanico->taller->nombre }}</a>@else — @endif</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Empresa</div><div class="vx-info-value">{{ $mecanico->taller->empresa->nombre ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Centro</div><div class="vx-info-value">{{ $mecanico->taller->centro->nombre ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Estado</div><div class="vx-info-value">@if($mecanico->activo)<span class="vx-badge vx-badge-success">Activo</span>@else<span class="vx-badge vx-badge-gray">Inactivo</span>@endif</div></div>
    </div></div>
    <div>
        <div class="vx-card"><div class="vx-card-header"><h4>Estadísticas</h4></div><div class="vx-card-body" style="display:grid;grid-template-columns:1fr;gap:12px;">
            <div style="text-align:center;padding:16px;background:var(--vx-bg);border-radius:8px;"><div style="font-size:28px;font-weight:800;color:var(--vx-info);">{{ $mecanico->citas_count }}</div><div style="font-size:11px;color:var(--vx-text-muted);">Citas asignadas</div></div>
        </div></div>
    </div>
</div>
@endsection
