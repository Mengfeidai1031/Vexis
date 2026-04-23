@props([
    'name',
    'id' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => '— Seleccione —',
    'required' => false,
    'optionValue' => 'id',
    'optionLabel' => 'nombre',
    'empty' => null,
])

@php
    $fieldId = $id ?? $name;
    $hasError = $errors && $errors->has($name);
    $classes = 'vx-select vx-select-searchable' . ($hasError ? ' is-invalid' : '');
@endphp

<select
    name="{{ $name }}"
    id="{{ $fieldId }}"
    class="{{ $classes }}"
    data-searchable="1"
    @if($required) required @endif
    {{ $attributes->except(['class', 'data-searchable']) }}
>
    @if(!is_null($empty))
        <option value="">{{ $empty }}</option>
    @elseif(!$required)
        <option value="">{{ $placeholder }}</option>
    @else
        <option value="">{{ $placeholder }}</option>
    @endif

    @foreach($options as $opt)
        @php
            if (is_array($opt)) {
                $val = $opt[$optionValue] ?? null;
                $lbl = $opt[$optionLabel] ?? '';
            } elseif (is_object($opt)) {
                $val = data_get($opt, $optionValue);
                $lbl = data_get($opt, $optionLabel);
            } else {
                $val = $opt;
                $lbl = $opt;
            }
            $isSelected = (string) old($name, $selected) === (string) $val;
        @endphp
        <option value="{{ $val }}" @if($isSelected) selected @endif>{{ $lbl }}</option>
    @endforeach
</select>

