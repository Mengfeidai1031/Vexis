// Lighthouse accessibility audit for VEXIS.
//
// Boots Chromium via chrome-launcher, drives it with puppeteer-core to log in
// once as the seeded Super Admin, then loops through every static GET route
// running a Lighthouse accessibility-only audit on each. Outputs:
//   storage/lighthouse/<timestamp>/<route>.html  (per-route Lighthouse report)
//   storage/lighthouse/<timestamp>/summary.json  (aggregated scores + issues)
//   storage/lighthouse/<timestamp>/summary.html  (overview table, sorted asc)
//   storage/lighthouse/latest -> <timestamp>     (symlink, easy access)
//
// Usage: ensure `npm run dev` (or `php artisan serve`) is running, then:
//   npm run a11y                       # full sweep
//   npm run a11y -- --only=cliente,gestion   # subset by prefix
//   BASE_URL=http://127.0.0.1:8000 npm run a11y

import lighthouse from 'lighthouse';
import puppeteer from 'puppeteer';
import fs from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { URL } from 'node:url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const projectRoot = path.resolve(__dirname, '..');

const BASE_URL = process.env.BASE_URL || 'http://127.0.0.1:8000';
const LOGIN_EMAIL = process.env.A11Y_EMAIL || 'mengfei.dai@grupo-dai.com';
const LOGIN_PASSWORD = process.env.A11Y_PASSWORD || 'password';

