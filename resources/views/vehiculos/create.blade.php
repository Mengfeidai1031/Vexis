@extends('layouts.app')
@section('title', 'Crear Vehículo - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Crear Nuevo Vehículo</h1>
    <a href="{{ route('vehiculos.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width: 800px;">
    <div class="vx-card"><div class="vx-card-body">
        <form action="{{ route('vehiculos.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="chasis">Chasis (VIN) <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('chasis') is-invalid @enderror" id="chasis" name="chasis" value="{{ old('chasis') }}" maxlength="17" placeholder="WVWZZZ1JZXW123456" required style="text-transform: uppercase; font-family: var(--vx-font-mono);">
                    @error('chasis')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    <div class="vx-form-hint">Exactamente 17 caracteres</div>
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="empresa_id">Empresa <span class="required">*</span></label>
                    <select class="vx-select @error('empresa_id') is-invalid @enderror" id="empresa_id" name="empresa_id" required>
                        <option value="">Seleccione una empresa</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>{{ $empresa->nombre }}</option>
                        @endforeach
                    </select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nueva</a>
                    @error('empresa_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="modelo">Modelo <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('modelo') is-invalid @enderror" id="modelo" name="modelo" value="{{ old('modelo') }}" placeholder="Ej: Qashqai, Clio..." required>
                    @error('modelo')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="version">Versión <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('version') is-invalid @enderror" id="version" name="version" value="{{ old('version') }}" placeholder="Ej: 1.3 DIG-T N-Connecta" required>
                    @error('version')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="color_externo">Color Externo <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('color_externo') is-invalid @enderror" id="color_externo" name="color_externo" value="{{ old('color_externo') }}" placeholder="Ej: Blanco Perlado" required>
                    @error('color_externo')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="color_interno">Color Interno <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('color_interno') is-invalid @enderror" id="color_interno" name="color_interno" value="{{ old('color_interno') }}" placeholder="Ej: Negro Titanio" required>
                    @error('color_interno')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <a href="{{ route('vehiculos.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Guardar</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
@push('scripts')
<script>document.getElementById('chasis').addEventListener('input',function(e){e.target.value=e.target.value.toUpperCase();});</script>
@endpush
