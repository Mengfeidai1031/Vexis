@extends('layouts.app')
@section('title', 'Crear Campaña - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Crear Nueva Campaña</h1>
    <a href="{{ route('campanias.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width:750px;">
    <div class="vx-card"><div class="vx-card-body">
        <form action="{{ route('campanias.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="vx-form-group">
                <label class="vx-label" for="nombre">Nombre <span class="required">*</span></label>
                <input type="text" class="vx-input @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                @error('nombre')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="marca_id">Marca <span class="required">*</span></label>
                <select class="vx-select @error('marca_id') is-invalid @enderror" id="marca_id" name="marca_id" required>
                    <option value="">Seleccionar marca...</option>
                    @foreach($marcas as $marca)
                        <option value="{{ $marca->id }}" {{ old('marca_id') == $marca->id ? 'selected' : '' }}>{{ $marca->nombre }}</option>
                    @endforeach
                </select>
                @error('marca_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="descripcion">Descripción</label>
                <textarea class="vx-input" id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="fecha_inicio">Fecha Inicio</label>
                    <input type="date" class="vx-input" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}">
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="fecha_fin">Fecha Fin</label>
                    <input type="date" class="vx-input" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}">
                </div>
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="fotos">Fotos de publicidad</label>
                <input type="file" class="vx-input" id="fotos" name="fotos[]" multiple accept="image/*">
                <div class="vx-form-hint">JPG, PNG o WebP. Máximo 5MB por imagen. Puedes seleccionar varias.</div>
                @error('fotos.*')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div id="preview" style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;"></div>
            <div style="display:flex;justify-content:flex-end;gap:8px;">
                <a href="{{ route('campanias.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Crear Campaña</button>
            </div>
        </form>
    </div></div>
</div>
@push('scripts')
<script>
document.getElementById('fotos').addEventListener('change', function() {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    [...this.files].forEach(f => {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(f);
        img.style.cssText = 'height:80px;width:auto;border-radius:6px;object-fit:cover;';
        preview.appendChild(img);
    });
});
</script>
@endpush
@endsection
