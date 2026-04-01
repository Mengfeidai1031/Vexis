@extends('layouts.app')
@section('title', 'Nueva Incidencia - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Nueva Incidencia</h1><a href="{{ route('incidencias.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:700px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('incidencias.store') }}" method="POST" enctype="multipart/form-data">@csrf
        <div class="vx-form-group">
            <label class="vx-label">Título <span class="required">*</span></label>
            <input type="text" class="vx-input" name="titulo" value="{{ old('titulo') }}" required placeholder="Resumen breve del problema...">
        </div>
        <div class="vx-form-group">
            <label class="vx-label">Descripción <span class="required">*</span></label>
            <textarea class="vx-input" name="descripcion" rows="5" required placeholder="Describa el problema con todo el detalle posible...">{{ old('descripcion') }}</textarea>
        </div>
        <div class="vx-form-group">
            <label class="vx-label">Prioridad <span class="required">*</span></label>
            <select class="vx-select" name="prioridad" required>
                @foreach(\App\Models\Incidencia::$prioridades as $k => $v)
                <option value="{{ $k }}" {{ old('prioridad', 'media') == $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="vx-form-group">
            <label class="vx-label">Archivos adjuntos</label>
            <input type="file" class="vx-input" name="archivos[]" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip">
            <p style="font-size:10px;color:var(--vx-text-muted);margin:4px 0 0;">Máximo 10MB por archivo. Se permiten imágenes, PDFs, documentos y archivos comprimidos.</p>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:8px;">
            <a href="{{ route('incidencias.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
            <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-exclamation-triangle"></i> Crear Incidencia</button>
        </div>
    </form>
</div></div></div>
@endsection
