@extends('layouts.app')
@section('title', 'Nuevo permiso - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Nuevo permiso</h1>
    <a href="{{ route('permisos.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<div style="max-width:600px;">
    <div class="vx-card"><div class="vx-card-body">
        <form action="{{ route('permisos.store') }}" method="POST">
            @csrf
            <div class="vx-form-group">
                <label class="vx-label" for="name">Nombre del permiso <span class="required">*</span></label>
                <input type="text" class="vx-input @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="ver vehículos" required>
                @error('name')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                <div class="vx-form-hint">Convención: verbo + recurso (ej. "ver reportes", "crear reportes"). Sólo minúsculas, espacios y guiones.</div>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:8px;">
                <a href="{{ route('permisos.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Crear</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
