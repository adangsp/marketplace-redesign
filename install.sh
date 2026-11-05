#!/usr/bin/env bash
# ==============================================================================
# Script Otomasi Instalasi EduMarket & WAHA Gateway di Ubuntu Server
# Kompatibel: Ubuntu 20.04 LTS, Ubuntu 22.04 LTS, Ubuntu 24.04 LTS
# ==============================================================================

set -e

# Warna Terminal
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m' # No Color

# Banner
clear
echo -e "${CYAN}${BOLD}"
echo "=============================================================================="
echo "    ___       __          __  ___           __        __   _____ ____     "
echo "   /   | ____/ /___ _____/  |/  /___ ______/ /_____  / /_ / ___// __ \    "
echo "  / /| |/ __  / __ \`/ __  / /|_/ / __ \`/ ___/ //_/ _ \/ __/ \__ \/ /_/ /    "
echo " / ___ / /_/ / /_/ / /_/ / /  / / /_/ / /  / ,< /  __/ /_  ___/ / ____/     "
echo "/_/  |_\__,_/\__,_/\__,_/_/  /_/\__,_/_/  /_/|_|\___/\__/ /____/_/          "
echo "                                                                              "
echo "       Script Otomasi Instalasi EduMarket + WAHA WhatsApp Gateway             "
echo "=============================================================================="
echo -e "${NC}"

# 1. Cek Hak Akses Root
if [ "$EUID" -ne 0 ]; then
    echo -e "${RED}[ERROR] Script ini harus dijalankan sebagai root (gunakan sudo bash install.sh)!${NC}"
    exit 1
fi

# 2. Cek Sistem Operasi
if [ ! -f /etc/os-release ]; then
    echo -e "${RED}[ERROR] Sistem operasi tidak didukung. Script ini dikhususkan untuk Ubuntu.${NC}"
    exit 1
fi

