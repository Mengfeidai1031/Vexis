@extends('layouts.app')

@section('title', 'Gestión de Centros')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Gestión de Centros</h2>
            @can('crear centros')
                <a href="{{ route('centros.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nuevo Centro
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
                <form action="{{ route('centros.index') }}" method="GET">
                    <div class="input-group">
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Buscar por nombre, dirección, provincia, municipio o empresa..."
                            value="{{ request('search') }}"
                        >
                        <button class="btn btn-primary" type="submit">Buscar</button>
                        @if(request('search'))
                            <a href="{{ route('centros.index') }}" class="btn btn-secondary">Limpiar</a>
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
                @if($centros->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Empresa</th>
                                    <th>Dirección</th>
                                    <th>Municipio</th>
                                    <th>Provincia</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($centros as $centro)
                                    <tr>
                                        <td>{{ $centro->id }}</td>
                                        <td>{{ $centro->nombre }}</td>
                                        <td>{{ $centro->empresa->nombre }}</td>
                                        <td>{{ $centro->direccion }}</td>
                                        <td>{{ $centro->municipio }}</td>
                                        <td>{{ $centro->provincia }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('view', $centro)
                                                    <a href="{{ route('centros.show', $centro) }}" 
                                                       class="btn btn-sm btn-info">
                                                        Ver
                                                    </a>
                                                @endcan
                                                
                                                @can('update', $centro)
                                                    <a href="{{ route('centros.edit', $centro) }}" 
                                                       class="btn btn-sm btn-warning">
                                                        Editar
                                                    </a>
                                                @endcan
                                                
                                                @can('delete', $centro)
                                                    <form action="{{ route('centros.destroy', $centro) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('¿Está seguro de eliminar este centro?');">
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
                        {{ $centros->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="alert alert-info">
                        No se encontraron centros.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection