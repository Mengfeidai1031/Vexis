#!/usr/bin/env bash
# 05 — Emite certificado Let's Encrypt (HTTP-01) y pasa el sitio a HTTPS.
#      Graceful: si falla (rate limit, DNS, etc.) el sitio sigue operativo en HTTP.
source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/lib.sh"
require_root
require_env
init

if [ "${ENABLE_SSL}" != "true" ]; then
  warn "ENABLE_SSL=false — se omite HTTPS. Sitio en HTTP."
  exit 0
fi

DOMAIN="$(resolve_domain)"
EMAIL="$(env_get MAIL_FROM_ADDRESS)"; EMAIL="${EMAIL:-admin@${DOMAIN}}"

log "Solicitando certificado para ${DOMAIN}..."
if certbot --nginx -d "$DOMAIN" --non-interactive --agree-tos -m "$EMAIL" --redirect; then
  ok "Certificado emitido. Subiendo APP_URL a https."
  cd "$APP_DIR"
  set_env APP_URL "https://${DOMAIN}"
  set_env SESSION_SECURE_COOKIE true
  php artisan config:cache
  php artisan optimize
  systemctl reload nginx
  systemctl enable --now certbot.timer 2>/dev/null || true
  ok "05 — HTTPS activo en https://${DOMAIN}"
else
  warn "No se pudo emitir el certificado (rate limit / DNS / puerto 80 cerrado en consola OCI)."
  warn "El sitio sigue funcionando en HTTP. Reintenta luego: sudo certbot --nginx -d ${DOMAIN}"
fi
