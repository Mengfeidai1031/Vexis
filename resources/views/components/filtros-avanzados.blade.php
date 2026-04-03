@props(['action'])

<form action="{{ $action }}" method="GET" class="vx-filtros-form">
    <div class="vx-filtros-bar">
        <div class="vx-filtros-activos">
            {{ $slot }}
        </div>
        <div class="vx-filtros-controles">
            <div class="vx-filtros-selector-wrap">
                <button type="button" class="vx-btn vx-btn-secondary vx-btn-sm vx-filtros-toggle-btn">
                    <i class="bi bi-funnel"></i> Filtros
                </button>
                <div class="vx-filtros-selector">
                    <div class="vx-filtros-selector-title">Ocultar filtros</div>
                    <div class="vx-filtros-checks"></div>
                </div>
            </div>
            <button type="submit" class="vx-btn vx-btn-primary vx-btn-sm"><i class="bi bi-search"></i> Buscar</button>
            <a href="{{ $action }}" class="vx-btn vx-btn-secondary vx-btn-sm vx-filtros-limpiar" style="display:none;"><i class="bi bi-x-lg"></i> Limpiar</a>
        </div>
    </div>
</form>

@once
@push('styles')
<style>
.vx-filtros-form { margin-bottom: 16px; }
.vx-filtros-bar { display: flex; align-items: flex-start; gap: 8px; flex-wrap: wrap; }
.vx-filtros-activos { display: flex; gap: 8px; flex-wrap: wrap; flex: 1; min-width: 0; }
.vx-filtros-activos .vx-filtro { display: flex; flex-direction: column; gap: 3px; min-width: 160px; max-width: 220px; }
.vx-filtros-activos .vx-filtro.vx-filtro-hidden { display: none; }
.vx-filtros-activos .vx-filtro-label { font-size: 10px; font-weight: 700; color: var(--vx-text-muted); text-transform: uppercase; letter-spacing: 0.4px; line-height: 1; }
.vx-filtros-activos .vx-filtro .vx-select,
.vx-filtros-activos .vx-filtro .vx-input { font-size: 12px; padding: 5px 8px; height: 32px; }
.vx-filtros-controles { display: flex; gap: 6px; align-items: center; padding-top: 12px; }
.vx-filtros-selector-wrap { position: relative; }
.vx-filtros-selector { display: none; position: absolute; right: 0; top: 100%; margin-top: 4px; z-index: 50; background: #1e293b; color: #e2e8f0; border: 1px solid #334155; border-radius: var(--vx-radius); box-shadow: 0 8px 24px rgba(0,0,0,0.4); padding: 16px 20px; min-width: 280px; }
.vx-filtros-selector-wrap.open .vx-filtros-selector { display: block; }
.vx-filtros-selector-title { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.5px; margin-bottom: 10px; padding-bottom: 8px; border-bottom: 1px solid #334155; }
.vx-filtros-checks label { display: flex; align-items: center; gap: 8px; font-size: 13px; padding: 5px 0; cursor: pointer; white-space: nowrap; color: #e2e8f0; }
.vx-filtros-checks label:hover { color: #fff; }
.vx-filtros-checks input[type="checkbox"] { accent-color: var(--vx-primary); width: 15px; height: 15px; }

/* Searchable select */
.vx-searchable { position: relative; }
.vx-searchable .vx-ss-display {
    font-size: 12px; padding: 5px 8px; height: 32px; width: 100%;
    background: var(--vx-input-bg, #fff); color: var(--vx-text, #1e293b);
    border: 1px solid var(--vx-border, #cbd5e1); border-radius: var(--vx-radius, 6px);
    cursor: pointer; display: flex; align-items: center; justify-content: space-between;
    overflow: hidden; white-space: nowrap; text-overflow: ellipsis; box-sizing: border-box;
}
.vx-searchable .vx-ss-display .vx-ss-arrow { font-size: 10px; color: var(--vx-text-muted); margin-left: 4px; flex-shrink: 0; }
.vx-searchable .vx-ss-display .vx-ss-text { overflow: hidden; text-overflow: ellipsis; }
.vx-searchable .vx-ss-panel {
    display: none; position: absolute; top: 100%; left: 0; right: 0; z-index: 60;
    background: var(--vx-card-bg, #fff); border: 1px solid var(--vx-border, #cbd5e1);
    border-radius: var(--vx-radius, 6px); box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    margin-top: 2px; max-height: 260px; overflow: hidden; flex-direction: column;
}
.vx-searchable.open .vx-ss-panel { display: flex; }
.vx-searchable .vx-ss-search {
    padding: 6px 8px; border: none; border-bottom: 1px solid var(--vx-border, #e2e8f0);
    font-size: 12px; outline: none; background: transparent; color: var(--vx-text, #1e293b);
    width: 100%; box-sizing: border-box;
}
.vx-searchable .vx-ss-search::placeholder { color: var(--vx-text-muted, #94a3b8); }
.vx-searchable .vx-ss-list {
    overflow-y: auto; max-height: 210px; padding: 4px 0;
}
.vx-searchable .vx-ss-opt {
    padding: 5px 8px; font-size: 12px; cursor: pointer; white-space: nowrap;
    overflow: hidden; text-overflow: ellipsis;
}
.vx-searchable .vx-ss-opt:hover { background: var(--vx-primary, #33AADD); color: #fff; }
.vx-searchable .vx-ss-opt.selected { background: rgba(51,170,221,0.1); font-weight: 600; }
.vx-searchable .vx-ss-opt.hidden { display: none; }
.vx-searchable .vx-ss-empty { padding: 8px; font-size: 11px; color: var(--vx-text-muted); text-align: center; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Searchable select: transform all .vx-select inside .vx-filtros-form
    document.querySelectorAll('.vx-filtros-form .vx-filtro .vx-select').forEach(function(sel) {
        if (sel.options.length <= 2) return; // skip if only "Todos" + 0-1 option
        var wrap = document.createElement('div');
        wrap.className = 'vx-searchable';
        sel.parentNode.insertBefore(wrap, sel);
        sel.style.display = 'none';
        wrap.appendChild(sel);

        // Display button
        var display = document.createElement('div');
        display.className = 'vx-ss-display';
        var textSpan = document.createElement('span');
        textSpan.className = 'vx-ss-text';
        textSpan.textContent = sel.options[sel.selectedIndex]?.text || '';
        var arrow = document.createElement('span');
        arrow.className = 'vx-ss-arrow';
        arrow.innerHTML = '&#9662;';
        display.appendChild(textSpan);
        display.appendChild(arrow);
        wrap.insertBefore(display, sel);

        // Panel
        var panel = document.createElement('div');
        panel.className = 'vx-ss-panel';
        var search = document.createElement('input');
        search.type = 'text';
        search.className = 'vx-ss-search';
        search.placeholder = 'Escribir para filtrar...';
        panel.appendChild(search);

        var list = document.createElement('div');
        list.className = 'vx-ss-list';
        var empty = document.createElement('div');
        empty.className = 'vx-ss-empty';
        empty.textContent = 'Sin resultados';
        empty.style.display = 'none';

        Array.from(sel.options).forEach(function(opt) {
            var div = document.createElement('div');
            div.className = 'vx-ss-opt' + (opt.selected && opt.value ? ' selected' : '');
            div.textContent = opt.text;
            div.dataset.value = opt.value;
            div.addEventListener('click', function() {
                sel.value = this.dataset.value;
                textSpan.textContent = this.textContent;
                list.querySelectorAll('.vx-ss-opt').forEach(function(o) { o.classList.remove('selected'); });
                if (this.dataset.value) this.classList.add('selected');
                wrap.classList.remove('open');
                search.value = '';
                filterList('');
                sel.dispatchEvent(new Event('change'));
            });
            list.appendChild(div);
        });
        panel.appendChild(list);
        panel.appendChild(empty);
        wrap.appendChild(panel);

        function filterList(q) {
            q = q.toLowerCase();
            var visible = 0;
            list.querySelectorAll('.vx-ss-opt').forEach(function(o) {
                var match = !q || o.textContent.toLowerCase().indexOf(q) !== -1;
                o.classList.toggle('hidden', !match);
                if (match) visible++;
            });
            empty.style.display = visible === 0 ? '' : 'none';
        }

        search.addEventListener('input', function() { filterList(this.value); });
        search.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') { wrap.classList.remove('open'); }
        });

        display.addEventListener('click', function(e) {
            e.stopPropagation();
            // Close other open searchable selects
            document.querySelectorAll('.vx-searchable.open').forEach(function(other) {
                if (other !== wrap) other.classList.remove('open');
            });
            wrap.classList.toggle('open');
            if (wrap.classList.contains('open')) {
                search.value = '';
                filterList('');
                setTimeout(function() { search.focus(); }, 50);
            }
        });
    });

    // Close searchable selects on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.vx-searchable')) {
            document.querySelectorAll('.vx-searchable.open').forEach(function(w) { w.classList.remove('open'); });
        }
    });

    // Filter toggle checkboxes
    document.querySelectorAll('.vx-filtros-form').forEach(function(form) {
        var filtros = form.querySelectorAll('.vx-filtro');
        var checksContainer = form.querySelector('.vx-filtros-checks');
        var toggleBtn = form.querySelector('.vx-filtros-toggle-btn');
        var selectorWrap = form.querySelector('.vx-filtros-selector-wrap');
        var limpiarBtn = form.querySelector('.vx-filtros-limpiar');
        var anyActive = false;

        filtros.forEach(function(filtro) {
            var name = filtro.dataset.filtro;
            var label = filtro.querySelector('.vx-filtro-label');
            if (!label) return;
            var labelText = label.textContent;
            var input = filtro.querySelector('select, input[type="text"], input[type="date"]');
            var hasValue = input && input.value && input.value !== '';

            if (hasValue) anyActive = true;

            var chkLabel = document.createElement('label');
            var chk = document.createElement('input');
            chk.type = 'checkbox';
            chk.checked = true;
            chkLabel.appendChild(chk);
            chkLabel.appendChild(document.createTextNode(' ' + labelText));
            checksContainer.appendChild(chkLabel);

            chk.addEventListener('change', function() {
                if (this.checked) {
                    filtro.classList.remove('vx-filtro-hidden');
                } else {
                    filtro.classList.add('vx-filtro-hidden');
                    if (input) {
                        if (input.tagName === 'SELECT') {
                            input.selectedIndex = 0;
                            // Also reset searchable display
                            var ssWrap = filtro.querySelector('.vx-searchable');
                            if (ssWrap) {
                                var txt = ssWrap.querySelector('.vx-ss-text');
                                if (txt) txt.textContent = input.options[0]?.text || '';
                                ssWrap.querySelectorAll('.vx-ss-opt').forEach(function(o) { o.classList.remove('selected'); });
                            }
                        } else {
                            input.value = '';
                        }
                    }
                }
            });
        });

        if (anyActive) limpiarBtn.style.display = '';

        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            selectorWrap.classList.toggle('open');
        });

        document.addEventListener('click', function(e) {
            if (!selectorWrap.contains(e.target)) selectorWrap.classList.remove('open');
        });
    });
});
</script>
@endpush
@endonce
