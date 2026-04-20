# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

VEXIS is a Laravel 12 business management system for an automotive company (Grupo DAI). It covers management, commercial (offers, sales, valuations), spare parts (warehouses, stock, distribution), workshops (appointments, mechanics, replacement vehicles), and a client portal. The application is a single full-stack Laravel app with Blade templates, not a monorepo.

## Commands

```bash
# Full setup (composer install, .env, key:generate, migrate, npm install, build)
npm run setup

# Development (starts artisan serve:8000, queue:listen, pail logs, and Vite HMR concurrently)
npm run dev

# Run tests (clears cache first, then PHPUnit)
npm run test

# Run a single test
php artisan test --filter=TestClassName
php artisan test --filter=test_method_name

# Run migrations
php artisan migrate

# Code formatting
./vendor/bin/pint

# Build frontend assets
npm run build
```

## Architecture

### Tech Stack
- **Backend:** Laravel 12, PHP 8.2+, MySQL 8.0+ (database: `vexis_db`)
- **Frontend:** Blade templates, Tailwind CSS 4, Vite, Bootstrap Icons
- **Auth:** Spatie Laravel Permission (role-based access control, 40+ permissions)
- **Additional:** DomPDF (PDF generation), spatie/pdf-to-text (PDF parsing), maatwebsite/excel (exports), @google/generative-ai (Gemini chatbot)

### Key Patterns

- **Repository Pattern:** Interfaces in `app/Repositories/` with implementations bound in `AppServiceProvider`. Used for Users, Clientes, Vehiculos, Ofertas, Restricciones.
- **Policy-Based Authorization:** 8 policies in `app/Policies/` enforce row-level security. `UserRestrictionHelper` provides polymorphic access control — users can be restricted to specific empresas, clientes, or vehiculos.
- **Service Layer:** Business logic isolated in `app/Services/` (e.g., `OfertaPdfService` processes Nissan/Renault PDF offers using transactions).
- **Form Request Validation:** 12 dedicated request classes in `app/Http/Requests/`.

### Module Structure

Routes in `routes/web.php` (~596 lines) are organized by module, each wrapped in permission middleware:
- **Gestión** — users, departments, centers, roles, permissions, companies, news
- **Comercial** — clients, vehicles, offers (with PDF upload/parsing), sales, valuations, invoices, verifactu, price catalogs. The Comercial inicio page is split into 3 sections matching the menu dropdown: *Gestión Administrativa* (Ofertas, Tasaciones), *Gestión Ventas* (Ventas, Facturas, Verifactu), *Gestión de Vehículos* (Vehículos, Catálogo).
- **Recambios** — warehouses, stock, distribution
- **Talleres** — workshops, mechanics, appointments, replacement vehicles
- **Dataxis** — analytics dashboards (general, ventas, stock, taller, facturas, incidencias)
- **Cliente** — client-facing portal (chatbot, pretasación, configurador, noticias, talleres)

### Organization Hierarchy

Multi-tenant design: `Empresa` → `Centro` → `Departamento`. Users belong to a company and can be restricted to specific entities via the polymorphic `UserRestriction` model.

### Frontend Design System

