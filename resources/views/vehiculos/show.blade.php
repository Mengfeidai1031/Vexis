@extends('layouts.app')

@section('title', 'Detalle Vehículo')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detalle del Vehículo</h2>
            <div>
                @can('update', $vehiculo)
                    <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-warning">Editar</a>
                @endcan
                <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">{{ $vehiculo->descripcion_completa }}</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="30%">ID</th>
                            <td>{{ $vehiculo->id }}</td>
                        </tr>
                        <tr>
                            <th>Número de Chasis (VIN)</th>
                            <td><span class="badge bg-dark">{{ $vehiculo->chasis }}</span></td>
                        </tr>
                        <tr>
                            <th>Modelo</th>
                            <td><strong>{{ $vehiculo->modelo }}</strong></td>
                        </tr>
                        <tr>
                            <th>Versión</th>
                            <td>{{ $vehiculo->version }}</td>
                        </tr>
                        <tr>
                            <th>Color Externo</th>
                            <td>{{ $vehiculo->color_externo }}</td>
                        </tr>
                        <tr>
                            <th>Color Interno</th>
                            <td>{{ $vehiculo->color_interno }}</td>
                        </tr>
                        <tr>
                            <th>Empresa</th>
                            <td>{{ $vehiculo->empresa->nombre }} ({{ $vehiculo->empresa->abreviatura }})</td>
                        </tr>
                        <tr>
                            <th>Fecha de Creación</th>
                            <td>{{ $vehiculo->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Última Actualización</th>
                            <td>{{ $vehiculo->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection