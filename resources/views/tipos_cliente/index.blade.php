@extends('layouts.app')
@section('title', 'Tipos de Cliente - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title"><i class="bi bi-tags"></i> Tipos de Cliente</h1>
    <div class="vx-page-actions">
        @can('crear tipos-cliente')
        <a href="{{ route('tipos-cliente.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Tipo</a>
        @endcan
    </div>
</div>

<x-filtros-avanzados :action="route('tipos-cliente.index')">
    <div class="vx-filtro" data-filtro="id"><label class="vx-filtro-label">ID</label><input type="number" name="id" class="vx-input" value="{{ request('id') }}" placeholder="#"></div>
    <div class="vx-filtro" data-filtro="color"><label class="vx-filtro-label">Color</label><select name="color" class="vx-select"><option value="">Todos</option>@foreach($tipos_all->pluck('color')->filter()->unique() as $c)<option value="{{ $c }}" {{ request('color') == $c ? 'selected' : '' }}>{{ $c }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="nombre"><label class="vx-filtro-label">Nombre</label><select name="nombre" class="vx-select"><option value="">Todos</option>@foreach($tipos_all as $t)<option value="{{ $t->nombre }}" {{ request('nombre') == $t->nombre ? 'selected' : '' }}>{{ $t->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="descripcion"><label class="vx-filtro-label">Descripcion</label><select name="descripcion" class="vx-select"><option value="">Todas</option>@foreach($tipos_all as $t)@if($t->descripcion)<option value="{{ $t->descripcion }}" {{ request('descripcion') == $t->descripcion ? 'selected' : '' }}>{{ $t->descripcion }}</option>@endif @endforeach</select></div>
    <div class="vx-filtro" data-filtro="clientes_min"><label class="vx-filtro-label">Clientes (mín.)</label><input type="number" name="clientes_min" class="vx-input" value="{{ request('clientes_min') }}" min="0" placeholder="0"></div>
    <div class="vx-filtro" data-filtro="activo"><label class="vx-filtro-label">Estado</label><select name="activo" class="vx-select"><option value="">Todos</option><option value="1" @selected(request('activo')==='1')>Activos</option><option value="0" @selected(request('activo')==='0')>Inactivos</option></select></div>
</x-filtros-avanzados>

<div class="vx-card">
    <div class="vx-card-body" style="padding:0;">
        @if($tipos->count() > 0)
        <div class="vx-table-wrapper">
            <table class="vx-table">
                <thead>
                    <tr>
                        <x-columna-ordenable campo="id" label="ID" />
                        <th>Color</th>
                        <x-columna-ordenable campo="nombre" label="Nombre" />
                        <x-columna-ordenable campo="descripcion" label="Descripcion" />
                        <th>Clientes</th>
                        <x-columna-ordenable campo="activo" label="Estado" />
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tipos as $tipo)
                    <tr>
                        <td style="color: var(--vx-text-muted);">{{ $tipo->id }}</td>
                        <td><span style="display:inline-block;width:20px;height:20px;border-radius:6px;background:{{ $tipo->color }};border:1px solid var(--vx-border);"></span></td>
                        <td style="font-weight: 600;">{{ $tipo->nombre }}</td>
                        <td style="color:var(--vx-text-muted);">{{ $tipo->descripcion ?: '—' }}</td>
                        <td>{{ $tipo->clientes_count }}</td>
                        <td>
                            @if($tipo->activo)<span class="vx-badge vx-badge-success">Activo</span>
                            @else<span class="vx-badge vx-badge-gray">Inactivo</span>@endif
                        </td>
                        <td>
                            <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                                @can('editar tipos-cliente')
                                    <a href="{{ route('tipos-cliente.edit', $tipo) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>
                                @endcan
                                @can('eliminar tipos-cliente')
                                    <form action="{{ route('tipos-cliente.destroy', $tipo) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este tipo?');">
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
        <div style="padding: 16px 20px;">{{ $tipos->links('vendor.pagination.vexis') }}</div>
        @else
        <div class="vx-empty"><i class="bi bi-tags"></i><p>No se encontraron tipos de cliente.</p></div>
        @endif
    </div>
</div>
@endsection
