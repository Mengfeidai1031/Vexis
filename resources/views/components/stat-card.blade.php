@props([
    'label',
    'value',
    'icon' => null,
    'color' => 'primary',
    'href' => null,
    'trend' => null,
    'trendDirection' => null,
])

@php
    $tag = $href ? 'a' : 'div';
    $colorVar = match($color) {
        'success' => 'var(--vx-success)',
        'warning' => 'var(--vx-warning)',
        'danger'  => 'var(--vx-danger)',
        'info'    => 'var(--vx-info)',
        default   => 'var(--vx-primary)',
    };
    $bgVar = match($color) {
        'success' => 'var(--vx-success-bg)',
        'warning' => 'var(--vx-warning-bg)',
        'danger'  => 'var(--vx-danger-bg)',
        'info'    => 'var(--vx-info-bg)',
        default   => 'var(--vx-primary-bg)',
    };
@endphp

<{{ $tag }} @if($href) href="{{ $href }}" @endif class="vx-stat-card">
    @if($icon)
        <div class="vx-stat-icon" style="background: {{ $bgVar }}; color: {{ $colorVar }};">
            <i class="bi {{ $icon }}"></i>
        </div>
    @endif
    <div class="vx-stat-content">
        <h4>{{ $label }}</h4>
        <div class="vx-stat-value">{{ $value }}</div>
        @if(!is_null($trend))
            <div class="vx-stat-trend vx-stat-trend-{{ $trendDirection ?? 'neutral' }}">
                @if($trendDirection === 'up')<i class="bi bi-arrow-up-right"></i>@elseif($trendDirection === 'down')<i class="bi bi-arrow-down-right"></i>@endif
                {{ $trend }}
            </div>
        @endif
    </div>
</{{ $tag }}>

@once
@push('styles')
<style>
.vx-stat-trend { font-size: var(--vx-text-xs); font-weight: var(--vx-weight-semibold); margin-top: 4px; display: inline-flex; align-items: center; gap: 2px; }
.vx-stat-trend-up { color: var(--vx-success); }
.vx-stat-trend-down { color: var(--vx-danger); }
.vx-stat-trend-neutral { color: var(--vx-text-muted); }
</style>
@endpush
@endonce
