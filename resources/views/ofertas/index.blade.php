@extends('layouts.app')

@section('title', 'Gestión de Ofertas Comerciales')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Gestión de Ofertas Comerciales</h2>
            @can('crear ofertas')
                <a href="{{ route('ofertas.create') }}" class="btn btn-primary">
                    <i class="bi bi-file-earmark-pdf"></i> Nueva Oferta (Subir PDF)
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
                <form action="{{ route('ofertas.index') }}" method="GET">
                    <div class="input-group">
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Buscar por cliente (nombre, DNI), vehículo (modelo, chasis)..."
                            value="{{ request('search') }}"
                        >
                        <button class="btn btn-primary" type="submit">Buscar</button>
                        @if(request('search'))
                            <a href="{{ route('ofertas.index') }}" class="btn btn-secondary">Limpiar</a>
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
                @if($ofertas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Vehículo</th>
                                    <th>Líneas</th>
                                    <th>Precio Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ofertas as $oferta)
                                    <tr>
                                        <td>{{ $oferta->id }}</td>
                                        <td>{{ $oferta->fecha->format('d/m/Y') }}</td>
                                        <td>
                                            <strong>{{ $oferta->cliente->nombre_completo }}</strong><br>
                                            <small class="text-muted">{{ $oferta->cliente->dni }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $oferta->vehiculo->modelo }}</strong><br>
                                            <small class="text-muted">{{ $oferta->vehiculo->version }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $oferta->lineas->count() }} líneas</span>
                                        </td>
                                        <td>
                                            <strong class="text-success">{{ number_format($oferta->precio_total, 2, ',', '.') }} €</strong>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('ver ofertas')
                                                    <a href="{{ route('ofertas.show', $oferta->id) }}" 
                                                       class="btn btn-sm btn-info"
                                                       title="Ver detalle">
                                                        Ver
                                                    </a>
                                                @endcan
                                                
                                                @if($oferta->pdf_path)
                                                    <a href="{{ asset('storage/' . $oferta->pdf_path) }}" 
                                                       class="btn btn-sm btn-secondary" 
                                                       target="_blank"
                                                       title="Descargar PDF original">
                                                        PDF
                                                    </a>
                                                @endif
                                                
                                                @can('eliminar ofertas')
                                                    <form action="{{ route('ofertas.destroy', $oferta->id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('¿Está seguro de eliminar esta oferta?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
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
                        {{ $ofertas->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        No se encontraron ofertas comerciales.
                        @can('crear ofertas')
                            <a href="{{ route('ofertas.create') }}">Sube tu primera oferta en PDF</a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection