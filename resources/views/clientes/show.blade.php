@extends('layouts.app')

@section('title', 'Detalle Cliente')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detalle del Cliente</h2>
            <div>
                @can('update', $cliente)
                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning">Editar</a>
                @endcan
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">{{ $cliente->nombre_completo }}</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="30%">ID</th>
                            <td>{{ $cliente->id }}</td>
                        </tr>
                        <tr>
                            <th>Nombre</th>
                            <td>{{ $cliente->nombre }}</td>
                        </tr>
                        <tr>
                            <th>Apellidos</th>
                            <td>{{ $cliente->apellidos }}</td>
                        </tr>
                        <tr>
                            <th>DNI</th>
                            <td><span class="badge bg-secondary">{{ $cliente->dni }}</span></td>
                        </tr>
                        <tr>
                            <th>Empresa</th>
                            <td>{{ $cliente->empresa->nombre }} ({{ $cliente->empresa->abreviatura }})</td>
                        </tr>
                        <tr>
                            <th>Domicilio</th>
                            <td>{{ $cliente->domicilio }}</td>
                        </tr>
                        <tr>
                            <th>Código Postal</th>
                            <td>{{ $cliente->codigo_postal }}</td>
                        </tr>
                        <tr>
                            <th>Fecha de Creación</th>
                            <td>{{ $cliente->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Última Actualización</th>
                            <td>{{ $cliente->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection