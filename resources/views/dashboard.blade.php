@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">¡Bienvenido, {{ Auth::user()->nombre_completo }}!</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                        <p><strong>Empresa:</strong> {{ Auth::user()->empresa->nombre }}</p>
                        <p><strong>Departamento:</strong> {{ Auth::user()->departamento->nombre }}</p>
                        <p><strong>Centro:</strong> {{ Auth::user()->centro->nombre }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Roles Asignados:</strong></p>
                        @if(Auth::user()->roles->count() > 0)
                            <ul>
                                @foreach(Auth::user()->roles as $role)
                                    <li><span class="badge bg-primary">{{ $role->name }}</span></li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">Sin roles asignados</p>
                        @endif
                        
                        <p><strong>Permisos:</strong></p>
                        @if(Auth::user()->getAllPermissions()->count() > 0)
                            <p class="text-muted">
                                Tienes {{ Auth::user()->getAllPermissions()->count() }} permisos activos
                            </p>
                        @else
                            <p class="text-muted">Sin permisos asignados</p>
                        @endif
                    </div>
                </div>
                
                <hr>
                
                <h5>Accesos Rápidos</h5>
                <div class="row mt-3">
                    @can('ver usuarios')
                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Usuarios</h5>
                                    <p class="card-text">Gestión de usuarios</p>
                                    <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">Acceder</a>
                                </div>
                            </div>
                        </div>
                    @endcan
                    
                    @can('ver departamentos')
                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Departamentos</h5>
                                    <p class="card-text">Gestión de departamentos</p>
                                    <a href="{{ route('departamentos.index') }}" class="btn btn-primary btn-sm">Acceder</a>
                                </div>
                            </div>
                        </div>
                    @endcan
                    
                    @can('ver centros')
                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Centros</h5>
                                    <p class="card-text">Gestión de centros</p>
                                    <a href="{{ route('centros.index') }}" class="btn btn-primary btn-sm">Acceder</a>
                                </div>
                            </div>
                        </div>
                    @endcan
                    
                    @can('ver roles')
                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Roles</h5>
                                    <p class="card-text">Gestión de roles</p>
                                    <a href="{{ route('roles.index') }}" class="btn btn-primary btn-sm">Acceder</a>
                                </div>
                            </div>
                        </div>
                    @endcan

                    @can('ver clientes')
                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Clientes</h5>
                                    <p class="card-text">Gestión de clientes</p>
                                    <a href="{{ route('clientes.index') }}" class="btn btn-primary btn-sm">Acceder</a>
                                </div>
                            </div>
                        </div>
                    @endcan
                    @can('ver vehículos')
                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Vehículos</h5>
                                    <p class="card-text">Gestión de vehículos</p>
                                    <a href="{{ route('vehiculos.index') }}" class="btn btn-primary btn-sm">Acceder</a>
                                </div>
                            </div>
                        </div>
                    @endcan
                    @can('ver ofertas')
                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Ofertas</h5>
                                    <p class="card-text">Gestión de ofertas comerciales</p>
                                    <a href="{{ route('ofertas.index') }}" class="btn btn-primary btn-sm">Acceder</a>
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection