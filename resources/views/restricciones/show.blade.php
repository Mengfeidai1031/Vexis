@extends('layouts.app')

@section('title', 'Ver Restricción')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detalle de la Restricción</h2>
            <div>
                @can('update', $restriccion)
                    <a href="{{ route('restricciones.edit', $restriccion) }}" class="btn btn-warning">Editar</a>
                @endcan
                <a href="{{ route('restricciones.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Restricción #{{ $restriccion->id }}</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="30%">ID</th>
                            <td>{{ $restriccion->id }}</td>
                        </tr>
                        <tr>
                            <th>Usuario</th>
                            <td>
                                <strong>{{ $restriccion->user->nombre_completo }}</strong><br>
                                <small class="text-muted">{{ $restriccion->user->email }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Tipo de Restricción</th>
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
                        </tr>
                        <tr>
                            <th>Entidad Restringida</th>
                            <td>
                                @if($restriccion->restrictable)
                                    @if($restriccion->restrictable_type === 'App\Models\Empresa')
                                        <strong>{{ $restriccion->restrictable->nombre }}</strong>
                                        @if($restriccion->restrictable->cif)
                                            <br><small class="text-muted">CIF: {{ $restriccion->restrictable->cif }}</small>
                                        @endif
                                    @elseif($restriccion->restrictable_type === 'App\Models\Cliente')
                                        <strong>{{ $restriccion->restrictable->nombre_completo }}</strong>
                                        <br><small class="text-muted">{{ $restriccion->restrictable->email }} - {{ $restriccion->restrictable->empresa->nombre }}</small>
                                    @elseif($restriccion->restrictable_type === 'App\Models\Vehiculo')
                                        <strong>{{ $restriccion->restrictable->modelo }} {{ $restriccion->restrictable->version }}</strong>
                                        <br><small class="text-muted">{{ $restriccion->restrictable->empresa->nombre }}</small>
                                    @elseif($restriccion->restrictable_type === 'App\Models\Centro')
                                        <strong>{{ $restriccion->restrictable->nombre }}</strong>
                                        <br><small class="text-muted">{{ $restriccion->restrictable->empresa->nombre }}</small>
                                    @elseif($restriccion->restrictable_type === 'App\Models\Departamento')
                                        <strong>{{ $restriccion->restrictable->nombre }}</strong>
                                        @if($restriccion->restrictable->abreviatura)
                                            <br><small class="text-muted">Abreviatura: {{ $restriccion->restrictable->abreviatura }}</small>
                                        @endif
                                    @endif
                                @else
                                    <span class="text-danger">Entidad eliminada</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha de Creación</th>
                            <td>{{ $restriccion->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Última Actualización</th>
                            <td>{{ $restriccion->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
