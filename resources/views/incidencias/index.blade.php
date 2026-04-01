@extends('layouts.app')
@section('title', 'Incidencias - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title"><i class="bi bi-exclamation-triangle" style="margin-right:6px;"></i> Incidencias</h1>
    <div class="vx-page-actions">
        @can('crear incidencias')<a href="{{ route('incidencias.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nueva Incidencia</a>@endcan
    </div>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:20px;">
    <div class="vx-card"><div class="vx-card-body" style="text-align:center;padding:16px;">
        <div style="font-size:28px;font-weight:800;color:var(--vx-primary);font-family:var(--vx-font-mono);">{{ $stats['total'] }}</div>
        <div style="font-size:11px;color:var(--vx-text-muted);">Total</div>
    </div></div>
    <div class="vx-card"><div class="vx-card-body" style="text-align:center;padding:16px;">
        <div style="font-size:28px;font-weight:800;color:#e65100;font-family:var(--vx-font-mono);">{{ $stats['abiertas'] }}</div>
        <div style="font-size:11px;color:var(--vx-text-muted);">Abiertas</div>
    </div></div>
    <div class="vx-card"><div class="vx-card-body" style="text-align:center;padding:16px;">
        <div style="font-size:28px;font-weight:800;color:#1565c0;font-family:var(--vx-font-mono);">{{ $stats['en_progreso'] }}</div>
        <div style="font-size:11px;color:var(--vx-text-muted);">En Progreso</div>
    </div></div>
    <div class="vx-card"><div class="vx-card-body" style="text-align:center;padding:16px;">
        <div style="font-size:28px;font-weight:800;color:var(--vx-success);font-family:var(--vx-font-mono);">{{ $stats['resueltas'] }}</div>
        <div style="font-size:11px;color:var(--vx-text-muted);">Resueltas/Cerradas</div>
    </div></div>
    <div class="vx-card"><div class="vx-card-body" style="text-align:center;padding:16px;">
        <div style="font-size:28px;font-weight:800;color:var(--vx-danger);font-family:var(--vx-font-mono);">{{ $stats['criticas'] }}</div>
        <div style="font-size:11px;color:var(--vx-text-muted);">Críticas activas</div>
    </div></div>
</div>

<form action="{{ route('incidencias.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por código o título..." value="{{ request('search') }}" style="flex:1;">
    <select name="estado" class="vx-select" style="width:auto;"><option value="">Todos los estados</option>@foreach(\App\Models\Incidencia::$estados as $k => $v)<option value="{{ $k }}" {{ request('estado') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select>
    <select name="prioridad" class="vx-select" style="width:auto;"><option value="">Todas las prioridades</option>@foreach(\App\Models\Incidencia::$prioridades as $k => $v)<option value="{{ $k }}" {{ request('prioridad') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select>
    <select name="tecnico_id" class="vx-select" style="width:auto;"><option value="">Todos los técnicos</option>@foreach($tecnicos as $t)<option value="{{ $t->id }}" {{ request('tecnico_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>@endforeach</select>
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i></button>
    @if(request()->anyFilled(['search','estado','prioridad','tecnico_id']))<a href="{{ route('incidencias.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>@endif
</form>

<div class="vx-card"><div class="vx-card-body" style="padding:0;">
    @if($incidencias->count() > 0)
    <div class="vx-table-wrapper"><table class="vx-table">
        <thead><tr><th>Código</th><th>Título</th><th>Prioridad</th><th>Estado</th><th>Creada por</th><th>Técnico</th><th>Fecha</th><th>Acciones</th></tr></thead>
        <tbody>@foreach($incidencias as $inc)
        <tr>
            <td style="font-family:var(--vx-font-mono);font-size:11px;">{{ $inc->codigo_incidencia }}</td>
            <td style="font-weight:600;max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $inc->titulo }}</td>
            <td>@switch($inc->prioridad)
                @case('baja')<span class="vx-badge" style="background:#e8f5e9;color:#2e7d32;">Baja</span>@break
                @case('media')<span class="vx-badge vx-badge-info">Media</span>@break
                @case('alta')<span class="vx-badge vx-badge-warning">Alta</span>@break
                @case('critica')<span class="vx-badge vx-badge-danger">Crítica</span>@break
            @endswitch</td>
            <td>@switch($inc->estado)
                @case('abierta')<span class="vx-badge" style="background:#fff3e0;color:#e65100;">Abierta</span>@break
                @case('en_progreso')<span class="vx-badge vx-badge-info">En Progreso</span>@break
                @case('resuelta')<span class="vx-badge vx-badge-success">Resuelta</span>@break
                @case('cerrada')<span class="vx-badge" style="background:#eee;color:#666;">Cerrada</span>@break
            @endswitch</td>
            <td style="font-size:12px;">{{ $inc->usuario?->name ?? '—' }}</td>
            <td style="font-size:12px;">{{ $inc->tecnico?->name ?? '<span style="color:var(--vx-text-muted);">Sin asignar</span>' }}</td>
            <td style="font-size:12px;">{{ $inc->fecha_apertura->format('d/m/Y H:i') }}</td>
            <td><a href="{{ route('incidencias.show', $inc) }}" class="vx-btn vx-btn-primary" style="padding:4px 10px;font-size:11px;"><i class="bi bi-eye"></i></a></td>
        </tr>@endforeach</tbody>
    </table></div>
    <div style="padding:16px 20px;">{{ $incidencias->links('vendor.pagination.vexis') }}</div>
    @else<div class="vx-empty"><i class="bi bi-exclamation-triangle"></i><p>No se encontraron incidencias.</p></div>@endif
</div></div>
@endsection
