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

<form action="{{ route('roles.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por nombre de rol..." value="{{ request('search') }}">
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i> Buscar</button>
    @if(request('search'))
        <a href="{{ route('roles.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>
    @endif
</form>

<div class="vx-card">
    <div class="vx-card-body" style="padding: 0;">
        @if($roles->count() > 0)
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Permisos</th>
                            <th>Usuarios</th>
                            <th>Creado</th>
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
                                            <a href="{{ route('roles.show', $role->id) }}" class="vx-btn vx-btn-info vx-btn-sm" title="Ver"><i class="bi bi-eye"></i></a>
                                        @endcan
                                        @can('editar roles')
                                            <a href="{{ route('roles.edit', $role->id) }}" class="vx-btn vx-btn-warning vx-btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                                        @endcan
                                        @can('eliminar roles')
                                            @if($role->users_count == 0)
                                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este rol?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="vx-btn vx-btn-danger vx-btn-sm" title="Eliminar"><i class="bi bi-trash"></i></button>
                                                </form>
                                            @else
                                                <button class="vx-btn vx-btn-danger vx-btn-sm" disabled title="Tiene usuarios asignados"><i class="bi bi-trash"></i></button>
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
