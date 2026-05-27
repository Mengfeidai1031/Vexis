#!/usr/bin/env bash
# deploy.sh — Orquestador: ejecuta 01..05 en orden. Equivalente a lanzarlos a mano.
#   sudo bash deploy/deploy.sh
source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/lib.sh"
require_root
require_env

# Pregunta el dominio una sola vez, al principio; el resto corre sin intervencion.
prompt_domain

D="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
for step in 01-install-stack 02-database 02b-duckdns 03-app 04-webserver 05-ssl; do
  echo
  log "================  ${step}  ================"
  bash "$D/${step}.sh"
done

echo
DOMAIN="$(resolve_domain)"
ok "DESPLIEGUE COMPLETO."
ok "Accede a:  https://${DOMAIN}   (o http:// si SSL no se emitio)"
ok "Login Super Admin: mengfei.dai@grupo-dai.com / password"
