#!/usr/bin/env bash
# deploy/lib.sh — funciones y deteccion compartidas por todos los scripts de deploy.
# Se "sourcea" desde cada script: source "$(dirname "$0")/lib.sh"
set -euo pipefail

# ---------------------------------------------------------------------------
# Rutas base
# ---------------------------------------------------------------------------
LIB_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APP_DIR="$(cd "$LIB_DIR/.." && pwd)"          # raiz del repo (carpeta que contiene /deploy)
ENV_FILE="$APP_DIR/.env"

# deploy.conf opcional (overrides)
[ -f "$LIB_DIR/deploy.conf" ] && source "$LIB_DIR/deploy.conf"

# Valores por defecto (sobreescribibles desde deploy.conf)
PHP_VER="${PHP_VER:-8.3}"
WEB_USER_OVERRIDE="${WEB_USER:-}"
DUCKDNS_SUBDOMAIN="${DUCKDNS_SUBDOMAIN:-}"  # p.ej. "vexis" -> vexis.duckdns.org
DUCKDNS_TOKEN="${DUCKDNS_TOKEN:-}"
APP_DOMAIN="${APP_DOMAIN:-}"        # override avanzado: dominio propio (DNS A -> IP)
ENABLE_SSL="${ENABLE_SSL:-true}"
TIMEZONE="${TIMEZONE:-Atlantic/Canary}"
FPM_SOCK="/run/php-fpm-vexis.sock"  # socket fijo (lo configuramos en el pool)

# ---------------------------------------------------------------------------
# Logging
# ---------------------------------------------------------------------------
c_blue="\033[1;34m"; c_green="\033[1;32m"; c_red="\033[1;31m"; c_yellow="\033[1;33m"; c_off="\033[0m"
log()  { echo -e "${c_blue}==>${c_off} $*"; }
ok()   { echo -e "${c_green}  ok${c_off} $*"; }
warn() { echo -e "${c_yellow}  !!${c_off} $*"; }
die()  { echo -e "${c_red}ERROR:${c_off} $*" >&2; exit 1; }

# ---------------------------------------------------------------------------
# Comprobaciones
# ---------------------------------------------------------------------------
require_root() { [ "$(id -u)" -eq 0 ] || die "Ejecuta con sudo/root: sudo bash $0"; }

require_env() {
  [ -f "$ENV_FILE" ] || die ".env no encontrado en $ENV_FILE — pega tu .env ahi antes de continuar (vi $ENV_FILE)."
}

# ---------------------------------------------------------------------------
# Deteccion de distro / gestor de paquetes / usuario web
# ---------------------------------------------------------------------------
detect_os() {
  if command -v apt-get >/dev/null 2>&1; then
    PKG="apt"
    WEB_USER="${WEB_USER_OVERRIDE:-www-data}"
  elif command -v dnf >/dev/null 2>&1; then
    PKG="dnf"
    WEB_USER="${WEB_USER_OVERRIDE:-nginx}"
  elif command -v yum >/dev/null 2>&1; then
    PKG="yum"
    WEB_USER="${WEB_USER_OVERRIDE:-nginx}"
  else
    die "Gestor de paquetes no soportado (ni apt ni dnf/yum)."
  fi
}

detect_php() {
  if [ "$PKG" = "apt" ]; then
    FPM_SERVICE="php${PHP_VER}-fpm"
    PHP_CONFD="/etc/php/${PHP_VER}/fpm/conf.d"
    FPM_POOL="/etc/php/${PHP_VER}/fpm/pool.d/www.conf"
  else
    FPM_SERVICE="php-fpm"
    PHP_CONFD="/etc/php.d"
    FPM_POOL="/etc/php-fpm.d/www.conf"
  fi
}

selinux_enforcing() { command -v getenforce >/dev/null 2>&1 && [ "$(getenforce 2>/dev/null)" = "Enforcing" ]; }

