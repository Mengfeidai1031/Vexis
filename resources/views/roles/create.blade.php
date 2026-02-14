@extends('layouts.app')

@section('title', 'Crear Rol')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Crear Nuevo Rol</h2>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf

                    <!-- Nombre del Rol -->
                    <div class="mb-4">
                        <label for="name" class="form-label">Nombre del Rol <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('name') is-invalid @enderror" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required
                            placeholder="Ej: Administrador, Gerente, Vendedor..."
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Permisos -->
                    <div class="mb-4">
                        <label class="form-label">Permisos</label>
                        <p class="text-muted">Seleccione los permisos que tendrá este rol</p>
                        
                        @if($permissions->count() > 0)
                            <div class="row">
                                @foreach($permissions as $module => $modulePermissions)
                                    <div class="col-md-6 mb-4">
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0 text-capitalize">
                                                    <input 
                                                        type="checkbox" 
                                                        class="form-check-input me-2 select-all-module" 
                                                        data-module="{{ $module }}"
                                                    >
                                                    {{ ucfirst($module) }}
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                @foreach($modulePermissions as $permission)
                                                    <div class="form-check mb-2">
                                                        <input 
                                                            class="form-check-input permission-checkbox module-{{ $module }}" 
                                                            type="checkbox" 
                                                            name="permissions[]" 
                                                            value="{{ $permission->id }}" 
                                                            id="permission-{{ $permission->id }}"
                                                            {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}
                                                        >
                                                        <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning">
                                No hay permisos disponibles.
                            </div>
                        @endif
                        
                        @error('permissions')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Rol</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Seleccionar/deseleccionar todos los permisos de un módulo
    document.querySelectorAll('.select-all-module').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const module = this.dataset.module;
            const moduleCheckboxes = document.querySelectorAll('.module-' + module);
            
            moduleCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
        });
    });

    // Actualizar el checkbox del módulo si se seleccionan/deseleccionan permisos individuales
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const classes = Array.from(this.classList);
            const moduleClass = classes.find(c => c.startsWith('module-'));
            
            if (moduleClass) {
                const module = moduleClass.replace('module-', '');
                const moduleCheckboxes = document.querySelectorAll('.module-' + module);
                const moduleSelectAll = document.querySelector(`.select-all-module[data-module="${module}"]`);
                
                const allChecked = Array.from(moduleCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(moduleCheckboxes).some(cb => cb.checked);
                
                if (moduleSelectAll) {
                    moduleSelectAll.checked = allChecked;
                    moduleSelectAll.indeterminate = someChecked && !allChecked;
                }
            }
        });
    });
</script>
@endpush