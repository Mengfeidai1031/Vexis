@extends('layouts.app')
@section('title', 'Nueva Incidencia - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Nueva Incidencia</h1><a href="{{ route('incidencias.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:700px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('incidencias.store') }}" method="POST" enctype="multipart/form-data">@csrf

        {{-- Emisor (automático, no editable) --}}
        <div class="vx-form-group">
            <label class="vx-label">Emisor</label>
            <input type="text" class="vx-input" value="{{ Auth::user()->nombre_completo }}" disabled style="background:var(--vx-bg);cursor:not-allowed;">
            <p style="font-size:10px;color:var(--vx-text-muted);margin:4px 0 0;">Se asigna automáticamente al usuario actual.</p>
        </div>

        <div class="vx-form-group">
            <label class="vx-label">Título <span class="required">*</span></label>
            <input type="text" class="vx-input" name="titulo" value="{{ old('titulo') }}" required placeholder="Resumen breve del problema...">
        </div>
        <div class="vx-form-group">
            <label class="vx-label">Descripción del usuario <span class="required">*</span></label>
            <textarea class="vx-input" name="descripcion" rows="5" required placeholder="Describa el problema con todo el detalle posible...">{{ old('descripcion') }}</textarea>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group">
                <label class="vx-label">Prioridad <span class="required">*</span></label>
                <select class="vx-select" name="prioridad" required>
                    @foreach(\App\Models\Incidencia::$prioridades as $k => $v)
                    <option value="{{ $k }}" {{ old('prioridad', 'media') == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="vx-form-group">
                <label class="vx-label">Estado <span class="required">*</span></label>
                <select class="vx-select" name="estado" required>
                    @foreach(\App\Models\Incidencia::$estados as $k => $v)
                    <option value="{{ $k }}" {{ old('estado', 'abierta') == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="vx-form-group">
                <label class="vx-label">Técnico asignado</label>
                <select class="vx-select" name="tecnico_id">
                    <option value="">Sin asignar</option>
                    @foreach($tecnicos as $t)
                    <option value="{{ $t->id }}" {{ old('tecnico_id') == $t->id ? 'selected' : '' }}>{{ $t->nombre_completo }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Archivos del usuario --}}
        <div class="vx-form-group">
            <label class="vx-label"><i class="bi bi-person"></i> Archivos del usuario</label>
            <input type="file" class="vx-input" name="archivos_usuario[]" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip">
            <p style="font-size:10px;color:var(--vx-text-muted);margin:4px 0 0;">Máximo 10MB por archivo. Imágenes, PDFs, documentos y archivos comprimidos.</p>
        </div>

        {{-- Archivos del técnico --}}
        <div class="vx-form-group">
            <label class="vx-label"><i class="bi bi-tools"></i> Archivos del técnico</label>
            <input type="file" class="vx-input" name="archivos_tecnico[]" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip">
            <p style="font-size:10px;color:var(--vx-text-muted);margin:4px 0 0;">Documentación técnica, capturas, informes, etc.</p>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;">
            <a href="{{ route('incidencias.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
            <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-exclamation-triangle"></i> Crear Incidencia</button>
        </div>
    </form>
</div></div></div>
@endsection
