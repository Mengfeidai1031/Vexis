#!/usr/bin/env bash
# 06 — Anti-reclamacion de Oracle Always Free.
#      Oracle recupera instancias "idle" si, durante 7 dias, el percentil 95 de CPU < 20%,
#      red < 10% y memoria < 20% (las 3 a la vez). Generamos rafagas cortas de CPU de baja
#      prioridad (nice 19) que mantienen el p95 de CPU por encima del umbral SIN afectar a
#      la app (cede CPU en cuanto la web la necesita).
source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/lib.sh"
require_root
init

DOMAIN="$(resolve_domain)"
SCRIPT="/usr/local/bin/vexis-keepalive.sh"

log "Instalando script keep-alive en ${SCRIPT}"
cat > "$SCRIPT" <<EOF
#!/usr/bin/env bash
# Rafaga CPU de baja prioridad ~4 min sobre la mitad de los nucleos + ping HTTP.
DURATION=240
CORES=\$(nproc)
N=\$(( CORES / 2 )); [ "\$N" -lt 1 ] && N=1

pids=""
for i in \$(seq 1 "\$N"); do
  timeout "\$DURATION" nice -n 19 sh -c 'while :; do :; done' &
  pids="\$pids \$!"
done

# Trafico de red local (cuenta como actividad de red)
curl -fsS --max-time 10 -o /dev/null "http://${DOMAIN}/" 2>/dev/null || \
  curl -fsS --max-time 10 -o /dev/null "http://127.0.0.1/" 2>/dev/null || true

wait \$pids 2>/dev/null || true
EOF
chmod +x "$SCRIPT"

log "Creando servicio + timer systemd..."
cat > /etc/systemd/system/vexis-keepalive.service <<EOF
[Unit]
Description=VEXIS keep-alive (anti idle reclamation Oracle Cloud)
After=network-online.target

[Service]
Type=oneshot
Nice=19
IOSchedulingClass=idle
ExecStart=${SCRIPT}
EOF

cat > /etc/systemd/system/vexis-keepalive.timer <<EOF
[Unit]
Description=Lanza VEXIS keep-alive cada 25 min

[Timer]
OnBootSec=5min
OnUnitActiveSec=25min
AccuracySec=1min
Persistent=true

[Install]
WantedBy=timers.target
EOF

systemctl daemon-reload
systemctl enable --now vexis-keepalive.timer
systemctl start vexis-keepalive.service 2>/dev/null || true

ok "06 — Keep-alive activo (rafaga cada 25 min). Estado: systemctl list-timers vexis-keepalive.timer"
