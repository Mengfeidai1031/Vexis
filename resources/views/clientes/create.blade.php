@extends('layouts.app')

@section('title', 'Crear Cliente')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Crear Nuevo Cliente</h2>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('clientes.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Nombre -->
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('nombre') is-invalid @enderror" 
                                id="nombre" 
                                name="nombre" 
                                value="{{ old('nombre') }}" 
                                required
                            >
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Apellidos -->
                        <div class="col-md-6 mb-3">
                            <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('apellidos') is-invalid @enderror" 
                                id="apellidos" 
                                name="apellidos" 
                                value="{{ old('apellidos') }}" 
                                required
                            >
                            @error('apellidos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- DNI -->
                        <div class="col-md-6 mb-3">
                            <label for="dni" class="form-label">DNI</label>
                            <input 
                                type="text" 
                                class="form-control @error('dni') is-invalid @enderror" 
                                id="dni" 
                                name="dni" 
                                value="{{ old('dni') }}" 
                                maxlength="10"
                                placeholder="12345678A"
                            >
                            @error('dni')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Empresa -->
                        <div class="col-md-6 mb-3">
                            <label for="empresa_id" class="form-label">Empresa <span class="text-danger">*</span></label>
                            <select 
                                class="form-select @error('empresa_id') is-invalid @enderror" 
                                id="empresa_id" 
                                name="empresa_id" 
                                required
                            >
                                <option value="">Seleccione una empresa</option>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                        {{ $empresa->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('empresa_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                            <input 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                placeholder="cliente@ejemplo.com"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('telefono') is-invalid @enderror" 
                                id="telefono" 
                                name="telefono" 
                                value="{{ old('telefono') }}" 
                                placeholder="928123456"
                                maxlength="20"
                                required
                            >
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Domicilio -->
                    <div class="mb-3">
                        <label for="domicilio" class="form-label">Domicilio <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('domicilio') is-invalid @enderror" 
                            id="domicilio" 
                            name="domicilio" 
                            value="{{ old('domicilio') }}" 
                            placeholder="Calle, número, piso..."
                            required
                        >
                        @error('domicilio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Código Postal -->
                    <div class="mb-3">
                        <label for="codigo_postal" class="form-label">Código Postal <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('codigo_postal') is-invalid @enderror" 
                            id="codigo_postal" 
                            name="codigo_postal" 
                            value="{{ old('codigo_postal') }}" 
                            maxlength="5"
                            placeholder="35001"
                            required
                        >
                        @error('codigo_postal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Debe tener exactamente 5 dígitos</small>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection