# 🎓 EduMarket - Marketplace Produk Digital & Aplikasi Sekolah

EduMarket adalah landing page marketplace modern, interaktif, dan responsif yang dirancang khusus untuk memamerkan serta menjual produk digital berupa **aplikasi web pendidikan, pembelajaran, dan sistem informasi sekolah**.

---

## 🚀 Fitur Unggulan

1. **Desain Modern & Canggih**
   - Tampilan visual premium dengan kombinasi palet warna modern, Glassmorphism, efek glow, dan partikel animasi hero section.
   - **Dark Mode / Light Mode**: Mendukung perubahan tema gelap & terang secara instan dengan penyimpanan preferensi di `localStorage`.
   - **Responsif Penuh**: Optimal diakses dari Smartphone, Tablet, Laptop, hingga Desktop monitor lebar.

2. **Koleksi Produk Pendidikan Lengkap**
   - Kategori aplikasi: *Aplikasi Sekolah*, *E-Learning*, *Sistem Informasi*, dan *Aplikasi Pembelajaran*.
   - Filter instan berdasarkan kategori tanpa reload halaman.
   - Pencarian cerdas (*Realtime Search dengan Debounce*) dan pengurutan (*Harga Terendah/Tertinggi, Rating, Terpopuler*).
   - Modal Detail Produk interaktif: Galeri gambar/thumbnail, checklist fitur, badge teknologi (*Tech Stack*), dan ulasan pengguna.

3. **Alur Pemesanan & Checkout Interaktif (SweetAlert2)**
   - Formulir pembelian instan menggunakan popup modal SweetAlert2.
   - Pilihan metode pembayaran: **QRIS Instan**, **Transfer Bank Virtual Account**, dan **E-Wallet** (Dana, OVO, GoPay).
   - Menghasilkan nomor invoice unik otomatis (contoh: `EDU-202611-8921`).
   - Integrasi tombol langsung ke WhatsApp Admin untuk konfirmasi pesanan cepat.

4. **Portal & Dashboard Admin Terpadu (Content Management System)**
   - **Login & Otentikasi Aman**: Halaman login modern dengan session PHP, proteksi middleware, toggle show/hide password, dan kredensial default auto-fill.
   - **Manajemen Produk Digital**: Tambah produk baru, edit nama, kategori, harga normal, harga diskon, upload foto produk (Drag & Drop / File Browser / URL), atur fitur, tech stack, status aktif, serta badge Bestseller / Featured.
   - **Upload Foto Produk & QRIS**: Handler upload file aman (`uploads/products/` & `uploads/payments/`) dengan validasi MIME, ekstensi, dan ukuran file.
   - **Manajemen Metode Pembayaran**: Konfigurasi nomor rekening bank (BCA, Mandiri, BRI), E-Wallet (Dana, OVO, GoPay), QRIS resmi, pemilik rekening, dan petunjuk transfer dengan switch aktif/nonaktif realtime.
   - **Manajemen Pesanan Masuk**: Monitoring transaksi customer, pembaruan status pembayaran (*Pending* -> *Paid* -> *Expired* -> *Refunded*) dengan SweetAlert2, serta tombol direct WhatsApp konfirmasi.
   - **Statistik & Analitik Penjualan**: Ringkasan total produk, estimasi omset/pendapatan, pesanan menunggu konfirmasi, dan pesanan terbaru.
   - **Profil Toko & Pengaturan Branding**: Kustomisasi nama marketplace/toko, tagline, deskripsi profil, upload logo toko & favicon dengan drag & drop dan live preview, nomor WhatsApp & telepon kantor, email support, jam kerja operasional, tautan media sosial (Instagram, Facebook, YouTube), serta jaminan garansi dan teks hak cipta.
   - **Ganti Kata Sandi & Keamanan Akun**: Fitur pembaruan kata sandi admin secara mandiri dengan validasi kata sandi lama, proteksi hashing `bcrypt`, toggle visibilitas sandi, dan notifikasi SweetAlert2.

5. **Interaktivitas & Animasi Menarik**
   - Preloader halus dengan animasi ring spinner.
   - Efek mesin ketik (*Typing Effect*) dinamis pada judul hero.
   - Animasi counter angka statistik yang berjalan mulus saat di-scroll (*Intersection Observer*).
   - Testimonial slider / carousel dengan rotasi otomatis (5 detik), tombol navigasi, dan indikator dot.
   - Akordion FAQ (*Frequently Asked Questions*) yang responsif.
   - Efek gelombang ripple pada tombol saat diklik.
   - *Easter Egg* seru: Tekan kode Konami (`↑ ↑ ↓ ↓ ← → ← → B A`) pada keyboard untuk membuka kupon diskon rahasia!

6. **Backend API & Database Siap Pakai**
   - Arsitektur RESTful API PHP dengan PDO (*Prepared Statements*) yang aman dari ancaman SQL Injection.
   - Validasi input formulir di sisi klien (JS) dan sisi server (PHP).
   - Sistem *Fallback Offline & Auto-Migration*: Panel admin dan landing page otomatis membuat tabel `admins` & `payment_settings` jika belum ada, serta tetap berjalan jika MySQL sedang offline.

---

## 🛠️ Teknologi yang Digunakan

