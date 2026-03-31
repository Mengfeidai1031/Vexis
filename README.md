# Vexis - Documentación del proyecto (Versión 1)

Este repositorio contiene la **versión 1 (V1)** de Vexis, una aplicación web de gestión interna basada en Laravel, orientada a la administración de:

- usuarios,
- estructura organizativa (empresa, departamento, centro),
- clientes,
- vehículos,
- ofertas,
- y restricciones de acceso por usuario.

Este README describe el estado actual del sistema en V1 y sirve como base para futuras versiones.

---

## 1) Estado actual del proyecto

V1 está enfocada en un flujo administrativo completo con:

- autenticación de usuarios,
- autorización por roles y permisos,
- políticas de acceso por recurso,
- restricciones de visibilidad por entidad,
- gestión CRUD de módulos principales,
- exportación de vehículos a Excel/PDF,
- importación y procesamiento de PDFs de ofertas (Nissan y Renault/Dacia).

---

## 2) Stack tecnológico

### Backend

- PHP `^8.2`
- Laravel `^12.0`
- MySQL/MariaDB (o motor compatible con Laravel)

### Frontend

- Vite `^7`
- Tailwind CSS `^4`
- Axios

### Librerías clave

- `spatie/laravel-permission` (roles y permisos)
- `maatwebsite/excel` (exportaciones Excel)
- `barryvdh/laravel-dompdf` (generación de PDF)
- `spatie/pdf-to-text` (extracción de texto desde PDF)

---

## 3) Arquitectura y organización

El proyecto sigue una estructura en capas habitual en Laravel, reforzada con repositorios e interfaces:

- `app/Http/Controllers`: controladores por módulo
- `app/Models`: modelos Eloquent
- `app/Policies`: políticas de autorización por recurso
- `app/Repositories` + `app/Repositories/Interfaces`: acceso a datos desacoplado
- `app/Services`: lógica de dominio especializada (ej. procesamiento PDF de ofertas)
- `app/Exports`: clases de exportación
- `database/migrations`: definición de esquema
- `database/seeders`: datos iniciales y escenarios de prueba
- `resources/views`: vistas Blade
- `routes/web.php`: rutas HTTP de la aplicación

En `AppServiceProvider` se registran:

- los bindings de interfaces a repositorios,
- y las políticas (`Gate::policy`) para autorización por modelo.

---

## 4) Módulos funcionales en V1

La V1 incluye CRUD con control de acceso para:

- Usuarios
- Departamentos
- Centros
- Roles
- Restricciones
- Clientes
- Vehículos
- Ofertas

Además:

- endpoint interno para obtener centros por empresa (`/api/centros-by-empresa`),
- exportación de vehículos a Excel,
- exportación de vehículos a PDF,
- procesamiento de ofertas desde PDF con creación de cabecera y líneas.

---

## 5) Autenticación, autorización y seguridad de acceso

### Autenticación

- Login basado en sesión (`/login`)
- Logout con invalidación de sesión y regeneración de token
- Acceso a rutas protegidas mediante middleware `auth`

### Roles y permisos

Se utiliza `spatie/laravel-permission` con permisos por módulo, por ejemplo:

- `ver usuarios`, `crear usuarios`, `editar usuarios`, `eliminar usuarios`
- mismo patrón para departamentos, centros, clientes, vehículos, ofertas, roles y restricciones

Roles definidos en seeders:

- Super Admin
- Administrador
- Gerente
- Vendedor
- Consultor

### Políticas (Policies)

Existen políticas para modelos principales:

- `ClientePolicy`
- `VehiculoPolicy`
- `OfertaPolicy`
- `UserRestrictionPolicy`
- `CentroPolicy`
- `DepartamentoPolicy`
- `UserPolicy`
- `EmpresaPolicy`

Las rutas sensibles combinan permisos (`middleware('permission:...')`) y políticas (`middleware('can:...,modelo')`).

### Restricciones por usuario

La aplicación contempla restricciones por entidad mediante `UserRestriction`, incluyendo modelo polimórfico en migraciones recientes de V1.

---

## 6) Gestión de ofertas por PDF (V1)

El servicio `OfertaPdfService` implementa:

