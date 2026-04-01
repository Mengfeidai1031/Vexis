# Vexis - Documentación del proyecto (Versión 2)

Este repositorio contiene la **versión 2 (V2)** de Vexis, plataforma interna de gestión empresarial para automoción de Grupo ARI.

La V2 amplía el alcance de la V1 con nuevos módulos operativos y de análisis, manteniendo la base de seguridad por roles, permisos y políticas.

---

## 1) Resumen de la V2

La V2 consolida el sistema en 5 áreas funcionales:

- **Gestión**: usuarios, empresas, departamentos, centros, roles, restricciones, noticias, campañas, naming PCs, festivos, vacaciones.
- **Comercial**: clientes, vehículos, ofertas, ventas, tasaciones, catálogo de precios.
- **Recambios**: almacenes, stock y repartos.
- **Talleres**: talleres, mecánicos, citas y coches de sustitución.
- **Cliente + Analítica**: portal cliente y módulo DatAxis (vistas de análisis).

Incluye además integración de chatbot con Gemini en el módulo cliente, con control de acceso por permisos.

---

## 2) Stack tecnológico

### Backend

- PHP `^8.2`
- Laravel `^12.0`
- MySQL/MariaDB (o compatible)

### Frontend

- Vite `^7`
- Tailwind CSS `^4`
- Axios

### Paquetes clave

- `spatie/laravel-permission` (roles/permisos)
- `maatwebsite/excel` (exportaciones Excel)
- `barryvdh/laravel-dompdf` (exportaciones PDF)
- `spatie/pdf-to-text` (parseo de PDFs de ofertas)
- `@google/generative-ai` (chatbot del módulo cliente)

---

## 3) Arquitectura

Estructura principal de la aplicación:

- `app/Http/Controllers`: controladores por módulo
- `app/Models`: entidades de dominio
- `app/Policies`: autorización por recurso
- `app/Repositories` + `app/Repositories/Interfaces`: acceso a datos desacoplado
- `app/Services`: lógica de negocio especializada
- `app/Exports`: exportaciones
- `database/migrations`: evolución del esquema
- `database/seeders`: carga inicial y datos de ejemplo
- `resources/views`: vistas Blade
- `routes/web.php`: rutas de aplicación

En `AppServiceProvider` se mantienen bindings de repositorios e inscripción de políticas con `Gate::policy`.

---

## 4) Seguridad y control de acceso

### Autenticación

- Login/Logout con sesión.
- Registro disponible en V2 (`/register`).
- Protección global con middleware `auth`.

### Autorización

- Permisos granulares por módulo (ver/crear/editar/eliminar).
- Roles base: `Super Admin`, `Administrador`, `Gerente`, `Vendedor`, `Consultor`.
- Políticas activas para: empresa, usuario, centro, departamento, cliente, vehículo, oferta y restricciones.

### Restricciones de visibilidad

Se conserva el modelo de restricciones por usuario (`user_restrictions`), con soporte polimórfico para segmentar acceso por entidad.

---

## 5) Funcionalidades destacadas de la V2

### 5.1 Comercial

- CRUD de clientes, vehículos y ofertas.
- Exportación en clientes, vehículos, ventas y tasaciones (Excel/PDF).
- Procesamiento de ofertas por PDF (Nissan y Renault/Dacia) con `OfertaPdfService`.

### 5.2 Recambios

- CRUD de almacenes.
- CRUD de stock con exportación Excel/PDF.
- CRUD de repartos.

### 5.3 Talleres

- CRUD de talleres.
- CRUD de mecánicos.
- CRUD de citas de taller.
- CRUD de coches de sustitución + reserva.

### 5.4 Gestión

- Empresas, usuarios, departamentos, centros, roles y restricciones.
- Noticias y campañas (incluyendo gestión de fotos de campañas).
- Naming de equipos y festivos.
- Gestión de vacaciones.

### 5.5 Cliente y DatAxis

- Portal cliente con campañas, precios, concesionarios, tasación y pre-tasación.
- Chatbot con Gemini, limitado por permisos del usuario autenticado.
- DatAxis con vistas de análisis: general, ventas, stock y taller.

---

## 6) Requisitos de entorno

