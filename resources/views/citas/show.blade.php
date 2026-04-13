@extends('layouts.app')
@section('title', 'Cita #' . $cita->id . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Cita #{{ $cita->id }}</h1>
    <div class="vx-page-actions">@can('editar citas')<a href="{{ route('citas.edit', $cita) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>@endcan <a href="{{ route('citas.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;max-width:950px;">
    <div class="vx-card"><div class="vx-card-header"><h4>Información de la Cita</h4></div><div class="vx-card-body">
        <div class="vx-info-row"><div class="vx-info-label">Cliente</div><div class="vx-info-value" style="font-weight:600;">{{ $cita->cliente_nombre }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Vehículo</div><div class="vx-info-value">{{ $cita->vehiculo_info ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Fecha</div><div class="vx-info-value">{{ $cita->fecha->format('d/m/Y') }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Hora inicio</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);">{{ $cita->hora_inicio }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Hora fin</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);">{{ $cita->hora_fin ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Estado</div><div class="vx-info-value"><span class="vx-badge {{ $cita->estado === 'completada' ? 'vx-badge-success' : ($cita->estado === 'cancelada' ? 'vx-badge-danger' : ($cita->estado === 'en_curso' ? 'vx-badge-warning' : 'vx-badge-primary')) }}">{{ \App\Models\CitaTaller::$estados[$cita->estado] ?? $cita->estado }}</span></div></div>
        @if($cita->descripcion)
        <div class="vx-info-row"><div class="vx-info-label">Descripción</div><div class="vx-info-value">{{ $cita->descripcion }}</div></div>
        @endif
    </div></div>
    <div class="vx-card"><div class="vx-card-header"><h4>Asignación</h4></div><div class="vx-card-body">
        <div class="vx-info-row"><div class="vx-info-label">Mecánico</div><div class="vx-info-value">@if($cita->mecanico)<a href="{{ route('mecanicos.show', $cita->mecanico) }}">{{ $cita->mecanico->nombre_completo }}</a>@else — @endif</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Taller</div><div class="vx-info-value">@if($cita->taller)<a href="{{ route('talleres.show', $cita->taller) }}">{{ $cita->taller->nombre }}</a>@else — @endif</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Marca</div><div class="vx-info-value">@if($cita->marca)<span class="vx-badge" style="background:{{ $cita->marca->color }}20;color:{{ $cita->marca->color }};">{{ $cita->marca->nombre }}</span>@else — @endif</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Empresa</div><div class="vx-info-value">{{ $cita->empresa->nombre ?? '—' }}</div></div>
    </div></div>
</div>
@endsection
