#!/usr/bin/env bash
# update.sh — Actualiza VEXIS tras un cambio en el repo (sustituye a "git pull" a secas).
#   cd /var/www/Vexis && sudo bash deploy/update.sh [rama]
# Rama por defecto: la que tenga checkout actual (normalmente main en produccion).
source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/lib.sh"
require_root
require_env
init

cd "$APP_DIR"
BRANCH="${1:-$(git rev-parse --abbrev-ref HEAD)}"

log "Modo mantenimiento ON"
php artisan down --render="errors::503" || true

log "git pull origin ${BRANCH}..."
git pull --ff-only origin "$BRANCH"

log "composer install --no-dev..."
export COMPOSER_ALLOW_SUPERUSER=1
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

log "Build de assets..."
export PUPPETEER_SKIP_DOWNLOAD=true PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true
if [ -f package-lock.json ]; then npm ci; else npm install; fi
npm run build

log "Migraciones..."
php artisan migrate --force

log "Permisos + cache..."
chown -R "${WEB_USER}:${WEB_USER}" "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
if selinux_enforcing; then
  restorecon -R "$APP_DIR/storage" "$APP_DIR/bootstrap/cache" 2>/dev/null || true
fi
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

log "Recargando php-fpm (limpia opcache)..."
systemctl reload "$FPM_SERVICE" || systemctl restart "$FPM_SERVICE"

php artisan up
ok "Actualizacion completada (rama ${BRANCH})."