// Static GET routes worth auditing. Excludes: parameterized routes, exports
// (PDF/Excel), download/stream endpoints, and pure action endpoints.
const ROUTES = [
  // public
  { path: '/', auth: false, group: 'public' },
  { path: '/login', auth: false, group: 'auth' },
  { path: '/register', auth: false, group: 'auth' },

  // dashboard
  { path: '/dashboard', auth: true, group: 'dashboard' },
  { path: '/profile', auth: true, group: 'dashboard' },

  // gestion
  { path: '/gestion', auth: true, group: 'gestion' },
  { path: '/gestion/politica', auth: true, group: 'gestion' },
  { path: '/gestion/marcas', auth: true, group: 'gestion' },
  { path: '/gestion/permisos', auth: true, group: 'gestion' },
  { path: '/gestion/logs', auth: true, group: 'gestion' },
  { path: '/users', auth: true, group: 'gestion' },
  { path: '/users/create', auth: true, group: 'gestion' },
  { path: '/roles', auth: true, group: 'gestion' },
  { path: '/roles/create', auth: true, group: 'gestion' },
  { path: '/permisos', auth: true, group: 'gestion' },
  { path: '/permisos/create', auth: true, group: 'gestion' },
  { path: '/empresas', auth: true, group: 'gestion' },
  { path: '/empresas/create', auth: true, group: 'gestion' },
  { path: '/centros', auth: true, group: 'gestion' },
  { path: '/centros/create', auth: true, group: 'gestion' },
  { path: '/departamentos', auth: true, group: 'gestion' },
  { path: '/departamentos/create', auth: true, group: 'gestion' },
  { path: '/restricciones', auth: true, group: 'gestion' },
  { path: '/restricciones/create', auth: true, group: 'gestion' },
  { path: '/festivos', auth: true, group: 'gestion' },
  { path: '/festivos/create', auth: true, group: 'gestion' },
  { path: '/vacaciones', auth: true, group: 'gestion' },
  { path: '/vacaciones/create', auth: true, group: 'gestion' },
  { path: '/noticias', auth: true, group: 'gestion' },
  { path: '/noticias/create', auth: true, group: 'gestion' },
  { path: '/campanias', auth: true, group: 'gestion' },
  { path: '/campanias/create', auth: true, group: 'gestion' },
  { path: '/incidencias', auth: true, group: 'gestion' },
  { path: '/incidencias/create', auth: true, group: 'gestion' },
  { path: '/tipos-cliente', auth: true, group: 'gestion' },
  { path: '/tipos-cliente/create', auth: true, group: 'gestion' },
  { path: '/naming-pcs', auth: true, group: 'gestion' },
  { path: '/naming-pcs/create', auth: true, group: 'gestion' },
  { path: '/settings', auth: true, group: 'gestion' },

  // comercial
  { path: '/comercial', auth: true, group: 'comercial' },
  { path: '/clientes', auth: true, group: 'comercial' },
  { path: '/clientes/create', auth: true, group: 'comercial' },
  { path: '/vehiculos', auth: true, group: 'comercial' },
  { path: '/vehiculos/create', auth: true, group: 'comercial' },
  { path: '/vehiculos/documentos/generar', auth: true, group: 'comercial' },
  { path: '/ofertas', auth: true, group: 'comercial' },
  { path: '/ofertas/create', auth: true, group: 'comercial' },
  { path: '/tasaciones', auth: true, group: 'comercial' },
  { path: '/tasaciones/create', auth: true, group: 'comercial' },
  { path: '/ventas', auth: true, group: 'comercial' },
  { path: '/ventas/create', auth: true, group: 'comercial' },
  { path: '/facturas', auth: true, group: 'comercial' },
  { path: '/facturas/create', auth: true, group: 'comercial' },
  { path: '/verifactu', auth: true, group: 'comercial' },
  // /verifactu/cumplimiento + /verifactu/declaracion son descargas PDF, no HTML.
  { path: '/catalogo-precios', auth: true, group: 'comercial' },
  { path: '/catalogo-precios/create', auth: true, group: 'comercial' },

  // recambios
  { path: '/recambios', auth: true, group: 'recambios' },
  { path: '/almacenes', auth: true, group: 'recambios' },
  { path: '/almacenes/create', auth: true, group: 'recambios' },
  { path: '/stocks', auth: true, group: 'recambios' },
  { path: '/stocks/create', auth: true, group: 'recambios' },
  { path: '/repartos', auth: true, group: 'recambios' },
  { path: '/repartos/create', auth: true, group: 'recambios' },

  // talleres
  { path: '/talleres-modulo', auth: true, group: 'talleres' },
  { path: '/talleres', auth: true, group: 'talleres' },
  { path: '/talleres/create', auth: true, group: 'talleres' },
  { path: '/mecanicos', auth: true, group: 'talleres' },
  { path: '/mecanicos/create', auth: true, group: 'talleres' },
  { path: '/citas', auth: true, group: 'talleres' },
  { path: '/citas/create', auth: true, group: 'talleres' },
  { path: '/coches-sustitucion', auth: true, group: 'talleres' },
  { path: '/coches-sustitucion/create', auth: true, group: 'talleres' },

  // dataxis
  { path: '/dataxis', auth: true, group: 'dataxis' },
  { path: '/dataxis/general', auth: true, group: 'dataxis' },
  { path: '/dataxis/ventas', auth: true, group: 'dataxis' },
  { path: '/dataxis/stock', auth: true, group: 'dataxis' },
  { path: '/dataxis/taller', auth: true, group: 'dataxis' },
  { path: '/dataxis/facturas', auth: true, group: 'dataxis' },
  { path: '/dataxis/incidencias', auth: true, group: 'dataxis' },

  // cliente portal (auth as a regular user is fine — Super Admin can access too)
  { path: '/cliente', auth: true, group: 'cliente' },
  { path: '/cliente/chatbot', auth: true, group: 'cliente' },
  { path: '/cliente/pretasacion', auth: true, group: 'cliente' },
  { path: '/cliente/configurador', auth: true, group: 'cliente' },
  { path: '/cliente/concesionarios', auth: true, group: 'cliente' },
  { path: '/cliente/talleres', auth: true, group: 'cliente' },
  { path: '/cliente/precios', auth: true, group: 'cliente' },
  { path: '/cliente/noticias', auth: true, group: 'cliente' },
  { path: '/cliente/campanias', auth: true, group: 'cliente' },
  { path: '/cliente/tasacion', auth: true, group: 'cliente' },
];

const args = Object.fromEntries(
  process.argv.slice(2).flatMap((a) => {
    const m = a.match(/^--([^=]+)(?:=(.*))?$/);
    return m ? [[m[1], m[2] ?? true]] : [];
  })
);

const onlyGroups = typeof args.only === 'string' ? args.only.split(',') : null;
const limit = args.limit ? parseInt(args.limit, 10) : null;

const targets = ROUTES
  .filter((r) => !onlyGroups || onlyGroups.includes(r.group))
  .slice(0, limit ?? undefined);

const stamp = new Date().toISOString().replace(/[:.]/g, '-');
const outDir = path.join(projectRoot, 'storage', 'lighthouse', stamp);
await fs.mkdir(outDir, { recursive: true });

console.log(`[lighthouse-a11y] base=${BASE_URL} routes=${targets.length} out=${outDir}`);

