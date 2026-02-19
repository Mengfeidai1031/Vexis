@extends('layouts.app')

@section('title', 'Detalle Centro')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detalle del Centro</h2>
            <div>
                @can('update', $centro)
                    <a href="{{ route('centros.edit', $centro) }}" class="btn btn-warning">Editar</a>
                @endcan
                <a href="{{ route('centros.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">{{ $centro->nombre }}</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="30%">ID</th>
                            <td>{{ $centro->id }}</td>
                        </tr>
                        <tr>
                            <th>Nombre</th>
                            <td>{{ $centro->nombre }}</td>
                        </tr>
                        <tr>
                            <th>Empresa</th>
                            <td>{{ $centro->empresa->nombre }} ({{ $centro->empresa->abreviatura }})</td>
                        </tr>
                        <tr>
                            <th>Dirección</th>
                            <td>{{ $centro->direccion }}</td>
                        </tr>
                        <tr>
                            <th>Municipio</th>
                            <td>{{ $centro->municipio }}</td>
                        </tr>
                        <tr>
                            <th>Provincia</th>
                            <td>{{ $centro->provincia }}</td>
                        </tr>
                        <tr>
                            <th>Usuarios Asociados</th>
                            <td>{{ $centro->users->count() }}</td>
                        </tr>
                        <tr>
                            <th>Fecha de Creación</th>
                            <td>{{ $centro->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Última Actualización</th>
                            <td>{{ $centro->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Lista de usuarios del centro -->
        @if($centro->users->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Usuarios en este Centro</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($centro->users as $user)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $user->nombre_completo }}</strong><br>
                                    <small class="text-muted">{{ $user->departamento->nombre }} - {{ $user->email }}</small>
                                </div>
                                @can('view', $user)
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info">Ver</a>
                                @endcan
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection