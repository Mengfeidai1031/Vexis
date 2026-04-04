@extends('layouts.app')
@section('title', 'Mecánicos - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Mecánicos</h1><div class="vx-page-actions">@can('crear mecanicos')<a href="{{ route('mecanicos.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo</a>@endcan</div></div>
<x-filtros-avanzados :action="route('mecanicos.index')">
    <div class="vx-filtro" data-filtro="taller"><label class="vx-filtro-label">Taller</label><select name="taller_id" class="vx-select"><option value="">Todos</option>@foreach($talleres as $t)<option value="{{ $t->id }}" {{ request('taller_id') == $t->id ? 'selected' : '' }}>{{ $t->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="activo"><label class="vx-filtro-label">Estado</label><select name="activo" class="vx-select"><option value="">Todos</option><option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activo</option><option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivo</option></select></div>
</x-filtros-avanzados>
<div class="vx-card"><div class="vx-card-body" style="padding:0;">
    @if($mecanicos->count() > 0)
    <div class="vx-table-wrapper"><table class="vx-table">
        <thead><tr><x-columna-ordenable campo="nombre" label="Nombre" /><x-columna-ordenable campo="especialidad" label="Especialidad" /><x-columna-ordenable campo="taller_id" label="Taller" /><x-columna-ordenable campo="activo" label="Estado" /><th>Acciones</th></tr></thead>
        <tbody>@foreach($mecanicos as $m)
        <tr>
            <td style="font-weight:600;"><i class="bi bi-person-gear" style="color:var(--vx-success);margin-right:4px;"></i>{{ $m->nombre_completo }}</td>
            <td style="font-size:12px;">{{ $m->especialidad ?? '—' }}</td>
            <td style="font-size:12px;">{{ $m->taller->nombre ?? '—' }}</td>
            <td>@if($m->activo)<span class="vx-badge vx-badge-success">Activo</span>@else<span class="vx-badge vx-badge-gray">Inactivo</span>@endif</td>
            <td><div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                @can('editar mecanicos')<a href="{{ route('mecanicos.edit', $m) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>@endcan
                @can('eliminar mecanicos')<form action="{{ route('mecanicos.destroy', $m) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">@csrf @method('DELETE')<button type="submit" class="act-danger"><i class="bi bi-trash"></i> Eliminar</button></form>@endcan
            </div></div></td>
        </tr>@endforeach</tbody>
    </table></div>
    <div style="padding:16px 20px;">{{ $mecanicos->links('vendor.pagination.vexis') }}</div>
    @else<div class="vx-empty"><i class="bi bi-person-gear"></i><p>No se encontraron mecánicos.</p></div>@endif
</div></div>
@endsection
