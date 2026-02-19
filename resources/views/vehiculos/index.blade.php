@extends('layouts.app')

@section('title', 'Gestión de Vehículos')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Gestión de Vehículos</h2>
            <div class="d-flex gap-2">
                @can('ver vehículos')
                    <a href="{{ route('vehiculos.export') }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel"></i> Exportar a Excel
                    </a>
                    <a href="{{ route('vehiculos.exportPdf') }}" class="btn btn-danger">
                        <i class="bi bi-file-earmark-pdf"></i> Exportar a PDF
                    </a>
                @endcan
                @can('crear vehículos')
                    <a href="{{ route('vehiculos.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Nuevo Vehículo
                    </a>
                @endcan
            </div>
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
                <form action="{{ route('vehiculos.index') }}" method="GET">
                    <div class="input-group">
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Buscar por chasis, modelo, versión, colores o empresa..."
                            value="{{ request('search') }}"
                        >
                        <button class="btn btn-primary" type="submit">Buscar</button>
                        @if(request('search'))
                            <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">Limpiar</a>
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
                @if($vehiculos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Chasis</th>
                                    <th>Modelo</th>
                                    <th>Versión</th>
                                    <th>Color Externo</th>
                                    <th>Color Interno</th>
                                    <th>Empresa</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehiculos as $vehiculo)
                                    <tr>
                                        <td>{{ $vehiculo->id }}</td>
                                        <td><span class="badge bg-dark">{{ $vehiculo->chasis }}</span></td>
                                        <td><strong>{{ $vehiculo->modelo }}</strong></td>
                                        <td>{{ $vehiculo->version }}</td>
                                        <td>{{ $vehiculo->color_externo }}</td>
                                        <td>{{ $vehiculo->color_interno }}</td>
                                        <td>{{ $vehiculo->empresa->nombre }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('view', $vehiculo)
                                                    <a href="{{ route('vehiculos.show', $vehiculo) }}" 
                                                       class="btn btn-sm btn-info">
                                                        Ver
                                                    </a>
                                                @endcan
                                                
                                                @can('update', $vehiculo)
                                                    <a href="{{ route('vehiculos.edit', $vehiculo) }}" 
                                                       class="btn btn-sm btn-warning">
                                                        Editar
                                                    </a>
                                                @endcan
                                                
                                                @can('delete', $vehiculo)
                                                    <form action="{{ route('vehiculos.destroy', $vehiculo) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('¿Está seguro de eliminar este vehículo?');">
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
                        {{ $vehiculos->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="alert alert-info">
                        No se encontraron vehículos.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection