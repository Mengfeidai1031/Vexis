@extends('layouts.app')
@section('title', 'Talleres - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Talleres</h1>
    <div class="vx-page-actions">@can('crear talleres')<a href="{{ route('talleres.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Taller</a>@endcan</div>
</div>
<form action="{{ route('talleres.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por nombre, código o localidad..." value="{{ request('search') }}" style="flex:1;">
    <select name="isla" class="vx-select" style="width:auto;"><option value="">Todas las islas</option>@foreach(\App\Models\Taller::$islas as $i)<option value="{{ $i }}" {{ request('isla') == $i ? 'selected' : '' }}>{{ $i }}</option>@endforeach</select>
    <select name="marca_id" class="vx-select" style="width:auto;"><option value="">Todas las marcas</option>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ request('marca_id') == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select>
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i></button>
    @if(request()->anyFilled(['search','isla','marca_id']))<a href="{{ route('talleres.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>@endif
</form>
<div class="vx-card"><div class="vx-card-body" style="padding:0;">
    @if($talleres->count() > 0)
    <div class="vx-table-wrapper"><table class="vx-table">
        <thead><tr><th>Código</th><th>Nombre</th><th>Marca</th><th>Isla</th><th>Localidad</th><th>Cap.</th><th>Mec.</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>@foreach($talleres as $t)
        <tr>
            <td style="font-family:var(--vx-font-mono);font-size:11px;">{{ $t->codigo }}</td>
            <td style="font-weight:600;">{{ $t->nombre }}</td>
            <td>@if($t->marca)<span class="vx-badge" style="background:{{ $t->marca->color }}20;color:{{ $t->marca->color }};">{{ $t->marca->nombre }}</span>@else — @endif</td>
            <td style="font-size:12px;">{{ $t->isla ?? '—' }}</td>
            <td style="font-size:12px;">{{ $t->localidad ?? '—' }}</td>
            <td style="text-align:center;">{{ $t->capacidad_diaria }}</td>
            <td style="text-align:center;"><span class="vx-badge vx-badge-info">{{ $t->mecanicos_count }}</span></td>
            <td>@if($t->activo)<span class="vx-badge vx-badge-success">Activo</span>@else<span class="vx-badge vx-badge-gray">Inactivo</span>@endif</td>
            <td><div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                <a href="{{ route('talleres.show', $t) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>
                @can('editar talleres')<a href="{{ route('talleres.edit', $t) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>@endcan
                @can('eliminar talleres')<form action="{{ route('talleres.destroy', $t) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">@csrf @method('DELETE')<button type="submit" class="act-danger"><i class="bi bi-trash"></i> Eliminar</button></form>@endcan
            </div></div></td>
        </tr>@endforeach</tbody>
    </table></div>
    <div style="padding:16px 20px;">{{ $talleres->links('vendor.pagination.vexis') }}</div>
    @else<div class="vx-empty"><i class="bi bi-tools"></i><p>No se encontraron talleres.</p></div>@endif
</div></div>
@endsection
