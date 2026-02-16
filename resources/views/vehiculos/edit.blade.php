@extends('layouts.app')

@section('title', 'Editar Vehículo')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Editar Vehículo: {{ $vehiculo->descripcion_completa }}</h2>
            <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('vehiculos.update', $vehiculo->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Chasis -->
                        <div class="col-md-6 mb-3">
                            <label for="chasis" class="form-label">Número de Chasis (VIN) <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('chasis') is-invalid @enderror" 
                                id="chasis" 
                                name="chasis" 
                                value="{{ old('chasis', $vehiculo->chasis) }}" 
                                maxlength="17"
                                required
                                style="text-transform: uppercase;"
                            >
                            @error('chasis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Debe tener exactamente 17 caracteres</small>
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
                                    <option value="{{ $empresa->id }}" {{ old('empresa_id', $vehiculo->empresa_id) == $empresa->id ? 'selected' : '' }}>
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
                        <!-- Modelo -->
                        <div class="col-md-6 mb-3">
                            <label for="modelo" class="form-label">Modelo <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('modelo') is-invalid @enderror" 
                                id="modelo" 
                                name="modelo" 
                                value="{{ old('modelo', $vehiculo->modelo) }}" 
                                required
                            >
                            @error('modelo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Versión -->
                        <div class="col-md-6 mb-3">
                            <label for="version" class="form-label">Versión <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('version') is-invalid @enderror" 
                                id="version" 
                                name="version" 
                                value="{{ old('version', $vehiculo->version) }}" 
                                required
                            >
                            @error('version')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Color Externo -->
                        <div class="col-md-6 mb-3">
                            <label for="color_externo" class="form-label">Color Externo <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('color_externo') is-invalid @enderror" 
                                id="color_externo" 
                                name="color_externo" 
                                value="{{ old('color_externo', $vehiculo->color_externo) }}" 
                                required
                            >
                            @error('color_externo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Color Interno -->
                        <div class="col-md-6 mb-3">
                            <label for="color_interno" class="form-label">Color Interno <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('color_interno') is-invalid @enderror" 
                                id="color_interno" 
                                name="color_interno" 
                                value="{{ old('color_interno', $vehiculo->color_interno) }}" 
                                required
                            >
                            @error('color_interno')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Vehículo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Convertir chasis a mayúsculas automáticamente
    document.getElementById('chasis').addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });
</script>
@endpush