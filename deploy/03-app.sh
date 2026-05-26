#!/usr/bin/env bash
# 03 — Despliega la app Laravel: dependencias, build de assets, .env produccion,
#      migraciones + seed, permisos y cache de Laravel.
source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/lib.sh"
require_root
require_env
init

cd "$APP_DIR"
DOMAIN="$(resolve_domain)"
log "App dir: $APP_DIR"
log "Dominio: $DOMAIN"

# ---------------------------------------------------------------------------
# .env -> produccion (mantiene APP_KEY, DB_*, GEMINI_* tal cual los pegaste)
# ---------------------------------------------------------------------------
log "Ajustando .env a produccion..."
set_env APP_ENV production
set_env APP_DEBUG false
set_env APP_URL "http://${DOMAIN}"      # 05-ssl.sh lo sube a https si el cert se emite
set_env LOG_LEVEL warning
set_env SESSION_SECURE_COOKIE false

# ---------------------------------------------------------------------------
# Composer (produccion)
# ---------------------------------------------------------------------------
log "composer install --no-dev..."
export COMPOSER_ALLOW_SUPERUSER=1
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# ---------------------------------------------------------------------------
# APP_KEY (solo si falta)
# ---------------------------------------------------------------------------
if [ -z "$(env_get APP_KEY)" ]; then
  log "Generando APP_KEY..."
  php artisan key:generate --force
else
  ok "APP_KEY presente (se conserva)."
fi

# ---------------------------------------------------------------------------
# Frontend (Vite). Saltamos descarga de Chromium de puppeteer (devDep no usada en build).
# ---------------------------------------------------------------------------
log "Instalando dependencias npm y compilando assets..."
export PUPPETEER_SKIP_DOWNLOAD=true PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true
if [ -f package-lock.json ]; then npm ci; else npm install; fi
npm run build

# ---------------------------------------------------------------------------
# Migraciones + seed (estado actual completo)
# ---------------------------------------------------------------------------
log "Migrando y sembrando la base de datos..."
php artisan migrate --force --seed
php artisan storage:link || true

# ---------------------------------------------------------------------------
# Permisos
# ---------------------------------------------------------------------------
log "Aplicando permisos (${WEB_USER})..."
chown -R "${WEB_USER}:${WEB_USER}" "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
find "$APP_DIR/storage" "$APP_DIR/bootstrap/cache" -type d -exec chmod 775 {} \;
find "$APP_DIR/storage" "$APP_DIR/bootstrap/cache" -type f -exec chmod 664 {} \;

# ---------------------------------------------------------------------------
# Cache de produccion
# ---------------------------------------------------------------------------
log "Cacheando config/rutas/vistas..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# ---------------------------------------------------------------------------
# SELinux (Oracle Linux): contextos y red para nginx/php-fpm
# ---------------------------------------------------------------------------
if selinux_enforcing; then
  log "SELinux Enforcing: aplicando contextos y booleans..."
  setsebool -P httpd_can_network_connect 1 || true
  setsebool -P httpd_can_network_connect_db 1 || true
  chcon -R -t httpd_sys_content_t "$APP_DIR" 2>/dev/null || true
  chcon -R -t httpd_sys_rw_content_t "$APP_DIR/storage" "$APP_DIR/bootstrap/cache" 2>/dev/null || true
  ok "SELinux configurado."
fi

ok "03 — Aplicacion desplegada."
