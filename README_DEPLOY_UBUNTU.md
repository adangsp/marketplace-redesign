# 🚀 Panduan Otomasi Instalasi di Ubuntu Server

Dokumen ini menyediakan panduan lengkap untuk melakukan instalasi dan deploy aplikasi **EduMarket** beserta **WAHA WhatsApp Gateway** di **Ubuntu Server (20.04 / 22.04 / 24.04 LTS)**.

Tersedia 2 metode instalasi:
1. **Metode 1 (Rekomendasi Native LEMP)**: Menggunakan Script Otomasi `install.sh` (1 Perintah).
2. **Metode 2 (Full Containerized)**: Menggunakan `docker compose up -d`.

---

## 📋 Persyaratan Minimum Server (VPS / Dedicated)
- **OS**: Ubuntu 20.04, 22.04, atau 24.04 LTS (Fresh install disarankan)
- **CPU**: 1 vCPU (2 vCPU disarankan jika menjalankan WAHA browser engine)
- **RAM**: Minimal 1 GB (2 GB disarankan)
- **Storage**: Minimal 10 GB SSD
- **Akses**: Akses `root` atau user dengan hak akses `sudo`
- **Domain**: Opsional (dibutuhkan jika ingin SSL otomatis Let's Encrypt)

---

## ⚡ METODE 1: Menggunakan Script Otomasi `install.sh` (Direkomendasikan)

Script `install.sh` akan secara otomatis melakukan:
- Update repository Ubuntu & instalasi paket dasar.
- Instalasi **PHP 8.2 FPM** lengkap beserta ekstensi (`pdo_mysql`, `curl`, `mbstring`, `xml`, `gd`, `zip`, `intl`).
- Optimalisasi batas upload file produk di `php.ini` (64MB).
- Instalasi & konfigurasi **MySQL Server 8.0**.
- Pembuatan database `marketplace_db`, user MySQL baru, dan auto-import `database/marketplace.sql`.
- Instalasi & konfigurasi **Nginx** dengan virtual host, security headers, gzip, dan cache aset.
- Konfigurasi izin direktori (`www-data:www-data`, chmod uploads).
- Instalasi **Docker** dan otomatis menjalankan **WAHA WhatsApp Gateway** di port `3000`.
- Konfigurasi firewall **UFW** (OpenSSH, Nginx Full 80/443, WAHA 3000).
- Pemasangan gratis sertifikat **SSL Let's Encrypt** via Certbot jika domain diarahkan ke server.

### Langkah-langkah:

### 1. Upload atau Clone Proyek ke Server
Masuk ke server Ubuntu via SSH, lalu upload folder proyek ke server atau clone via git:
```bash
# Contoh clone atau pindah ke folder proyek di server:
cd /root
# (Pastikan file proyek sudah berada di folder ini)
```

### 2. Beri Izin Eksekusi pada Script
```bash
chmod +x install.sh
```

### 3. Jalankan Script Installer
```bash
sudo ./install.sh
```

### 4. Ikuti Panduan Interaktif di Layar
Script akan menanyakan beberapa parameter (cukup tekan `Enter` untuk menggunakan nilai default):
- **Domain atau IP Server**: Masukkan domain Anda (misal: `toko.sekolah.sch.id`) atau IP Publik server.
- **Direktori Web Root**: Default `/var/www/edumarket`.
- **Database Name / User / Password**: Otomatis dibuatkan kredensial aman.
- **Jalankan WAHA WhatsApp Gateway**: Ketik `Y`.
- **Pasang SSL Certbot**: Ketik `Y` (jika menggunakan domain).

Tunggu proses berlangsung sekitar 2-3 menit hingga muncul pesan:
```
==============================================================================
                   INSTALASI BERHASIL DISELESAIKAN! 🎉
==============================================================================
```

---

## 🐳 METODE 2: Menggunakan Docker Compose (Full Container)

Jika Anda menyukai deployment berbasis kontainer Docker murni:

### 1. Pastikan Docker & Docker Compose Terpasang
```bash
sudo apt update
sudo apt install -y docker.io docker-compose-v2
sudo systemctl enable --now docker
```

### 2. Konfigurasi Lingkungan
Salin file template `.env.example`:
```bash
cp .env.example .env
nano .env
```
Sesuaikan password database dan port sesuai kebutuhan.

### 3. Jalankan Semua Layanan
```bash
docker compose up -d
```
Docker akan otomatis mengunduh image, membangun PHP-FPM, menyiapkan database MySQL dari `database/marketplace.sql`, menjalankan Nginx di port 80, serta menjalankan WAHA di port 3000.

---

## 🔑 Akses & Langkah Awal Setelah Instalasi

### 1. Buka Admin Portal
- URL: `http://IP_SERVER_ANDA/admin/` atau `https://DOMAIN_ANDA/admin/`
- **Username Default**: `admin`
- **Password Default**: `admin123`

> ⚠️ **PENTING**: Segera ganti kata sandi admin default melalui menu **Ganti Password** di Admin Panel setelah login pertama kali.

### 2. Menghubungkan WhatsApp Gateway (WAHA)
1. Di Admin Panel, buka menu **WhatsApp Gateway** pada sidebar.
2. Pastikan Status menunjukkan sesi `default`.
3. Klik tombol **Mulai Sesi**.
4. Dalam beberapa detik, **QR Code WhatsApp** akan tampil di layar.
5. Buka aplikasi WhatsApp di HP Anda > **Menu (⋮) / Pengaturan** > **Perangkat Tertaut** > **Tautkan Perangkat** > Scan QR Code di layar.
6. Status akan otomatis berubah menjadi `🟢 TERHUBUNG (WORKING)`.
7. Anda dapat mencoba mengirim pesan uji coba melalui formulir **Uji Coba Pengiriman Pesan**.

---

## 🛠️ Perintah Berguna untuk Pemeliharaan Server

### Cek Status Layanan Web & PHP (Metode 1)
```bash
# Status Web Server Nginx
sudo systemctl status nginx
sudo nginx -t

# Status PHP-FPM
sudo systemctl status php8.2-fpm

# Status Database MySQL
sudo systemctl status mysql
```

### Manajemen WAHA WhatsApp Gateway
```bash
# Cek log WhatsApp real-time
docker logs -f waha

# Restart WAHA
docker restart waha

# Cek penggunaan resource container
docker stats waha
```

### Backup Database Manual
```bash
mysqldump -u edumarket_user -p marketplace_db > /backup_edumarket_$(date +%F).sql
```

### Cek Log Error Nginx
```bash
sudo tail -f /var/log/nginx/edumarket_error.log
```