- PHP 8.2+
- Composer
- Node.js 20+ y npm
- Base de datos configurada en `.env`

Variables importantes:

- `DB_*` para conexión a base de datos
- `APP_KEY` generado con Artisan
- `GEMINI_API_KEY` para habilitar el chatbot de cliente

Nota: para `spatie/pdf-to-text` se necesitan utilidades del sistema compatibles con extracción de texto PDF.

---

## 7) Instalación

### Opción rápida

```bash
composer run setup
```

Ejecuta instalación de dependencias, creación de `.env`, clave de aplicación, migraciones, instalación frontend y build.

### Opción manual

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install
npm run build
```

---

## 8) Desarrollo local

```bash
composer run dev
```

Inicia en paralelo:

- servidor Laravel,
- `queue:listen`,
- logs con `pail`,
- Vite en modo desarrollo.

---

## 9) Seeders y datos iniciales

`DatabaseSeeder` en V2 carga, en orden:

- empresas, departamentos y centros,
- roles/permisos,
- usuarios,
- clientes y vehículos,
- marcas, noticias y festivos,
- talleres,
- catálogo de precios,
- almacenes,
- datos de ejemplo.

Usuarios iniciales (password: `password`):

- `superadmin@grupoari.com`
- `admin@grupoari.com`
- `francisco@grupoari.com`
- `maria@grupoari.com`
- `joseantonio@grupoari.com`
- `pedro@grupoari.com`

---

## 10) Rutas principales (resumen)

- `/`, `/login`, `/register`, `/dashboard`
- Gestión: `/gestion`, `/empresas`, `/users`, `/departamentos`, `/centros`, `/roles`, `/restricciones`, `/noticias`, `/campanias`, `/naming-pcs`, `/festivos`, `/vacaciones`
- Comercial: `/comercial`, `/clientes`, `/vehiculos`, `/ofertas`, `/ventas`, `/tasaciones`, `/catalogo-precios`
- Recambios: `/recambios`, `/almacenes`, `/stocks`, `/repartos`
- Talleres: `/talleres-modulo`, `/talleres`, `/mecanicos`, `/citas`, `/coches-sustitucion`
- Analítica: `/dataxis`, `/dataxis/general`, `/dataxis/ventas`, `/dataxis/stock`, `/dataxis/taller`
- Cliente: `/cliente`, `/cliente/chatbot`, `/cliente/pretasacion`, `/cliente/tasacion`, `/cliente/campanias`, `/cliente/concesionarios`, `/cliente/precios`, `/cliente/configurador`

---

## 11) Comandos útiles

```bash
# Tests
composer run test

# Seed completo
php artisan db:seed

# Seeder puntual
php artisan db:seed --class=RolePermissionSeeder

# Limpiar caches
php artisan optimize:clear
```

---

## 12) Base de datos (visión general)

Entidades incorporadas en V2 (además de V1):

- `marcas`, `noticias`, `campanias`, `naming_pcs`, `vacaciones`, `festivos`
- `almacenes`, `stocks`, `repartos`
- `talleres`, `mecanicos`, `citas_taller`, `coches_sustitucion`
- `ventas`, `tasaciones`, `catalogo_precios`

Se mantienen también las tablas base de usuarios, permisos, clientes, vehículos, ofertas y estructura organizativa.

---

## 13) Cambios por versión

### V1

- Núcleo de gestión, comercial base y seguridad por permisos/policies.

### V2 (actual)

- Expansión a módulos de recambios, talleres, cliente y analítica.
- Nuevos CRUD operativos (almacenes, stock, repartos, talleres, mecánicos, citas, coches de sustitución, ventas, tasaciones, catálogo de precios, noticias, campañas, festivos, naming PCs).
- Registro de usuarios habilitado.
- Chatbot cliente con Gemini y controles por permiso.
- Mayor cobertura de exportaciones Excel/PDF.

---

## 14) Estado de versión documentada

- Versión documentada: **V2**
- Rama objetivo: **versión 2**
- Última actualización de este README: **2026-04-01**

---

## 15) Licencia

El proyecto mantiene licencia MIT (según `composer.json`), salvo cambios futuros definidos por el equipo.
