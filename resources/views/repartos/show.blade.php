@extends('layouts.app')
@section('title', $reparto->codigo_reparto . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Reparto: {{ $reparto->codigo_reparto }}</h1>
    <div class="vx-page-actions">
        @can('editar repartos')<a href="{{ route('repartos.edit', $reparto) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>@endcan
        <a href="{{ route('repartos.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>
<div style="max-width:700px;"><div class="vx-card"><div class="vx-card-body">
    <div class="vx-info-row"><div class="vx-info-label">Código</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);font-weight:600;">{{ $reparto->codigo_reparto }}</div></div>
    <div class="vx-info-row"><div class="vx-info-label">Pieza</div><div class="vx-info-value" style="font-weight:600;">{{ $reparto->stock->nombre_pieza ?? '—' }} <span style="color:var(--vx-text-muted);font-size:12px;">({{ $reparto->stock->referencia ?? '' }})</span></div></div>
    <div class="vx-info-row"><div class="vx-info-label">Cantidad</div><div class="vx-info-value"><span class="vx-badge vx-badge-primary">{{ $reparto->cantidad }} uds.</span></div></div>
    <div class="vx-info-row"><div class="vx-info-label">Almacén Origen</div><div class="vx-info-value">{{ $reparto->almacenOrigen->nombre ?? '—' }}</div></div>
    <div class="vx-info-row"><div class="vx-info-label">Almacén Destino</div><div class="vx-info-value">{{ $reparto->almacenDestino->nombre ?? 'Externo' }}</div></div>
    <div class="vx-info-row"><div class="vx-info-label">Estado</div><div class="vx-info-value">
        @switch($reparto->estado)
            @case('pendiente')<span class="vx-badge vx-badge-warning">Pendiente</span>@break
            @case('en_transito')<span class="vx-badge vx-badge-info">En Tránsito</span>@break
            @case('entregado')<span class="vx-badge vx-badge-success">Entregado</span>@break
            @case('cancelado')<span class="vx-badge vx-badge-danger">Cancelado</span>@break
        @endswitch
    </div></div>
    <div class="vx-info-row"><div class="vx-info-label">Empresa</div><div class="vx-info-value">{{ $reparto->empresa->nombre ?? '—' }}</div></div>
    <div class="vx-info-row"><div class="vx-info-label">Centro</div><div class="vx-info-value">{{ $reparto->centro->nombre ?? '—' }}</div></div>
    <div class="vx-info-row"><div class="vx-info-label">Fecha solicitud</div><div class="vx-info-value">{{ $reparto->fecha_solicitud->format('d/m/Y') }}</div></div>
    <div class="vx-info-row"><div class="vx-info-label">Fecha entrega</div><div class="vx-info-value">{{ $reparto->fecha_entrega?->format('d/m/Y') ?? '—' }}</div></div>
    <div class="vx-info-row"><div class="vx-info-label">Solicitado por</div><div class="vx-info-value">{{ $reparto->solicitado_por ?? '—' }}</div></div>
    @if($reparto->observaciones)<div class="vx-info-row"><div class="vx-info-label">Observaciones</div><div class="vx-info-value">{{ $reparto->observaciones }}</div></div>@endif
</div></div></div>
@endsection
