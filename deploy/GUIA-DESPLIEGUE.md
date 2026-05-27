# Guía de despliegue VEXIS — DigitalOcean (de cero a producción)

Resumen de qué hace cada script y qué tienes que hacer tú. Droplet de referencia:
Ubuntu 24.04 x64, 1 vCPU, 2 GB RAM, 50 GB SSD.

---

## 1. Qué hacen los scripts (`deploy/`)

| Archivo | Función |
|---|---|
| `lib.sh` | Detección de distro, usuario web, IP/dominio, swap, helpers `.env`. Lo usan todos. |
| `deploy.conf` | Configuración del dominio (DuckDNS) y overrides. |
| `01-install-stack.sh` | Swap (2 GB) + Nginx, PHP-FPM 8.3 + extensiones, MariaDB, Node 20, Composer, poppler (`pdftotext`), certbot, y firewall **ufw** (SSH + 80/443). |
| `02-database.sh` | Crea la BD `vexis_db` + usuario leyendo tu `.env`. |
| `02b-duckdns.sh` | Dominio `vexis.duckdns.org`: lo apunta a tu IP y lo reactualiza cada 5 min. |
| `03-app.sh` | composer `--no-dev`, build de Vite, `.env`→producción, `migrate --force --seed`, permisos, cache. |
| `04-webserver.sh` | Configura el vhost de Nginx → `public/`. |
| `05-ssl.sh` | Emite HTTPS con Let's Encrypt (graceful; sube `APP_URL` a `https`). |
| `deploy.sh` | Orquestador: ejecuta 01→05 en orden. |
| `update.sh` | Actualiza tras cambios en el repo (git pull + build + migrar). |
| `README.md` | Documentación operativa completa. |

---

## 2. Tu flujo completo (en orden)

### Paso 1 — Conéctate al Droplet por SSH

DigitalOcean no necesita abrir puertos en el panel (red plana, sin bloqueo entrante). Entra directo
(en los Droplets de DigitalOcean el usuario es **root**):

```bash
ssh -i ~/.ssh/vexis root@TU_IP_PUBLICA
```

### Paso 2 — En el servidor

```bash
# Clonar el repo (rama main = producción) dentro de /var/www
sudo mkdir -p /var/www && cd /var/www
sudo git clone -b main <URL_DE_TU_REPO>.git Vexis
cd Vexis

# Pegar tu .env local TAL CUAL
sudo vi .env          # pegas el contenido y guardas con :wq

# Lanzar todo el despliegue
sudo bash deploy/deploy.sh
```

**Nada más arrancar, `deploy.sh` te pide el dominio DuckDNS por terminal:**

```
 Dominio DuckDNS de tu web (ej: vexis -> vexis.duckdns.org)
 Subdominio DuckDNS (sin .duckdns.org): vexis
 Token DuckDNS: ........
```

Créalos antes (~2 min) en https://www.duckdns.org: login con Google/GitHub, crea el subdominio y copia el token.

A partir de ahí continúa **todo solo**: instala, configura, migra y publica. Al terminar verás la URL pública y el login del Super Admin.

> Alternativa paso a paso (mismo resultado):
> ```bash
> sudo bash deploy/01-install-stack.sh
> sudo bash deploy/02-database.sh
> sudo bash deploy/03-app.sh
> sudo bash deploy/04-webserver.sh
> sudo bash deploy/05-ssl.sh
> ```

---

## 3. Notas del Droplet DigitalOcean

- **IP pública fija**: el Droplet mantiene su IPv4 al reiniciar; DuckDNS además la reactualiza cada 5 min.
- **Firewall**: lo gestiona **ufw** (SSH + 80 + 443), activado por `01-install-stack.sh`. No hay panel que tocar.
- **Swap**: `01` crea 2 GB de swap si no existe (con 2 GB de RAM evita que el build de Vite muera por memoria).
- **Sin keep-alive**: DigitalOcean no reclama por inactividad; la VM corre 24/7 mientras exista.

---

## 4. Decisiones técnicas clave

