@extends('layouts.app')
@section('title', 'Crear Tipo de Cliente - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Crear Tipo de Cliente</h1>
    <a href="{{ route('tipos-cliente.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width:600px;">
    <div class="vx-card"><div class="vx-card-body">
        <form action="{{ route('tipos-cliente.store') }}" method="POST">
            @csrf
            <div class="vx-form-group">
                <label class="vx-label">Nombre <span class="required">*</span></label>
                <input type="text" name="nombre" class="vx-input @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required maxlength="100">
                @error('nombre')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="vx-form-group">
                <label class="vx-label">Descripción</label>
                <input type="text" name="descripcion" class="vx-input" value="{{ old('descripcion') }}" maxlength="255">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label">Color</label>
                    <input type="color" name="color" class="vx-input" value="{{ old('color', '#33AADD') }}" style="height:42px;padding:4px;">
                </div>
                <div class="vx-form-group">
                    <label class="vx-label">Estado</label>
                    <label style="display:flex;align-items:center;gap:8px;margin-top:8px;">
                        <input type="checkbox" name="activo" value="1" {{ old('activo', '1') ? 'checked' : '' }}> Activo
                    </label>
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:8px;">
                <a href="{{ route('tipos-cliente.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Guardar</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
