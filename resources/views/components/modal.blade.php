@props([
    'id',
    'title' => null,
    'icon' => null,
    'size' => 'md',
])

@php
    $maxWidth = match($size) {
        'sm' => '420px',
        'lg' => '860px',
        'xl' => '1100px',
        default => '640px',
    };
@endphp

<div id="{{ $id }}" class="vx-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-title" style="display:none;">
    <div class="vx-modal" style="max-width: {{ $maxWidth }};">
        @if($title)
            <div class="vx-modal-header">
                <h4 id="{{ $id }}-title">
                    @if($icon)<i class="bi {{ $icon }}" style="color: var(--vx-primary);"></i>@endif
                    {{ $title }}
                </h4>
                <button type="button" class="vx-modal-close" data-close-modal="{{ $id }}" aria-label="Cerrar">&times;</button>
            </div>
        @endif

        @isset($body)
            <div class="vx-modal-body">{{ $body }}</div>
        @endisset

        @isset($footer)
            <div class="vx-modal-footer">{{ $footer }}</div>
        @endisset

        @if(!isset($body) && !isset($footer))
            {{ $slot }}
        @endif
    </div>
</div>

@once
@push('styles')
<style>
.vx-modal-overlay {
    position: fixed; inset: 0; background: rgba(15, 17, 23, 0.55);
    backdrop-filter: blur(2px);
    z-index: var(--vx-z-modal); display: flex;
    align-items: center; justify-content: center; padding: var(--vx-space-4);
    animation: vxFadeIn var(--vx-transition-slow);
}
.vx-modal {
    background: var(--vx-surface); border-radius: var(--vx-radius-lg);
    box-shadow: var(--vx-shadow-xl); width: 100%;
    border: 1px solid var(--vx-border);
    overflow: hidden;
    max-height: 90vh; display: flex; flex-direction: column;
    animation: vxFadeIn var(--vx-transition-bounce);
}
.vx-modal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: var(--vx-space-4) var(--vx-space-5);
    border-bottom: 1px solid var(--vx-border);
    flex-shrink: 0;
}
.vx-modal-header h4 {
    font-size: var(--vx-text-lg); font-weight: var(--vx-weight-bold);
    color: var(--vx-text); display: flex; align-items: center;
    gap: var(--vx-space-2); margin: 0;
}
.vx-modal-close {
    background: none; border: none; font-size: 22px; line-height: 1;
    color: var(--vx-text-muted); cursor: pointer; padding: 4px 8px;
    border-radius: var(--vx-radius-sm); transition: all var(--vx-transition);
}
.vx-modal-close:hover { color: var(--vx-danger); background: var(--vx-danger-bg); }
.vx-modal-body { padding: var(--vx-space-5); overflow-y: auto; flex: 1; }
.vx-modal-footer {
    display: flex; justify-content: flex-end; gap: var(--vx-space-2);
    padding: var(--vx-space-3) var(--vx-space-5);
    border-top: 1px solid var(--vx-border);
    background: var(--vx-surface-alt); flex-shrink: 0;
}
</style>
@endpush

@push('scripts')
<script>
(function () {
    if (window.__vxModalInit) return;
    window.__vxModalInit = true;

    function closeModal(overlay) {
        if (!overlay) return;
        overlay.style.display = 'none';
        const form = overlay.querySelector('form');
        if (form && form.dataset.resetOnClose !== '0') form.reset();
    }

    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-open-modal]');
        if (trigger) {
            e.preventDefault();
            const id = trigger.dataset.openModal;
            const overlay = document.getElementById(id);
            if (overlay) overlay.style.display = 'flex';
            return;
        }
        const closer = e.target.closest('[data-close-modal]');
        if (closer) {
            const overlay = document.getElementById(closer.dataset.closeModal) || closer.closest('.vx-modal-overlay');
            closeModal(overlay);
            return;
        }
        const overlay = e.target.closest('.vx-modal-overlay');
        if (overlay && e.target === overlay) closeModal(overlay);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.vx-modal-overlay').forEach(o => {
                if (o.style.display !== 'none') closeModal(o);
            });
        }
    });
})();
</script>
@endpush
@endonce
