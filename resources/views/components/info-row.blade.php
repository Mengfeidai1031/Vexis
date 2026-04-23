@props([
    'label',
    'value' => null,
    'icon' => null,
    'mono' => false,
])

<div class="vx-info-row">
    <div class="vx-info-label">
        @if($icon)<i class="bi {{ $icon }}" style="color:var(--vx-text-muted);margin-right:6px;"></i>@endif
        {{ $label }}
    </div>
    <div class="vx-info-value" @if($mono) style="font-family: var(--vx-font-mono);" @endif>
        {{ $value ?? $slot }}
    </div>
</div>
