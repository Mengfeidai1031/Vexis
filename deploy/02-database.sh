#!/usr/bin/env bash
# 02 — Crea la base de datos y el usuario MariaDB leyendo los valores de tu .env.
source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/lib.sh"
require_root
require_env
init

DB_NAME="$(env_get DB_DATABASE)"
DB_USER="$(env_get DB_USERNAME)"
DB_PASS="$(env_get DB_PASSWORD)"
DB_HOST="$(env_get DB_HOST)"; DB_HOST="${DB_HOST:-127.0.0.1}"

[ -n "$DB_NAME" ] || die "DB_DATABASE vacio en .env"
[ -n "$DB_USER" ] || die "DB_USERNAME vacio en .env"
[ -n "$DB_PASS" ] || die "DB_PASSWORD vacio en .env"

log "Creando BD '$DB_NAME' y usuario '$DB_USER'@'localhost'..."

# Conexion root via socket unix (MariaDB lo permite sin password en fresh install).
mysql --protocol=socket -uroot <<SQL
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
ALTER USER '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
CREATE USER IF NOT EXISTS '${DB_USER}'@'127.0.0.1' IDENTIFIED BY '${DB_PASS}';
ALTER USER '${DB_USER}'@'127.0.0.1' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'127.0.0.1';
FLUSH PRIVILEGES;
SQL

# Verificacion de login del usuario de la app
if mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" -e "USE \`${DB_NAME}\`;" 2>/dev/null; then
  ok "Login de '$DB_USER' en '$DB_NAME' verificado."
else
  die "El usuario '$DB_USER' no puede conectar. Revisa DB_* en .env."
fi

ok "02 — Base de datos lista."