- **MariaDB** (no MySQL 8): es 100% compatible con `DB_CONNECTION=mysql` y permite crear la BD/usuario por socket, sin la contraseña temporal ni `validate_password` de MySQL 8.
- **Dominio (DuckDNS)**: el script **pide subdominio y token por terminal** al arrancar (créalos antes, ~2 min, en https://www.duckdns.org). `02b-duckdns.sh` apunta el dominio a tu IP y lo reactualiza cada 5 min. Alternativa avanzada: dominio propio en `deploy.conf` → `APP_DOMAIN` (DNS A → IP), que omite DuckDNS.
- **Swap automático**: con 2 GB de RAM, `01` crea 2 GB de swap para que el build de Vite no se quede sin memoria.

---

## 5. Qué se modifica en tu `.env`

Tu `.env` se pega tal cual (local). Solo se ajustan estas claves a producción:

| Clave | Antes | Después |
|---|---|---|
| `APP_ENV` | `local` | `production` |
| `APP_DEBUG` | `true` | `false` |
| `APP_URL` | `http://localhost` | `https://<dominio>` |
| `LOG_LEVEL` | `debug` | `warning` |
| `SESSION_SECURE_COOKIE` | — | `true` (si hay HTTPS) |

`APP_KEY`, `DB_*`, `GEMINI_*` y el resto **quedan intactos**.

---

## 6. Comprobaciones tras el deploy

```bash
curl -I http://<dominio>                        # responde 200/301
sudo systemctl status nginx php*-fpm mariadb     # active (running)
cd /var/www/Vexis && php artisan about           # environment: production
free -h && swapon --show                         # comprueba RAM + swap
```

**Login Super Admin:** `mengfei.dai@grupo-dai.com` / `password`

---

## 7. Actualizar la app más adelante

`git pull` por sí solo **no basta**. Usa el script de actualización (pull + deps + build + migrar + caché + opcache, con modo mantenimiento):

```bash
cd /var/www/Vexis
sudo bash deploy/update.sh        # rama actual (main en producción)
```

---

## 8. Recursos del Droplet

El Droplet de **2 GB RAM / 1 vCPU** va bien para tráfico bajo/medio. `01-install-stack.sh` añade
**2 GB de swap** automáticamente para que el `npm run build` no falle por memoria — no hay que hacer
nada manual. Si en el futuro usas un Droplet de 1 GB, el swap automático sigue cubriéndote (el build
irá más lento).

---

## 9. Desplegar con ayuda de Claude Code (opcional, recomendado)

Claude Code puede ejecutar los scripts, leer los errores y resolverlos solo hasta dejar la web
desplegada. Instálalo en el servidor:

```bash
curl -fsSL https://claude.ai/install.sh | bash
cd /var/www/Vexis
claude        # la primera vez te pedirá iniciar sesión
```

> ⚠️ Antes de pegar el prompt, ten listo tu dominio DuckDNS (~2 min): entra en
> https://www.duckdns.org, login con Google/GitHub, crea el subdominio y copia el **token**.
> Pondrás ambos en las dos líneas marcadas con 👉.

### Prompt para pegar en Claude Code

Copia esto, **rellena las dos líneas 👉** con tu subdominio y token de DuckDNS, y pégalo:

```
Estoy desplegando esta app Laravel (VEXIS) en un Droplet de DigitalOcean (Ubuntu 24.04,
1 vCPU, 2 GB RAM) recién creado y vacío, al que entro por SSH como root. El repo ya está
clonado en /var/www/Vexis (rama main) y ya he pegado mi .env de producción en /var/www/Vexis/.env.

Quiero que dejes la web 100% funcional, accesible desde Internet con HTTPS y corriendo 24/7,
usando los scripts que hay en la carpeta deploy/. Lee deploy/README.md y deploy/GUIA-DESPLIEGUE.md
para entender el procedimiento antes de empezar.

DOMINIO — uso DuckDNS. Antes de ejecutar nada, escribe estos dos valores en
deploy/deploy.conf (variables DUCKDNS_SUBDOMAIN y DUCKDNS_TOKEN):
👉 SUBDOMINIO DuckDNS: ________________      (ej: vexis  -> vexis.duckdns.org)
👉 TOKEN DuckDNS:      ________________

Pasos:
1. Comprueba que estás en /var/www/Vexis y que .env existe y tiene contenido.
2. Escribe el subdominio y el token de arriba en deploy/deploy.conf
   (DUCKDNS_SUBDOMAIN y DUCKDNS_TOKEN).
3. Ejecuta el despliegue: sudo bash deploy/deploy.sh
4. Si algún paso falla, diagnostícalo (revisa la salida del script, nginx -t,
   systemctl status nginx/php*-fpm/mariadb, journalctl -u <servicio>, y
   storage/logs/laravel.log), corrige la causa y reintenta el paso que falló.
   Repite hasta que TODO el despliegue termine sin errores.
5. En DigitalOcean NO hay que abrir puertos en el panel (red plana). El firewall es ufw y lo
   abre 01-install-stack.sh (SSH + 80 + 443). Si la web no fuese accesible desde fuera o
   Let's Encrypt fallara, revisa `ufw status` y que el dominio DuckDNS resuelva a la IP.
6. Verificación final: confirma que `php artisan about` muestra environment=production,
   que nginx/php-fpm/mariadb están active(running), y que la web responde (curl -I) por
   HTTPS en el dominio vexis.duckdns.org.

Trabaja de forma autónoma resolviendo los errores hasta que el despliegue esté completo.
Al terminar, dame la URL final y el usuario de acceso (Super Admin).
```
