@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Gestión de Usuarios</h2>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Usuario
            </a>
        </div>
    </div>
</div>

<!-- Mensajes de éxito -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Buscador -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('users.index') }}" method="GET">
                    <div class="input-group">
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Buscar por nombre, apellidos, email, teléfono, empresa, departamento o centro..."
                            value="{{ request('search') }}"
                        >
                        <button class="btn btn-primary" type="submit">Buscar</button>
                        @if(request('search'))
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Limpiar</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de usuarios -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre Completo</th>
                                    <th>Email</th>
                                    <th>Empresa</th>
                                    <th>Departamento</th>
                                    <th>Centro</th>
                                    <th>Teléfono</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->nombre_completo }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->empresa->nombre }}</td>
                                        <td>{{ $user->departamento->nombre }}</td>
                                        <td>{{ $user->centro->nombre }}</td>
                                        <td>{{ $user->telefono ?? 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('users.show', $user->id) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Ver">
                                                    Ver
                                                </a>
                                                <a href="{{ route('users.edit', $user->id) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Editar">
                                                    Editar
                                                </a>
                                                <form action="{{ route('users.destroy', $user->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('¿Está seguro de eliminar este usuario?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger" 
                                                            title="Eliminar">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        No se encontraron usuarios.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection