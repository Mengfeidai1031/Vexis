@extends('layouts.app')
@section('title', 'Nuevo Modelo - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Añadir al Catálogo</h1><a href="{{ route('catalogo-precios.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:650px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('catalogo-precios.store') }}" method="POST">@csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Marca <span class="required">*</span></label><select class="vx-select @error('marca_id') is-invalid @enderror" name="marca_id" required>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ old('marca_id') == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Modelo <span class="required">*</span></label><input type="text" class="vx-input" name="modelo" value="{{ old('modelo') }}" required placeholder="Ej: Qashqai"></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Versión</label><input type="text" class="vx-input" name="version" value="{{ old('version') }}" placeholder="Ej: Acenta 1.3 DIG-T MHEV"></div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Combustible</label><select class="vx-select" name="combustible"><option value="">—</option>@foreach(\App\Models\CatalogoPrecio::$combustibles as $c)<option value="{{ $c }}" {{ old('combustible') == $c ? 'selected' : '' }}>{{ $c }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Potencia (CV)</label><input type="number" class="vx-input" name="potencia_cv" value="{{ old('potencia_cv') }}" min="0"></div>
            <div class="vx-form-group"><label class="vx-label">Año modelo</label><input type="number" class="vx-input" name="anio_modelo" value="{{ old('anio_modelo', date('Y')) }}" min="2020" max="2030"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Precio Base (€) <span class="required">*</span></label><input type="number" class="vx-input" name="precio_base" value="{{ old('precio_base') }}" step="0.01" min="0" required style="font-family:var(--vx-font-mono);"></div>
            <div class="vx-form-group"><label class="vx-label">Precio Oferta (€)</label><input type="number" class="vx-input" name="precio_oferta" value="{{ old('precio_oferta') }}" step="0.01" min="0" style="font-family:var(--vx-font-mono);color:var(--vx-success);"></div>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:8px;"><a href="{{ route('catalogo-precios.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Guardar</button></div>
    </form>
</div></div></div>
@endsection
