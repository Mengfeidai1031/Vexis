@extends('layouts.app')
@section('title', 'Mecánicos - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Mecánicos</h1><div class="vx-page-actions">@can('crear mecanicos')<a href="{{ route('mecanicos.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo</a>@endcan</div></div>
<form action="{{ route('mecanicos.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por nombre o especialidad..." value="{{ request('search') }}" style="flex:1;">
    <select name="taller_id" class="vx-select" style="width:auto;"><option value="">Todos los talleres</option>@foreach($talleres as $t)<option value="{{ $t->id }}" {{ request('taller_id') == $t->id ? 'selected' : '' }}>{{ $t->nombre }}</option>@endforeach</select>
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i></button>
    @if(request()->anyFilled(['search','taller_id']))<a href="{{ route('mecanicos.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>@endif
</form>
<div class="vx-card"><div class="vx-card-body" style="padding:0;">
    @if($mecanicos->count() > 0)
    <div class="vx-table-wrapper"><table class="vx-table">
        <thead><tr><th>Nombre</th><th>Especialidad</th><th>Taller</th><th>Estado</th><th>Acciones</th></tr></thead>
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
