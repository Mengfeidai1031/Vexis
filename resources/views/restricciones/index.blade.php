@extends('layouts.app')

@section('title', 'Gestión de Restricciones')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Gestión de Restricciones</h2>
            @can('crear restricciones')
                <a href="{{ route('restricciones.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nueva Restricción
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
                <form action="{{ route('restricciones.index') }}" method="GET">
                    <div class="input-group">
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Buscar por usuario o entidad restringida..."
                            value="{{ request('search') }}"
                        >
                        <button class="btn btn-primary" type="submit">Buscar</button>
                        @if(request('search'))
                            <a href="{{ route('restricciones.index') }}" class="btn btn-secondary">Limpiar</a>
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
                @if($restricciones->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Tipo</th>
                                    <th>Entidad Restringida</th>
                                    <th>Fecha Creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($restricciones as $restriccion)
                                    <tr>
                                        <td>{{ $restriccion->id }}</td>
                                        <td>
                                            <strong>{{ $restriccion->user->nombre_completo }}</strong><br>
                                            <small class="text-muted">{{ $restriccion->user->email }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $typeName = match($restriccion->restrictable_type) {
                                                    'App\Models\Empresa' => 'Empresa',
                                                    'App\Models\Cliente' => 'Cliente',
                                                    'App\Models\Vehiculo' => 'Vehículo',
                                                    'App\Models\Centro' => 'Centro',
                                                    'App\Models\Departamento' => 'Departamento',
                                                    default => 'Desconocido',
                                                };
                                            @endphp
                                            <span class="badge bg-info">{{ $typeName }}</span>
                                        </td>
                                        <td>
                                            @if($restriccion->restrictable)
                                                @if($restriccion->restrictable_type === 'App\Models\Empresa')
                                                    {{ $restriccion->restrictable->nombre }}
                                                @elseif($restriccion->restrictable_type === 'App\Models\Cliente')
                                                    {{ $restriccion->restrictable->nombre_completo }}
                                                @elseif($restriccion->restrictable_type === 'App\Models\Vehiculo')
                                                    {{ $restriccion->restrictable->modelo }} {{ $restriccion->restrictable->version }}
                                                @elseif($restriccion->restrictable_type === 'App\Models\Centro')
                                                    {{ $restriccion->restrictable->nombre }}
                                                @elseif($restriccion->restrictable_type === 'App\Models\Departamento')
                                                    {{ $restriccion->restrictable->nombre }}
                                                @endif
                                            @else
                                                <span class="text-muted">Entidad eliminada</span>
                                            @endif
                                        </td>
                                        <td>{{ $restriccion->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('view', $restriccion)
                                                    <a href="{{ route('restricciones.show', $restriccion) }}" 
                                                       class="btn btn-sm btn-info">
                                                        Ver
                                                    </a>
                                                @endcan
                                                
                                                @can('update', $restriccion)
                                                    <a href="{{ route('restricciones.edit', $restriccion) }}" 
                                                       class="btn btn-sm btn-warning">
                                                        Editar
                                                    </a>
                                                @endcan
                                                
                                                @can('delete', $restriccion)
                                                    <form action="{{ route('restricciones.destroy', $restriccion) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('¿Está seguro de eliminar esta restricción?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 pagination-wrapper">
                        {{ $restricciones->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="alert alert-info">
                        No se encontraron restricciones.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
