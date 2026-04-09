@extends('layouts.app')
@section('title', 'Almacenes - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title"><i class="bi bi-boxes" style="color:var(--vx-primary);margin-right:6px;"></i>Almacenes</h1>
    <div class="vx-page-actions">
        @can('crear almacenes')
            <a href="{{ route('almacenes.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Almacén</a>
        @endcan
    </div>
</div>
<x-filtros-avanzados :action="route('almacenes.index')">
    <div class="vx-filtro" data-filtro="codigo"><label class="vx-filtro-label">Código</label><select name="codigo" class="vx-select"><option value="">Todos</option>@foreach($codigos_almacenes as $c)<option value="{{ $c }}" {{ request('codigo') == $c ? 'selected' : '' }}>{{ $c }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="nombre"><label class="vx-filtro-label">Nombre</label><select name="nombre" class="vx-select"><option value="">Todos</option>@foreach($nombres_almacenes as $n)<option value="{{ $n }}" {{ request('nombre') == $n ? 'selected' : '' }}>{{ $n }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="localidad"><label class="vx-filtro-label">Localidad</label><select name="localidad" class="vx-select"><option value="">Todas</option>@foreach($localidades_almacenes as $l)<option value="{{ $l }}" {{ request('localidad') == $l ? 'selected' : '' }}>{{ $l }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="isla"><label class="vx-filtro-label">Isla</label><select name="isla" class="vx-select"><option value="">Todas</option>@foreach(\App\Models\Almacen::$islas as $isla)<option value="{{ $isla }}" {{ request('isla') == $isla ? 'selected' : '' }}>{{ $isla }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="empresa"><label class="vx-filtro-label">Empresa</label><select name="empresa_id" class="vx-select"><option value="">Todas</option>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ request('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="centro"><label class="vx-filtro-label">Centro</label><select name="centro_id" class="vx-select"><option value="">Todos</option>@foreach($centros as $c)<option value="{{ $c->id }}" {{ request('centro_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="activo"><label class="vx-filtro-label">Estado</label><select name="activo" class="vx-select"><option value="">Todos</option><option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activo</option><option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivo</option></select></div>
</x-filtros-avanzados>
<div class="vx-card">
    <div class="vx-card-body" style="padding:0;">
        @if($almacenes->count() > 0)
        <div class="vx-table-wrapper">
            <table class="vx-table">
                <thead><tr><x-columna-ordenable campo="codigo" label="Código" /><x-columna-ordenable campo="nombre" label="Nombre" /><x-columna-ordenable campo="localidad" label="Localidad" /><x-columna-ordenable campo="isla" label="Isla" /><x-columna-ordenable campo="empresa_id" label="Empresa" /><x-columna-ordenable campo="centro_id" label="Centro" /><x-columna-ordenable campo="activo" label="Estado" /><th>Acciones</th></tr></thead>
                <tbody>
                    @foreach($almacenes as $almacen)
                    <tr>
                        <td><span class="vx-badge vx-badge-primary" style="font-family:var(--vx-font-mono);">{{ $almacen->codigo }}</span></td>
                        <td style="font-weight:600;">{{ $almacen->nombre }}</td>
                        <td style="font-size:12px;">{{ $almacen->localidad ?? '—' }}</td>
                        <td><span class="vx-badge vx-badge-info">{{ $almacen->isla ?? '—' }}</span></td>
                        <td style="font-size:12px;">{{ $almacen->empresa->abreviatura ?? '—' }}</td>
                        <td style="font-size:12px;">{{ $almacen->centro->nombre ?? '—' }}</td>
                        <td>@if($almacen->activo)<span class="vx-badge vx-badge-success">Activo</span>@else<span class="vx-badge vx-badge-gray">Inactivo</span>@endif</td>
                        <td>
                            <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                                @can('ver almacenes')<a href="{{ route('almacenes.show', $almacen) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>@endcan
                                @can('editar almacenes')<a href="{{ route('almacenes.edit', $almacen) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>@endcan
                                @can('eliminar almacenes')
                                <form action="{{ route('almacenes.destroy', $almacen) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="act-danger"><i class="bi bi-trash"></i> Eliminar</button>
                                </form>
                                @endcan
                            </div></div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:16px 20px;">{{ $almacenes->links('vendor.pagination.vexis') }}</div>
        @else
        <div class="vx-empty"><i class="bi bi-boxes"></i><p>No se encontraron almacenes.</p></div>
        @endif
    </div>
</div>
@endsection
