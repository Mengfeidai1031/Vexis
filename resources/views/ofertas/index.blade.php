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

<!-- Búsqueda y Filtros -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-search"></i> Búsqueda y Filtros
                    </h5>
                    <button 
                        class="btn btn-sm btn-outline-secondary" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#filtrosAvanzados"
                        aria-expanded="false"
                        aria-controls="filtrosAvanzados"
                    >
                        <i class="bi bi-funnel"></i> Filtros Avanzados
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('ofertas.index') }}" method="GET" id="filtrosForm">
                    <!-- Búsqueda General -->
                    <div class="row mb-3">
                        <div class="col-md-10">
                            <label for="search" class="form-label">Búsqueda General</label>
                            <input 
                                type="text" 
                                id="search"
                                name="search" 
                                class="form-control" 
                                placeholder="Buscar por descripción, cliente (nombre, DNI, email), vehículo (modelo, versión, chasis)..."
                                value="{{ $filters['search'] ?? '' }}"
                            >
                            <small class="form-text text-muted">
                                Busca en descripciones de líneas, datos del cliente y del vehículo
                            </small>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100" type="submit">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </div>
                    </div>

                    <!-- Filtros Avanzados (Colapsable) -->
                    <div class="collapse {{ !empty(array_filter($filters ?? [], function($v) { return $v !== null && $v !== '' && $v !== 'search'; })) ? 'show' : '' }}" id="filtrosAvanzados">
                        <hr>
                        <h6 class="mb-3">Filtros Avanzados</h6>
                        <div class="row g-3">
                            <!-- Filtro por Fecha Desde -->
                            <div class="col-md-3">
                                <label for="fecha_desde" class="form-label">Fecha Desde</label>
                                <input 
                                    type="date" 
                                    id="fecha_desde"
                                    name="fecha_desde" 
                                    class="form-control" 
                                    value="{{ $filters['fecha_desde'] ?? '' }}"
                                >
                            </div>

                            <!-- Filtro por Fecha Hasta -->
                            <div class="col-md-3">
                                <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                                <input 
                                    type="date" 
                                    id="fecha_hasta"
                                    name="fecha_hasta" 
                                    class="form-control" 
                                    value="{{ $filters['fecha_hasta'] ?? '' }}"
                                >
                            </div>

                            <!-- Filtro por Cliente -->
                            <div class="col-md-3">
                                <label for="cliente_id" class="form-label">Cliente</label>
                                <select 
                                    id="cliente_id"
                                    name="cliente_id" 
                                    class="form-select"
                                >
                                    <option value="">Todos los clientes</option>
                                    @foreach($clientes as $cliente)
                                        <option 
                                            value="{{ $cliente->id }}"
                                            {{ ($filters['cliente_id'] ?? '') == $cliente->id ? 'selected' : '' }}
                                        >
                                            {{ $cliente->nombre_completo }}
                                            @if($cliente->dni)
                                                ({{ $cliente->dni }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro por Vehículo -->
                            <div class="col-md-3">
                                <label for="vehiculo_id" class="form-label">Vehículo</label>
                                <select 
                                    id="vehiculo_id"
                                    name="vehiculo_id" 
                                    class="form-select"
                                >
                                    <option value="">Todos los vehículos</option>
                                    @foreach($vehiculos as $vehiculo)
                                        <option 
                                            value="{{ $vehiculo->id }}"
                                            {{ ($filters['vehiculo_id'] ?? '') == $vehiculo->id ? 'selected' : '' }}
                                        >
                                            {{ $vehiculo->modelo }}
                                            @if($vehiculo->version)
                                                - {{ $vehiculo->version }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro por Empresa -->
                            <div class="col-md-3">
                                <label for="empresa_id" class="form-label">Empresa</label>
                                <select 
                                    id="empresa_id"
                                    name="empresa_id" 
                                    class="form-select"
                                >
                                    <option value="">Todas las empresas</option>
                                    @foreach($empresas as $empresa)
                                        <option 
                                            value="{{ $empresa->id }}"
                                            {{ ($filters['empresa_id'] ?? '') == $empresa->id ? 'selected' : '' }}
                                        >
                                            {{ $empresa->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-funnel-fill"></i> Aplicar Filtros
                                </button>
                                @if(!empty(array_filter($filters ?? [], function($v) { return $v !== null && $v !== ''; })))
                                    <a href="{{ route('ofertas.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle"></i> Limpiar Filtros
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Indicador de Filtros Activos -->
@if(!empty(array_filter($filters ?? [], function($v) { return $v !== null && $v !== ''; })))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-info-circle"></i> 
                    <strong>Filtros activos:</strong>
                    @if(!empty($filters['fecha_desde']))
                        <span class="badge bg-primary">Desde: {{ \Carbon\Carbon::parse($filters['fecha_desde'])->format('d/m/Y') }}</span>
                    @endif
                    @if(!empty($filters['fecha_hasta']))
                        <span class="badge bg-primary">Hasta: {{ \Carbon\Carbon::parse($filters['fecha_hasta'])->format('d/m/Y') }}</span>
                    @endif
                    @if(!empty($filters['cliente_id']))
                        <span class="badge bg-primary">Cliente seleccionado</span>
                    @endif
                    @if(!empty($filters['vehiculo_id']))
                        <span class="badge bg-primary">Vehículo seleccionado</span>
                    @endif
                    @if(!empty($filters['empresa_id']))
                        <span class="badge bg-primary">Empresa seleccionada</span>
                    @endif
                    @if(!empty($filters['search']))
                        <span class="badge bg-primary">Búsqueda: "{{ $filters['search'] }}"</span>
                    @endif
                </div>
                <a href="{{ route('ofertas.index') }}" class="btn btn-sm btn-outline-info">
                    <i class="bi bi-x-circle"></i> Limpiar
                </a>
            </div>
        </div>
    </div>
@endif

<!-- Tabla -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul"></i> Ofertas 
                        <span class="badge bg-secondary">{{ $ofertas->total() }}</span>
                    </h5>
                </div>
            </div>
            <div class="card-body">
                @if($ofertas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Empresa</th>
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
                                            @if($oferta->cliente->empresa)
                                                <strong>{{ $oferta->cliente->empresa->nombre }}</strong><br>
                                                <small class="text-muted">{{ $oferta->cliente->empresa->abreviatura }}</small>
                                            @else
                                                <span class="text-muted">Sin empresa</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($oferta->vehiculo)
                                            <strong>{{ $oferta->vehiculo->modelo }}</strong><br>
                                            <small class="text-muted">{{ $oferta->vehiculo->version }}</small>
                                            @else
                                                <span class="text-muted">Sin vehículo</span><br>
                                                <small class="badge bg-warning">Doc. Informativo</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $oferta->lineas->count() }} líneas</span>
                                        </td>
                                        <td>
                                            @php
                                                // Buscar el último Total de las líneas
                                                $ultimoTotal = $oferta->lineas()
                                                    ->where('tipo', 'Total')
                                                    ->orderBy('id', 'desc')
                                                    ->first();
                                                $precioMostrar = $ultimoTotal ? $ultimoTotal->precio : 0;
                                            @endphp
                                            <strong class="text-success">{{ number_format($precioMostrar, 2, ',', '.') }} €</strong>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('view', $oferta)
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
                                                
                                                @can('delete', $oferta)
                                                    <form action="{{ route('ofertas.destroy', $oferta) }}" 
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

                    <div class="mt-3 pagination-wrapper">
                        {{ $ofertas->links('pagination::bootstrap-5') }}
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