@once
@push('styles')
<style>
.vx-ss-wrap { position: relative; }
.vx-ss-wrap .vx-ss-btn {
    width: 100%; min-height: 36px; padding: 9px 12px;
    border: 1px solid var(--vx-border); border-radius: var(--vx-radius);
    font-family: var(--vx-font); font-size: var(--vx-text-base);
    color: var(--vx-text); background: var(--vx-surface);
    display: flex; align-items: center; justify-content: space-between;
    gap: 8px; cursor: pointer; transition: all var(--vx-transition);
    text-align: left;
}
.vx-ss-wrap .vx-ss-btn:hover { border-color: var(--vx-border-strong); }
.vx-ss-wrap.open .vx-ss-btn,
.vx-ss-wrap .vx-ss-btn:focus { outline: none; border-color: var(--vx-primary); box-shadow: var(--vx-shadow-focus); }
.vx-ss-wrap.is-invalid .vx-ss-btn { border-color: var(--vx-danger); }
.vx-ss-wrap .vx-ss-label { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.vx-ss-wrap .vx-ss-label.is-placeholder { color: var(--vx-text-muted); }
.vx-ss-wrap .vx-ss-chevron { font-size: 12px; color: var(--vx-text-muted); flex-shrink: 0; transition: transform var(--vx-transition); }
.vx-ss-wrap.open .vx-ss-chevron { transform: rotate(180deg); }
.vx-ss-wrap .vx-ss-panel {
    display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0;
    background: var(--vx-surface); border: 1px solid var(--vx-border);
    border-radius: var(--vx-radius); box-shadow: var(--vx-shadow-lg);
    z-index: var(--vx-z-dropdown); max-height: 280px; flex-direction: column; overflow: hidden;
}
.vx-ss-wrap.open .vx-ss-panel { display: flex; }
.vx-ss-wrap .vx-ss-search-wrap {
    padding: 8px; border-bottom: 1px solid var(--vx-border);
    position: relative;
}
.vx-ss-wrap .vx-ss-search-wrap i {
    position: absolute; top: 50%; left: 18px; transform: translateY(-50%);
    font-size: 12px; color: var(--vx-text-muted); pointer-events: none;
}
.vx-ss-wrap .vx-ss-input {
    width: 100%; padding: 7px 10px 7px 30px; border: 1px solid var(--vx-border);
    border-radius: var(--vx-radius-sm); font-family: var(--vx-font);
    font-size: var(--vx-text-sm); color: var(--vx-text);
    background: var(--vx-surface-alt); outline: none;
}
.vx-ss-wrap .vx-ss-input:focus { border-color: var(--vx-primary); }
.vx-ss-wrap .vx-ss-list { overflow-y: auto; max-height: 220px; padding: 4px 0; }
.vx-ss-wrap .vx-ss-opt {
    padding: 8px 12px; font-size: var(--vx-text-base);
    color: var(--vx-text); cursor: pointer;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    transition: background var(--vx-transition-fast);
}
.vx-ss-wrap .vx-ss-opt:hover,
.vx-ss-wrap .vx-ss-opt.is-hover { background: var(--vx-primary-bg); color: var(--vx-primary-dark); }
.vx-ss-wrap .vx-ss-opt.is-selected { background: var(--vx-primary-bg); color: var(--vx-primary-dark); font-weight: var(--vx-weight-semibold); }
.vx-ss-wrap .vx-ss-opt.is-hidden { display: none; }
.vx-ss-wrap .vx-ss-empty { padding: 14px; font-size: var(--vx-text-sm); color: var(--vx-text-muted); text-align: center; }
[data-theme="dark"] .vx-ss-wrap .vx-ss-input { background: var(--vx-surface-alt); }
</style>
@endpush

@push('scripts')
<script>
(function () {
    if (window.__vxSearchableInit) return;
    window.__vxSearchableInit = true;

    function buildSearchable(sel) {
        if (sel.dataset.vxSsReady === '1') return;
        if (sel.multiple) return;
        sel.dataset.vxSsReady = '1';

        const wrap = document.createElement('div');
        wrap.className = 'vx-ss-wrap';
        if (sel.classList.contains('is-invalid')) wrap.classList.add('is-invalid');
        sel.parentNode.insertBefore(wrap, sel);
        sel.style.display = 'none';
        wrap.appendChild(sel);

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'vx-ss-btn';
        btn.setAttribute('aria-haspopup', 'listbox');
        btn.setAttribute('aria-expanded', 'false');

        const label = document.createElement('span');
        label.className = 'vx-ss-label';

        const chevron = document.createElement('i');
        chevron.className = 'bi bi-chevron-down vx-ss-chevron';

        btn.appendChild(label);
        btn.appendChild(chevron);
        wrap.appendChild(btn);

        const panel = document.createElement('div');
        panel.className = 'vx-ss-panel';

        const searchWrap = document.createElement('div');
        searchWrap.className = 'vx-ss-search-wrap';
        const searchIcon = document.createElement('i');
        searchIcon.className = 'bi bi-search';
        const search = document.createElement('input');
        search.type = 'text';
        search.className = 'vx-ss-input';
        search.placeholder = 'Escribir para filtrar…';
        searchWrap.appendChild(searchIcon);
        searchWrap.appendChild(search);
        panel.appendChild(searchWrap);

        const list = document.createElement('div');
        list.className = 'vx-ss-list';
        list.setAttribute('role', 'listbox');

        const empty = document.createElement('div');
        empty.className = 'vx-ss-empty';
        empty.textContent = 'Sin resultados';
        empty.style.display = 'none';

        function refreshLabel() {
            const current = Array.from(sel.options).find(o => o.value === sel.value);
            if (current && current.value) {
                label.textContent = current.text;
                label.classList.remove('is-placeholder');
            } else {
                label.textContent = (sel.options[0] && !sel.options[0].value) ? sel.options[0].text : 'Seleccione';
                label.classList.add('is-placeholder');
            }
        }

        function rebuildList() {
            list.innerHTML = '';
            Array.from(sel.options).forEach(opt => {
                const div = document.createElement('div');
                div.className = 'vx-ss-opt';
                div.textContent = opt.text;
                div.dataset.value = opt.value;
                div.setAttribute('role', 'option');
                if (opt.value === sel.value) div.classList.add('is-selected');
                div.addEventListener('click', () => {
                    sel.value = opt.value;
                    refreshLabel();
                    list.querySelectorAll('.vx-ss-opt').forEach(o => o.classList.remove('is-selected'));
                    div.classList.add('is-selected');
                    close();
                    sel.dispatchEvent(new Event('change', { bubbles: true }));
                });
                list.appendChild(div);
            });
        }

        panel.appendChild(list);
        panel.appendChild(empty);
        wrap.appendChild(panel);

        function filter(q) {
            q = (q || '').toLowerCase().trim();
            let visible = 0;
            list.querySelectorAll('.vx-ss-opt').forEach(o => {
                const match = !q || o.textContent.toLowerCase().includes(q);
                o.classList.toggle('is-hidden', !match);
                if (match) visible++;
            });
            empty.style.display = visible === 0 ? 'block' : 'none';
        }

        function open() {
            document.querySelectorAll('.vx-ss-wrap.open').forEach(w => { if (w !== wrap) w.classList.remove('open'); });
            wrap.classList.add('open');
            btn.setAttribute('aria-expanded', 'true');
            search.value = '';
            filter('');
            setTimeout(() => search.focus(), 30);
        }
        function close() {
            wrap.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        }

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            wrap.classList.contains('open') ? close() : open();
        });
        search.addEventListener('input', () => filter(search.value));
        search.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') { close(); btn.focus(); }
            if (e.key === 'Enter') {
                e.preventDefault();
                const first = list.querySelector('.vx-ss-opt:not(.is-hidden)');
                if (first) first.click();
            }
        });

        document.addEventListener('click', (e) => {
            if (!wrap.contains(e.target)) close();
        });

        rebuildList();
        refreshLabel();

        // Keep in sync if a third party sets .value
        const obs = new MutationObserver(() => {
            rebuildList();
            refreshLabel();
        });
        obs.observe(sel, { childList: true, attributes: true, attributeFilter: ['value'] });
    }

    function boot() {
        document
            .querySelectorAll('select.vx-select-searchable[data-searchable="1"]')
            .forEach(buildSearchable);
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
    // Expose so forms loaded dynamically can trigger init
    window.vxInitSearchable = boot;
})();
</script>
@endpush
@endonce
