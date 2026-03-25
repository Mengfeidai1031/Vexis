@extends('layouts.app')
@section('title', 'Tasaciones - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Tasaciones</h1><div class="vx-page-actions">@can('crear tasaciones')<a href="{{ route('tasaciones.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nueva Tasación</a>@endcan</div></div>
<form action="{{ route('tasaciones.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por código, marca, modelo o matrícula..." value="{{ request('search') }}" style="flex:1;">
    <select name="estado" class="vx-select" style="width:auto;"><option value="">Todos</option>@foreach(\App\Models\Tasacion::$estados as $k => $v)<option value="{{ $k }}" {{ request('estado') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select>
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i></button>
    @if(request()->anyFilled(['search','estado']))<a href="{{ route('tasaciones.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>@endif
</form>
<div class="vx-card"><div class="vx-card-body" style="padding:0;">
    @if($tasaciones->count() > 0)
    <div class="vx-table-wrapper"><table class="vx-table">
        <thead><tr><th>Código</th><th>Vehículo</th><th>Año</th><th>Km</th><th>Matrícula</th><th>Estado Veh.</th><th>Valor Est.</th><th>Estado</th><th>Fecha</th><th>Acciones</th></tr></thead>
        <tbody>@foreach($tasaciones as $t)
        <tr>
            <td style="font-family:var(--vx-font-mono);font-size:11px;">{{ $t->codigo_tasacion }}</td>
            <td style="font-weight:600;font-size:13px;">{{ $t->vehiculo_marca }} {{ $t->vehiculo_modelo }}</td>
            <td style="text-align:center;">{{ $t->vehiculo_anio }}</td>
            <td style="font-family:var(--vx-font-mono);font-size:12px;">{{ number_format($t->kilometraje) }}</td>
            <td style="font-family:var(--vx-font-mono);font-size:12px;">{{ $t->matricula ?? '—' }}</td>
            <td><span class="vx-badge vx-badge-{{ match($t->estado_vehiculo) { 'excelente' => 'success', 'bueno' => 'info', 'regular' => 'warning', default => 'danger' } }}">{{ ucfirst($t->estado_vehiculo) }}</span></td>
            <td style="font-family:var(--vx-font-mono);font-weight:700;">{{ $t->valor_estimado ? number_format($t->valor_estimado, 2).'€' : '—' }}</td>
            <td>@switch($t->estado) @case('pendiente')<span class="vx-badge vx-badge-warning">Pendiente</span>@break @case('valorada')<span class="vx-badge vx-badge-info">Valorada</span>@break @case('aceptada')<span class="vx-badge vx-badge-success">Aceptada</span>@break @case('rechazada')<span class="vx-badge vx-badge-danger">Rechazada</span>@break @endswitch</td>
            <td style="font-size:12px;">{{ $t->fecha_tasacion->format('d/m/Y') }}</td>
            <td><div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                <a href="{{ route('tasaciones.show', $t) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>
                @can('editar tasaciones')<a href="{{ route('tasaciones.edit', $t) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>@endcan
                @can('eliminar tasaciones')<form action="{{ route('tasaciones.destroy', $t) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">@csrf @method('DELETE')<button type="submit" class="act-danger"><i class="bi bi-trash"></i> Eliminar</button></form>@endcan
            </div></div></td>
        </tr>@endforeach</tbody>
    </table></div>
    <div style="padding:16px 20px;">{{ $tasaciones->links('vendor.pagination.vexis') }}</div>
    @else<div class="vx-empty"><i class="bi bi-calculator"></i><p>No se encontraron tasaciones.</p></div>@endif
</div></div>
@endsection
