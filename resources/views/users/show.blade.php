@extends('layouts.app')

@section('title', 'Detalle Usuario')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detalle del Usuario</h2>
            <div>
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Editar</a>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">{{ $user->nombre_completo }}</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="30%">ID</th>
                            <td>{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <th>Nombre</th>
                            <td>{{ $user->nombre }}</td>
                        </tr>
                        <tr>
                            <th>Apellidos</th>
                            <td>{{ $user->apellidos }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono</th>
                            <td>{{ $user->telefono ?? 'No especificado' }}</td>
                        </tr>
                        <tr>
                            <th>Extensión</th>
                            <td>{{ $user->extension ?? 'No especificado' }}</td>
                        </tr>
                        <tr>
                            <th>Empresa</th>
                            <td>{{ $user->empresa->nombre }} ({{ $user->empresa->abreviatura }})</td>
                        </tr>
                        <tr>
                            <th>Departamento</th>
                            <td>{{ $user->departamento->nombre }} ({{ $user->departamento->abreviatura }})</td>
                        </tr>
                        <tr>
                            <th>Centro</th>
                            <td>
                                {{ $user->centro->nombre }}<br>
                                <small class="text-muted">
                                    {{ $user->centro->direccion }}, {{ $user->centro->municipio }}, {{ $user->centro->provincia }}
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <th>Roles Asignados</th>
                            <td>
                                @if($user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Sin roles asignados</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Restricciones</th>
                            <td>
                                @php
                                    $restrictionsCount = $user->restrictions->count();
                                @endphp
                                @if($restrictionsCount > 0)
                                    <span class="badge bg-warning">{{ $restrictionsCount }} restricciones</span>
                                @else
                                    <span class="badge bg-success">Sin restricciones (ve todo)</span>
                                @endif
                                @can('editar restricciones')
                                    <a href="{{ route('restricciones.edit', $user->id) }}" class="btn btn-sm btn-warning ms-2">
                                        Gestionar
                                    </a>
                                @endcan
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha de Creación</th>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Última Actualización</th>
                            <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection