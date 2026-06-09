# Vexis - Documentación del proyecto (Versión 3)

Este repositorio contiene la **versión 3 (V3)** de Vexis, plataforma interna de gestión empresarial para automoción de **Grupo DAI**.

La V3 consolida Vexis como producto: añade el área fiscal y de facturación (facturas y Verifactu), gestión de incidencias, tipos de cliente, activación/desactivación de módulos por Super Admin, panel de control de IA, endurecimiento de seguridad y despliegue automatizado a producción. Mantiene la base de seguridad por roles, permisos y políticas heredada de V1 y V2.

> Nota: respecto a la V2, la organización pasa a denominarse **Grupo DAI** (dominio de correo `@grupo-dai.com`).

---

## 1) Resumen de la V3

La V3 organiza el sistema en las siguientes áreas:

- **Gestión**: usuarios, empresas, departamentos, centros, roles, permisos, restricciones, noticias, campañas, naming PCs, festivos, vacaciones, visor de logs y ajustes del sistema.
- **Comercial y fiscal**: clientes, tipos de cliente, vehículos, ofertas, ventas (con conceptos de extras/descuentos e impuestos automáticos), facturas, Verifactu, tasaciones y catálogo.
- **Recambios**: almacenes, stock y repartos.
- **Talleres**: talleres, mecánicos, citas y coches de sustitución.
- **Incidencias**: tickets con archivos adjuntos y asignaciones.
- **Cliente + Analítica**: portal de cliente (con chatbot y pre-tasación por IA) y módulo DatAxis de análisis.

Novedades transversales de la V3:

- Activación/desactivación de módulos desde **Ajustes** (Super Admin) mediante middleware `module:`.
- **Panel de Control IA** (Super Admin) con registro de uso de Gemini (`ai_usage`).
- **Visor de logs** en tiempo real (seguridad y errores).
- **Modo mantenimiento** y **cabeceras de seguridad** (security headers).
- **Rate limiting** en login, registro y endpoints de IA.
- **Generación automática de PDF** de documentos de vehículo e historial documental.
- **Manual de usuario** integrado.
- Integración con la **API externa de la DGT** y generación/asignación de matrícula.

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
- `barryvdh/laravel-dompdf` (exportaciones y facturas PDF)
- `spatie/pdf-to-text` (parseo de PDFs de ofertas)
- `endroid/qr-code` (QR de Verifactu) — **nuevo en V3**
- `@google/generative-ai` (chatbot y pre-tasación del módulo cliente)

### Calidad y pruebas

- `larastan/larastan` (análisis estático PHPStan)
- `laravel/pint` (estilo de código)
- `phpunit/phpunit` (tests)
- `lighthouse` + `puppeteer` (auditoría de accesibilidad, script `npm run a11y`)

---

## 3) Arquitectura

Estructura principal de la aplicación:

- `app/Http/Controllers`: controladores por módulo
- `app/Http/Middleware`: `CheckModuleEnabled` (toggles de módulo), `MaintenanceMode`, `SecurityHeaders`
- `app/Models`: entidades de dominio
- `app/Policies`: autorización por recurso
- `app/Repositories` + `app/Repositories/Interfaces`: acceso a datos desacoplado
- `app/Services`: lógica de negocio especializada (ofertas PDF, Verifactu, IA, documentos de vehículo, ...)
- `app/Exports`: exportaciones
- `database/migrations`: evolución del esquema
- `database/seeders`: carga inicial y datos de ejemplo
- `resources/views`: vistas Blade
- `routes/web.php`: rutas de aplicación

En `AppServiceProvider` se mantienen los bindings de repositorios e inscripción de políticas con `Gate::policy`.

---

## 4) Seguridad y control de acceso

### Autenticación

- Login/Logout con sesión y registro (`/register`), con **rate limiting** (`throttle`).
- Protección global con middleware `auth`.

### Autorización

- Permisos granulares por módulo (ver/crear/editar/eliminar).
- Roles: `Super Admin`, `Administrador`, `Gerente`, `Vendedor`, `Consultor`, `Mecánico` y `Cliente`.
- Políticas activas por recurso (empresa, usuario, centro, departamento, cliente, vehículo, oferta, restricciones, ...).
- Zonas exclusivas de Super Admin: ajustes, permisos, visor de logs y control de IA.

### Activación de módulos

