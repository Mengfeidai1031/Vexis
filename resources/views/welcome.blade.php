@extends('layouts.app')

@section('title', 'Bienvenido - Grupo ARI')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Bienvenido al Sistema de Gestión Grupo ARI</h3>
            </div>
            <div class="card-body">
                <p class="lead">Sistema de gestión de clientes, vehículos y ofertas comerciales.</p>
                
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Usuarios</h5>
                                <p class="card-text">Gestión de usuarios del sistema</p>
                                <a href="#" class="btn btn-primary">Acceder</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Clientes</h5>
                                <p class="card-text">Gestión de clientes</p>
                                <a href="#" class="btn btn-primary">Acceder</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Vehículos</h5>
                                <p class="card-text">Gestión de vehículos</p>
                                <a href="#" class="btn btn-primary">Acceder</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection