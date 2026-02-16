@extends('layouts.app')

@section('title', 'Gestión de Clientes')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Gestión de Clientes</h2>
            @can('crear clientes')
                <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                    Nuevo Cliente
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
                <form action="{{ route('clientes.index') }}" method="GET">
                    <div class="input-group">
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Buscar por nombre, apellidos, DNI, domicilio, código postal o empresa..."
                            value="{{ request('search') }}"
                        >
                        <button class="btn btn-primary" type="submit">Buscar</button>
                        @if(request('search'))
                            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Limpiar</a>
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
                @if($clientes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre Completo</th>
                                    <th>DNI</th>
                                    <th>Empresa</th>
                                    <th>Domicilio</th>
                                    <th>CP</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clientes as $cliente)
                                    <tr>
                                        <td>{{ $cliente->id }}</td>
                                        <td>{{ $cliente->nombre_completo }}</td>
                                        <td><span class="badge bg-secondary">{{ $cliente->dni }}</span></td>
                                        <td>{{ $cliente->empresa->nombre }}</td>
                                        <td>{{ $cliente->domicilio }}</td>
                                        <td>{{ $cliente->codigo_postal }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('ver clientes')
                                                    <a href="{{ route('clientes.show', $cliente->id) }}" 
                                                       class="btn btn-sm btn-info">
                                                        Ver
                                                    </a>
                                                @endcan
                                                
                                                @can('editar clientes')
                                                    <a href="{{ route('clientes.edit', $cliente->id) }}" 
                                                       class="btn btn-sm btn-warning">
                                                        Editar
                                                    </a>
                                                @endcan
                                                
                                                @can('eliminar clientes')
                                                    <form action="{{ route('clientes.destroy', $cliente->id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('¿Está seguro de eliminar este cliente?');">
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

                    <div class="mt-3">
                        {{ $clientes->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        No se encontraron clientes.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection