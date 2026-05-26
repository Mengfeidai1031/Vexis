# Guía de despliegue VEXIS — Oracle Cloud (de cero a producción)

Resumen de qué hace cada script, qué tienes que hacer tú, y la aclaración del **único paso manual**.

---

## 1. Qué hacen los scripts (`deploy/`)

| Archivo | Función |
|---|---|
| `lib.sh` | Detección de distro (apt/dnf), usuario web, IP/dominio, helpers `.env`, SELinux. Lo usan todos. |
| `deploy.conf` | Overrides opcionales (normalmente no se toca). |
| `01-install-stack.sh` | Instala Nginx, PHP-FPM 8.3 + extensiones, MariaDB, Node 20, Composer, poppler (`pdftotext`), certbot. Abre el firewall del SO (iptables/ufw/firewalld). |
| `02-database.sh` | Crea la BD `vexis_db` + usuario leyendo tu `.env`. |
| `02b-duckdns.sh` | (Opcional) Dominio con nombre `vexis.duckdns.org` si lo configuras en `deploy.conf`. |
| `03-app.sh` | composer `--no-dev`, build de Vite, `.env`→producción, `migrate --force --seed`, permisos, cache, SELinux. |
| `04-webserver.sh` | Configura el vhost de Nginx → `public/`. |
| `05-ssl.sh` | Emite HTTPS con Let's Encrypt (graceful; sube `APP_URL` a `https`). |
| `06-keepalive.sh` | Timer de systemd anti-reclamación por inactividad de Oracle. |
| `deploy.sh` | Orquestador: ejecuta 01→06 en orden. |
| `README.md` | Documentación operativa completa. |

---

## 2. Tu flujo completo (en orden)

### Paso 1 — MANUAL, en la web de Oracle (con el ratón) · *solo una vez*

Abrir los puertos web a nivel de red de Oracle. **Esto NO se puede hacer por SSH**, solo desde la consola.

1. Entra en **cloud.oracle.com** y haz login.
2. Menú ☰ → **Networking** → **Virtual Cloud Networks**.
3. Pincha en tu VCN.
4. Menú lateral → **Subnets** → pincha en tu subnet.
5. **Security Lists** → pincha en la *Default Security List*.
6. Botón **Add Ingress Rules** y crea **una regla**:
   - **Source CIDR:** `0.0.0.0/0`
   - **IP Protocol:** `TCP`
   - **Destination Port Range:** `80,443`
7. Pulsa **Add Ingress Rules** para guardar.

*(Recomendado: reservar la IP pública en Instance → Reserved Public IP, para que no cambie al reiniciar.)*

### Paso 2 — Por SSH, en el servidor

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

**Nada más arrancar, `deploy.sh` te pregunta el dominio por terminal:**

```
 Dominio publico de tu web
 1) DuckDNS  -> nombre bonito, ej: vexis.duckdns.org   [recomendado]
 2) sslip.io -> automatico con la IP, ej: 1.2.3.4.sslip.io  (cero pasos)
 Elige opcion [1/2] (Enter = 2):
```

- Si eliges **1)**, te pide el **subdominio** y luego el **token** de DuckDNS (créalos antes, ~2 min, en https://www.duckdns.org).
- Si pulsas **Enter** (opción 2), usa el dominio automático con IP.

A partir de ahí continúa **todo solo**: instala, configura, migra y publica. Al terminar verás la URL pública y el login del Super Admin.

> Alternativa paso a paso (mismo resultado):
> ```bash
> sudo bash deploy/01-install-stack.sh
> sudo bash deploy/02-database.sh
> sudo bash deploy/03-app.sh
> sudo bash deploy/04-webserver.sh
> sudo bash deploy/05-ssl.sh
> sudo bash deploy/06-keepalive.sh
> ```

---

## 3. Aclaración del paso manual (por qué y qué significa)

Oracle tiene **dos cortafuegos**, como dos puertas:

- 🚪 **Puerta del servidor** (iptables/ufw/firewalld) → **la abren mis scripts automáticamente**. ✅
- 🚪 **Puerta de la red de Oracle** (Security List) → **la abres tú con el ratón** en la web. 👈 esto es lo único manual.

La regla que creas significa: *"permite que cualquiera de Internet entre por el puerto 80 (web) y el 443 (web segura HTTPS)"*.

**Si NO lo haces:**
- Nadie de fuera puede ver tu web, aunque el servidor funcione perfecto.
- El certificado HTTPS (candado) **falla**, porque Let's Encrypt no puede llegar a tu servidor por el puerto 80.

---

## 4. Decisiones técnicas clave

- **MariaDB** (no MySQL 8): es 100% compatible con `DB_CONNECTION=mysql` y permite crear la BD/usuario igual en Ubuntu y en Oracle Linux, sin la contraseña temporal ni `validate_password` de MySQL 8.
- **Dominio**: el script **pregunta por terminal** al arrancar (no hay que editar nada):
  - **DuckDNS** (recomendado, `vexis.duckdns.org`): te pide subdominio y token (créalos antes, ~2 min). `02b-duckdns.sh` automatiza el resto.
  - **sslip.io** (Enter): `<IP_PÚBLICA>.sslip.io`, cero pasos, pero con la IP en el nombre.
  - **Dominio propio** (opcional): defínelo en `deploy.conf` → `APP_DOMAIN` (DNS A → IP) y no preguntará.
- **Keep-alive**: ráfaga de CPU de baja prioridad (`nice 19`) ~4 min cada 25 min. Mantiene el percentil 95 de CPU por encima del 20% que exige Oracle para no marcar la instancia como *idle*, **sin frenar la web** (cede la CPU en cuanto la app la necesita).

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
systemctl list-timers vexis-keepalive.timer      # próxima ejecución del keep-alive
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

## 8. Qué shape de Oracle elegir

- ✅ **ARM Ampere A1** (hasta 4 OCPU / 24 GB): recomendado. El build de Vite va sobrado.
- ⚠️ **AMD micro (E2.1.Micro, 1 GB RAM)**: `npm run build` puede quedarse sin memoria.
  Si usas este, añade swap **antes** de `deploy.sh`:
  ```bash
  sudo fallocate -l 2G /swapfile && sudo chmod 600 /swapfile
  sudo mkswap /swapfile && sudo swapon /swapfile
  echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
  ```

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
Estoy desplegando esta app Laravel (VEXIS) en un servidor Oracle Cloud Always Free
recién creado y vacío, al que entro por SSH. El repo ya está clonado en /var/www/Vexis
(rama main) y ya he pegado mi .env de producción en /var/www/Vexis/.env.

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
5. RECUERDA: hay un paso que NO puedes hacer por SSH — abrir los puertos TCP 80 y 443
   en la Security List de la consola web de Oracle. Si detectas que el sitio no es
   accesible desde fuera o que Let's Encrypt falla por el puerto 80, avísame con
   instrucciones claras para hacerlo en la consola, y continúa con lo que sí puedas.
6. Verificación final: confirma que `php artisan about` muestra environment=production,
   que nginx/php-fpm/mariadb están active(running), que el timer vexis-keepalive está
   activo, y que la web responde (curl -I) por HTTPS en el dominio vexis.duckdns.org.

Trabaja de forma autónoma resolviendo los errores hasta que el despliegue esté completo.
Al terminar, dame la URL final y el usuario de acceso (Super Admin).
```
