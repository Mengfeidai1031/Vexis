@extends('layouts.app')
@section('title', 'Editar Incidencia - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Editar: {{ $incidencia->codigo_incidencia }}</h1><a href="{{ route('incidencias.show', $incidencia) }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:700px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('incidencias.update', $incidencia) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')

        {{-- Emisor (no editable) --}}
        <div class="vx-form-group">
            <label class="vx-label">Emisor</label>
            <input type="text" class="vx-input" value="{{ $incidencia->usuario?->nombre_completo ?? '—' }}" disabled style="background:var(--vx-bg);cursor:not-allowed;">
        </div>

        <div class="vx-form-group">
            <label class="vx-label">Título <span class="required">*</span></label>
            <input type="text" class="vx-input" name="titulo" value="{{ old('titulo', $incidencia->titulo) }}" required>
        </div>
        <div class="vx-form-group">
            <label class="vx-label">Descripción del usuario <span class="required">*</span></label>
            <textarea class="vx-input" name="descripcion" rows="5" required>{{ old('descripcion', $incidencia->descripcion) }}</textarea>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group">
                <label class="vx-label">Prioridad <span class="required">*</span></label>
                <select class="vx-select" name="prioridad" required>
                    @foreach(\App\Models\Incidencia::$prioridades as $k => $v)
                    <option value="{{ $k }}" {{ old('prioridad', $incidencia->prioridad) == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="vx-form-group">
                <label class="vx-label">Estado <span class="required">*</span></label>
                <select class="vx-select" name="estado" required>
                    @foreach(\App\Models\Incidencia::$estados as $k => $v)
                    <option value="{{ $k }}" {{ old('estado', $incidencia->estado) == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="vx-form-group">
                <label class="vx-label">Técnico asignado</label>
                <select class="vx-select" name="tecnico_id">
                    <option value="">Sin asignar</option>
                    @foreach($tecnicos as $t)
                    <option value="{{ $t->id }}" {{ old('tecnico_id', $incidencia->tecnico_id) == $t->id ? 'selected' : '' }}>{{ $t->nombre_completo }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Comentario técnico --}}
        <div class="vx-form-group">
            <label class="vx-label"><i class="bi bi-chat-dots"></i> Comentario del técnico</label>
            <textarea class="vx-input" name="comentario_tecnico" rows="3" placeholder="Notas del técnico sobre la resolución...">{{ old('comentario_tecnico', $incidencia->comentario_tecnico) }}</textarea>
        </div>

        {{-- Archivos del usuario --}}
        <div class="vx-form-group">
            <label class="vx-label"><i class="bi bi-person"></i> Adjuntar archivos del usuario</label>
            <input type="file" class="vx-input" name="archivos_usuario[]" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip">
        </div>

        {{-- Archivos del técnico --}}
        <div class="vx-form-group">
            <label class="vx-label"><i class="bi bi-tools"></i> Adjuntar archivos del técnico</label>
            <input type="file" class="vx-input" name="archivos_tecnico[]" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip">
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;">
            <a href="{{ route('incidencias.show', $incidencia) }}" class="vx-btn vx-btn-secondary">Cancelar</a>
            <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button>
        </div>
    </form>
</div></div></div>
@endsection