- **Frontend**: HTML5 Semantik, CSS3 Modern (Custom Variables, Flexbox, CSS Grid), JavaScript (Vanilla ES6+)
- **Backend**: PHP 8.x (REST API, PDO)
- **Database**: MySQL 8.x / MariaDB
- **Library & Icons**:
  - [SweetAlert2](https://sweetalert2.github.io/) (Modal interaktif, alert transaksi, ulasan)
  - [Font Awesome 6.5](https://fontawesome.com/) (Ikon antarmuka)
  - [AOS (Animate On Scroll)](https://michalsnik.github.io/aos/) (Animasi saat halaman digulir)
  - [Google Fonts](https://fonts.google.com/) (*Plus Jakarta Sans* & *Inter*)

---

## 📂 Struktur Direktori Proyek

```plaintext
c:\laragon\www\landing-page\
├── .htaccess                   # Konfigurasi Apache, URL rewrite, kompresi Gzip, & cache
├── index.html                  # Halaman utama landing page marketplace
├── README.md                   # Dokumentasi proyek
├── admin/                      # Portal & Dashboard Admin
│   ├── auth.php                # Session check & middleware otentikasi
│   ├── login.php               # Halaman login admin modern & responsive
│   ├── logout.php              # Logout session handler
│   ├── index.php               # Dashboard admin (kelola produk, harga, foto, bayar, pesanan)
│   └── api/                    # RESTful API khusus Admin
│       ├── auth.php            # Verifikasi login admin
│       ├── orders.php          # CRUD & update status pesanan
│       ├── payments.php        # CRUD metode pembayaran (Bank, QRIS, E-Wallet)
│       ├── products.php        # CRUD produk aplikasi & upload foto
│       ├── stats.php           # Data statistik dashboard
│       └── upload.php          # File upload handler (foto produk & QRIS)
├── api/                        # RESTful API Publik (Landing Page)
│   ├── contact.php             # Endpoint penanganan pesan kontak
│   ├── orders.php              # Endpoint pembuatan invoice pesanan
│   ├── products.php            # Endpoint katalog & detail produk
│   ├── reviews.php             # Endpoint ulasan & rating produk
│   └── subscribe.php           # Endpoint pendaftaran newsletter
├── assets/
│   ├── css/
│   │   └── style.css           # Stylesheet landing page (tema gelap, animasi, responsif)
│   ├── js/
│   │   └── app.js              # Logika interaktif frontend (SweetAlert2, modal, filter)
│   └── images/
│       ├── favicon.svg         # Favicon vektor EduMarket
│       └── qris-sample.svg     # Sampel kode QRIS nasional
├── config/
│   └── database.php            # Koneksi database PDO & auto-migration
├── database/
│   └── marketplace.sql         # Skema database MySQL lengkap (products, admins, payments, orders)
└── uploads/                    # Direktori penyimpanan file yang diunggah
    ├── products/               # Foto produk aplikasi
    ├── payments/               # Foto/QRIS metode pembayaran
    └── avatars/                # Foto profil admin
```

---

## 🔑 Kredensial Login Admin

Untuk masuk ke panel admin:
- **URL Login**: `http://localhost/landing-page/admin/login.php`
- **Username**: `admin`
- **Password**: `admin123`

*(Tersedia tombol **"Auto Fill"** di halaman login untuk mengisi username dan password secara instan).*

---

## 💻 Panduan Menjalankan di Laragon

### Langkah 1: Jalankan Laragon
1. Buka aplikasi **Laragon**.
2. Klik tombol **Start All** untuk mengaktifkan Apache dan MySQL.

### Langkah 2: Import Database MySQL
1. Buka antarmuka database favorit Anda (HeidiSQL bawaan Laragon atau phpMyAdmin di `http://localhost/phpmyadmin`).
2. Buat database baru bernama `marketplace_db` (atau langsung jalankan file SQL).
3. Buka dan eksekusi file:
   `c:\laragon\www\landing-page\database\marketplace.sql`
4. Seluruh tabel (`products`, `reviews`, `orders`, `contacts`, `testimonials`, `subscribers`) beserta data produk edukasi otomatis terisi.

### Langkah 3: Akses Melalui Browser
Buka browser Anda dan kunjungi salah satu URL berikut:
- **`http://localhost/landing-page/`**
- atau **`http://landing-page.test/`** (jika virtual host otomatis Laragon aktif).

---

## 📦 Produk Edukasi Bawaan (Sample Data)

1. **EduSchool Pro**: Sistem Informasi Sekolah Terpadu (SPP, Akademik, Siswa & Guru)
2. **SmartLearn LMS**: Learning Management System berbasis modul video & penugasan
3. **AbsensiKu Digital**: Presensi digital guru & siswa berbasis QR Code dan radius GPS
4. **RaporDigital**: Pengolahan nilai & pencetakan Rapor Kurikulum Merdeka & K13
5. **QuizMaster Pro**: Ujian Berbasis Komputer (CBT) dengan fitur proteksi anti-curang
6. **Perpustakaan Digital (DigiLib)**: Sirkulasi buku barcode & pembaca E-Book online
7. **PPDB Online Pro**: Portal pendaftaran peserta didik baru (Zonasi, Afirmasi, Prestasi)
8. **E-Counseling BK Sekolah**: Aplikasi Bimbingan Konseling & pencatatan poin kedisiplinan

---

## 🔒 Keamanan & Praktik Terbaik

- Menggunakan **PDO Prepared Statements** untuk mencegah SQL Injection.
- Sanitasi input dan proteksi XSS pada semua endpoint API.
- Header keamanan (*X-Content-Type-Options*, *X-Frame-Options*, *X-XSS-Protection*).
- Respons JSON terstruktur dengan format standard HTTP status codes (200, 201, 400, 404, 405, 500).