. /etc/os-release
if [ "$ID" != "ubuntu" ]; then
    echo -e "${YELLOW}[WARNING] Sistem operasi terdeteksi sebagai $NAME. Script ini dioptimasi untuk Ubuntu.${NC}"
    read -rp "Tetap lanjutkan instalasi? (y/N): " CONTINUE_OS
    if [[ ! "$CONTINUE_OS" =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

# 3. Mendapatkan IP Publik Server
SERVER_IP=$(curl -s -4 ifconfig.me || curl -s -4 icanhazip.com || echo "localhost")

echo -e "${BLUE}[INFO] Menyiapkan parameter instalasi...${NC}"
echo ""

# 4. Input Konfigurasi (Interactive dengan Nilai Default)
read -rp "Masukkan Domain atau IP Server [Default: $SERVER_IP]: " DOMAIN_NAME
DOMAIN_NAME=${DOMAIN_NAME:-$SERVER_IP}

read -rp "Masukkan Direktori Web Root [Default: /var/www/edumarket]: " TARGET_DIR
TARGET_DIR=${TARGET_DIR:-/var/www/edumarket}

read -rp "Masukkan Nama Database MySQL [Default: marketplace_db]: " DB_NAME
DB_NAME=${DB_NAME:-marketplace_db}

read -rp "Masukkan Username Database MySQL [Default: edumarket_user]: " DB_USER
DB_USER=${DB_USER:-edumarket_user}

# Generate password acak jika kosong
DEFAULT_DB_PASS=$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 16 ; echo '')
read -rp "Masukkan Password Database MySQL [Default: Acak]: " DB_PASS
DB_PASS=${DB_PASS:-$DEFAULT_DB_PASS}

read -rp "Ingin menginstal & menjalankan WAHA WhatsApp Gateway via Docker? (Y/n): " INSTALL_WAHA
INSTALL_WAHA=${INSTALL_WAHA:-Y}

read -rp "Ingin menginstal Let's Encrypt SSL Gratis via Certbot? (y/N): " INSTALL_SSL
INSTALL_SSL=${INSTALL_SSL:-N}

if [[ "$INSTALL_SSL" =~ ^[Yy]$ ]] && [[ "$DOMAIN_NAME" == "localhost" || "$DOMAIN_NAME" =~ ^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
    echo -e "${YELLOW}[PERINGATAN] Let's Encrypt membutuhkan domain valid (bukan IP address / localhost). SSL Certbot dilewati.${NC}"
    INSTALL_SSL="N"
fi

if [[ "$INSTALL_SSL" =~ ^[Yy]$ ]]; then
    read -rp "Masukkan Email untuk notifikasi sertifikat SSL: " SSL_EMAIL
fi

echo ""
echo -e "${CYAN}--------------------------------------------------------------${NC}"
echo -e "${BOLD}Ringkasan Konfigurasi:${NC}"
echo -e " Domain / IP       : ${GREEN}$DOMAIN_NAME${NC}"
echo -e " Direktori Web     : ${GREEN}$TARGET_DIR${NC}"
echo -e " Nama Database     : ${GREEN}$DB_NAME${NC}"
echo -e " User Database     : ${GREEN}$DB_USER${NC}"
echo -e " Password Database : ${GREEN}$DB_PASS${NC}"
echo -e " WAHA Gateway      : ${GREEN}$INSTALL_WAHA${NC}"
echo -e " SSL Certbot       : ${GREEN}$INSTALL_SSL${NC}"
echo -e "${CYAN}--------------------------------------------------------------${NC}"
read -rp "Mulai proses instalasi sekarang? (Y/n): " CONFIRM_INSTALL
CONFIRM_INSTALL=${CONFIRM_INSTALL:-Y}
if [[ ! "$CONFIRM_INSTALL" =~ ^[Yy]$ ]]; then
    echo "Instalasi dibatalkan."
    exit 0
fi

# ==============================================================================
# LANGKAH 1: Update Repository & Install Paket Dasar
# ==============================================================================
echo ""
echo -e "${BLUE}=== [1/8] Memperbarui Repository & Menginstal Paket Dasar ===${NC}"
export DEBIAN_FRONTEND=noninteractive
apt-get update -y
apt-get install -y software-properties-common curl git unzip ufw lsb-release ca-certificates apt-transport-https

# ==============================================================================
# LANGKAH 2: Instalasi PHP 8.2 & Ekstensi yang Dibutuhkan
# ==============================================================================
echo ""
echo -e "${BLUE}=== [2/8] Menginstal PHP & Ekstensi yang Diperlukan ===${NC}"
add-apt-repository -y ppa:ondrej/php
apt-get update -y

PHP_VER="8.2"
apt-get install -y \
    php${PHP_VER}-fpm \
    php${PHP_VER}-mysql \
    php${PHP_VER}-curl \
    php${PHP_VER}-mbstring \
    php${PHP_VER}-xml \
    php${PHP_VER}-gd \
    php${PHP_VER}-zip \
    php${PHP_VER}-intl \
    php${PHP_VER}-cli

# Tweak PHP Settings untuk upload file produk besar
PHP_INI="/etc/php/${PHP_VER}/fpm/php.ini"
if [ -f "$PHP_INI" ]; then
    sed -i 's/upload_max_filesize = .*/upload_max_filesize = 64M/' "$PHP_INI"
    sed -i 's/post_max_size = .*/post_max_size = 64M/' "$PHP_INI"
    sed -i 's/memory_limit = .*/memory_limit = 256M/' "$PHP_INI"
    sed -i 's/max_execution_time = .*/max_execution_time = 300/' "$PHP_INI"
    systemctl restart php${PHP_VER}-fpm
fi

# ==============================================================================
# LANGKAH 3: Instalasi & Konfigurasi MySQL Server
# ==============================================================================
echo ""
echo -e "${BLUE}=== [3/8] Menginstal & Mengonfigurasi MySQL Database ===${NC}"
apt-get install -y mysql-server

systemctl enable mysql
systemctl start mysql

# Buat Database dan User MySQL
echo -e "${YELLOW}Menyiapkan Database '$DB_NAME' dan User '$DB_USER'...${NC}"
mysql -u root <<EOF
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED WITH caching_sha2_password BY '${DB_PASS}';
ALTER USER '${DB_USER}'@'localhost' IDENTIFIED WITH caching_sha2_password BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
EOF

# Import Database Schema jika ada
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SQL_FILE="$SCRIPT_DIR/database/marketplace.sql"

if [ -f "$SQL_FILE" ]; then
    echo -e "${YELLOW}Mengimpor schema awal dari database/marketplace.sql...${NC}"
    mysql -u "${DB_USER}" -p"${DB_PASS}" "${DB_NAME}" < "$SQL_FILE" || mysql -u root "${DB_NAME}" < "$SQL_FILE"
    echo -e "${GREEN}[OK] Database berhasil diimpor!${NC}"
else
    echo -e "${YELLOW}[INFO] File marketplace.sql tidak ditemukan di direktori saat ini. Database kosong telah disiapkan dan tabel akan dibuat otomatis saat pertama kali dibuka.${NC}"
fi

# ==============================================================================
# LANGKAH 4: Deploy File Aplikasi ke Web Root
# ==============================================================================
echo ""
echo -e "${BLUE}=== [4/8] Menyalin & Menyiapkan File Aplikasi ===${NC}"
mkdir -p "$TARGET_DIR"

# Jika dijalankan di dalam folder repo aplikasi, salin file ke $TARGET_DIR
if [ "$SCRIPT_DIR" != "$TARGET_DIR" ]; then
    echo -e "${YELLOW}Menyalin file aplikasi dari $SCRIPT_DIR ke $TARGET_DIR...${NC}"
    rsync -av --exclude='.git' --exclude='node_modules' "$SCRIPT_DIR/" "$TARGET_DIR/"
fi

# Buat direktori uploads dan izin akses
mkdir -p "$TARGET_DIR/uploads/logos"
mkdir -p "$TARGET_DIR/uploads/products"
mkdir -p "$TARGET_DIR/uploads/qris"

# Buat file .env di web root
cat <<EOF > "$TARGET_DIR/.env"
# Konfigurasi Lingkungan EduMarket (Dihasilkan Otomatis)
APP_ENV=production
APP_URL=http://${DOMAIN_NAME}

DB_HOST=localhost
DB_NAME=${DB_NAME}
DB_USER=${DB_USER}
DB_PASS=${DB_PASS}
DB_CHARSET=utf8mb4

WAHA_URL=http://localhost:3000
WAHA_API_KEY=
WAHA_SESSION=default
EOF

# Set Kepemilikan & Hak Akses
chown -R www-data:www-data "$TARGET_DIR"
find "$TARGET_DIR" -type d -exec chmod 755 {} \;
find "$TARGET_DIR" -type f -exec chmod 644 {} \;
chmod -R 775 "$TARGET_DIR/uploads"
chmod 600 "$TARGET_DIR/.env"

# ==============================================================================
# LANGKAH 5: Instalasi & Konfigurasi Nginx
# ==============================================================================
echo ""
echo -e "${BLUE}=== [5/8] Menginstal & Mengonfigurasi Nginx Web Server ===${NC}"
apt-get install -y nginx

NGINX_CONF="/etc/nginx/sites-available/edumarket.conf"

cat <<'EOF' > "$NGINX_CONF"
server {
    listen 80;
    listen [::]:80;
    server_name __DOMAIN_NAME__;

    root __TARGET_DIR__;
    index index.php index.html;

    charset utf-8;
    client_max_body_size 64M;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Logging
    access_log /var/log/nginx/edumarket_access.log;
    error_log /var/log/nginx/edumarket_error.log;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml application/json application/javascript application/rss+xml application/atom+xml image/svg+xml;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php__PHP_VER__-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 32k;
    }

    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
        access_log off;
    }

    # Blokir akses ke file tersembunyi dan folder konfigurasi sensitif
    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~ /(config|database|services|\.git|\.env) {
        deny all;
        return 404;
    }

    location ~* /uploads/.*\.php$ {
        deny all;
        return 403;
    }
}
EOF

# Substitusi variabel di konfigurasi Nginx
sed -i "s|__DOMAIN_NAME__|${DOMAIN_NAME}|g" "$NGINX_CONF"
sed -i "s|__TARGET_DIR__|${TARGET_DIR}|g" "$NGINX_CONF"
sed -i "s|__PHP_VER__|${PHP_VER}|g" "$NGINX_CONF"

# Hapus default site dan pasang symlink site baru
rm -f /etc/nginx/sites-enabled/default
ln -sf "$NGINX_CONF" /etc/nginx/sites-enabled/edumarket.conf

nginx -t
systemctl restart nginx
systemctl enable nginx

# ==============================================================================
# LANGKAH 6: Instalasi & Menjalankan WAHA Gateway via Docker
# ==============================================================================
if [[ "$INSTALL_WAHA" =~ ^[Yy]$ ]]; then
    echo ""
    echo -e "${BLUE}=== [6/8] Menginstal Docker & Menjalankan WAHA WhatsApp Gateway ===${NC}"
    if ! command -v docker &> /dev/null; then
        echo -e "${YELLOW}Menginstal Docker Engine...${NC}"
        curl -fsSL https://get.docker.com -o get-docker.sh
        sh get-docker.sh
        rm -f get-docker.sh
        systemctl enable docker
        systemctl start docker
    fi

    # Hentikan kontainer lama jika ada
    if [ "$(docker ps -aq -f name=waha)" ]; then
        echo -e "${YELLOW}Merestart kontainer WAHA yang sudah ada...${NC}"
        docker rm -f waha || true
    fi

    # Buat direktori persistensi sesi WAHA
    mkdir -p /var/waha-data

    echo -e "${YELLOW}Menjalankan WAHA Container (devlikeapro/waha) di port 3000...${NC}"
    docker run -d \
        --name waha \
        --restart unless-stopped \
        -p 3000:3000/tcp \
        -v /var/waha-data:/app/.wwebjs_auth \
        -e WHATSAPP_DEFAULT_ENGINE=WEBJS \
        devlikeapro/waha

    echo -e "${GREEN}[OK] WAHA WhatsApp Gateway berhasil dijalankan di http://localhost:3000!${NC}"
else
    echo ""
    echo -e "${YELLOW}=== [6/8] Instalasi WAHA dilewati sesuai permintaan ===${NC}"
fi

# ==============================================================================
# LANGKAH 7: Konfigurasi Firewall UFW
# ==============================================================================
echo ""
echo -e "${BLUE}=== [7/8] Mengonfigurasi Firewall (UFW) ===${NC}"
ufw allow OpenSSH || ufw allow 22/tcp
ufw allow 'Nginx Full' || (ufw allow 80/tcp && ufw allow 443/tcp)
if [[ "$INSTALL_WAHA" =~ ^[Yy]$ ]]; then
    ufw allow 3000/tcp comment 'WAHA WhatsApp Gateway'
fi
ufw --force enable

# ==============================================================================
# LANGKAH 8: Instalasi SSL Let's Encrypt (Certbot)
# ==============================================================================
if [[ "$INSTALL_SSL" =~ ^[Yy]$ ]]; then
    echo ""
    echo -e "${BLUE}=== [8/8] Memasang Sertifikat SSL Let's Encrypt ===${NC}"
    apt-get install -y certbot python3-certbot-nginx
    certbot --nginx -d "${DOMAIN_NAME}" --non-interactive --agree-tos -m "${SSL_EMAIL}" --redirect || {
        echo -e "${YELLOW}[WARNING] Pemasangan SSL otomatis belum berhasil. Pastikan DNS domain ${DOMAIN_NAME} telah mengarah ke IP server ${SERVER_IP}.${NC}"
    }
else
    echo ""
    echo -e "${BLUE}=== [8/8] Konfigurasi SSL dilewati ===${NC}"
fi

# ==============================================================================
# SELESAI & RINGKASAN
# ==============================================================================
echo ""
echo -e "${GREEN}${BOLD}=============================================================================="
echo "                   INSTALASI BERHASIL DISELESAIKAN! 🎉"
echo "==============================================================================${NC}"
echo ""
echo -e "${BOLD}Detail Akses Aplikasi:${NC}"
echo -e " 🌐 URL Website          : ${CYAN}http://${DOMAIN_NAME}/${NC}"
echo -e " 🔑 URL Admin Portal     : ${CYAN}http://${DOMAIN_NAME}/admin/${NC}"
echo -e " 👤 Akun Admin Default   : Username: ${BOLD}admin${NC} | Password: ${BOLD}admin123${NC}"
echo -e " 📁 Direktori Web        : ${TARGET_DIR}"
echo -e " 🗄️ Database             : ${DB_NAME} (User: ${DB_USER})"
echo -e " 💬 WAHA Dashboard       : ${CYAN}http://${DOMAIN_NAME}:3000/dashboard${NC}"
echo ""
echo -e "${BOLD}Langkah Selanjutnya:${NC}"
echo -e " 1. Buka browser dan login ke: ${CYAN}http://${DOMAIN_NAME}/admin/${NC}"
echo -e " 2. Buka menu ${BOLD}WhatsApp Gateway${NC} > klik ${GREEN}Mulai Sesi${NC} lalu scan QR Code."
echo -e " 3. Ubah password default admin di menu ${BOLD}Ganti Password${NC} untuk keamanan."
echo ""
echo -e "${BOLD}Perintah Berguna di Server:${NC}"
echo -e " - Cek status Web Server : ${YELLOW}systemctl status nginx${NC}"
echo -e " - Cek status PHP-FPM    : ${YELLOW}systemctl status php${PHP_VER}-fpm${NC}"
echo -e " - Cek log WAHA Docker   : ${YELLOW}docker logs -f waha${NC}"
echo -e " - Restart WAHA Docker   : ${YELLOW}docker restart waha${NC}"
echo -e "${GREEN}==============================================================================${NC}"
EOF
