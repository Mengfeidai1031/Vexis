@extends('layouts.app')

@section('title', 'Detalle Oferta Comercial')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detalle de la Oferta Comercial #{{ $oferta->id }}</h2>
            <div>
                @if($oferta->pdf_path)
                    <a href="{{ asset('storage/' . $oferta->pdf_path) }}" 
                       class="btn btn-secondary" 
                       target="_blank">
                        <i class="bi bi-file-pdf"></i> Ver PDF Original
                    </a>
                @endif
                <a href="{{ route('ofertas.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>

<!-- Mensaje de documento informativo si no hay vehículo -->
@if(!$oferta->vehiculo_id)
    <div class="alert alert-warning">
        <h5 class="alert-heading"><i class="bi bi-info-circle"></i> Documento Informativo</h5>
        <p class="mb-0">Este documento no contiene número de bastidor/chasis, por lo que se considera un documento informativo y <strong>no se ha registrado ningún vehículo</strong>.</p>
    </div>
@endif

<div class="row">
    <!-- Datos del Cliente -->
    <div class="col-md-6 mb-3">
        <div class="card h-100 border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person"></i> Cliente</h5>
            </div>
            <div class="card-body">
                @if($oferta->cliente)
                    <table class="table table-sm table-bordered mb-0">
                    <tbody>
                        <tr>
                                <th width="35%">ID</th>
                                <td>{{ $oferta->cliente->id }}</td>
                            </tr>
                            <tr>
                                <th>Nombre Completo</th>
                                <td><strong>{{ $oferta->cliente->nombre_completo }}</strong></td>
                            </tr>
                            <tr>
                                <th>DNI</th>
                                <td>
                                    @if($oferta->cliente->dni)
                                        <span class="badge bg-dark">{{ $oferta->cliente->dni }}</span>
                                    @else
                                        <span class="text-muted">No disponible</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $oferta->cliente->email }}</td>
                            </tr>
                            <tr>
                                <th>Teléfono</th>
                                <td>{{ $oferta->cliente->telefono }}</td>
                        </tr>
                        <tr>
                                <th>Domicilio</th>
                                <td>{{ $oferta->cliente->domicilio }}</td>
                        </tr>
                            <tr>
                                <th>Código Postal</th>
                                <td>{{ $oferta->cliente->codigo_postal }}</td>
                            </tr>
                            <tr>
                                <th>Empresa</th>
                                <td>
                                    @if($oferta->cliente->empresa)
                                        <a href="#empresa-card">{{ $oferta->cliente->empresa->nombre }}</a>
                                    @else
                                        <span class="text-muted">Sin empresa</span>
                        @endif
                                </td>
                            </tr>
                    </tbody>
                </table>
                @else
                    <div class="alert alert-warning mb-0">Cliente no encontrado</div>
                @endif
            </div>
            @if($oferta->cliente)
                <div class="card-footer">
                    @if($oferta->cliente)
                        @can('update', $oferta->cliente)
                            <a href="{{ route('clientes.edit', $oferta->cliente) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Editar Cliente
                            </a>
                        @endcan
                        @can('view', $oferta->cliente)
                            <a href="{{ route('clientes.show', $oferta->cliente) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i> Ver Cliente
                            </a>
                        @endcan
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Datos de la Empresa -->
    <div class="col-md-6 mb-3">
        <div class="card h-100 border-success" id="empresa-card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-building"></i> Empresa</h5>
            </div>
            <div class="card-body">
                @if($oferta->cliente && $oferta->cliente->empresa)
                    @php $empresa = $oferta->cliente->empresa; @endphp
                    <table class="table table-sm table-bordered mb-0">
                    <tbody>
                        <tr>
                                <th width="35%">ID</th>
                                <td>{{ $empresa->id }}</td>
                            </tr>
                            <tr>
                                <th>Nombre</th>
                                <td><strong>{{ $empresa->nombre }}</strong></td>
                            </tr>
                            <tr>
                                <th>Abreviatura</th>
                                <td><span class="badge bg-secondary">{{ $empresa->abreviatura }}</span></td>
                            </tr>
                            <tr>
                                <th>CIF</th>
                                <td><span class="badge bg-dark">{{ $empresa->cif }}</span></td>
                            </tr>
                            <tr>
                                <th>Domicilio</th>
                                <td>{{ $empresa->domicilio }}</td>
                        </tr>
                        <tr>
                                <th>Código Postal</th>
                                <td>{{ $empresa->codigo_postal ?? 'No disponible' }}</td>
                        </tr>
                            <tr>
                                <th>Teléfono</th>
                                <td>{{ $empresa->telefono }}</td>
                            </tr>
                    </tbody>
                </table>
                @else
                    <div class="alert alert-warning mb-0">Empresa no encontrada</div>
                @endif
            </div>
            @if($oferta->cliente && $oferta->cliente->empresa)
                <div class="card-footer">
                    @if(Route::has('empresas.edit'))
                        <a href="{{ route('empresas.edit', $empresa->id) }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-pencil"></i> Editar Empresa
                        </a>
                    @endif
                    @if(Route::has('empresas.show'))
                        <a href="{{ route('empresas.show', $empresa->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i> Ver Empresa
                        </a>
                    @endif
                    @if(!Route::has('empresas.edit') && !Route::has('empresas.show'))
                        <span class="text-muted small">ID: {{ $empresa->id }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <!-- Datos del Vehículo -->
    <div class="col-md-6 mb-3">
        <div class="card h-100 {{ $oferta->vehiculo_id ? 'border-info' : 'border-secondary' }}">
            <div class="card-header {{ $oferta->vehiculo_id ? 'bg-info' : 'bg-secondary' }} text-white">
                <h5 class="mb-0"><i class="bi bi-car-front"></i> Vehículo</h5>
            </div>
            <div class="card-body">
                @if($oferta->vehiculo)
                <table class="table table-sm table-bordered mb-0">
                    <tbody>
                        <tr>
                                <th width="35%">ID</th>
                                <td>{{ $oferta->vehiculo->id }}</td>
                            </tr>
                            <tr>
                                <th>Chasis/Bastidor</th>
                                <td><code>{{ $oferta->vehiculo->chasis }}</code></td>
                            </tr>
                            <tr>
                                <th>Modelo</th>
                                <td><strong>{{ $oferta->vehiculo->modelo }}</strong></td>
                            </tr>
                            <tr>
                                <th>Versión</th>
                                <td>{{ $oferta->vehiculo->version }}</td>
                            </tr>
                            <tr>
                                <th>Color Externo</th>
                                <td>{{ $oferta->vehiculo->color_externo }}</td>
                            </tr>
                            <tr>
                                <th>Color Interno</th>
                                <td>{{ $oferta->vehiculo->color_interno }}</td>
                            </tr>
                            <tr>
                                <th>Empresa</th>
                                <td>{{ $oferta->vehiculo->empresa->nombre ?? 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
                @else
                    <div class="alert alert-secondary mb-0">
                        <i class="bi bi-info-circle"></i> <strong>Sin vehículo registrado</strong><br>
                        <small>Este documento es informativo y no contiene número de bastidor/chasis.</small>
                    </div>
                @endif
            </div>
            @if($oferta->vehiculo)
                <div class="card-footer">
                    @if($oferta->vehiculo)
                        @can('update', $oferta->vehiculo)
                            <a href="{{ route('vehiculos.edit', $oferta->vehiculo) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-pencil"></i> Editar Vehículo
                            </a>
                        @endcan
                        @can('view', $oferta->vehiculo)
                            <a href="{{ route('vehiculos.show', $oferta->vehiculo) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i> Ver Vehículo
                            </a>
                        @endcan
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Datos de Oferta Cabecera -->
    <div class="col-md-6 mb-3">
        <div class="card h-100 border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Oferta Cabecera</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th width="35%">ID</th>
                            <td>{{ $oferta->id }}</td>
                        </tr>
                        <tr>
                            <th>Fecha</th>
                            <td>{{ $oferta->fecha->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Cliente ID</th>
                            <td>{{ $oferta->cliente_id }}</td>
                        </tr>
                        <tr>
                            <th>Vehículo ID</th>
                            <td>{{ $oferta->vehiculo_id ?? 'NULL (Sin vehículo)' }}</td>
                        </tr>
                        <tr>
                            <th>PDF</th>
                            <td><small>{{ basename($oferta->pdf_path) }}</small></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Detalle de Líneas de Oferta -->
<div class="row">
    <div class="col-12">
        <div class="card border-dark">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Oferta Líneas ({{ $oferta->lineas->count() }} registros)</h5>
            </div>
            <div class="card-body">
                @if($oferta->lineas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="20%">Tipo</th>
                                    <th width="55%">Descripción</th>
                                    <th width="20%" class="text-end">Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($oferta->lineas as $linea)
                                    <tr>
                                        <td>{{ $linea->id }}</td>
                                        <td>
                                            @php
                                                $tipoLower = strtolower($linea->tipo);
                                                $badgeClass = 'bg-secondary';
                                                if (str_contains($tipoLower, 'modelo')) $badgeClass = 'bg-primary';
                                                elseif (str_contains($tipoLower, 'promocion') || str_contains($tipoLower, 'oferta')) $badgeClass = 'bg-danger';
                                                elseif (str_contains($tipoLower, 'igic') || str_contains($tipoLower, 'impuesto')) $badgeClass = 'bg-warning text-dark';
                                                elseif (str_contains($tipoLower, 'gastos')) $badgeClass = 'bg-info';
                                                elseif (str_contains($tipoLower, 'color') || str_contains($tipoLower, 'pintura') || str_contains($tipoLower, 'tapicería')) $badgeClass = 'bg-success';
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $linea->tipo }}</span>
                                        </td>
                                        <td>{{ $linea->descripcion }}</td>
                                        <td class="text-end">
                                            <strong class="{{ $linea->precio < 0 ? 'text-danger' : 'text-success' }}">
                                                {{ number_format($linea->precio, 2, ',', '.') }} €
                                            </strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning">
                        No se encontraron líneas en esta oferta.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Datos Extraídos del PDF (Debug) -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card border-secondary">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-code-slash"></i> Datos Extraídos del PDF (Debug)</h5>
                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#debugData">
                    Mostrar/Ocultar
                </button>
            </div>
            <div class="collapse" id="debugData">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Datos extraídos del PDF:</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>cliente_nombre_pdf:</span>
                                    <code>{{ $oferta->cliente_nombre_pdf ?? 'NULL' }}</code>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>cliente_dni_pdf:</span>
                                    <code>{{ $oferta->cliente_dni_pdf ?? 'NULL' }}</code>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>vehiculo_modelo_pdf:</span>
                                    <code>{{ $oferta->vehiculo_modelo_pdf ?? 'NULL' }}</code>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>vehiculo_chasis_pdf:</span>
                                    <code>{{ $oferta->vehiculo_chasis_pdf ?? 'NULL' }}</code>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Timestamps:</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>created_at:</span>
                                    <code>{{ $oferta->created_at }}</code>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>updated_at:</span>
                                    <code>{{ $oferta->updated_at }}</code>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Información adicional -->
<div class="row mt-3 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <strong>Creado:</strong> {{ $oferta->created_at->format('d/m/Y H:i') }} | 
                    <strong>Actualizado:</strong> {{ $oferta->updated_at->format('d/m/Y H:i') }}
                    @if($oferta->pdf_path)
                        | <strong>PDF:</strong> {{ basename($oferta->pdf_path) }}
                    @endif
                </small>
                <div>
                    @can('delete', $oferta)
                        <form action="{{ route('ofertas.destroy', $oferta) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('¿Está seguro de eliminar esta oferta?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i> Eliminar Oferta
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