// 1. Launch Chromium via Puppeteer with a fixed remote debugging port so
// Lighthouse can attach to the same instance and reuse the auth session.
const debugPort = 9222 + Math.floor(Math.random() * 1000);
const browser = await puppeteer.launch({
  headless: 'new',
  defaultViewport: { width: 1366, height: 900 },
  args: [
    `--remote-debugging-port=${debugPort}`,
    '--no-sandbox',
    '--disable-gpu',
    '--disable-dev-shm-usage',
  ],
});

try {
  const page = (await browser.pages())[0] ?? (await browser.newPage());
  console.log('[auth] logging in as', LOGIN_EMAIL);
  // Vite HMR keeps WebSockets alive — networkidle never fires, so use domcontentloaded.
  await page.goto(`${BASE_URL}/login`, { waitUntil: 'domcontentloaded', timeout: 60000 });
  await page.evaluate(
    (email, pwd) => {
      document.querySelector('input[name="email"]').value = email;
      document.querySelector('input[name="password"]').value = pwd;
      document.querySelector('form').submit();
    },
    LOGIN_EMAIL,
    LOGIN_PASSWORD
  );
  try {
    await page.waitForFunction(
      () => !location.pathname.startsWith('/login'),
      { timeout: 30000, polling: 500 }
    );
  } catch (e) {
    const dumpFile = path.join(outDir, '_login-fail.html');
    const screenFile = path.join(outDir, '_login-fail.png');
    await fs.writeFile(dumpFile, await page.content());
    await page.screenshot({ path: screenFile, fullPage: true });
    const errors = await page.evaluate(() => {
      const out = [];
      document.querySelectorAll('.alert, .vx-alert, [role="alert"], .invalid-feedback, .text-danger').forEach((el) => out.push(el.textContent.trim()));
      return out;
    });
    throw new Error(`login submit did not redirect. url=${page.url()} errors=${JSON.stringify(errors)} dump=${dumpFile} png=${screenFile}`);
  }
  const loggedUrl = page.url();
  console.log('[auth] ok →', loggedUrl);

  // 3. Run lighthouse accessibility audit per route, reusing the same Chrome.
  const results = [];
  for (let i = 0; i < targets.length; i++) {
    const t = targets[i];
    const url = BASE_URL + t.path;
    const slug = t.path === '/' ? 'root' : t.path.replace(/^\/|\/$/g, '').replace(/[\/]/g, '_');
    const reportFile = path.join(outDir, `${slug}.html`);
    process.stdout.write(`[${String(i + 1).padStart(3, ' ')}/${targets.length}] ${url} ... `);

    try {
      const runnerResult = await lighthouse(url, {
        port: debugPort,
        output: 'html',
        logLevel: 'error',
        onlyCategories: ['accessibility'],
        // Skip throttling — we care about a11y only, not perf.
        throttlingMethod: 'provided',
      });

      await fs.writeFile(reportFile, runnerResult.report);
      const lhr = runnerResult.lhr;
      const score = lhr.categories.accessibility?.score ?? null;
      const failedAudits = Object.values(lhr.audits)
        .filter(
          (a) =>
            a.score !== null &&
            a.score < 1 &&
            lhr.categories.accessibility.auditRefs.some((ref) => ref.id === a.id)
        )
        .map((a) => ({ id: a.id, title: a.title, score: a.score, displayValue: a.displayValue }));

      results.push({
        path: t.path,
        group: t.group,
        url,
        score,
        scorePct: score !== null ? Math.round(score * 100) : null,
        failed: failedAudits.length,
        issues: failedAudits,
        report: path.relative(outDir, reportFile),
      });
      console.log(`${score !== null ? Math.round(score * 100) : '??'}/100 (${failedAudits.length} issues)`);
    } catch (err) {
      console.log(`FAIL — ${err.message}`);
      results.push({
        path: t.path,
        group: t.group,
        url,
        error: err.message,
      });
    }
  }

  // 4. Aggregate.
  const valid = results.filter((r) => typeof r.scorePct === 'number');
  const avg =
    valid.length > 0
      ? Math.round(valid.reduce((s, r) => s + r.scorePct, 0) / valid.length)
      : null;
  const issueCount = {};
  for (const r of valid) {
    for (const i of r.issues) {
      issueCount[i.id] = (issueCount[i.id] || 0) + 1;
    }
  }
  const topIssues = Object.entries(issueCount)
    .sort((a, b) => b[1] - a[1])
    .map(([id, count]) => ({ id, count }));

  const summary = {
    base: BASE_URL,
    timestamp: stamp,
    user: LOGIN_EMAIL,
    routesAudited: results.length,
    averageScore: avg,
    topIssues,
    results: results.sort((a, b) => (a.scorePct ?? 0) - (b.scorePct ?? 0)),
  };

  await fs.writeFile(path.join(outDir, 'summary.json'), JSON.stringify(summary, null, 2));
  await fs.writeFile(path.join(outDir, 'summary.html'), renderSummaryHtml(summary));

  // symlink "latest"
  const latest = path.join(projectRoot, 'storage', 'lighthouse', 'latest');
  try { await fs.unlink(latest); } catch {}
  try { await fs.symlink(stamp, latest, 'dir'); } catch (e) {
    console.warn('[warn] could not create latest symlink:', e.message);
  }

  console.log(`\n[done] avg ${avg}/100 across ${valid.length} routes — ${outDir}/summary.html`);
} finally {
  if (browser) await browser.close();
}

