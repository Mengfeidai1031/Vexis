@props([
    'title',
    'icon' => null,
    'count' => null,
])

<h3 class="vx-section-title">
    @if($icon)<i class="bi {{ $icon }}"></i>@endif
    <span>{{ $title }}</span>
    @if(!is_null($count))
        <span class="vx-section-count">{{ $count }}</span>
    @endif
    {{ $slot }}
</h3>

@once
@push('styles')
<style>
.vx-section-title {
    font-size: var(--vx-text-md); font-weight: var(--vx-weight-bold);
    color: var(--vx-text-secondary); margin-bottom: var(--vx-space-3);
    display: flex; align-items: center; gap: var(--vx-space-2);
    letter-spacing: 0.2px;
}
.vx-section-title i { font-size: 16px; color: var(--vx-primary); }
.vx-section-count {
    background: var(--vx-primary-bg); color: var(--vx-primary);
    font-size: var(--vx-text-xs); font-weight: var(--vx-weight-bold);
    padding: 1px 8px; border-radius: var(--vx-radius-full);
    margin-left: var(--vx-space-1);
}
</style>
@endpush
@endonce
