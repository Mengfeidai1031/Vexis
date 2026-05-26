#!/usr/bin/env bash
# 02b — (Opcional) Dominio con nombre via DuckDNS: vexis.duckdns.org
#       Solo actua si DUCKDNS_SUBDOMAIN y DUCKDNS_TOKEN estan definidos en deploy.conf.
#       Apunta el dominio a la IP publica y lo mantiene actualizado cada 5 min.
source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/lib.sh"
require_root
init

# Si se ejecuta suelto y aun no hay dominio decidido, preguntar aqui.
prompt_domain

if [ -z "$DUCKDNS_SUBDOMAIN" ] || [ -z "$DUCKDNS_TOKEN" ]; then
  warn "DuckDNS no configurado en deploy.conf — se usara sslip.io. (02b omitido)"
  exit 0
fi

IP="$(public_ip)"
[ -n "$IP" ] || die "No pude detectar la IP publica para DuckDNS."
log "Apuntando ${DUCKDNS_SUBDOMAIN}.duckdns.org -> ${IP}"

RESP="$(curl -fsS "https://www.duckdns.org/update?domains=${DUCKDNS_SUBDOMAIN}&token=${DUCKDNS_TOKEN}&ip=${IP}" || true)"
if [ "$RESP" != "OK" ]; then
  die "DuckDNS respondio '${RESP}'. Revisa que el subdominio '${DUCKDNS_SUBDOMAIN}' exista en tu cuenta y que el TOKEN sea correcto."
fi
ok "DuckDNS actualizado (respuesta: OK)."

# Updater periodico (ip vacio = DuckDNS detecta la IP de origen automaticamente)
SCRIPT="/usr/local/bin/vexis-duckdns.sh"
cat > "$SCRIPT" <<EOF
#!/usr/bin/env bash
curl -fsS "https://www.duckdns.org/update?domains=${DUCKDNS_SUBDOMAIN}&token=${DUCKDNS_TOKEN}&ip=" -o /var/log/vexis-duckdns.log 2>/dev/null || true
EOF
chmod +x "$SCRIPT"

cat > /etc/systemd/system/vexis-duckdns.service <<EOF
[Unit]
Description=VEXIS DuckDNS IP updater
After=network-online.target

[Service]
Type=oneshot
ExecStart=${SCRIPT}
EOF

cat > /etc/systemd/system/vexis-duckdns.timer <<EOF
[Unit]
Description=Actualiza la IP en DuckDNS cada 5 min

[Timer]
OnBootSec=2min
OnUnitActiveSec=5min
Persistent=true

[Install]
WantedBy=timers.target
EOF

systemctl daemon-reload
systemctl enable --now vexis-duckdns.timer

# Esperar a que el DNS resuelva (necesario para Let's Encrypt en 05)
log "Esperando propagacion DNS de ${DUCKDNS_SUBDOMAIN}.duckdns.org..."
for i in $(seq 1 12); do
  RES="$(getent hosts "${DUCKDNS_SUBDOMAIN}.duckdns.org" | awk '{print $1}' | head -1 || true)"
  [ "$RES" = "$IP" ] && { ok "Resuelve a ${IP}."; break; }
  sleep 5
done

ok "02b — DuckDNS listo: https://${DUCKDNS_SUBDOMAIN}.duckdns.org"
