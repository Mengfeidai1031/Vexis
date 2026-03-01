@extends('layouts.app')
@section('title', 'Crear Centro - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Crear Nuevo Centro</h1>
    <a href="{{ route('centros.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width: 700px;">
    <div class="vx-card">
        <div class="vx-card-body">
            <form action="{{ route('centros.store') }}" method="POST">
                @csrf
                <div class="vx-form-group">
                    <label class="vx-label" for="nombre">Nombre <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                    @error('nombre')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="empresa_id">Empresa <span class="required">*</span></label>
                    <select class="vx-select @error('empresa_id') is-invalid @enderror" id="empresa_id" name="empresa_id" required>
                        <option value="">Seleccione una empresa</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>{{ $empresa->nombre }}</option>
                        @endforeach
                    </select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nueva</a>
                    @error('empresa_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="direccion">Dirección <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion') }}" required>
                    @error('direccion')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                    <div class="vx-form-group">
                        <label class="vx-label" for="provincia">Provincia <span class="required">*</span></label>
                        <input type="text" class="vx-input @error('provincia') is-invalid @enderror" id="provincia" name="provincia" value="{{ old('provincia') }}" required>
                        @error('provincia')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="municipio">Municipio <span class="required">*</span></label>
                        <input type="text" class="vx-input @error('municipio') is-invalid @enderror" id="municipio" name="municipio" value="{{ old('municipio') }}" required>
                        @error('municipio')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 8px;">
                    <a href="{{ route('centros.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
