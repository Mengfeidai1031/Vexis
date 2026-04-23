@extends('layouts.app')
@section('title', 'Roles y Permisos - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Roles y Permisos</h1>
    <div class="vx-page-actions">
        @can('crear roles')
            <a href="{{ route('roles.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Rol</a>
        @endcan
    </div>
</div>

<x-filtros-avanzados :action="route('roles.index')">
    <div class="vx-filtro" data-filtro="id"><label class="vx-filtro-label">ID</label><input type="number" name="id" class="vx-input" value="{{ request('id') }}" placeholder="#"></div>
    <div class="vx-filtro" data-filtro="nombre"><label class="vx-filtro-label">Nombre</label><select name="nombre" class="vx-select"><option value="">Todos</option>@foreach($roles as $r)<option value="{{ $r->name }}" {{ request('nombre') == $r->name ? 'selected' : '' }}>{{ $r->name }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="permisos_min"><label class="vx-filtro-label">Permisos (mín.)</label><input type="number" name="permisos_min" class="vx-input" value="{{ request('permisos_min') }}" min="0" placeholder="0"></div>
    <div class="vx-filtro" data-filtro="usuarios_min"><label class="vx-filtro-label">Usuarios (mín.)</label><input type="number" name="usuarios_min" class="vx-input" value="{{ request('usuarios_min') }}" min="0" placeholder="0"></div>
    <div class="vx-filtro" data-filtro="creado_desde"><label class="vx-filtro-label">Creado (desde)</label><input type="date" name="creado_desde" class="vx-input" value="{{ request('creado_desde') }}"></div>
</x-filtros-avanzados>

<div class="vx-card">
    <div class="vx-card-body" style="padding: 0;">
        @if($roles->count() > 0)
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    <thead>
                        <tr>
                            <x-columna-ordenable campo="id" label="ID" />
                            <x-columna-ordenable campo="name" label="Nombre" />
                            <th>Permisos</th>
                            <th>Usuarios</th>
                            <x-columna-ordenable campo="created_at" label="Creado" />
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td style="color: var(--vx-text-muted);">{{ $role->id }}</td>
                                <td style="font-weight: 600;">{{ $role->name }}</td>
                                <td><span class="vx-badge vx-badge-info">{{ $role->permissions_count }} permisos</span></td>
                                <td><span class="vx-badge vx-badge-gray">{{ $role->users_count }} usuarios</span></td>
                                <td>{{ $role->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">@can('ver roles')
                                            <a href="{{ route('roles.show', $role->id) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>
                                        @endcan
                                        @can('editar roles')
                                            <a href="{{ route('roles.edit', $role->id) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>
                                        @endcan
                                        @can('eliminar roles')
                                            @if($role->users_count == 0)
                                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este rol?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="act-danger"><i class="bi bi-trash"></i> Eliminar</button>
                                                </form>
                                            @else
                                                <button class="act-danger" disabled title="Tiene usuarios asignados"><i class="bi bi-trash"></i> Eliminar</button>
                                            @endif
                                        @endcan</div></div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding: 16px 20px;">{{ $roles->links('vendor.pagination.vexis') }}</div>
        @else
            <div class="vx-empty"><i class="bi bi-shield-lock"></i><p>No se encontraron roles.</p></div>
        @endif
    </div>
</div>
@endsection
