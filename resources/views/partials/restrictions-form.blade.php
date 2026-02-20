{{-- Partial: Restricciones para formularios de usuario --}}
{{-- Variables esperadas: $availableRestrictions, $userRestrictions (opcional, para edit) --}}
@can('editar restricciones')
<div class="vx-form-group">
    <label class="vx-label">Restricciones</label>
    <p class="vx-form-hint" style="margin-bottom: 12px;">Si no seleccionas ninguna restricción de un tipo, el usuario podrá ver todo de ese tipo.</p>

    @php
        $restrictionTypes = [
            'empresas' => ['label' => 'Empresas', 'icon' => 'bi-building', 'nameField' => 'nombre', 'extraField' => 'cif'],
            'clientes' => ['label' => 'Clientes', 'icon' => 'bi-person-lines-fill', 'nameField' => 'nombre_completo', 'extraField' => null],
            'vehiculos' => ['label' => 'Vehículos', 'icon' => 'bi-truck', 'nameField' => null, 'extraField' => null],
            'centros' => ['label' => 'Centros', 'icon' => 'bi-geo-alt', 'nameField' => 'nombre', 'extraField' => null],
            'departamentos' => ['label' => 'Departamentos', 'icon' => 'bi-diagram-3', 'nameField' => 'nombre', 'extraField' => 'abreviatura'],
        ];
        $existingRestrictions = $userRestrictions ?? [];
    @endphp

    @foreach($restrictionTypes as $type => $config)
        @if(isset($availableRestrictions[$type]) && count($availableRestrictions[$type]) > 0)
        <div class="vx-section">
            <div class="vx-section-header">
                <label class="vx-checkbox" style="margin: 0;">
                    <input type="checkbox" class="select-all-type" data-type="{{ $type }}" id="select-all-{{ $type }}">
                    <span><i class="bi {{ $config['icon'] }}" style="margin-right: 4px;"></i> {{ $config['label'] }}</span>
                </label>
            </div>
            <div class="vx-section-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 6px;">
                    @foreach($availableRestrictions[$type] as $item)
                        <label class="vx-checkbox">
                            <input
                                class="restriction-checkbox type-{{ $type }}"
                                type="checkbox"
                                name="restrictions[{{ $type }}][]"
                                value="{{ $item->id }}"
                                {{ in_array($item->id, old('restrictions.' . $type, $existingRestrictions[$type] ?? [])) ? 'checked' : '' }}
                            >
                            <span style="font-size: 12px;">
                                @if($type === 'vehiculos')
                                    {{ $item->modelo }} {{ $item->version }}
                                @else
                                    {{ $config['nameField'] ? $item->{$config['nameField']} : $item->nombre }}
                                @endif
                                @if($config['extraField'] && $item->{$config['extraField']})
                                    <span style="color: var(--vx-text-muted);">({{ $item->{$config['extraField']} }})</span>
                                @endif
                                <span style="color: var(--vx-text-muted);">· {{ $item->empresa->nombre ?? '' }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    @endforeach
</div>
@endcan
