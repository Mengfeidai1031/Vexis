@extends('layouts.app')
@section('title', 'Editar ' . $cliente->nombre_completo . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Editar: {{ $cliente->nombre_completo }}</h1>
    <a href="{{ route('clientes.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width: 750px;">
    <div class="vx-card"><div class="vx-card-body">
        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
            @csrf @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="nombre">Nombre <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required>
                    @error('nombre')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="apellidos">Apellidos <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('apellidos') is-invalid @enderror" id="apellidos" name="apellidos" value="{{ old('apellidos', $cliente->apellidos) }}" required>
                    @error('apellidos')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="dni">DNI</label>
                    <input type="text" class="vx-input @error('dni') is-invalid @enderror" id="dni" name="dni" value="{{ old('dni', $cliente->dni) }}" maxlength="10">
                    @error('dni')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="empresa_id">Empresa <span class="required">*</span></label>
                    <select class="vx-select @error('empresa_id') is-invalid @enderror" id="empresa_id" name="empresa_id" required>
                        <option value="">Seleccione</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}" {{ old('empresa_id', $cliente->empresa_id) == $empresa->id ? 'selected' : '' }}>{{ $empresa->nombre }}</option>
                        @endforeach
                    </select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nueva</a>
                    @error('empresa_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="email">Correo Electrónico <span class="required">*</span></label>
                    <input type="email" class="vx-input @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $cliente->email) }}" required>
                    @error('email')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="telefono">Teléfono <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" maxlength="20" required>
                    @error('telefono')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="domicilio">Domicilio <span class="required">*</span></label>
                <input type="text" class="vx-input @error('domicilio') is-invalid @enderror" id="domicilio" name="domicilio" value="{{ old('domicilio', $cliente->domicilio) }}" required>
                @error('domicilio')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="codigo_postal">Código Postal <span class="required">*</span></label>
                <input type="text" class="vx-input @error('codigo_postal') is-invalid @enderror" id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal', $cliente->codigo_postal) }}" maxlength="5" required style="max-width: 150px;">
                @error('codigo_postal')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                <div class="vx-form-hint">Exactamente 5 dígitos</div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <a href="{{ route('clientes.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
