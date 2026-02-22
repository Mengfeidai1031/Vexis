@extends('layouts.app')
@section('title', 'Crear Noticia - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Crear Nueva Noticia</h1>
    <a href="{{ route('noticias.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width:750px;">
    <div class="vx-card"><div class="vx-card-body">
        <form action="{{ route('noticias.store') }}" method="POST">
            @csrf
            <div class="vx-form-group">
                <label class="vx-label" for="titulo">Título <span class="required">*</span></label>
                <input type="text" class="vx-input @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                @error('titulo')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="contenido">Contenido <span class="required">*</span></label>
                <textarea class="vx-input @error('contenido') is-invalid @enderror" id="contenido" name="contenido" rows="8" required>{{ old('contenido') }}</textarea>
                @error('contenido')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="categoria">Categoría <span class="required">*</span></label>
                    <select class="vx-select @error('categoria') is-invalid @enderror" id="categoria" name="categoria" required>
                        @foreach(\App\Models\Noticia::$categorias as $k => $v)
                            <option value="{{ $k }}" {{ old('categoria') == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="vx-form-group" style="display:flex;align-items:flex-end;gap:20px;padding-bottom:12px;">
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
                        <input type="checkbox" name="destacada" value="1" {{ old('destacada') ? 'checked' : '' }}> Destacada
                    </label>
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
                        <input type="checkbox" name="publicada" value="1" {{ old('publicada', '1') ? 'checked' : '' }} checked> Publicada
                    </label>
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:8px;">
                <a href="{{ route('noticias.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Publicar</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