El middleware `module:` (`CheckModuleEnabled`) permite habilitar/deshabilitar módulos (p.ej. `facturas`, `verifactu`, `incidencias`) desde **Ajustes**, sin tocar código.

### Endurecimiento

- Cabeceras de seguridad (`SecurityHeaders`).
- Modo mantenimiento (`MaintenanceMode`).
- Visor de logs en tiempo real (stream / descarga / limpieza).
- Restricciones de visibilidad por usuario (`user_restrictions`, soporte polimórfico).

---

## 5) Funcionalidades destacadas de la V3

### 5.1 Comercial y fiscal

- Tipos de cliente (CRUD).
- Vehículos con marca/modelo/versión dinámicos desde catálogo y matrícula (manual o vía **API DGT**).
- Ventas con líneas de concepto (extras/descuentos) e impuestos automáticos (IGIC/IVA).
- **Facturas** vinculadas a venta, con generación de PDF (datos registrales, IGIC/IVA, RGPD, reserva de dominio y garantía) y exportación.
- **Verifactu**: cadena de hash SHA-256, estados AEAT, XML, QR, declaración responsable y verificación de la cadena.
- Generación automática de documentos PDF de vehículo (varios tipos) e historial documental.

### 5.2 Incidencias

- Tickets con archivos adjuntos, asignaciones y seguimiento.

### 5.3 Recambios y talleres

- Recambios: almacenes, stock (con exportación Excel/PDF) y repartos.
- Talleres: talleres, mecánicos, citas y coches de sustitución (con reserva).

### 5.4 Cliente y DatAxis

- Portal cliente: campañas, precios, concesionarios, noticias, talleres, configurador (acepta JPG/PNG), pre-tasación y tasación.
- **Chatbot** y **pre-tasación** con Gemini (con rate limiting y control por permisos).
- DatAxis: análisis general, ventas, stock, taller, **facturas** e **incidencias**.

### 5.5 Administración

- Ajustes del sistema y toggles de módulos (Super Admin).
- Panel de Control IA con registro de uso (`ai_usage`).
- Visor de logs y gestión de permisos.
- Manual de usuario integrado.

---

## 6) Requisitos de entorno

- PHP 8.2+
- Composer
- Node.js 20+ y npm
- Base de datos configurada en `.env`

Variables importantes:

- `DB_*` para conexión a base de datos
- `APP_KEY` generado con Artisan
- `GEMINI_API_KEY` para habilitar la IA del módulo cliente (opcionales: `GEMINI_MODEL`, `GEMINI_API_VERSION` y claves/proyectos por feature `GEMINI_CHATBOT_*`, `GEMINI_PRETASACION_*`)
- `APP_MAINTENANCE_DRIVER` para el modo mantenimiento

Nota: para `spatie/pdf-to-text` se necesitan utilidades del sistema compatibles con extracción de texto PDF.

---

## 7) Instalación

### Opción rápida

```bash
composer run setup
```

Instala dependencias, crea `.env` si no existe, genera `APP_KEY`, ejecuta migraciones y construye el frontend.

> En V3, `setup` no ejecuta seeders: lanza `php artisan db:seed` aparte para cargar los datos iniciales.

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

`DatabaseSeeder` en V3 carga, en orden:

- empresas, departamentos y centros,
- roles/permisos,
- tipos de cliente,
- marcas,
- usuarios,
- clientes y vehículos,
- catálogo de precios,
- noticias y festivos,
- talleres,
- almacenes,
- datos de ejemplo,
- ajustes del sistema (`settings`).

Usuarios iniciales (password: `password`), dominio `@grupo-dai.com`:

- `mengfei.dai@grupo-dai.com` (Super Admin)
- `carmen.santana@grupo-dai.com` (Administrador)
- `francisco.hernandez@grupo-dai.com` (Gerente)
- `maria.gonzalez@grupo-dai.com` (Vendedor)
- `joseantonio.rodriguez@grupo-dai.com` (Vendedor)
- `pedro.cabrera@grupo-dai.com` (Consultor)

También existen usuarios adicionales por centro/rol para pruebas (Tenerife, Gran Canaria, etc.).

---

## 10) Rutas principales (resumen)

