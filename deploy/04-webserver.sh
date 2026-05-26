#!/usr/bin/env bash
# 04 — Configura el virtual host de Nginx para Laravel y lo activa.
source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/lib.sh"
require_root
init

DOMAIN="$(resolve_domain)"
VHOST="/etc/nginx/conf.d/vexis.conf"
log "Generando vhost Nginx para ${DOMAIN} -> ${APP_DIR}/public"

# En Ubuntu el site 'default' ocupa el puerto 80: lo retiramos para evitar conflicto.
rm -f /etc/nginx/sites-enabled/default 2>/dev/null || true

cat > "$VHOST" <<EOF
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMAIN};
    root ${APP_DIR}/public;

    index index.php;
    charset utf-8;
    client_max_body_size 50M;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php\$ {
        fastcgi_pass unix:${FPM_SOCK};
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

log "Validando configuracion de Nginx..."
nginx -t
systemctl reload nginx
ok "04 — Nginx sirviendo en http://${DOMAIN}"
