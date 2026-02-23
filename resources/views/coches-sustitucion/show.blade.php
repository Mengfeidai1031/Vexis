@extends('layouts.app')
@section('title', $coches_sustitucion->matricula . ' - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">{{ $coches_sustitucion->matricula }} — {{ $coches_sustitucion->modelo }}</h1>
    <div class="vx-page-actions">@can('editar coches-sustitucion')<a href="{{ route('coches-sustitucion.edit', $coches_sustitucion) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>@endcan <a href="{{ route('coches-sustitucion.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;max-width:900px;">
    <div class="vx-card"><div class="vx-card-header"><h4>Información</h4></div><div class="vx-card-body">
        <div class="vx-info-row"><div class="vx-info-label">Matrícula</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);font-weight:700;">{{ $coches_sustitucion->matricula }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Modelo</div><div class="vx-info-value">{{ $coches_sustitucion->modelo }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Marca</div><div class="vx-info-value">@if($coches_sustitucion->marca)<span class="vx-badge" style="background:{{ $coches_sustitucion->marca->color }}20;color:{{ $coches_sustitucion->marca->color }};">{{ $coches_sustitucion->marca->nombre }}</span>@endif</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Color</div><div class="vx-info-value">{{ $coches_sustitucion->color ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Año</div><div class="vx-info-value">{{ $coches_sustitucion->anio ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Taller</div><div class="vx-info-value">{{ $coches_sustitucion->taller->nombre ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Disponible</div><div class="vx-info-value">@if($coches_sustitucion->disponible)<span class="vx-badge vx-badge-success">Sí</span>@else<span class="vx-badge vx-badge-warning">No</span>@endif</div></div>
    </div></div>
    <div>
        {{-- Crear reserva --}}
        <div class="vx-card" style="margin-bottom:16px;"><div class="vx-card-header"><h4>Nueva Reserva</h4></div><div class="vx-card-body">
            <form action="{{ route('coches-sustitucion.reservar', $coches_sustitucion) }}" method="POST">@csrf
                <div class="vx-form-group"><label class="vx-label">Cliente <span class="required">*</span></label><input type="text" class="vx-input" name="cliente_nombre" required></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 12px;">
                    <div class="vx-form-group"><label class="vx-label">Desde <span class="required">*</span></label><input type="date" class="vx-input" name="fecha_inicio" required></div>
                    <div class="vx-form-group"><label class="vx-label">Hasta <span class="required">*</span></label><input type="date" class="vx-input" name="fecha_fin" required></div>
                </div>
                <div class="vx-form-group"><label class="vx-label">Observaciones</label><textarea class="vx-input" name="observaciones" rows="1"></textarea></div>
                <button type="submit" class="vx-btn vx-btn-primary vx-btn-sm" style="width:100%;"><i class="bi bi-calendar-plus"></i> Reservar</button>
            </form>
        </div></div>
        {{-- Historial reservas --}}
        @if($coches_sustitucion->reservas->count() > 0)
        <div class="vx-card"><div class="vx-card-header"><h4>Historial ({{ $coches_sustitucion->reservas->count() }})</h4></div><div class="vx-card-body" style="padding:0;">
            <table class="vx-table"><thead><tr><th>Cliente</th><th>Desde</th><th>Hasta</th><th>Estado</th></tr></thead><tbody>
            @foreach($coches_sustitucion->reservas->sortByDesc('fecha_inicio') as $r)
            <tr><td style="font-size:12px;">{{ $r->cliente_nombre }}</td><td style="font-size:12px;">{{ $r->fecha_inicio->format('d/m/Y') }}</td><td style="font-size:12px;">{{ $r->fecha_fin->format('d/m/Y') }}</td><td>
                @switch($r->estado) @case('reservado')<span class="vx-badge vx-badge-warning">Reservado</span>@break @case('entregado')<span class="vx-badge vx-badge-info">Entregado</span>@break @case('devuelto')<span class="vx-badge vx-badge-success">Devuelto</span>@break @case('cancelado')<span class="vx-badge vx-badge-gray">Cancelado</span>@break @endswitch
            </td></tr>@endforeach</tbody></table>
        </div></div>
        @endif
    </div>
</div>
@endsection