- almacenamiento del PDF en disco (`storage/app/public`),
- extracción de texto,
- parseo por marca (`nissan` y `renault_dacia`),
- detección y normalización de datos de empresa/cliente,
- extracción de líneas de oferta,
- búsqueda de chasis,
- creación de vehículo asociado cuando aplica,
- creación de cabecera y líneas de oferta,
- cálculo y actualización de totales,
- persistencia transaccional para consistencia.

---

## 7) Requisitos de entorno

Antes de arrancar:

- PHP 8.2+
- Composer
- Node.js 20+ y npm
- Base de datos configurada en `.env`

Para funcionalidades PDF basadas en extracción de texto, asegúrate de tener disponibles en el servidor las utilidades necesarias para `spatie/pdf-to-text` (según sistema operativo).

---

## 8) Instalación y arranque local

### Opción rápida (recomendada)

```bash
composer run setup
```

Este script ejecuta: instalación de dependencias, creación de `.env` si no existe, `key:generate`, migraciones, instalación de paquetes frontend y build.

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

### Entorno de desarrollo

Puedes usar el comando compuesto definido en `composer.json`:

```bash
composer run dev
```

Esto levanta en paralelo:

- servidor Laravel,
- listener de cola,
- logs con `pail`,
- Vite en modo desarrollo.

---

## 9) Datos de prueba y seeders

`DatabaseSeeder` ejecuta, en orden, seeders de empresa, estructura, permisos, usuarios, clientes, vehículos y datos de prueba de restricciones.

Usuarios iniciales relevantes (contraseña por defecto: `password`):

- `superadmin@grupoari.com` (Super Admin)
- `admin@grupoari.com` (Administrador)
- `juan@grupoari.com` (Gerente)
- `maria@grupoari.com` (Vendedor)
- `pedro@grupoari.com` (Consultor)

También existen usuarios específicos para pruebas de políticas y restricciones (por ejemplo, `admin@test.com`, `restringido@test.com`, etc.).

---

## 10) Rutas principales (resumen)

- Pública: `/`
- Autenticación: `/login`, `/logout`
- Panel: `/dashboard`
- Módulos protegidos por permisos y políticas:
	- `/users`
	- `/departamentos`
	- `/centros`
	- `/roles`
	- `/restricciones`
	- `/clientes`
	- `/vehiculos`
	- `/ofertas`

Rutas de exportación de vehículos:

- `/vehiculos/export/excel`
- `/vehiculos/export/pdf`

---

## 11) Comandos útiles

```bash
# Ejecutar tests
composer run test

# Limpiar cachés de configuración/rutas/vistas
php artisan optimize:clear

# Ejecutar seeders
php artisan db:seed

# Ejecutar un seeder concreto
php artisan db:seed --class=RolePermissionSeeder
```

---

## 12) Estructura de base de datos (visión general)

Tablas núcleo en V1:

- `empresas`
- `departamentos`
- `centros`
- `users`
- `clientes`
- `vehiculos`
- `oferta_cabeceras`
- `oferta_lineas`
- `user_restrictions`
- tablas de permisos/roles de Spatie (`roles`, `permissions`, pivotes)

La evolución de esquema está trazada en migraciones fechadas dentro de `database/migrations`.

---

## 13) Convenciones de evolución para futuras versiones

Para mantener este README alineado con próximas entregas (V2, V3, ...), se recomienda:

1. Añadir una sección "Cambios por versión" al final.
2. Mantener este documento como referencia acumulada del sistema.
3. Registrar en cada versión:
	 - módulos nuevos,
	 - cambios de permisos/policies,
	 - cambios de datos/migraciones,
	 - cambios en instalación/despliegue.

### Plantilla de registro por versión

```md
## Cambios por versión

### V1 (actual)
- Estado inicial productivo del sistema.

### V2
- [pendiente]
```

---

## 14) Estado de versión documentada

- Versión funcional documentada: **V1**
- Rama objetivo de esta documentación: **rama de versión 1**
- Última actualización de este README: **2026-04-01**

---

## 15) Licencia

El proyecto mantiene la licencia indicada en `composer.json` (MIT), salvo que se acuerde una política distinta en versiones futuras.
