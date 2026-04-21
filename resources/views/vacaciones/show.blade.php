@extends('layouts.app')
@section('title', 'Solicitud Vacaciones #' . $vacacion->id . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Solicitud de Vacaciones #{{ $vacacion->id }}</h1>
    <div class="vx-page-actions">
        <a href="{{ route('vacaciones.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>

<div style="max-width: 800px;">
    <div class="vx-card">
        <div class="vx-card-header">
            <h3><i class="bi bi-calendar-heart" style="color: var(--vx-primary); margin-right: 8px;"></i>Detalle</h3>
        </div>
        <div class="vx-card-body">
            <div class="vx-info-row"><div class="vx-info-label">Solicitante</div><div class="vx-info-value" style="font-weight:600;">{{ $vacacion->user->nombre }} {{ $vacacion->user->apellidos }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Fecha Inicio</div><div class="vx-info-value">{{ $vacacion->fecha_inicio->format('d/m/Y') }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Fecha Fin</div><div class="vx-info-value">{{ $vacacion->fecha_fin->format('d/m/Y') }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Días Solicitados</div><div class="vx-info-value"><span class="vx-badge vx-badge-info">{{ $vacacion->dias_solicitados }} días</span></div></div>
            <div class="vx-info-row">
                <div class="vx-info-label">Estado</div>
                <div class="vx-info-value">
                    @php $color = match($vacacion->estado) { 'aprobada' => 'success', 'rechazada' => 'danger', default => 'warning' }; @endphp
                    <span class="vx-badge vx-badge-{{ $color }}">{{ \App\Models\Vacacion::$estados[$vacacion->estado] ?? $vacacion->estado }}</span>
                </div>
            </div>
            @if($vacacion->motivo)
            <div class="vx-info-row"><div class="vx-info-label">Motivo</div><div class="vx-info-value">{{ $vacacion->motivo }}</div></div>
            @endif
            @if($vacacion->respuesta)
            <div class="vx-info-row"><div class="vx-info-label">Respuesta</div><div class="vx-info-value">{{ $vacacion->respuesta }}</div></div>
            @endif
            @if($vacacion->aprobador)
            <div class="vx-info-row"><div class="vx-info-label">Gestionado por</div><div class="vx-info-value">{{ $vacacion->aprobador->nombre }} {{ $vacacion->aprobador->apellidos }}</div></div>
            @endif
            <div class="vx-info-row"><div class="vx-info-label">Creado</div><div class="vx-info-value">{{ $vacacion->created_at->format('d/m/Y H:i') }}</div></div>
        </div>
    </div>
</div>
@endsection
