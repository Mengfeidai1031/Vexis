@props([
    'type' => 'gray',
    'icon' => null,
    'mono' => false,
])

@php
    $cls = 'vx-badge vx-badge-' . $type;
    if ($mono) $cls .= ' vx-badge-mono';
@endphp

<span class="{{ $cls }}" {{ $attributes }}>
    @if($icon)<i class="bi {{ $icon }}"></i>@endif
    {{ $slot }}
</span>

@once
@push('styles')
<style>
.vx-badge-mono { font-family: var(--vx-font-mono); letter-spacing: 0.5px; }
</style>
@endpush
@endonce
