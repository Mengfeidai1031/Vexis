@extends('layouts.app')
@section('title', 'Crear Departamento - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Crear Nuevo Departamento</h1>
    <a href="{{ route('departamentos.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width: 600px;">
    <div class="vx-card">
        <div class="vx-card-body">
            <form action="{{ route('departamentos.store') }}" method="POST">
                @csrf
                <div class="vx-form-group">
                    <label class="vx-label" for="nombre">Nombre <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                    @error('nombre')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="abreviatura">Abreviatura <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('abreviatura') is-invalid @enderror" id="abreviatura" name="abreviatura" value="{{ old('abreviatura') }}" maxlength="10" required>
                    @error('abreviatura')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    <div class="vx-form-hint">Máximo 10 caracteres</div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 8px;">
                    <a href="{{ route('departamentos.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
