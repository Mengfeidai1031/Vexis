@extends('layouts.app')

@section('title', 'Bienvenido - Grupo ARI')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-lg border-0">
            <div class="card-body text-center p-5">
                <!-- Logo Grupo ARI -->
                <div class="mb-4">
                    <h1 class="display-4 fw-bold text-primary mb-2">GRUPO ARI</h1>
                    <div class="border-top border-primary border-3 mx-auto" style="width: 100px; margin-top: 10px;"></div>
                </div>
                
                <!-- Descripción -->
                <p class="lead text-muted mb-4">
                    Sistema de gestión de clientes, vehículos y ofertas comerciales
                </p>
                
                <!-- Botón de inicio de sesión -->
                <div class="mt-5">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 py-3">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Iniciar Sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection