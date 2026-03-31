# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

VEXIS is a Laravel 12 business management system for an automotive company (Grupo ARI). It covers management, commercial (offers, sales, valuations), spare parts (warehouses, stock, distribution), workshops (appointments, mechanics, replacement vehicles), and a client portal. The application is a single full-stack Laravel app with Blade templates, not a monorepo.

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
- **Comercial** — clients, vehicles, offers (with PDF upload/parsing), sales, valuations, price catalogs
- **Recambios** — warehouses, stock, distribution
- **Talleres** — workshops, mechanics, appointments, replacement vehicles
- **Cliente** — client-facing portal

### Organization Hierarchy

Multi-tenant design: `Empresa` → `Centro` → `Departamento`. Users belong to a company and can be restricted to specific entities via the polymorphic `UserRestriction` model.

### Frontend Design System

The main layout (`resources/views/layouts/app.blade.php`) defines the VEXIS design system with CSS custom properties (primary: #33AADD), dark mode via `data-theme`, Plus Jakarta Sans font, and custom component classes (`.vx-card`, `.vx-dash-module`, `.vx-avatar`).

### Testing

PHPUnit with in-memory SQLite (configured in `phpunit.xml`). Test suites: Unit and Feature under `/tests`.

### Session, Cache, Queue

All three use the `database` driver.
