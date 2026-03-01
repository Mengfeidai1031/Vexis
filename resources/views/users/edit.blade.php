@extends('layouts.app')

@section('title', 'Editar ' . $user->nombre_completo . ' - VEXIS')

@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Editar Usuario: {{ $user->nombre_completo }}</h1>
    <a href="{{ route('users.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<div style="max-width: 800px;">
    <div class="vx-card">
        <div class="vx-card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

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

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                    <div class="vx-form-group">
                        <label class="vx-label" for="empresa_id">Empresa <span class="required">*</span></label>
                        <select class="vx-select @error('empresa_id') is-invalid @enderror" id="empresa_id" name="empresa_id" required>
                            <option value="">Seleccione una empresa</option>
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}" {{ old('empresa_id', $user->empresa_id) == $empresa->id ? 'selected' : '' }}>{{ $empresa->nombre }}</option>
                            @endforeach
                        </select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nueva</a>
                        @error('empresa_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="vx-form-group">
                        <label class="vx-label" for="departamento_id">Departamento <span class="required">*</span></label>
                        <select class="vx-select @error('departamento_id') is-invalid @enderror" id="departamento_id" name="departamento_id" required>
                            <option value="">Seleccione un departamento</option>
                            @foreach($departamentos as $departamento)
                                <option value="{{ $departamento->id }}" {{ old('departamento_id', $user->departamento_id) == $departamento->id ? 'selected' : '' }}>{{ $departamento->nombre }}</option>
                            @endforeach
                        </select><a href="{{ route('departamentos.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a>
                        @error('departamento_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="vx-form-group">
                    <label class="vx-label" for="centro_id">Centro <span class="required">*</span></label>
                    <select class="vx-select @error('centro_id') is-invalid @enderror" id="centro_id" name="centro_id" required>
                        <option value="">Seleccione un centro</option>
                        @foreach($centros as $centro)
                            <option value="{{ $centro->id }}" data-empresa="{{ $centro->empresa_id }}" {{ old('centro_id', $user->centro_id) == $centro->id ? 'selected' : '' }}>{{ $centro->nombre }} ({{ $centro->empresa->nombre }})</option>
                        @endforeach
                    </select><a href="{{ route('centros.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a>
                    @error('centro_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="vx-form-group">
                    <label class="vx-label" for="email">Email <span class="required">*</span></label>
                    <input type="email" class="vx-input @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                    <div class="vx-form-group">
                        <label class="vx-label" for="telefono">Teléfono</label>
                        <input type="text" class="vx-input @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $user->telefono) }}" maxlength="12">
                        @error('telefono')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="vx-form-group">
                        <label class="vx-label" for="extension">Extensión</label>
                        <input type="text" class="vx-input @error('extension') is-invalid @enderror" id="extension" name="extension" value="{{ old('extension', $user->extension) }}" maxlength="10">
                        @error('extension')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Roles --}}
                <div class="vx-form-group">
                    <label class="vx-label">Roles</label>
                    <p class="vx-form-hint" style="margin-bottom: 8px;">Seleccione uno o varios roles para el usuario</p>
                    @php $userRoleIds = $user->roles->pluck('id')->toArray(); @endphp
                    <div style="display: flex; flex-wrap: wrap; gap: 8px 16px;">
                        @foreach($roles as $role)
                            <label class="vx-checkbox">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ in_array($role->id, old('roles', $userRoleIds)) ? 'checked' : '' }}>
                                <span>{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('roles')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Restricciones --}}
                @include('partials.restrictions-form')

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                    <div class="vx-form-group">
                        <label class="vx-label" for="password">Nueva Contraseña</label>
                        <input type="password" class="vx-input @error('password') is-invalid @enderror" id="password" name="password" placeholder="Dejar en blanco para no cambiar">
                        @error('password')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                        <div class="vx-form-hint">Mínimo 6 caracteres</div>
                    </div>

                    <div class="vx-form-group">
                        <label class="vx-label" for="password_confirmation">Confirmar Contraseña</label>
                        <input type="password" class="vx-input" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 8px;">
                    <a href="{{ route('users.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('empresa_id').addEventListener('change', function() {
        const empresaId = this.value;
        const centroSelect = document.getElementById('centro_id');
        centroSelect.value = '';
        centroSelect.querySelectorAll('option').forEach(option => {
            if (option.value === '') return;
            option.style.display = (option.getAttribute('data-empresa') === empresaId) ? '' : 'none';
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const empresaSelect = document.getElementById('empresa_id');
        if (empresaSelect.value) empresaSelect.dispatchEvent(new Event('change'));

        // Init restriction select-all states
        document.querySelectorAll('.select-all-type').forEach(cb => {
            const all = document.querySelectorAll('.type-' + cb.dataset.type);
            const allChecked = Array.from(all).every(c => c.checked);
            const someChecked = Array.from(all).some(c => c.checked);
            cb.checked = allChecked;
            cb.indeterminate = someChecked && !allChecked;
        });
    });

    document.querySelectorAll('.select-all-type').forEach(cb => {
        cb.addEventListener('change', function() {
            document.querySelectorAll('.type-' + this.dataset.type).forEach(c => c.checked = this.checked);
        });
    });

    document.querySelectorAll('.restriction-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const typeClass = Array.from(this.classList).find(c => c.startsWith('type-'));
            if (typeClass) {
                const type = typeClass.replace('type-', '');
                const all = document.querySelectorAll('.type-' + type);
                const selectAll = document.querySelector(`.select-all-type[data-type="${type}"]`);
                if (selectAll) {
                    selectAll.checked = Array.from(all).every(c => c.checked);
                    selectAll.indeterminate = Array.from(all).some(c => c.checked) && !selectAll.checked;
                }
            }
        });
    });
</script>
@endpush
