@extends('layouts.app')
@section('title', 'Editar Perfil - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Editar Perfil</h1>
    <a href="{{ route('dashboard') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width: 600px;">
    {{-- Info personal --}}
    <div class="vx-card" style="margin-bottom: 20px;">
        <div class="vx-card-header"><h4>Información Personal</h4></div>
        <div class="vx-card-body">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf @method('PUT')
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                    <div class="vx-form-group">
                        <label class="vx-label" for="nombre">Nombre <span class="required">*</span></label>
                        <input type="text" class="vx-input @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $user->nombre) }}" required>
                        @error('nombre')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="apellidos">Apellidos <span class="required">*</span></label>
                        <input type="text" class="vx-input @error('apellidos') is-invalid @enderror" id="apellidos" name="apellidos" value="{{ old('apellidos', $user->apellidos) }}" required>
                        @error('apellidos')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="vx-form-group">
                    <label class="vx-label">Email</label>
                    <input type="email" class="vx-input" value="{{ $user->email }}" disabled style="opacity: 0.6;">
                    <div class="vx-form-hint">El email no se puede cambiar</div>
                </div>
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Cambiar contraseña --}}
    <div class="vx-card">
        <div class="vx-card-header"><h4>Cambiar Contraseña</h4></div>
        <div class="vx-card-body">
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf @method('PUT')
                <div class="vx-form-group">
                    <label class="vx-label" for="current_password">Contraseña Actual <span class="required">*</span></label>
                    <input type="password" class="vx-input @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                    @error('current_password')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                    <div class="vx-form-group">
                        <label class="vx-label" for="password">Nueva Contraseña <span class="required">*</span></label>
                        <input type="password" class="vx-input @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="password_confirmation">Confirmar <span class="required">*</span></label>
                        <input type="password" class="vx-input" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="vx-btn vx-btn-warning"><i class="bi bi-key"></i> Cambiar Contraseña</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
