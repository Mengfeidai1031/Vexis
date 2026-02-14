@extends('layouts.app')

@section('title', 'Editar Centro')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Editar Centro: {{ $centro->nombre }}</h2>
            <a href="{{ route('centros.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('centros.update', $centro->id) }}" method="POST">
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
                            value="{{ old('nombre', $centro->nombre) }}" 
                            required
                        >
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Empresa -->
                    <div class="mb-3">
                        <label for="empresa_id" class="form-label">Empresa <span class="text-danger">*</span></label>
                        <select 
                            class="form-select @error('empresa_id') is-invalid @enderror" 
                            id="empresa_id" 
                            name="empresa_id" 
                            required
                        >
                            <option value="">Seleccione una empresa</option>
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}" {{ old('empresa_id', $centro->empresa_id) == $empresa->id ? 'selected' : '' }}>
                                    {{ $empresa->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('empresa_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dirección -->
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('direccion') is-invalid @enderror" 
                            id="direccion" 
                            name="direccion" 
                            value="{{ old('direccion', $centro->direccion) }}" 
                            required
                        >
                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Provincia -->
                    <div class="mb-3">
                        <label for="provincia" class="form-label">Provincia <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('provincia') is-invalid @enderror" 
                            id="provincia" 
                            name="provincia" 
                            value="{{ old('provincia', $centro->provincia) }}" 
                            required
                        >
                        @error('provincia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Municipio -->
                    <div class="mb-3">
                        <label for="municipio" class="form-label">Municipio <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('municipio') is-invalid @enderror" 
                            id="municipio" 
                            name="municipio" 
                            value="{{ old('municipio', $centro->municipio) }}" 
                            required
                        >
                        @error('municipio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('centros.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Centro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection