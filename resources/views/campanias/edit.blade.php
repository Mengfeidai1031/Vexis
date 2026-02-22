@extends('layouts.app')
@section('title', 'Editar Campaña - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Editar: {{ $campania->nombre }}</h1>
    <a href="{{ route('campanias.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width:750px;">
    <div class="vx-card"><div class="vx-card-body">
        <form action="{{ route('campanias.update', $campania) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="vx-form-group">
                <label class="vx-label" for="nombre">Nombre <span class="required">*</span></label>
                <input type="text" class="vx-input @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $campania->nombre) }}" required>
                @error('nombre')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="marca_id">Marca <span class="required">*</span></label>
                <select class="vx-select" id="marca_id" name="marca_id" required>
                    @foreach($marcas as $marca)
                        <option value="{{ $marca->id }}" {{ old('marca_id', $campania->marca_id) == $marca->id ? 'selected' : '' }}>{{ $marca->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="descripcion">Descripción</label>
                <textarea class="vx-input" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $campania->descripcion) }}</textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="fecha_inicio">Fecha Inicio</label>
                    <input type="date" class="vx-input" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', $campania->fecha_inicio?->format('Y-m-d')) }}">
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="fecha_fin">Fecha Fin</label>
                    <input type="date" class="vx-input" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin', $campania->fecha_fin?->format('Y-m-d')) }}">
                </div>
            </div>
            <div class="vx-form-group" style="padding-bottom:4px;">
                <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
                    <input type="checkbox" name="activa" value="1" {{ old('activa', $campania->activa) ? 'checked' : '' }}> Campaña activa
                </label>
            </div>
            @if($campania->fotos->count() > 0)
            <div class="vx-form-group">
                <label class="vx-label">Fotos actuales</label>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @foreach($campania->fotos as $foto)
                    <div style="position:relative;display:inline-block;">
                        <img src="{{ asset('storage/' . $foto->ruta) }}" alt="{{ $foto->nombre_original }}" style="height:80px;width:auto;border-radius:6px;object-fit:cover;">
                        <form action="{{ route('campanias.fotos.destroy', $foto) }}" method="POST" style="position:absolute;top:-6px;right:-6px;" onsubmit="return confirm('¿Eliminar foto?');">
                            @csrf @method('DELETE')
                            <button type="submit" style="width:20px;height:20px;border-radius:50%;background:var(--vx-danger);color:white;border:none;font-size:10px;cursor:pointer;display:flex;align-items:center;justify-content:center;"><i class="bi bi-x"></i></button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="vx-form-group">
                <label class="vx-label" for="fotos">Añadir más fotos</label>
                <input type="file" class="vx-input" id="fotos" name="fotos[]" multiple accept="image/*">
                <div class="vx-form-hint">JPG, PNG o WebP. Máximo 5MB por imagen.</div>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:8px;">
                <a href="{{ route('campanias.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
