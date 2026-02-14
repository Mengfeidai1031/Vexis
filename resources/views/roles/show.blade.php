@extends('layouts.app')

@section('title', 'Detalle Rol')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detalle del Rol</h2>
            <div>
                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">Editar</a>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">{{ $role->name }}</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="30%">ID</th>
                            <td>{{ $role->id }}</td>
                        </tr>
                        <tr>
                            <th>Nombre del Rol</th>
                            <td>{{ $role->name }}</td>
                        </tr>
                        <tr>
                            <th>Cantidad de Permisos</th>
                            <td><span class="badge bg-info">{{ $role->permissions->count() }} permisos</span></td>
                        </tr>
                        <tr>
                            <th>Usuarios con este Rol</th>
                            <td><span class="badge bg-secondary">{{ $role->users->count() }} usuarios</span></td>
                        </tr>
                        <tr>
                            <th>Fecha de Creación</th>
                            <td>{{ $role->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Última Actualización</th>
                            <td>{{ $role->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Permisos asignados -->
        @if($role->permissions->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Permisos Asignados</h5>
                </div>
                <div class="card-body">
                    @php
                        $groupedPermissions = $role->permissions->groupBy(function($permission) {
                            return explode(' ', $permission->name)[1] ?? 'otros';
                        });
                    @endphp

                    <div class="row">
                        @foreach($groupedPermissions as $module => $permissions)
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 text-capitalize">{{ ucfirst($module) }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            @foreach($permissions as $permission)
                                                <li class="mb-1">
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                    {{ $permission->name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="card mt-3">
                <div class="card-body">
                    <div class="alert alert-warning mb-0">
                        Este rol no tiene permisos asignados.
                    </div>
                </div>
            </div>
        @endif

        <!-- Usuarios con este rol -->
        @if($role->users->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Usuarios con este Rol</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($role->users as $user)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $user->nombre_completo }}</strong><br>
                                    <small class="text-muted">{{ $user->email }} - {{ $user->departamento->nombre }}</small>
                                </div>
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