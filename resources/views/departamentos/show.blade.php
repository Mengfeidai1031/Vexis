@extends('layouts.app')

@section('title', 'Detalle Departamento')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detalle del Departamento</h2>
            <div>
                <a href="{{ route('departamentos.edit', $departamento->id) }}" class="btn btn-warning">Editar</a>
                <a href="{{ route('departamentos.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">{{ $departamento->nombre }}</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="40%">ID</th>
                            <td>{{ $departamento->id }}</td>
                        </tr>
                        <tr>
                            <th>Nombre</th>
                            <td>{{ $departamento->nombre }}</td>
                        </tr>
                        <tr>
                            <th>Abreviatura</th>
                            <td><span class="badge bg-secondary">{{ $departamento->abreviatura }}</span></td>
                        </tr>
                        <tr>
                            <th>Usuarios Asociados</th>
                            <td>{{ $departamento->users->count() }}</td>
                        </tr>
                        <tr>
                            <th>Fecha de Creación</th>
                            <td>{{ $departamento->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Última Actualización</th>
                            <td>{{ $departamento->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Lista de usuarios del departamento -->
        @if($departamento->users->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Usuarios en este Departamento</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($departamento->users as $user)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $user->nombre_completo }}
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">Ver</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection