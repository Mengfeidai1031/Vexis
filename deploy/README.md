# Despliegue VEXIS en DigitalOcean — de cero a producción

Scripts que llevan un Droplet **vacío** (Ubuntu 24.04 LTS) hasta VEXIS funcionando 24/7,
accesible desde Internet con HTTPS y dominio DuckDNS.

Stack que instalan: **Nginx + PHP-FPM 8.3 + MariaDB + Node 20 + Composer + Certbot**.
Incluyen **swap automático (2 GB)** y **firewall ufw** (SSH + 80/443).

Droplet de referencia: Ubuntu 24.04 x64, 1 vCPU, 2 GB RAM, 50 GB SSD.

---

## Red y firewall (sin pasos manuales)

DigitalOcean tiene red plana: el Droplet ya trae **IPv4 pública estable** (no cambia al reiniciar) y
**no bloquea tráfico entrante por defecto**. No hay que tocar nada en el panel.

El único cortafuegos es **ufw**, que `01-install-stack.sh` configura solo (permite SSH + 80 + 443).
Si más adelante añades un *Cloud Firewall* de DigitalOcean, recuerda permitir TCP **22/80/443**.

---

## Pasos en el servidor

```bash
# 1. Clonar el repo (rama main = producción) dentro de /var/www
sudo mkdir -p /var/www && cd /var/www
sudo git clone -b main <URL_DE_TU_REPO>.git Vexis
cd Vexis

# 2. Pegar tu .env (el de tu máquina local, tal cual)
sudo vi .env        # pega el contenido y guarda (:wq)

# 3. Ejecutar el despliegue completo
sudo bash deploy/deploy.sh
```

Eso es todo. Al terminar verás la URL pública y el login del Super Admin.

### Ejecución paso a paso (alternativa a deploy.sh)

Si prefieres lanzarlos uno a uno, este es el **orden exacto**:

```bash
sudo bash deploy/01-install-stack.sh   # PHP, MariaDB, Nginx, Node, Composer, certbot, firewall SO
sudo bash deploy/02-database.sh        # crea BD + usuario MariaDB leyendo tu .env
sudo bash deploy/03-app.sh             # composer, build assets, .env→prod, migrate --seed, permisos, cache
sudo bash deploy/04-webserver.sh       # vhost Nginx
sudo bash deploy/05-ssl.sh             # HTTPS Let's Encrypt (graceful)
```

---

## Dominio y HTTPS

El dominio se gestiona con **DuckDNS** (`https://vexis.duckdns.org`).

**Requisito previo (~2 min):** entra en https://www.duckdns.org, login con Google/GitHub, crea el
subdominio y copia el **token**.

**El propio `deploy.sh` te pide subdominio y token por terminal** al arrancar:

```
 Dominio DuckDNS de tu web (ej: vexis -> vexis.duckdns.org)
 Subdominio DuckDNS (sin .duckdns.org): vexis
 Token DuckDNS: ........
```

`02b-duckdns.sh` apunta el dominio a tu IP y lo mantiene actualizado cada 5 min vía systemd timer.
Tu respuesta se guarda en `deploy.conf` (no vuelve a preguntar). También puedes rellenar
`DUCKDNS_SUBDOMAIN` y `DUCKDNS_TOKEN` en `deploy.conf` antes de ejecutar.

- **Dominio propio** (opcional/avanzado): pon `APP_DOMAIN="tu.dominio.com"` en `deploy.conf`
  (con registro DNS **A** → IP del servidor); en ese caso se omite DuckDNS.
- Si Let's Encrypt falla (rate limit, propagación), el sitio queda en **HTTP** y se reintenta con:
  ```bash
  sudo certbot --nginx -d <tu-dominio>
  ```

---

## Qué tocan los scripts en tu `.env`

Tu `.env` se pega **tal cual** (local). `03-app.sh` solo ajusta a producción:

| Clave | Antes (local) | Después (prod) |
|---|---|---|
| `APP_ENV` | `local` | `production` |
| `APP_DEBUG` | `true` | `false` |
| `APP_URL` | `http://localhost` | `https://<dominio>` |
| `LOG_LEVEL` | `debug` | `warning` |
| `SESSION_SECURE_COOKIE` | — | `true` (si hay HTTPS) |

`APP_KEY`, `DB_*`, `GEMINI_*` y el resto se conservan intactos.

> **Nota:** se instala **MariaDB** (no MySQL 8). Es 100% compatible con `DB_CONNECTION=mysql`
> y permite crear la BD/usuario por socket sin la contraseña temporal ni `validate_password` de MySQL 8.

---

## Comprobaciones post-deploy

```bash
curl -I http://<dominio>                       # 200/301
sudo systemctl status nginx php*-fpm mariadb    # active (running)
cd /var/www/Vexis && php artisan about          # entorno production
```

## Re-desplegar tras cambios en el repo

`git pull` por sí solo **no basta** (hay que reinstalar deps, recompilar assets, migrar y limpiar
opcache). Usa el script de actualización, que lo hace todo con modo mantenimiento:

```bash
cd /var/www/Vexis
sudo bash deploy/update.sh         # rama actual (main en producción)
sudo bash deploy/update.sh main    # forzar rama concreta
```
