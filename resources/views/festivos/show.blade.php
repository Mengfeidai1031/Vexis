@extends('layouts.app')
@section('title', $festivo->nombre . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Detalle del Festivo</h1>
    <div class="vx-page-actions">
        @can('editar festivos')
            <a href="{{ route('festivos.edit', $festivo) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>
        @endcan
        <a href="{{ route('festivos.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>

<div style="max-width: 800px;">
    <div class="vx-card">
        <div class="vx-card-header">
            <h3><i class="bi bi-calendar-event" style="color: var(--vx-primary); margin-right: 8px;"></i>{{ $festivo->nombre }}</h3>
        </div>
        <div class="vx-card-body">
            <div class="vx-info-row"><div class="vx-info-label">ID</div><div class="vx-info-value">{{ $festivo->id }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Nombre</div><div class="vx-info-value" style="font-weight:600;">{{ $festivo->nombre }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Fecha</div><div class="vx-info-value"><span class="vx-badge vx-badge-info">{{ $festivo->fecha->format('d/m/Y') }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Año</div><div class="vx-info-value">{{ $festivo->anio }}</div></div>
            <div class="vx-info-row">
                <div class="vx-info-label">Ámbito</div>
                <div class="vx-info-value">
                    @php $color = match($festivo->ambito) { 'nacional' => 'danger', 'autonomico' => 'info', default => 'success' }; @endphp
                    <span class="vx-badge vx-badge-{{ $color }}">{{ ucfirst($festivo->ambito) }}</span>
                </div>
            </div>
            @if($festivo->municipio)
            <div class="vx-info-row"><div class="vx-info-label">Municipio</div><div class="vx-info-value">{{ $festivo->municipio }}</div></div>
            @endif
            <div class="vx-info-row"><div class="vx-info-label">Creado</div><div class="vx-info-value">{{ $festivo->created_at->format('d/m/Y H:i') }}</div></div>
        </div>
    </div>
</div>
@endsection
