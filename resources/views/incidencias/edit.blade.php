@extends('layouts.app')
@section('title', 'Editar Incidencia - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Editar: {{ $incidencia->codigo_incidencia }}</h1><a href="{{ route('incidencias.show', $incidencia) }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>

<form action="{{ route('incidencias.update', $incidencia) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')

{{-- 2 columnas: Usuario | Técnico --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">

    {{-- COLUMNA IZQUIERDA — Usuario --}}
    <div style="display:flex;flex-direction:column;gap:16px;">
        <div class="vx-card">
            <div class="vx-card-header" style="background:rgba(51,170,221,0.06);"><h4><i class="bi bi-person" style="color:var(--vx-primary);"></i> Datos del usuario</h4></div>
            <div class="vx-card-body">
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
                    <textarea class="vx-input" name="descripcion" rows="6" required>{{ old('descripcion', $incidencia->descripcion) }}</textarea>
                </div>
                <div class="vx-form-group">
                    <label class="vx-label">Prioridad <span class="required">*</span></label>
                    <select class="vx-select" name="prioridad" required>
                        @foreach(\App\Models\Incidencia::$prioridades as $k => $v)
                        <option value="{{ $k }}" {{ old('prioridad', $incidencia->prioridad) == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="vx-form-group" style="margin-bottom:0;">
                    <label class="vx-label"><i class="bi bi-paperclip"></i> Adjuntar archivos del usuario</label>
                    <input type="file" class="vx-input" name="archivos_usuario[]" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip">
                </div>
            </div>
        </div>
    </div>

    {{-- COLUMNA DERECHA — Técnico --}}
    <div style="display:flex;flex-direction:column;gap:16px;">
        <div class="vx-card">
            <div class="vx-card-header" style="background:rgba(142,68,173,0.06);"><h4><i class="bi bi-person-gear" style="color:#8e44ad;"></i> Datos del técnico</h4></div>
            <div class="vx-card-body">
                <div class="vx-form-group">
                    <label class="vx-label">Técnico asignado</label>
                    <select class="vx-select" name="tecnico_id">
                        <option value="">Sin asignar</option>
                        @foreach($tecnicos as $t)
                        <option value="{{ $t->id }}" {{ old('tecnico_id', $incidencia->tecnico_id) == $t->id ? 'selected' : '' }}>{{ $t->nombre_completo }}</option>
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
                    <label class="vx-label"> Comentario del técnico</label>
                    <textarea class="vx-input" name="comentario_tecnico" rows="6" placeholder="Notas del técnico sobre la resolución...">{{ old('comentario_tecnico', $incidencia->comentario_tecnico) }}</textarea>
                </div>
                <div class="vx-form-group" style="margin-bottom:0;">
                    <label class="vx-label"><i class="bi bi-paperclip"></i> Adjuntar archivos del técnico</label>
                    <input type="file" class="vx-input" name="archivos_tecnico[]" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip">
                </div>
            </div>
        </div>
    </div>
</div>

<div style="display:flex;justify-content:flex-end;gap:8px;">
    <a href="{{ route('incidencias.show', $incidencia) }}" class="vx-btn vx-btn-secondary">Cancelar</a>
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button>
</div>
</form>
@endsection
