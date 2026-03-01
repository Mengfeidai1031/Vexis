@extends('layouts.app')
@section('title', 'Editar ' . $almacen->nombre . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Editar: {{ $almacen->nombre }}</h1>
    <a href="{{ route('almacenes.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width:750px;">
    <div class="vx-card"><div class="vx-card-body">
        <form action="{{ route('almacenes.update', $almacen) }}" method="POST">
            @csrf @method('PUT')
            <div style="display:grid;grid-template-columns:2fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="nombre">Nombre <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $almacen->nombre) }}" required>
                    @error('nombre')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="codigo">Código <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('codigo') is-invalid @enderror" id="codigo" name="codigo" value="{{ old('codigo', $almacen->codigo) }}" required style="font-family:var(--vx-font-mono);">
                    @error('codigo')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="domicilio">Domicilio <span class="required">*</span></label>
                <input type="text" class="vx-input @error('domicilio') is-invalid @enderror" id="domicilio" name="domicilio" value="{{ old('domicilio', $almacen->domicilio) }}" required>
                @error('domicilio')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="localidad">Localidad</label>
                    <input type="text" class="vx-input" id="localidad" name="localidad" value="{{ old('localidad', $almacen->localidad) }}">
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="codigo_postal">CP</label>
                    <input type="text" class="vx-input" id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal', $almacen->codigo_postal) }}" maxlength="5" style="max-width:100px;">
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="isla">Isla</label>
                    <select class="vx-select" id="isla" name="isla">
                        <option value="">Seleccionar...</option>
                        @foreach(\App\Models\Almacen::$islas as $isla)
                            <option value="{{ $isla }}" {{ old('isla', $almacen->isla) == $isla ? 'selected' : '' }}>{{ $isla }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="empresa_id">Empresa <span class="required">*</span></label>
                    <select class="vx-select" id="empresa_id" name="empresa_id" required>
                        @foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id', $almacen->empresa_id) == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach
                    </select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nueva</a>
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="centro_id">Centro <span class="required">*</span></label>
                    <select class="vx-select" id="centro_id" name="centro_id" required>
                        @foreach($centros as $c)<option value="{{ $c->id }}" {{ old('centro_id', $almacen->centro_id) == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach
                    </select><a href="{{ route('centros.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a>
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="telefono">Teléfono</label>
                    <input type="text" class="vx-input" id="telefono" name="telefono" value="{{ old('telefono', $almacen->telefono) }}" maxlength="12">
                </div>
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="observaciones">Observaciones</label>
                <textarea class="vx-input" id="observaciones" name="observaciones" rows="2">{{ old('observaciones', $almacen->observaciones) }}</textarea>
            </div>
            <div class="vx-form-group" style="padding-bottom:4px;">
                <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
                    <input type="checkbox" name="activo" value="1" {{ old('activo', $almacen->activo) ? 'checked' : '' }}> Almacén activo
                </label>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:8px;">
                <a href="{{ route('almacenes.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