function renderSummaryHtml(summary) {
  const rows = summary.results
    .map((r) => {
      if (r.error) {
        return `<tr class="err"><td>${esc(r.path)}</td><td>${esc(r.group)}</td><td colspan="3">ERROR: ${esc(r.error)}</td></tr>`;
      }
      const cls = r.scorePct >= 90 ? 'ok' : r.scorePct >= 70 ? 'mid' : 'bad';
      return `<tr class="${cls}">
  <td><a href="${esc(r.report)}" target="_blank">${esc(r.path)}</a></td>
  <td>${esc(r.group)}</td>
  <td class="num">${r.scorePct}</td>
  <td class="num">${r.failed}</td>
  <td>${r.issues.slice(0, 3).map((i) => esc(i.id)).join(', ')}${r.issues.length > 3 ? ` (+${r.issues.length - 3})` : ''}</td>
</tr>`;
    })
    .join('\n');

  const topRows = summary.topIssues
    .slice(0, 20)
    .map((i) => `<tr><td>${esc(i.id)}</td><td class="num">${i.count}</td></tr>`)
    .join('\n');

  return `<!doctype html>
<html lang="es"><head><meta charset="utf-8"><title>VEXIS · Lighthouse a11y · ${esc(summary.timestamp)}</title>
<style>
  body{font-family:system-ui,sans-serif;margin:24px;color:#222}
  h1{margin:0 0 4px}
  .meta{color:#666;font-size:14px;margin-bottom:24px}
  .kpi{display:inline-block;padding:10px 18px;border-radius:8px;background:#f1f5f9;margin-right:12px;font-weight:600}
  table{border-collapse:collapse;width:100%;margin-top:16px;font-size:14px}
  th,td{border:1px solid #e2e8f0;padding:6px 10px;text-align:left}
  th{background:#f8fafc}
  td.num{text-align:right;font-variant-numeric:tabular-nums}
  tr.ok td{background:#ecfdf5}
  tr.mid td{background:#fffbeb}
  tr.bad td{background:#fef2f2}
  tr.err td{background:#f3f4f6;color:#7f1d1d}
  h2{margin-top:32px}
  a{color:#0369a1}
</style></head>
<body>
<h1>VEXIS · Lighthouse Accessibility</h1>
<div class="meta">${esc(summary.timestamp)} · ${esc(summary.base)} · usuario ${esc(summary.user)}</div>
<div>
  <span class="kpi">Media: ${summary.averageScore ?? '—'}/100</span>
  <span class="kpi">Rutas: ${summary.routesAudited}</span>
  <span class="kpi">Tipos de issue distintos: ${summary.topIssues.length}</span>
</div>

<h2>Top issues recurrentes</h2>
<table><thead><tr><th>Audit ID</th><th>Apariciones</th></tr></thead><tbody>
${topRows || '<tr><td colspan="2">Ninguno 🎉</td></tr>'}
</tbody></table>

<h2>Rutas (peor → mejor)</h2>
<table><thead>
<tr><th>Ruta</th><th>Grupo</th><th>Score</th><th># issues</th><th>Issues principales</th></tr>
</thead><tbody>
${rows}
</tbody></table>
</body></html>`;
}

function esc(s) {
  return String(s ?? '').replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));
}
