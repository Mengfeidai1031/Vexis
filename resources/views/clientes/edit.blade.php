@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Editar Cliente: {{ $cliente->nombre_completo }}</h2>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Nombre -->
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('nombre') is-invalid @enderror" 
                                id="nombre" 
                                name="nombre" 
                                value="{{ old('nombre', $cliente->nombre) }}" 
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
                                value="{{ old('apellidos', $cliente->apellidos) }}" 
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
                            <label for="dni" class="form-label">DNI <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('dni') is-invalid @enderror" 
                                id="dni" 
                                name="dni" 
                                value="{{ old('dni', $cliente->dni) }}" 
                                maxlength="10"
                                required
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
                                    <option value="{{ $empresa->id }}" {{ old('empresa_id', $cliente->empresa_id) == $empresa->id ? 'selected' : '' }}>
                                        {{ $empresa->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('empresa_id')
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
                            value="{{ old('domicilio', $cliente->domicilio) }}" 
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
                            value="{{ old('codigo_postal', $cliente->codigo_postal) }}" 
                            maxlength="5"
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
                        <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection