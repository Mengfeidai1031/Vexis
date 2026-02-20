@extends('layouts.app')
@section('title', 'Crear Rol - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Crear Nuevo Rol</h1>
    <a href="{{ route('roles.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<div style="max-width: 900px;">
    <div class="vx-card">
        <div class="vx-card-body">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="vx-form-group">
                    <label class="vx-label" for="name">Nombre del Rol <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Ej: Administrador, Gerente, Vendedor...">
                    @error('name')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="vx-form-group">
                    <label class="vx-label">Permisos</label>
                    <p class="vx-form-hint" style="margin-bottom: 12px;">Seleccione los permisos que tendrá este rol</p>

                    @if($permissions->count() > 0)
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 12px;">
                            @foreach($permissions as $module => $modulePermissions)
                                <div class="vx-section">
                                    <div class="vx-section-header">
                                        <label class="vx-checkbox" style="margin: 0; text-transform: capitalize;">
                                            <input type="checkbox" class="select-all-module" data-module="{{ $module }}">
                                            <span>{{ ucfirst($module) }}</span>
                                        </label>
                                    </div>
                                    <div class="vx-section-body">
                                        @foreach($modulePermissions as $permission)
                                            <label class="vx-checkbox" style="padding: 3px 0;">
                                                <input class="permission-checkbox module-{{ $module }}" type="checkbox" name="permissions[]" value="{{ $permission->id }}" {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                <span style="font-size: 12px;">{{ $permission->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="vx-alert vx-alert-warning"><i class="bi bi-exclamation-triangle-fill"></i><span>No hay permisos disponibles.</span></div>
                    @endif
                    @error('permissions')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 8px;">
                    <a href="{{ route('roles.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Guardar Rol</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.select-all-module').forEach(cb => {
        cb.addEventListener('change', function() {
            document.querySelectorAll('.module-' + this.dataset.module).forEach(c => c.checked = this.checked);
        });
    });
    document.querySelectorAll('.permission-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const moduleClass = Array.from(this.classList).find(c => c.startsWith('module-'));
            if (moduleClass) {
                const module = moduleClass.replace('module-', '');
                const all = document.querySelectorAll('.module-' + module);
                const selectAll = document.querySelector(`.select-all-module[data-module="${module}"]`);
                if (selectAll) {
                    selectAll.checked = Array.from(all).every(c => c.checked);
                    selectAll.indeterminate = Array.from(all).some(c => c.checked) && !selectAll.checked;
                }
            }
        });
    });
</script>
@endpush
