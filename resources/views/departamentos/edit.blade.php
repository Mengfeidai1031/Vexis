@extends('layouts.app')

@section('title', 'Editar Departamento')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Editar Departamento: {{ $departamento->nombre }}</h2>
            <a href="{{ route('departamentos.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('departamentos.update', $departamento->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('nombre') is-invalid @enderror" 
                            id="nombre" 
                            name="nombre" 
                            value="{{ old('nombre', $departamento->nombre) }}" 
                            required
                        >
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Abreviatura -->
                    <div class="mb-3">
                        <label for="abreviatura" class="form-label">Abreviatura <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('abreviatura') is-invalid @enderror" 
                            id="abreviatura" 
                            name="abreviatura" 
                            value="{{ old('abreviatura', $departamento->abreviatura) }}" 
                            maxlength="10"
                            required
                        >
                        @error('abreviatura')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Máximo 10 caracteres</small>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('departamentos.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Departamento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection