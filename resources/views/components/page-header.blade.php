@props([
    'title',
    'subtitle' => null,
    'back' => null,
    'backLabel' => 'Volver',
    'icon' => null,
])

<div class="vx-page-header">
    <div class="vx-page-header-main">
        @if($icon)
            <div class="vx-page-header-icon"><i class="bi {{ $icon }}"></i></div>
        @endif
        <div>
            <h1 class="vx-page-title">{{ $title }}</h1>
            @if($subtitle)
                <p class="vx-page-subtitle">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    <div class="vx-page-actions">
        @if($back)
            <a href="{{ $back }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> {{ $backLabel }}</a>
        @endif
        {{ $slot }}
    </div>
</div>

@once
@push('styles')
<style>
.vx-page-header-main { display: flex; align-items: center; gap: 12px; min-width: 0; }
.vx-page-header-icon {
    width: 38px; height: 38px; border-radius: var(--vx-radius);
    background: var(--vx-primary-bg); color: var(--vx-primary);
    display: flex; align-items: center; justify-content: center; font-size: 18px;
    flex-shrink: 0;
}
.vx-page-subtitle {
    font-size: var(--vx-text-base); color: var(--vx-text-secondary);
    margin-top: 2px; line-height: var(--vx-leading-tight);
}
@media (max-width: 768px) {
    .vx-page-header-icon { display: none; }
}
</style>
@endpush
@endonce
