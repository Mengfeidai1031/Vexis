#!/usr/bin/env bash
# 01 — Instala el stack completo: Nginx, PHP-FPM 8.3 + extensiones, MariaDB,
#      Composer, Node 20, poppler-utils (pdftotext), certbot. Configura firewall.
source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/lib.sh"
require_root
init

log "Distro detectada: PKG=$PKG  WEB_USER=$WEB_USER  PHP=$PHP_VER"

# ---------------------------------------------------------------------------
# Paquetes base + repos
# ---------------------------------------------------------------------------
if [ "$PKG" = "apt" ]; then
  export DEBIAN_FRONTEND=noninteractive
  log "Actualizando indices apt..."
  apt-get update -y
  apt-get install -y software-properties-common curl ca-certificates gnupg lsb-release unzip git

  log "Anadiendo PPA ondrej/php..."
  add-apt-repository -y ppa:ondrej/php
  apt-get update -y

  log "Instalando Nginx, MariaDB y PHP ${PHP_VER}..."
  apt-get install -y nginx mariadb-server poppler-utils \
    php${PHP_VER}-fpm php${PHP_VER}-cli php${PHP_VER}-mysql php${PHP_VER}-mbstring \
    php${PHP_VER}-xml php${PHP_VER}-curl php${PHP_VER}-zip php${PHP_VER}-gd \
    php${PHP_VER}-bcmath php${PHP_VER}-intl php${PHP_VER}-gmp

  log "Instalando Node 20 (NodeSource)..."
  curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
  apt-get install -y nodejs

  log "Instalando certbot..."
  apt-get install -y certbot python3-certbot-nginx

else
  # ----- Oracle Linux / RHEL family (dnf/yum) -----
  PM="$PKG"
  log "Instalando utilidades base..."
  $PM install -y curl ca-certificates gnupg2 unzip git policycoreutils

  OSVER="$(rpm -E %rhel 2>/dev/null || echo 9)"
  log "Habilitando EPEL y Remi (EL${OSVER})..."
  $PM install -y "https://dl.fedoraproject.org/pub/epel/epel-release-latest-${OSVER}.noarch.rpm" || true
  $PM install -y "https://rpms.remirepo.net/enterprise/remi-release-${OSVER}.rpm"
  $PM module reset -y php || true
  $PM module enable -y "php:remi-${PHP_VER}"

  log "Instalando Nginx, MariaDB y PHP ${PHP_VER}..."
  $PM install -y nginx mariadb-server poppler-utils \
    php php-fpm php-cli php-mysqlnd php-mbstring php-xml php-curl \
    php-zip php-gd php-bcmath php-intl php-gmp php-opcache

  log "Instalando Node 20 (NodeSource)..."
  curl -fsSL https://rpm.nodesource.com/setup_20.x | bash -
  $PM install -y nodejs

  log "Instalando certbot..."
  $PM install -y certbot python3-certbot-nginx || $PM install -y certbot
fi

# ---------------------------------------------------------------------------
# Composer
# ---------------------------------------------------------------------------
if ! command -v composer >/dev/null 2>&1; then
  log "Instalando Composer..."
  php -r "copy('https://getcomposer.org/installer','/tmp/composer-setup.php');"
  php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
  rm -f /tmp/composer-setup.php
fi
ok "Composer $(composer --version 2>/dev/null | head -1)"

# ---------------------------------------------------------------------------
# Zona horaria + arranque de servicios
# ---------------------------------------------------------------------------
timedatectl set-timezone "$TIMEZONE" 2>/dev/null || true

log "Habilitando servicios (mariadb, php-fpm, nginx) en arranque..."
systemctl enable --now mariadb
systemctl enable --now "$FPM_SERVICE"
systemctl enable --now nginx

# ---------------------------------------------------------------------------
# php-fpm: ejecutar como WEB_USER y socket fijo + limites para subida de PDF
# ---------------------------------------------------------------------------
log "Configurando pool php-fpm (usuario ${WEB_USER}, socket ${FPM_SOCK})..."
patch_fpm_pool

mkdir -p "$PHP_CONFD"
cat > "$PHP_CONFD/99-vexis.ini" <<EOF
; Ajustes VEXIS (subida de PDF de ofertas, exports Excel, generacion de PDF)
upload_max_filesize = 50M
post_max_size = 52M
memory_limit = 512M
max_execution_time = 120
expose_php = Off
EOF

systemctl restart "$FPM_SERVICE"
ok "php-fpm reiniciado"

# ---------------------------------------------------------------------------
# Firewall del sistema operativo (NO el de la consola OCI — ver README)
# ---------------------------------------------------------------------------
log "Abriendo puertos 80/443 en el firewall del SO..."
if systemctl is-active --quiet firewalld 2>/dev/null; then
  firewall-cmd --permanent --add-service=http
  firewall-cmd --permanent --add-service=https
  firewall-cmd --reload
  ok "firewalld: http/https abiertos"
elif command -v ufw >/dev/null 2>&1 && ufw status 2>/dev/null | grep -q "Status: active"; then
  ufw allow 80/tcp
  ufw allow 443/tcp
  ok "ufw: 80/443 abiertos"
else
  # Oracle Ubuntu trae reglas iptables que bloquean todo menos SSH.
  iptables -C INPUT -p tcp --dport 80 -j ACCEPT 2>/dev/null  || iptables -I INPUT -p tcp --dport 80 -j ACCEPT
  iptables -C INPUT -p tcp --dport 443 -j ACCEPT 2>/dev/null || iptables -I INPUT -p tcp --dport 443 -j ACCEPT
  if [ "$PKG" = "apt" ]; then
    apt-get install -y iptables-persistent netfilter-persistent >/dev/null 2>&1 || true
    netfilter-persistent save 2>/dev/null || iptables-save > /etc/iptables/rules.v4 2>/dev/null || true
  else
    service iptables save 2>/dev/null || iptables-save > /etc/sysconfig/iptables 2>/dev/null || true
  fi
  ok "iptables: 80/443 abiertos y persistidos"
fi

ok "01 — Stack instalado."
