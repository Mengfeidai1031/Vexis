@extends('layouts.app')
@section('title', 'Editar ' . $empresa->nombre . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Editar: {{ $empresa->nombre }}</h1>
    <a href="{{ route('empresas.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width:750px;">
    <div class="vx-card"><div class="vx-card-body">
        <form action="{{ route('empresas.update', $empresa) }}" method="POST">
            @csrf @method('PUT')
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="nombre">Nombre <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $empresa->nombre) }}" required>
                    @error('nombre')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="abreviatura">Abreviatura <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('abreviatura') is-invalid @enderror" id="abreviatura" name="abreviatura" value="{{ old('abreviatura', $empresa->abreviatura) }}" maxlength="10" required style="max-width:150px;">
                    @error('abreviatura')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="cif">CIF <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('cif') is-invalid @enderror" id="cif" name="cif" value="{{ old('cif', $empresa->cif) }}" maxlength="10" required style="font-family:var(--vx-font-mono);">
                    @error('cif')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="telefono">Teléfono <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $empresa->telefono) }}" maxlength="12" required>
                    @error('telefono')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="domicilio">Domicilio <span class="required">*</span></label>
                <input type="text" class="vx-input @error('domicilio') is-invalid @enderror" id="domicilio" name="domicilio" value="{{ old('domicilio', $empresa->domicilio) }}" required>
                @error('domicilio')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="codigo_postal">Código Postal</label>
                <input type="text" class="vx-input @error('codigo_postal') is-invalid @enderror" id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal', $empresa->codigo_postal) }}" maxlength="5" style="max-width:150px;">
                @error('codigo_postal')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div style="display:flex;justify-content:flex-end;gap:8px;">
                <a href="{{ route('empresas.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
