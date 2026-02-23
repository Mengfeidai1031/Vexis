@extends('layouts.app')
@section('title', $tasacion->codigo_tasacion . ' - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">{{ $tasacion->codigo_tasacion }}</h1><div class="vx-page-actions">@can('editar tasaciones')<a href="{{ route('tasaciones.edit', $tasacion) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>@endcan <a href="{{ route('tasaciones.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div></div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;max-width:900px;">
    <div class="vx-card"><div class="vx-card-header"><h4>Vehículo Tasado</h4></div><div class="vx-card-body">
        <div class="vx-info-row"><div class="vx-info-label">Marca / Modelo</div><div class="vx-info-value" style="font-weight:700;">{{ $tasacion->vehiculo_marca }} {{ $tasacion->vehiculo_modelo }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Año</div><div class="vx-info-value">{{ $tasacion->vehiculo_anio }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Kilometraje</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);">{{ number_format($tasacion->kilometraje) }} km</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Matrícula</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);">{{ $tasacion->matricula ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Combustible</div><div class="vx-info-value">{{ $tasacion->combustible ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Estado vehículo</div><div class="vx-info-value"><span class="vx-badge vx-badge-{{ match($tasacion->estado_vehiculo) { 'excelente' => 'success', 'bueno' => 'info', 'regular' => 'warning', default => 'danger' } }}">{{ ucfirst($tasacion->estado_vehiculo) }}</span></div></div>
    </div></div>
    <div class="vx-card"><div class="vx-card-header"><h4>Valoración</h4></div><div class="vx-card-body">
        <div class="vx-info-row"><div class="vx-info-label">Estado</div><div class="vx-info-value">@switch($tasacion->estado) @case('pendiente')<span class="vx-badge vx-badge-warning">Pendiente</span>@break @case('valorada')<span class="vx-badge vx-badge-info">Valorada</span>@break @case('aceptada')<span class="vx-badge vx-badge-success">Aceptada</span>@break @case('rechazada')<span class="vx-badge vx-badge-danger">Rechazada</span>@break @endswitch</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Valor estimado</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);font-size:18px;font-weight:800;color:var(--vx-primary);">{{ $tasacion->valor_estimado ? number_format($tasacion->valor_estimado, 2).' €' : 'Pendiente' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Valor final</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);font-size:18px;font-weight:800;color:var(--vx-success);">{{ $tasacion->valor_final ? number_format($tasacion->valor_final, 2).' €' : '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Cliente</div><div class="vx-info-value">{{ $tasacion->cliente ? $tasacion->cliente->nombre . ' ' . $tasacion->cliente->apellidos : '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Tasador</div><div class="vx-info-value">{{ $tasacion->tasador->nombre_completo ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Fecha</div><div class="vx-info-value">{{ $tasacion->fecha_tasacion->format('d/m/Y') }}</div></div>
        @if($tasacion->observaciones)<div class="vx-info-row"><div class="vx-info-label">Observaciones</div><div class="vx-info-value">{{ $tasacion->observaciones }}</div></div>@endif
    </div></div>
</div>
@endsection
