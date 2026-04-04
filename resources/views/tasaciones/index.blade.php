@extends('layouts.app')
@section('title', 'Tasaciones - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Tasaciones</h1><div class="vx-page-actions"><a href="{{ route('tasaciones.export') }}" class="vx-btn vx-btn-success"><i class="bi bi-file-earmark-excel"></i> Excel</a><a href="{{ route('tasaciones.exportPdf') }}" class="vx-btn vx-btn-danger"><i class="bi bi-file-earmark-pdf"></i> PDF</a>@can('crear tasaciones')<a href="{{ route('tasaciones.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nueva Tasación</a>@endcan</div></div>
<x-filtros-avanzados :action="route('tasaciones.index')">
    <div class="vx-filtro" data-filtro="estado"><label class="vx-filtro-label">Estado</label><select name="estado" class="vx-select"><option value="">Todos</option>@foreach(\App\Models\Tasacion::$estados as $k => $v)<option value="{{ $k }}" {{ request('estado') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="estado_vehiculo"><label class="vx-filtro-label">Estado Vehículo</label><select name="estado_vehiculo" class="vx-select"><option value="">Todos</option>@foreach(\App\Models\Tasacion::$estadosVehiculo as $k => $v)<option value="{{ $k }}" {{ request('estado_vehiculo') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="combustible"><label class="vx-filtro-label">Combustible</label><select name="combustible" class="vx-select"><option value="">Todos</option>@foreach(\App\Models\Tasacion::$combustibles as $c)<option value="{{ $c }}" {{ request('combustible') == $c ? 'selected' : '' }}>{{ $c }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="marca"><label class="vx-filtro-label">Marca</label><select name="marca" class="vx-select"><option value="">Todas</option>@foreach($marcas_tasacion as $m)<option value="{{ $m }}" {{ request('marca') == $m ? 'selected' : '' }}>{{ $m }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="cliente"><label class="vx-filtro-label">Cliente</label><select name="cliente_id" class="vx-select"><option value="">Todos</option>@foreach($clientes as $c)<option value="{{ $c->id }}" {{ request('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre_completo }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="empresa"><label class="vx-filtro-label">Empresa</label><select name="empresa_id" class="vx-select"><option value="">Todas</option>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ request('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="desde"><label class="vx-filtro-label">Fecha desde</label><input type="date" name="fecha_desde" class="vx-input" value="{{ request('fecha_desde') }}"></div>
    <div class="vx-filtro" data-filtro="hasta"><label class="vx-filtro-label">Fecha hasta</label><input type="date" name="fecha_hasta" class="vx-input" value="{{ request('fecha_hasta') }}"></div>
</x-filtros-avanzados>
<div class="vx-card"><div class="vx-card-body" style="padding:0;">
    @if($tasaciones->count() > 0)
    <div class="vx-table-wrapper"><table class="vx-table">
        <thead><tr><x-columna-ordenable campo="codigo_tasacion" label="Código" /><x-columna-ordenable campo="vehiculo_marca" label="Vehículo" /><x-columna-ordenable campo="vehiculo_anio" label="Año" /><x-columna-ordenable campo="kilometraje" label="Km" /><x-columna-ordenable campo="matricula" label="Matrícula" /><x-columna-ordenable campo="estado_vehiculo" label="Estado Veh." /><x-columna-ordenable campo="valor_estimado" label="Valor Est." /><x-columna-ordenable campo="estado" label="Estado" /><x-columna-ordenable campo="fecha_tasacion" label="Fecha" /><th>Acciones</th></tr></thead>
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
