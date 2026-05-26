@once
@push('styles')
<style>
.vx-test-fab { position: fixed; top: calc(var(--vx-navbar-height) + 16px); right: 20px; background: var(--vx-surface); border: 1px solid var(--vx-border); border-radius: 999px; padding: 8px 14px; font-size: 12px; font-weight: 600; cursor: pointer; box-shadow: var(--vx-shadow-sm); z-index: 100; display: flex; align-items: center; gap: 6px; color: var(--vx-text-muted); }
.vx-test-fab:hover { color: var(--vx-primary); border-color: var(--vx-primary); }
.vx-test-modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 2000; display: none; align-items: center; justify-content: center; padding: 16px; }
.vx-test-modal-overlay.open { display: flex; }
.vx-test-modal { background: var(--vx-surface); border-radius: var(--vx-radius-lg); max-width: 480px; width: 100%; border: 1px solid var(--vx-border); box-shadow: var(--vx-shadow-lg); overflow: hidden; }
.vx-test-modal-head { display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; border-bottom: 1px solid var(--vx-border); }
.vx-test-modal-head h4 { font-size: 15px; font-weight: 700; margin: 0; color: var(--vx-text); }
.vx-test-modal-close { background: none; border: none; font-size: 22px; cursor: pointer; color: var(--vx-text-muted); }
.vx-test-modal-body { padding: 18px; }
.vx-test-user { display: flex; align-items: center; justify-content: space-between; padding: 7px 0; font-size: 12px; color: var(--vx-text-secondary); border-bottom: 1px solid var(--vx-border); }
.vx-test-user:last-child { border-bottom: none; }
.vx-test-user code { font-family: var(--vx-font-mono); font-size: 11px; background: rgba(51,170,221,0.08); color: var(--vx-primary); padding: 2px 6px; border-radius: 4px; cursor: pointer; }
.vx-test-user code:hover { background: rgba(51,170,221,0.18); }
.vx-test-error { color: var(--vx-danger); font-size: 12px; margin-top: 6px; display: none; }
.vx-test-pwd { background: var(--vx-gray-50); padding: 10px; border-radius: 6px; text-align: center; margin-top: 10px; font-size: 12px; color: var(--vx-text-muted); }
[data-theme="dark"] .vx-test-pwd { background: var(--vx-gray-100); }
</style>
@endpush

<button type="button" class="vx-test-fab" id="vxTestFab" title="Usuarios de prueba">
    <i class="bi bi-key"></i> Usuarios prueba
</button>

<div class="vx-test-modal-overlay" id="vxTestModal">
    <div class="vx-test-modal">
        <div class="vx-test-modal-head">
            <h4><i class="bi bi-key" style="color:var(--vx-primary);"></i> Acceso a usuarios de prueba</h4>
            <button type="button" class="vx-test-modal-close" id="vxTestModalClose" aria-label="Cerrar">&times;</button>
        </div>
        <div class="vx-test-modal-body">
            <div id="vxTestStep1">
                <p style="font-size:13px;color:var(--vx-text-muted);margin-bottom:12px;">Introduce la contraseña de acceso a la lista de usuarios de prueba:</p>
                <div class="vx-form-group">
                    <input type="password" id="vxTestPwd" class="vx-input" placeholder="Contraseña" autocomplete="off">
                    <div class="vx-test-error" id="vxTestErr">Contraseña incorrecta.</div>
                </div>
                <button type="button" id="vxTestPwdBtn" class="vx-btn vx-btn-primary" style="width:100%;justify-content:center;">Acceder</button>
            </div>
            <div id="vxTestStep2" style="display:none;">
                <div class="vx-test-user"><span><strong>Super Admin</strong> — Meng Fei Dai</span><code data-cp="mengfei.dai@grupo-dai.com">mengfei.dai@grupo-dai.com</code></div>
                <div class="vx-test-user"><span>Administrador</span><code data-cp="carmen.santana@grupo-dai.com">carmen.santana@grupo-dai.com</code></div>
                <div class="vx-test-user"><span>Gerente</span><code data-cp="francisco.hernandez@grupo-dai.com">francisco.hernandez@grupo-dai.com</code></div>
                <div class="vx-test-user"><span>Vendedor</span><code data-cp="maria.gonzalez@grupo-dai.com">maria.gonzalez@grupo-dai.com</code></div>
                <div class="vx-test-user"><span>Consultor</span><code data-cp="pedro.cabrera@grupo-dai.com">pedro.cabrera@grupo-dai.com</code></div>
                <div class="vx-test-user"><span>Vendedor (restringido Tenerife)</span><code data-cp="laura.martin@grupo-dai.com">laura.martin@grupo-dai.com</code></div>
                <div class="vx-test-user"><span>Gerente (restringido GC)</span><code data-cp="antonio.ramirez@grupo-dai.com">antonio.ramirez@grupo-dai.com</code></div>
                <div class="vx-test-pwd">Contraseña para todos: <code data-cp="password">password</code></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function(){
    const fab = document.getElementById('vxTestFab');
    const modal = document.getElementById('vxTestModal');
    const closeBtn = document.getElementById('vxTestModalClose');
    const pwdInput = document.getElementById('vxTestPwd');
    const pwdBtn = document.getElementById('vxTestPwdBtn');
    const err = document.getElementById('vxTestErr');
    const step1 = document.getElementById('vxTestStep1');
    const step2 = document.getElementById('vxTestStep2');
    if (!fab) return;
    function open() { modal.classList.add('open'); step1.style.display=''; step2.style.display='none'; pwdInput.value=''; err.style.display='none'; setTimeout(()=>pwdInput.focus(),50); }
    function close() { modal.classList.remove('open'); }
    function checkPwd() {
        if (pwdInput.value === 'VEXIS') { step1.style.display='none'; step2.style.display=''; }
        else { err.style.display='block'; pwdInput.value=''; pwdInput.focus(); }
    }
    fab.addEventListener('click', open);
    closeBtn.addEventListener('click', close);
    modal.addEventListener('click', e => { if (e.target === modal) close(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });
    pwdBtn.addEventListener('click', checkPwd);
    pwdInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); checkPwd(); } });
    document.querySelectorAll('[data-cp]').forEach(el => el.addEventListener('click', function(){
        const txt = this.dataset.cp;
        navigator.clipboard?.writeText(txt);
        const old = this.textContent;
        this.textContent = '¡Copiado!';
        setTimeout(()=> this.textContent = old, 900);
    }));
})();
</script>
@endpush
@endonce
