@props([
    'icon' => 'bi-inbox',
    'title' => null,
    'message' => 'No hay datos para mostrar.',
    'cta' => null,
    'ctaLabel' => null,
    'ctaIcon' => 'bi-plus-circle',
])

<div class="vx-empty-state">
    <div class="vx-empty-icon"><i class="bi {{ $icon }}"></i></div>
    @if($title)
        <h4 class="vx-empty-title">{{ $title }}</h4>
    @endif
    <p class="vx-empty-message">{{ $message }}</p>
    @if($cta && $ctaLabel)
        <a href="{{ $cta }}" class="vx-btn vx-btn-primary">
            <i class="bi {{ $ctaIcon }}"></i> {{ $ctaLabel }}
        </a>
    @endif
    {{ $slot }}
</div>

@once
@push('styles')
<style>
.vx-empty-state {
    text-align: center; padding: var(--vx-space-12) var(--vx-space-6);
    display: flex; flex-direction: column; align-items: center; gap: var(--vx-space-3);
}
.vx-empty-icon {
    width: 72px; height: 72px; border-radius: var(--vx-radius-full);
    background: var(--vx-primary-bg); color: var(--vx-primary);
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; margin-bottom: var(--vx-space-2);
}
.vx-empty-title {
    font-size: var(--vx-text-lg); font-weight: var(--vx-weight-bold);
    color: var(--vx-text); margin: 0;
}
.vx-empty-message {
    font-size: var(--vx-text-base); color: var(--vx-text-secondary);
    margin: 0; max-width: 380px; line-height: var(--vx-leading-normal);
}
</style>
@endpush
@endonce