The main layout (`resources/views/layouts/app.blade.php`) defines the VEXIS design system with CSS custom properties (primary: #33AADD), dark mode via `data-theme`, Plus Jakarta Sans font.

CSS class systems:
- `.vx-*` — base UI primitives: cards, tables (with sticky `thead`), forms (`.vx-form-grid`, `.vx-form-grid-3`, `.vx-form-full`, gap 16px), badges, alerts, pagination (`.vx-pagination-wrapper`), avatars, actions, page header.
- `.mod-*` — premium module inicio pages: banner + sections + gradient-icon cards with arrow hover. Used uniformly across Gestión, Comercial, Recambios, Talleres, Dataxis, Cliente, and Dashboard.
- `.dx-*` — Dataxis analytics: KPIs, responsive Chart.js wrappers (`.dx-chart-sm` / `.dx-chart-lg` / `.dx-grid` / `.dx-grid-full`).
- `.cli-*` — client portal premium styling (gradients, hover elevation, configurador cards).

Show pages standard max-widths: 800px (single-col) / 950px (two-col).

### Conventions

- **Filters:** Always use the `<x-filtros-avanzados>` Blade component with `<select>` elements populated from distinct collections. JS auto-converts selects with >2 options into searchable dropdowns. Do not build raw text-input filters.
- **Email domain:** Seeder users use `@grupo-dai.com`.
- **Charts:** Chart.js options must include `responsive: true, maintainAspectRatio: false`. Doughnut/pie/polar/radar canvases at 180px height; full-width line/bar at 120px.
- **Flash messages:** Global `success` / `error` / `warning` / `info` flash handling is rendered in the layout before `@yield('content')` — do not duplicate per view.
- **Destroy methods with files:** Always clean storage. Use `Storage::disk('public')->deleteDirectory(...)` and delete related Eloquent records (e.g., `CampaniaController::destroy`, `IncidenciaController::destroy`).
- **Seeder coherence:** Seeded vehicles must reference catalog models. Ventas/facturas/verifactu/repartos/stocks must reference real previously-seeded records (vendedor, cliente, vehículo, factura, stock). Tasaciones are excluded (external, no prior registration required). Verifactu is generated from a factura (or simultaneously when triggered from a venta).

### Testing

PHPUnit with in-memory SQLite (configured in `phpunit.xml`). Test suites: Unit and Feature under `/tests`.

### Session, Cache, Queue

All three use the `database` driver.

## Modo operación de Claude

- **Cero cortesía**: sin saludos, despedidas ni transiciones sociales.
- **Output directo**: si se pide código, entregar solo el código; si es corrección, aplicar el cambio.
- **Explicaciones mínimas**: solo ante breaking changes críticos o errores de lógica graves. Viñetas breves, nunca párrafos.
- **Búsquedas web cavernícola**: keywords inconexas, lenguaje simplificado (ej: `react-router docs v7 breaking changes`).
- **Prioridad técnica**: eficiencia y precisión por encima de brevedad; eliminar solo la "capa social".

## Verificaciones ejecutadas

### Verificación 1 — Auditoría de Código y Refactorización (Clean Code / SOLID / Patrones)
Commit: `5038a10 refactor: audit and refactor the code to ensure it complies with Clean Code, SOLID and design patterns`.
Alcance cubierto:
- Eliminación de código duplicado (DRY) en controllers/services.
- Extracción de lógica de negocio de controllers a Services, Actions y Repositories.
- Validación centralizada en Form Requests (`app/Http/Requests/`, 12 clases).
- Normalización de nombres (variables, métodos, clases, rutas) según convenciones Laravel.
- Eliminación de código muerto, imports no usados, comentarios obsoletos, `dd()/dump()`.
- Tipado estricto PHP 8.2: `declare(strict_types=1)`, return types y property types.
- `php artisan optimize` + Larastan nivel 6+.
- Uso correcto de Eloquent: `with()`, `withCount()` para evitar N+1.

Prohibiciones respetadas: no se tocaron vistas Blade visualmente, no se cambiaron nombres de rutas públicas, no se alteraron migraciones ya ejecutadas (esto cambió en V2), no se añadieron dependencias nuevas.

### Verificación 2 — Auditoría de Base de Datos e Integridad (DBA Senior)
Refactor agresivo aprovechando entorno pre-producción (migrate:fresh permitido).

**Consolidación de migraciones**: 11 ALTER eliminadas y plegadas en sus CREATE. Orden corregido (marcas y tipos_cliente creados antes de vehiculos/clientes). `create_tipos_cliente_table` movido a `2026_01_26_200000`, `create_marcas_table` movido a `2026_01_26_210000`.

**FKs**: todas las `unsignedBigInteger` manuales → `foreignId().constrained()` con `cascadeOnDelete` / `nullOnDelete` coherente. `user_restrictions` usa morph nativo (`morphs('restrictable')`).

**Índices añadidos** (cubren todos los filtros y agregaciones de Dataxis): `users(empresa_id,centro_id)`, `clientes(empresa_id,tipo_cliente_id)`, `vehiculos(empresa_id,marca_id)`, `ventas(empresa_id,estado)` + `(fecha_venta,empresa_id)` + `(vendedor_id,fecha_venta)`, `facturas(empresa_id,estado)` + `(fecha_factura,empresa_id)`, `citas_taller(fecha,mecanico_id)` + `(taller_id,fecha)`, `stocks(referencia)` + `(almacen_id,activo)`, `repartos(empresa_id,estado)` + `(fecha_solicitud,estado)`, `verifactus(factura_id,estado)` + `(fecha_registro,estado)`, `incidencias(estado,prioridad)`, etc.

**Tipos optimizados**: `decimal(12,2)` en importes (antes `double`), `enum` nativo MySQL en estados (facturas, verifactus, oferta_lineas.tipo, naming_pcs.tipo, settings.type, tasaciones.combustible, catalogo_precios.combustible), `unsignedInteger`/`unsignedSmallInteger` en contadores y años.

**SoftDeletes**: añadido en `Cliente` y `Vehiculo` (histórico preservado, requisito del ERP).

**Unique constraints nuevos**: `empresas.cif`, `departamentos.abreviatura`, `naming_pcs.nombre_equipo`.

**Charset**: `utf8mb4_unicode_ci` (default de conexión `mysql`/`mariadb` en `config/database.php`).

**Seeders**:
- Super Admin único: **Meng Fei Dai** — `mengfei.dai@grupo-dai.com` / `password` (nombre: Meng Fei, apellidos: Dai). NO crear otros Super Admins.
- Usuarios restringidos (morph polimórfico): `laura.martin@grupo-dai.com` (empresa 2 Tenerife) y `antonio.ramirez@grupo-dai.com` (centros 1,2 Gran Canaria).
- Email domain: `@grupo-dai.com` (formato `nombre.apellido@grupo-dai.com`).
- Rango histórico ampliado para Dataxis: **2024-01-01 → 2026-04-20**. Volúmenes: 120 ventas, 120 facturas, 120 verifactu encadenados por `fecha_factura`, 50 tasaciones, 150 citas, 80 repartos, 25 incidencias, 26 clientes (cubriendo 6 tipos), 28 vehículos, 59 catálogo precios, noticias y festivos distribuidos 2024-2026.
- Orden `DatabaseSeeder`: Empresa → Departamento → Centro → RolePermission → TipoCliente → Marca → User → Cliente → Vehiculo → CatalogoPrecio → Noticia → Festivo → Taller → Almacen → DatosEjemplo → Verifactu → Setting.

**Archivos eliminados**:
- `app/Console/Commands/PrepararUsuariosPrueba.php`
- `app/Console/Commands/TestUserRestrictions.php`
- `scripts/preparar_usuarios_prueba.php` (y directorio `scripts/`)
- `database/seeders/UserRestrictionsTestSeeder.php`

**Empresa y ámbito**: Grupo DAI, sede en Canarias, 3 empresas (DAI Motor Gran Canaria/Tenerife/Lanzarote), 7 centros, fiscalidad **IGIC 7%** (CP `35xxx`/`38xxx`) vs IVA 21% peninsular, clave régimen Verifactu `08` en Canarias.

**Verificado**: `php artisan migrate:fresh --seed` ejecuta limpio.
