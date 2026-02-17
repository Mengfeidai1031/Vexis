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
    <!-- Datos Extraídos del PDF -->
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-file-earmark-pdf"></i> Datos Extraídos del PDF</h5>
            </div>
            <div class="card-body">
                <h6 class="text-muted">CLIENTE</h6>
                <table class="table table-sm table-bordered mb-3">
                    <tbody>
                        <tr>
                            <th width="40%">Nombre (PDF)</th>
                            <td>{{ $oferta->cliente_nombre_pdf ?? 'No extraído' }}</td>
                        </tr>
                        <tr>
                            <th>DNI (PDF)</th>
                            <td><span class="badge bg-secondary">{{ $oferta->cliente_dni_pdf ?? 'No extraído' }}</span></td>
                        </tr>
                        @if($oferta->cliente)
                            <tr>
                                <th>Cliente BD</th>
                                <td><a href="{{ route('clientes.show', $oferta->cliente_id) }}">{{ $oferta->cliente->nombre_completo }}</a></td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <h6 class="text-muted">VEHÍCULO</h6>
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th width="40%">Modelo (PDF)</th>
                            <td>{{ $oferta->vehiculo_modelo_pdf ?? 'No extraído' }}</td>
                        </tr>
                        <tr>
                            <th>Chasis (PDF)</th>
                            <td><span class="badge bg-dark">{{ $oferta->vehiculo_chasis_pdf ?? 'No extraído' }}</span></td>
                        </tr>
                        @if($oferta->vehiculo)
                            <tr>
                                <th>Vehículo BD</th>
                                <td><a href="{{ route('vehiculos.show', $oferta->vehiculo_id) }}">{{ $oferta->vehiculo->modelo }}</a></td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <table class="table table-sm table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th width="40%">Fecha Oferta</th>
                            <td>{{ $oferta->fecha->format('d/m/Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Resumen de Cálculos -->
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-calculator"></i> Resumen de Cálculos</h5>
            </div>
            <div class="card-body">
                @php
                    $subtotalOpciones = $oferta->lineas()->where('tipo', 'opciones')->sum('precio');
                    $subtotalDescuentos = $oferta->lineas()->where('tipo', 'descuento')->sum('precio');
                    $subtotalAccesorios = $oferta->lineas()->where('tipo', 'accesorios')->sum('precio');
                @endphp

                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="60%">Opciones</th>
                            <td class="text-end">
                                <span class="badge bg-primary">{{ $oferta->lineas()->where('tipo', 'opciones')->count() }}</span>
                            </td>
                            <td class="text-end">{{ number_format($subtotalOpciones, 2, ',', '.') }} €</td>
                        </tr>
                        <tr>
                            <th>Descuentos</th>
                            <td class="text-end">
                                <span class="badge bg-warning">{{ $oferta->lineas()->where('tipo', 'descuento')->count() }}</span>
                            </td>
                            <td class="text-end text-danger">-{{ number_format($subtotalDescuentos, 2, ',', '.') }} €</td>
                        </tr>
                        <tr>
                            <th>Accesorios/Gastos</th>
                            <td class="text-end">
                                <span class="badge bg-info">{{ $oferta->lineas()->where('tipo', 'accesorios')->count() }}</span>
                            </td>
                            <td class="text-end">{{ number_format($subtotalAccesorios, 2, ',', '.') }} €</td>
                        </tr>
                        <tr class="table-light">
                            <th colspan="2">SUBTOTAL (sin impuestos)</th>
                            <th class="text-end">{{ number_format($oferta->total_sin_impuestos ?? 0, 2, ',', '.') }} €</th>
                        </tr>
                        <tr>
                            <th colspan="2">IGIC (9,5%)</th>
                            <td class="text-end">{{ number_format($oferta->impuestos ?? 0, 2, ',', '.') }} €</td>
                        </tr>
                        <tr class="table-success">
                            <th colspan="2">TOTAL OFERTA</th>
                            <th class="text-end">
                                <h5 class="mb-0 text-success">{{ number_format($oferta->total_con_impuestos ?? 0, 2, ',', '.') }} €</h5>
                            </th>
                        </tr>
                    </tbody>
                </table>

                <div class="alert alert-warning mb-0">
                    <small>
                        <strong>Verificación:</strong><br>
                        • Total líneas: {{ $oferta->lineas->count() }}<br>
                        • Base imponible: {{ number_format($oferta->base_imponible ?? 0, 2, ',', '.') }} €
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detalle de Líneas -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Detalle de Líneas Extraídas del PDF</h5>
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
                                <tr class="table-light">
                                    <th colspan="3" class="text-end">SUMA TOTAL:</th>
                                    <th class="text-end">
                                        {{ number_format($subtotalOpciones - $subtotalDescuentos + $subtotalAccesorios, 2, ',', '.') }} €
                                    </th>
                                </tr>
                            </tfoot>
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

<!-- Información adicional -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <small class="text-muted">
                    <strong>Creado:</strong> {{ $oferta->created_at->format('d/m/Y H:i') }} | 
                    <strong>Actualizado:</strong> {{ $oferta->updated_at->format('d/m/Y H:i') }}
                    @if($oferta->pdf_path)
                        | <strong>PDF:</strong> {{ basename($oferta->pdf_path) }}
                    @endif
                </small>
            </div>
        </div>
    </div>
</div>
@endsection