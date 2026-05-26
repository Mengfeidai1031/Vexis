@extends('layouts.app')

@section('title', 'Iniciar Sesión - VEXIS')

@push('styles')
<style>
    .vx-login-wrapper { display: flex; align-items: center; justify-content: center; min-height: calc(100vh - var(--vx-navbar-height) - 60px); padding: 40px 16px; }
    .vx-login-card { width: 100%; max-width: 420px; }
    .vx-login-logo { text-align: center; margin-bottom: 24px; }
    .vx-login-logo img { height: 40px; }
    .vx-login-title { font-size: 20px; font-weight: 800; text-align: center; margin-bottom: 4px; color: var(--vx-text); }
    .vx-login-subtitle { font-size: 13px; text-align: center; color: var(--vx-text-muted); margin-bottom: 24px; }
</style>
@endpush

@section('content')
<x-test-users-modal />

<div class="vx-login-wrapper">
    <div class="vx-login-card">
        <div class="vx-login-logo">
            <img src="{{ asset('img/vexis-logo.png') }}" alt="VEXIS">
        </div>

        <div class="vx-card">
            <div class="vx-card-body">
                <h1 class="vx-login-title">Iniciar Sesión</h1>
                <p class="vx-login-subtitle">Accede al sistema de gestión VEXIS</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="vx-form-group">
                        <label class="vx-label" for="email">Correo Electrónico</label>
                        <input
                            type="email"
                            class="vx-input @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="tu@email.com"
                        >
                        @error('email')
                            <div class="vx-invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="vx-form-group">
                        <label class="vx-label" for="password">Contraseña</label>
                        <input
                            type="password"
                            class="vx-input @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            required
                            placeholder="••••••••"
                        >
                        @error('password')
                            <div class="vx-invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="vx-form-group">
                        <label class="vx-checkbox">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>Recordarme</span>
                        </label>
                    </div>

                    <button type="submit" class="vx-btn vx-btn-primary vx-btn-lg" style="width: 100%; justify-content: center;">
                        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                    </button>
                </form>

                <div style="text-align: center; margin-top: 16px;">
                    <span style="font-size: 13px; color: var(--vx-text-muted);">¿No tienes cuenta? <a href="{{ route('register') }}" style="color: var(--vx-primary); font-weight: 600;">Registrarse</a></span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
