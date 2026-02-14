@extends('layouts.app')

@section('title', 'Gestión de Roles')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Gestión de Roles y Permisos</h2>
            @can('crear roles')
                <a href="{{ route('roles.create') }}" class="btn btn-primary">
                    Nuevo Rol
                </a>
            @endcan
        </div>
    </div>
</div>

<!-- Mensajes -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Buscador -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('roles.index') }}" method="GET">
                    <div class="input-group">
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Buscar por nombre de rol..."
                            value="{{ request('search') }}"
                        >
                        <button class="btn btn-primary" type="submit">Buscar</button>
                        @if(request('search'))
                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">Limpiar</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tabla -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($roles->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre del Rol</th>
                                    <th>Permisos Asignados</th>
                                    <th>Usuarios con este Rol</th>
                                    <th>Fecha Creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td>{{ $role->id }}</td>
                                        <td>
                                            <strong>{{ $role->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $role->permissions_count }} permisos</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $role->users_count }} usuarios</span>
                                        </td>
                                        <td>{{ $role->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('ver roles')
                                                    <a href="{{ route('roles.show', $role->id) }}" 
                                                       class="btn btn-sm btn-info">
                                                        Ver
                                                    </a>
                                                @endcan
                                                
                                                @can('editar roles')
                                                    <a href="{{ route('roles.edit', $role->id) }}" 
                                                       class="btn btn-sm btn-warning">
                                                        Editar
                                                    </a>
                                                @endcan
                                                
                                                @can('eliminar roles')
                                                    @if($role->users_count == 0)
                                                        <form action="{{ route('roles.destroy', $role->id) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('¿Está seguro de eliminar este rol?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                Eliminar
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-sm btn-danger" disabled title="No se puede eliminar porque tiene usuarios asignados">
                                                            Eliminar
                                                        </button>
                                                    @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $roles->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        No se encontraron roles.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection