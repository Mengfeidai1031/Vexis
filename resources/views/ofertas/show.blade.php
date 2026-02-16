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

<div class="row">
    <!-- Información General -->
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información General</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th width="40%">ID Oferta</th>
                            <td><strong>#{{ $oferta->id }}</strong></td>
                        </tr>
                        <tr>
                            <th>Fecha</th>
                            <td>{{ $oferta->fecha->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Cliente</th>
                            <td>
                                <strong>{{ $oferta->cliente->nombre_completo }}</strong><br>
                                <small class="text-muted">DNI: {{ $oferta->cliente->dni }}</small><br>
                                <small class="text-muted">{{ $oferta->cliente->domicilio }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Vehículo</th>
                            <td>
                                <strong>{{ $oferta->vehiculo->modelo }}</strong><br>
                                <small class="text-muted">{{ $oferta->vehiculo->version }}</small><br>
                                <small class="text-muted">Chasis: {{ $oferta->vehiculo->chasis }}</small><br>
                                <small class="text-muted">Color: {{ $oferta->vehiculo->color_externo }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Total Líneas</th>
                            <td><span class="badge bg-info">{{ $oferta->lineas->count() }} líneas</span></td>
                        </tr>
                        <tr>
                            <th>Precio Total</th>
                            <td><h4 class="text-success mb-0">{{ number_format($oferta->precio_total, 2, ',', '.') }} €</h4></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Resumen por Tipo -->
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Resumen por Tipo</h5>
            </div>
            <div class="card-body">
                @php
                    $resumenPorTipo = $oferta->lineas->groupBy('tipo')->map(function($lineas) {
                        return [
                            'cantidad' => $lineas->count(),
                            'total' => $lineas->sum('precio')
                        ];
                    });
                @endphp

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resumenPorTipo as $tipo => $datos)
                            <tr>
                                <td>
                                    @if($tipo === 'opciones')
                                        <span class="badge bg-primary">Opciones</span>
                                    @elseif($tipo === 'descuento')
                                        <span class="badge bg-warning">Descuentos</span>
                                    @else
                                        <span class="badge bg-info">Accesorios</span>
                                    @endif
                                </td>
                                <td>{{ $datos['cantidad'] }}</td>
                                <td><strong>{{ number_format($datos['total'], 2, ',', '.') }} €</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-success">
                            <th colspan="2">TOTAL</th>
                            <th>{{ number_format($oferta->precio_total, 2, ',', '.') }} €</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Detalle de Líneas -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Detalle de Líneas de la Oferta</h5>
            </div>
            <div class="card-body">
                @if($oferta->lineas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Tipo</th>
                                    <th width="60%">Descripción</th>
                                    <th width="20%" class="text-end">Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($oferta->lineas as $index => $linea)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($linea->tipo === 'opciones')
                                                <span class="badge bg-primary">Opciones</span>
                                            @elseif($linea->tipo === 'descuento')
                                                <span class="badge bg-warning">Descuento</span>
                                            @else
                                                <span class="badge bg-info">Accesorios</span>
                                            @endif
                                        </td>
                                        <td>{{ $linea->descripcion }}</td>
                                        <td class="text-end">
                                            <strong class="{{ $linea->tipo === 'descuento' ? 'text-danger' : 'text-success' }}">
                                                {{ $linea->tipo === 'descuento' ? '-' : '' }}{{ number_format($linea->precio, 2, ',', '.') }} €
                                            </strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-success">
                                    <th colspan="3" class="text-end">TOTAL OFERTA:</th>
                                    <th class="text-end">
                                        <h5 class="mb-0 text-success">{{ number_format($oferta->precio_total, 2, ',', '.') }} €</h5>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning">
                        No se encontraron líneas en esta oferta. El PDF puede no haberse procesado correctamente.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Información adicional -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <small class="text-muted">
                    <strong>Creado:</strong> {{ $oferta->created_at->format('d/m/Y H:i') }} | 
                    <strong>Actualizado:</strong> {{ $oferta->updated_at->format('d/m/Y H:i') }}
                    @if($oferta->pdf_path)
                        | <strong>PDF almacenado:</strong> {{ basename($oferta->pdf_path) }}
                    @endif
                </small>
            </div>
        </div>
    </div>
</div>
@endsection