# ---------------------------------------------------------------------------
# IP publica / dominio
# ---------------------------------------------------------------------------
public_ip() {
  local ip=""
  ip="$(curl -fsS --max-time 8 https://api.ipify.org 2>/dev/null || true)"
  [ -z "$ip" ] && ip="$(curl -fsS --max-time 8 https://ifconfig.me 2>/dev/null || true)"
  if [ -z "$ip" ]; then
    ip="$(curl -fsS --max-time 8 -H 'Authorization: Bearer Oracle' \
      http://169.254.169.254/opc/v2/vnics/ 2>/dev/null | grep -o '"publicIp"[ :]*"[^"]*"' | head -1 | grep -o '[0-9.]\+' || true)"
  fi
  echo "$ip"
}

resolve_domain() {
  if [ -n "$DUCKDNS_SUBDOMAIN" ]; then echo "${DUCKDNS_SUBDOMAIN}.duckdns.org"; return; fi
  if [ -n "$APP_DOMAIN" ]; then echo "$APP_DOMAIN"; return; fi
  die "No hay dominio configurado. Define DUCKDNS_SUBDOMAIN y DUCKDNS_TOKEN en deploy/deploy.conf (o ejecuta deploy.sh para configurarlo)."
}

# ---------------------------------------------------------------------------
# Helpers .env  (sin reordenar valores con '=' en su contenido)
# ---------------------------------------------------------------------------
env_get() { { grep -E "^$1=" "$ENV_FILE" 2>/dev/null || true; } | head -1 | cut -d= -f2- | sed -e 's/^"//' -e 's/"$//'; }

set_env() {
  local key="$1"; shift; local val="$*"
  local tmp; tmp="$(mktemp)"
  grep -vE "^${key}=" "$ENV_FILE" > "$tmp" 2>/dev/null || true
  printf '%s=%s\n' "$key" "$val" >> "$tmp"
  mv "$tmp" "$ENV_FILE"
}

# php-fpm como WEB_USER y socket fijo
patch_fpm_pool() {
  [ -f "$FPM_POOL" ] || die "Pool php-fpm no encontrado: $FPM_POOL"
  sed -ri "s|^;?\s*user\s*=.*|user = ${WEB_USER}|"            "$FPM_POOL"
  sed -ri "s|^;?\s*group\s*=.*|group = ${WEB_USER}|"          "$FPM_POOL"
  sed -ri "s|^;?\s*listen\s*=.*|listen = ${FPM_SOCK}|"        "$FPM_POOL"
  sed -ri "s|^;?\s*listen.owner\s*=.*|listen.owner = ${WEB_USER}|" "$FPM_POOL"
  sed -ri "s|^;?\s*listen.group\s*=.*|listen.group = ${WEB_USER}|" "$FPM_POOL"
  sed -ri "s|^;?\s*listen.mode\s*=.*|listen.mode = 0660|"     "$FPM_POOL"
}

# Persistir una variable en deploy.conf (conserva comentarios, sobreescribe la activa)
persist_conf() {
  local key="$1" val="$2" file="$LIB_DIR/deploy.conf"
  local tmp; tmp="$(mktemp)"
  grep -vE "^${key}=" "$file" > "$tmp" 2>/dev/null || true
  printf '%s="%s"\n' "$key" "$val" >> "$tmp"
  mv "$tmp" "$file"
}

# Pregunta interactiva por el dominio DuckDNS (solo si aun no esta configurado). Idempotente.
prompt_domain() {
  # Ya configurado (deploy.conf, dominio propio o re-ejecucion): no preguntar.
  if [ -n "$DUCKDNS_SUBDOMAIN" ] || [ -n "$APP_DOMAIN" ]; then
    return
  fi
  # Sin terminal interactiva no se puede preguntar: hay que configurarlo en deploy.conf.
  if [ ! -t 0 ]; then
    die "Dominio DuckDNS no configurado y sin terminal interactiva. Define DUCKDNS_SUBDOMAIN y DUCKDNS_TOKEN en deploy/deploy.conf."
  fi

  echo
  echo "============================================================"
  echo " Dominio DuckDNS de tu web (ej: vexis -> vexis.duckdns.org)"
  echo " Crea cuenta gratis y obten el token en https://www.duckdns.org"
  echo "============================================================"
  local sub tok
  while :; do
    read -rp " Subdominio DuckDNS (sin .duckdns.org): " sub
    [ -n "$sub" ] && break
    echo "  El subdominio no puede estar vacio."
  done
  while :; do
    read -rp " Token DuckDNS: " tok
    [ -n "$tok" ] && break
    echo "  El token no puede estar vacio."
  done
  persist_conf DUCKDNS_SUBDOMAIN "$sub"
  persist_conf DUCKDNS_TOKEN "$tok"
  DUCKDNS_SUBDOMAIN="$sub"; DUCKDNS_TOKEN="$tok"
  ok "Dominio configurado: ${sub}.duckdns.org"
  echo
}

# Inicializa todas las variables de entorno de los scripts
init() {
  detect_os
  detect_php
}
