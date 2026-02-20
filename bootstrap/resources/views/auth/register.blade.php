@extends('layouts.app')
@section('title', 'Registro - VEXIS')
@section('content')
<div style="min-height: calc(100vh - var(--vx-navbar-height) - 60px); display: flex; align-items: center; justify-content: center;">
    <div style="width: 100%; max-width: 420px; padding: 20px;">
        <div style="text-align: center; margin-bottom: 28px;">
            <img src="{{ asset('img/vexis-logo.png') }}" alt="VEXIS" style="width: 130px; margin-bottom: 12px;">
            <h2 style="font-size: 20px; font-weight: 600; color: var(--vx-text);">Crear Cuenta</h2>
            <p style="font-size: 13px; color: var(--vx-text-muted);">Regístrate para acceder como cliente</p>
        </div>
        <div class="vx-card">
            <div class="vx-card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 12px;">
                        <div class="vx-form-group">
                            <label class="vx-label" for="nombre">Nombre <span class="required">*</span></label>
                            <input type="text" class="vx-input @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required autofocus>
                            @error('nombre')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="apellidos">Apellidos <span class="required">*</span></label>
                            <input type="text" class="vx-input @error('apellidos') is-invalid @enderror" id="apellidos" name="apellidos" value="{{ old('apellidos') }}" required>
                            @error('apellidos')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="email">Correo Electrónico <span class="required">*</span></label>
                        <input type="email" class="vx-input @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="password">Contraseña <span class="required">*</span></label>
                        <input type="password" class="vx-input @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="password_confirmation">Confirmar Contraseña <span class="required">*</span></label>
                        <input type="password" class="vx-input" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="vx-btn vx-btn-primary" style="width: 100%; justify-content: center; padding: 12px; margin-top: 4px;">
                        <i class="bi bi-person-plus"></i> Registrarse
                    </button>
                </form>
                <div style="text-align: center; margin-top: 16px;">
                    <span style="font-size: 13px; color: var(--vx-text-muted);">¿Ya tienes cuenta? <a href="{{ route('login') }}" style="color: var(--vx-primary); font-weight: 600;">Iniciar Sesión</a></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
