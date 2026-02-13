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
                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                <p><strong>Empresa:</strong> {{ Auth::user()->empresa->nombre }}</p>
                <p><strong>Departamento:</strong> {{ Auth::user()->departamento->nombre }}</p>
                <p><strong>Centro:</strong> {{ Auth::user()->centro->nombre }}</p>
                
                <hr>
                
                <p>Has iniciado sesión correctamente. Desde aquí podrás acceder a todas las funcionalidades del sistema.</p>
            </div>
        </div>
    </div>
</div>
@endsection