- `/`, `/login`, `/register`, `/dashboard`, `/manual`
- Gestión: `/gestion`, `/empresas`, `/users`, `/departamentos`, `/centros`, `/roles`, `/permisos`, `/restricciones`, `/noticias`, `/campanias`, `/naming-pcs`, `/festivos`, `/vacaciones`, `/gestion/logs`, `/settings`
- Comercial: `/comercial`, `/clientes`, `/tipos-cliente`, `/vehiculos`, `/ofertas`, `/ventas`, `/facturas`, `/verifactu`, `/tasaciones`, `/catalogo-precios`
- Recambios: `/recambios`, `/almacenes`, `/stocks`, `/repartos`
- Talleres: `/talleres-modulo`, `/talleres`, `/mecanicos`, `/citas`, `/coches-sustitucion`
- Incidencias: `/incidencias`
- IA: `/ai/control` (Super Admin)
- Analítica: `/dataxis`, `/dataxis/general`, `/dataxis/ventas`, `/dataxis/stock`, `/dataxis/taller`, `/dataxis/facturas`, `/dataxis/incidencias`
- Cliente: `/cliente`, `/cliente/chatbot`, `/cliente/pretasacion`, `/cliente/tasacion`, `/cliente/campanias`, `/cliente/concesionarios`, `/cliente/precios`, `/cliente/configurador`, `/cliente/noticias`, `/cliente/talleres`

---

## 11) Comandos útiles

```bash
# Tests
composer run test

# Seed completo
php artisan db:seed

# Seeder puntual
php artisan db:seed --class=RolePermissionSeeder

# Limpiar cachés
php artisan optimize:clear

# Análisis estático (larastan/PHPStan)
vendor/bin/phpstan analyse

# Estilo de código
vendor/bin/pint

# Auditoría de accesibilidad (Lighthouse)
npm run a11y
```

---

## 12) Base de datos (visión general)

Entidades incorporadas en V3 (además de V1 y V2):

- `tipos_cliente`
- `facturas`, `verifactus`
- `venta_conceptos`
- `incidencias`
- `settings`
- `vehiculo_historial_documentos` (e historial documental de vehículos)
- `ai_usage`

Se mantienen las tablas base de V1/V2: usuarios, permisos/roles (Spatie), clientes, vehículos, ofertas, ventas, tasaciones, catálogo, almacenes/stock/repartos, talleres/mecánicos/citas, noticias/campañas, festivos, naming PCs, estructura organizativa, etc.

---

## 13) Despliegue (V3)

V3 incorpora **scripts de despliegue automatizado** (de cero a producción) orientados a **Oracle Cloud**, con DNS mediante **DuckDNS**:

```
deploy/
  deploy.sh            # orquestador
  01-install-stack.sh  # PHP, Nginx, base de datos, ...
  02-database.sh
  02b-duckdns.sh       # dominio dinámico DuckDNS
  03-app.sh
  04-webserver.sh
  05-ssl.sh            # certificado (Let's Encrypt)
  06-keepalive.sh      # evita la reclamación de la instancia gratuita
  update.sh            # actualizaciones
```

Guía detallada en `deploy/GUIA-DESPLIEGUE.md`.

---

## 14) Cambios por versión

### V1

- Núcleo de gestión, comercial base y seguridad por permisos/políticas.

### V2

- Recambios, talleres, ventas/tasaciones/catálogo, portal cliente con chatbot Gemini y DatAxis.

### V3 (actual)

- Renombrado a **Grupo DAI** (`@grupo-dai.com`).
- Área fiscal: **facturas** y **Verifactu** (cadena de hash, QR, XML, estados AEAT).
- Ventas con conceptos e impuestos automáticos (IGIC/IVA).
- **Tipos de cliente**; matrícula vía **API DGT**; documentos PDF de vehículo e historial.
- Módulo de **incidencias**.
- **Ajustes** con toggles de módulos; **Panel de Control IA**; **visor de logs**; **modo mantenimiento**; cabeceras de seguridad; **rate limiting**.
- **Manual de usuario**; nuevas vistas DatAxis (facturas, incidencias) y de cliente (noticias, talleres).
- Análisis estático (larastan), estilo (pint), auditoría de accesibilidad y ampliación de la batería de tests.
- **Scripts de despliegue automatizado** (Oracle Cloud + DuckDNS).

---

## 15) Estado de versión documentada

- Versión documentada: **V3**
- Rama objetivo: **versión 3 (VX_v.3)**
- Última actualización de este README: **2026-05-26**

---

## 16) Licencia

El proyecto mantiene licencia MIT (según `composer.json`), salvo cambios futuros definidos por el equipo.
