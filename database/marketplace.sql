-- =====================================================
-- Digital Product Marketplace - Database Schema
-- Database: marketplace_db
-- Description: Schema for educational/school web 
--              application marketplace
-- =====================================================

-- Create database
CREATE DATABASE IF NOT EXISTS `marketplace_db` 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE `marketplace_db`;

-- =====================================================
-- Table: products
-- Stores all digital product listings
-- =====================================================
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `description` TEXT NOT NULL,
  `short_description` VARCHAR(500) NOT NULL,
  `price` DECIMAL(12,2) NOT NULL,
  `discount_price` DECIMAL(12,2) NULL,
  `category` ENUM('aplikasi-sekolah', 'aplikasi-pembelajaran', 'sistem-informasi', 'e-learning') NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `gallery` JSON NULL,
  `features` JSON NULL,
  `tech_stack` JSON NULL,
  `demo_url` VARCHAR(255) NULL,
  `rating` DECIMAL(3,2) DEFAULT 0.00,
  `total_reviews` INT DEFAULT 0,
  `total_sales` INT DEFAULT 0,
  `is_featured` BOOLEAN DEFAULT FALSE,
  `is_bestseller` BOOLEAN DEFAULT FALSE,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: reviews
-- Stores product reviews from customers
-- =====================================================
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `reviewer_name` VARCHAR(255) NOT NULL,
  `reviewer_email` VARCHAR(255) NOT NULL,
  `reviewer_avatar` VARCHAR(255) NULL,
  `rating` INT NOT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
  `comment` TEXT NOT NULL,
  `is_verified` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: orders
-- Stores customer orders for products
-- =====================================================
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(50) NOT NULL UNIQUE,
  `product_id` INT NOT NULL,
  `customer_name` VARCHAR(255) NOT NULL,
  `customer_email` VARCHAR(255) NOT NULL,
  `customer_phone` VARCHAR(20) NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `payment_method` ENUM('bank_transfer', 'ewallet', 'qris') NOT NULL,
  `payment_status` ENUM('pending', 'paid', 'expired', 'refunded') DEFAULT 'pending',
  `notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: contacts
-- Stores contact form submissions
-- =====================================================
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: testimonials
-- Stores curated testimonials from clients
-- =====================================================
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `position` VARCHAR(255) NOT NULL,
  `institution` VARCHAR(255) NOT NULL,
  `avatar` VARCHAR(255) NULL,
  `content` TEXT NOT NULL,
  `rating` INT NOT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: subscribers
-- Stores newsletter email subscriptions
-- =====================================================
CREATE TABLE IF NOT EXISTS `subscribers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================
-- INDEXES for improved query performance
-- =====================================================
CREATE INDEX `idx_products_category` ON `products`(`category`);
CREATE INDEX `idx_products_status` ON `products`(`status`);
CREATE INDEX `idx_products_featured` ON `products`(`is_featured`);
CREATE INDEX `idx_products_bestseller` ON `products`(`is_bestseller`);
CREATE INDEX `idx_reviews_product` ON `reviews`(`product_id`);
CREATE INDEX `idx_orders_status` ON `orders`(`payment_status`);
CREATE INDEX `idx_orders_email` ON `orders`(`customer_email`);
CREATE INDEX `idx_contacts_read` ON `contacts`(`is_read`);


-- =====================================================
-- SEED DATA: Products (8 educational apps)
-- =====================================================
INSERT INTO `products` (`name`, `slug`, `description`, `short_description`, `price`, `discount_price`, `category`, `image`, `gallery`, `features`, `tech_stack`, `demo_url`, `rating`, `total_reviews`, `total_sales`, `is_featured`, `is_bestseller`) VALUES

-- 1. EduSchool Pro
(
  'EduSchool Pro',
  'eduschool-pro',
  'EduSchool Pro adalah Sistem Informasi Sekolah Terpadu yang dirancang khusus untuk memenuhi kebutuhan administrasi dan manajemen sekolah modern di Indonesia. Dengan fitur lengkap mulai dari pengelolaan data siswa, guru, jadwal pelajaran, hingga keuangan sekolah, EduSchool Pro menjadi solusi all-in-one untuk digitalisasi sekolah Anda.\n\nAplikasi ini telah digunakan oleh lebih dari 500 sekolah di seluruh Indonesia dan terus dikembangkan berdasarkan masukan dari para pengguna. Dengan antarmuka yang intuitif dan responsif, EduSchool Pro dapat diakses dari berbagai perangkat termasuk desktop, tablet, dan smartphone.\n\nDilengkapi dengan dashboard analitik yang powerful, kepala sekolah dan administrator dapat memantau seluruh aktivitas sekolah secara real-time. Sistem notifikasi otomatis memastikan informasi penting tersampaikan kepada guru, siswa, dan orang tua dengan tepat waktu.',
  'Sistem Informasi Sekolah Terpadu dengan fitur lengkap untuk manajemen data siswa, guru, jadwal, keuangan, dan administrasi sekolah modern.',
  2500000.00,
  1999000.00,
  'aplikasi-sekolah',
  'assets/images/products/eduschool-pro.png',
  '["assets/images/products/gallery/eduschool-1.png", "assets/images/products/gallery/eduschool-2.png", "assets/images/products/gallery/eduschool-3.png", "assets/images/products/gallery/eduschool-4.png"]',
  '["Manajemen Data Siswa & Guru", "Penjadwalan Otomatis", "Sistem Keuangan & SPP", "Dashboard Analitik Real-time", "Manajemen Kurikulum", "Portal Orang Tua", "Notifikasi WhatsApp & Email", "Multi-User & Role Management", "Backup Data Otomatis", "Laporan Cetak & Export Excel"]',
  '["PHP 8.1", "Laravel 10", "MySQL 8.0", "Bootstrap 5", "jQuery", "Chart.js", "DataTables", "WhatsApp API"]',
  'https://demo.eduschoolpro.id',
  4.85,
  45,
  523,
  TRUE,
  TRUE
),

-- 2. SmartLearn LMS
(
  'SmartLearn LMS',
  'smartlearn-lms',
  'SmartLearn LMS adalah Learning Management System yang komprehensif untuk mendukung pembelajaran daring dan hybrid di lingkungan pendidikan Indonesia. Platform ini memungkinkan guru untuk membuat, mengelola, dan mendistribusikan materi pembelajaran secara digital dengan mudah.\n\nDilengkapi dengan fitur video conference terintegrasi, forum diskusi, dan sistem penilaian otomatis, SmartLearn LMS menjadi partner terbaik untuk transformasi digital pendidikan. Siswa dapat mengakses materi kapan saja dan dari mana saja melalui browser atau aplikasi mobile.\n\nSistem gamifikasi dengan badges dan leaderboard membuat proses belajar menjadi lebih menyenangkan dan memotivasi siswa untuk terus belajar. Analitik pembelajaran yang detail membantu guru memahami progress setiap siswa.',
  'Learning Management System komprehensif untuk pembelajaran daring dengan fitur video conference, quiz, dan analitik pembelajaran.',
  1800000.00,
  1499000.00,
  'e-learning',
  'assets/images/products/smartlearn-lms.png',
  '["assets/images/products/gallery/smartlearn-1.png", "assets/images/products/gallery/smartlearn-2.png", "assets/images/products/gallery/smartlearn-3.png", "assets/images/products/gallery/smartlearn-4.png"]',
  '["Virtual Classroom & Video Conference", "Bank Soal & Quiz Builder", "Forum Diskusi Interaktif", "Sistem Gamifikasi", "Progress Tracking Siswa", "Upload Materi Multi-Format", "Sistem Penilaian Otomatis", "Notifikasi Real-time", "Sertifikat Digital Otomatis", "Integrasi Google Classroom"]',
  '["PHP 8.1", "Laravel 10", "MySQL 8.0", "Vue.js 3", "Tailwind CSS", "WebRTC", "Socket.io", "Redis"]',
  'https://demo.smartlearn.id',
  4.72,
  38,
  412,
  TRUE,
  FALSE
),

-- 3. AbsensiKu Digital
(
  'AbsensiKu Digital',
  'absensiku-digital',
  'AbsensiKu Digital adalah solusi absensi modern berbasis web yang menggunakan teknologi GPS, QR Code, dan pengenalan wajah untuk mencatat kehadiran siswa dan guru secara akurat dan efisien. Tidak perlu lagi absensi manual yang memakan waktu dan rawan kesalahan.\n\nAplikasi ini terintegrasi dengan notifikasi otomatis ke orang tua saat anak mereka tiba atau meninggalkan sekolah, memberikan ketenangan pikiran bagi para orang tua. Laporan kehadiran tersedia secara real-time dan dapat diakses oleh administrator sekolah kapan saja.\n\nDilengkapi dengan fitur rekap otomatis bulanan dan semester, AbsensiKu Digital menghemat waktu administrasi hingga 80% dibandingkan metode konvensional.',
  'Aplikasi Absensi Digital Sekolah dengan teknologi QR Code, GPS tracking, dan face recognition untuk guru dan siswa.',
  750000.00,
  599000.00,
  'aplikasi-sekolah',
  'assets/images/products/absensiku-digital.png',
  '["assets/images/products/gallery/absensiku-1.png", "assets/images/products/gallery/absensiku-2.png", "assets/images/products/gallery/absensiku-3.png"]',
  '["Absensi QR Code", "GPS Location Tracking", "Face Recognition", "Notifikasi Otomatis ke Orang Tua", "Rekap Kehadiran Otomatis", "Dashboard Monitoring", "Export Laporan Excel/PDF", "Multi-Shift Support"]',
  '["PHP 8.0", "CodeIgniter 4", "MySQL 5.7", "Bootstrap 5", "jQuery", "Face-API.js", "Google Maps API"]',
  'https://demo.absensiku.id',
  4.60,
  28,
  387,
  FALSE,
  FALSE
),

-- 4. RaporDigital
(
  'RaporDigital',
  'rapor-digital',
  'RaporDigital adalah sistem raport digital dan e-raport yang sesuai dengan kurikulum terbaru (Kurikulum Merdeka) dan kurikulum 2013. Aplikasi ini memudahkan guru dalam menginput nilai, mengelola kompetensi, dan menghasilkan raport yang profesional secara otomatis.\n\nDengan RaporDigital, proses pembuatan raport yang biasanya memakan waktu berminggu-minggu kini dapat diselesaikan hanya dalam hitungan jam. Sistem kalkulasi otomatis memastikan akurasi nilai dan mengurangi risiko kesalahan input.\n\nAplikasi ini mendukung berbagai format raport termasuk raport K-13, raport Kurikulum Merdeka, raport mid-semester, dan raport khusus. Orang tua dapat mengakses raport anak secara online melalui portal khusus.',
  'Sistem Raport Digital & E-Raport sesuai Kurikulum Merdeka dan K-13 dengan kalkulasi otomatis dan cetak raport profesional.',
  1200000.00,
  999000.00,
  'aplikasi-sekolah',
  'assets/images/products/rapor-digital.png',
  '["assets/images/products/gallery/rapor-1.png", "assets/images/products/gallery/rapor-2.png", "assets/images/products/gallery/rapor-3.png", "assets/images/products/gallery/rapor-4.png"]',
  '["Support Kurikulum Merdeka & K-13", "Input Nilai Batch/Import Excel", "Kalkulasi Otomatis", "Cetak Raport PDF Profesional", "Portal Orang Tua", "Manajemen Kompetensi Dasar", "Deskripsi Capaian Otomatis", "Multi-Semester Archive", "Tanda Tangan Digital", "Grafik Perkembangan Siswa"]',
  '["PHP 8.1", "Laravel 10", "MySQL 8.0", "Bootstrap 5", "jQuery", "DOMPDF", "DataTables", "Chart.js"]',
  'https://demo.rapordigital.id',
  4.78,
  52,
  648,
  FALSE,
  TRUE
),

-- 5. QuizMaster Pro
(
  'QuizMaster Pro',
  'quizmaster-pro',
  'QuizMaster Pro adalah platform ujian online dan Computer Based Test (CBT) yang powerful dan mudah digunakan. Dirancang untuk sekolah, universitas, dan lembaga pendidikan yang ingin menyelenggarakan ujian secara digital dengan keamanan tingkat tinggi.\n\nDengan fitur anti-cheating canggih termasuk lockdown browser, randomisasi soal, dan monitoring webcam, QuizMaster Pro memastikan integritas ujian tetap terjaga. Sistem mendukung berbagai tipe soal termasuk pilihan ganda, essay, menjodohkan, dan soal berbasis gambar.\n\nPlatform ini mampu menangani ribuan peserta ujian secara bersamaan dengan performa yang stabil. Hasil ujian tersedia secara instan dengan analisis butir soal yang detail untuk membantu guru meningkatkan kualitas penilaian.',
  'Platform Ujian Online & CBT dengan fitur anti-cheating, bank soal, dan analisis butir soal untuk evaluasi pembelajaran.',
  1500000.00,
  1299000.00,
  'aplikasi-pembelajaran',
  'assets/images/products/quizmaster-pro.png',
  '["assets/images/products/gallery/quizmaster-1.png", "assets/images/products/gallery/quizmaster-2.png", "assets/images/products/gallery/quizmaster-3.png", "assets/images/products/gallery/quizmaster-4.png"]',
  '["Bank Soal Terstruktur", "Multi Tipe Soal (PG, Essay, Menjodohkan)", "Anti-Cheating System", "Randomisasi Soal & Jawaban", "Timer & Auto-Submit", "Analisis Butir Soal", "Monitoring Real-time", "Cetak Kartu Ujian", "Import Soal dari Word/Excel", "Skor & Ranking Otomatis"]',
  '["PHP 8.1", "Laravel 10", "MySQL 8.0", "Vue.js 3", "Tailwind CSS", "WebSocket", "Redis", "Laravel Echo"]',
  'https://demo.quizmaster.id',
  4.68,
  33,
  295,
  TRUE,
  FALSE
),

-- 6. Perpustakaan Digital
(
  'Perpustakaan Digital',
  'perpustakaan-digital',
  'Perpustakaan Digital adalah Sistem Informasi Perpustakaan modern yang memudahkan pengelolaan koleksi buku, peminjaman, pengembalian, dan inventarisasi perpustakaan sekolah. Dengan sistem barcode/QR code scanning, proses sirkulasi buku menjadi lebih cepat dan efisien.\n\nAplikasi ini dilengkapi dengan katalog online (OPAC) yang memungkinkan anggota perpustakaan mencari dan memesan buku secara online. Sistem denda otomatis dan notifikasi jatuh tempo membantu meningkatkan tingkat pengembalian buku tepat waktu.\n\nFitur e-book reader terintegrasi memungkinkan perpustakaan untuk mengelola koleksi digital selain buku fisik, menjadikannya solusi perpustakaan hybrid yang lengkap.',
  'Sistem Informasi Perpustakaan Sekolah dengan katalog online, barcode scanning, dan manajemen peminjaman otomatis.',
  850000.00,
  699000.00,
  'sistem-informasi',
  'assets/images/products/perpustakaan-digital.png',
  '["assets/images/products/gallery/perpustakaan-1.png", "assets/images/products/gallery/perpustakaan-2.png", "assets/images/products/gallery/perpustakaan-3.png"]',
  '["Katalog Online (OPAC)", "Barcode & QR Code Scanning", "Manajemen Peminjaman & Pengembalian", "Sistem Denda Otomatis", "E-Book Reader Terintegrasi", "Laporan Statistik Perpustakaan", "Notifikasi Jatuh Tempo", "Kartu Anggota Digital"]',
  '["PHP 8.0", "CodeIgniter 4", "MySQL 5.7", "Bootstrap 5", "jQuery", "ZXing Library", "DataTables"]',
  'https://demo.perpustakaandigital.id',
  4.55,
  22,
  198,
  FALSE,
  FALSE
),

-- 7. PPDB Online
(
  'PPDB Online',
  'ppdb-online',
  'PPDB Online adalah Sistem Penerimaan Peserta Didik Baru berbasis web yang menyederhanakan seluruh proses penerimaan siswa baru dari pendaftaran hingga pengumuman. Dirancang sesuai dengan regulasi dan juknis PPDB terbaru dari Kemendikbud.\n\nSistem ini mendukung berbagai jalur penerimaan termasuk zonasi, afirmasi, perpindahan tugas, dan prestasi. Dengan formulir pendaftaran online yang dapat dikustomisasi, calon siswa dan orang tua dapat mendaftar dari mana saja tanpa perlu datang ke sekolah.\n\nDilengkapi dengan sistem seleksi otomatis, verifikasi dokumen online, dan pengumuman hasil secara real-time, PPDB Online menghemat waktu dan biaya operasional sekolah secara signifikan. Dashboard monitoring memungkinkan panitia PPDB memantau progress pendaftaran secara real-time.',
  'Sistem Penerimaan Peserta Didik Baru online dengan multi jalur seleksi, verifikasi dokumen, dan pengumuman otomatis.',
  1000000.00,
  849000.00,
  'aplikasi-sekolah',
  'assets/images/products/ppdb-online.png',
  '["assets/images/products/gallery/ppdb-1.png", "assets/images/products/gallery/ppdb-2.png", "assets/images/products/gallery/ppdb-3.png", "assets/images/products/gallery/ppdb-4.png"]',
  '["Multi Jalur Seleksi (Zonasi, Afirmasi, Prestasi)", "Formulir Pendaftaran Online", "Upload Dokumen Persyaratan", "Verifikasi Data Otomatis", "Seleksi & Perankingan Otomatis", "Pengumuman Hasil Online", "Cetak Bukti Pendaftaran", "Dashboard Monitoring Real-time", "Notifikasi SMS & WhatsApp", "Statistik & Laporan PPDB"]',
  '["PHP 8.1", "Laravel 10", "MySQL 8.0", "Bootstrap 5", "jQuery", "Leaflet.js", "WhatsApp API", "DOMPDF"]',
  'https://demo.ppdbonline.id',
  4.70,
  30,
  356,
  TRUE,
  FALSE
),

-- 8. E-Counseling
(
  'E-Counseling',
  'e-counseling',
  'E-Counseling adalah Aplikasi Bimbingan Konseling Online yang membantu guru BK dalam memberikan layanan konseling kepada siswa secara lebih efektif dan terdokumentasi. Aplikasi ini menyediakan platform aman untuk konseling individu, kelompok, dan konsultasi dengan orang tua.\n\nDengan fitur chat terenkripsi dan video call, siswa dapat berkonsultasi dengan guru BK kapan saja tanpa rasa malu atau takut. Sistem pencatatan otomatis membantu guru BK mendokumentasikan setiap sesi konseling dan memantau perkembangan siswa.\n\nDilengkapi dengan instrumen asesmen psikologis standar, deteksi dini masalah siswa, dan sistem rujukan, E-Counseling menjadi tool esensial untuk layanan BK yang komprehensif di era digital.',
  'Aplikasi Bimbingan Konseling Online dengan fitur chat aman, video call, asesmen psikologis, dan pencatatan sesi otomatis.',
  900000.00,
  749000.00,
  'aplikasi-pembelajaran',
  'assets/images/products/e-counseling.png',
  '["assets/images/products/gallery/counseling-1.png", "assets/images/products/gallery/counseling-2.png", "assets/images/products/gallery/counseling-3.png"]',
  '["Chat Konseling Terenkripsi", "Video Call Terintegrasi", "Instrumen Asesmen Psikologis", "Catatan Konseling Digital", "Deteksi Dini Masalah Siswa", "Jadwal Konseling Online", "Laporan BK Otomatis", "Portal Orang Tua", "Sistem Rujukan", "Dashboard Analitik BK"]',
  '["PHP 8.0", "Laravel 9", "MySQL 8.0", "Vue.js 3", "Tailwind CSS", "WebRTC", "Socket.io", "Chart.js"]',
  'https://demo.ecounseling.id',
  4.50,
  18,
  167,
  FALSE,
  FALSE
);


-- =====================================================
-- SEED DATA: Testimonials (6 entries)
-- =====================================================
INSERT INTO `testimonials` (`name`, `position`, `institution`, `avatar`, `content`, `rating`) VALUES

(
  'Dr. Hj. Siti Nurhaliza, M.Pd.',
  'Kepala Sekolah',
  'SMAN 1 Bandung',
  'assets/images/testimonials/testimonial-1.jpg',
  'Sejak menggunakan EduSchool Pro, administrasi sekolah kami menjadi jauh lebih efisien. Proses yang dulunya memakan waktu berhari-hari kini bisa selesai dalam hitungan jam. Sangat recommended untuk sekolah yang ingin go digital!',
  5
),

(
  'Ahmad Fauzi, S.Kom.',
  'Wakil Kepala Sekolah Bidang Kurikulum',
  'SMP Negeri 3 Surabaya',
  'assets/images/testimonials/testimonial-2.jpg',
  'RaporDigital benar-benar membantu guru-guru kami dalam pembuatan raport. Kalkulasi nilai otomatis dan deskripsi capaian yang auto-generate sangat menghemat waktu. Support tim developer juga sangat responsif.',
  5
),

(
  'Dewi Kartika Sari, M.Pd.',
  'Guru & Koordinator IT',
  'SMK Negeri 2 Yogyakarta',
  'assets/images/testimonials/testimonial-3.jpg',
  'SmartLearn LMS sangat membantu kami dalam menyelenggarakan pembelajaran hybrid. Siswa bisa belajar dari mana saja dan guru bisa memantau progress belajar secara real-time. Fitur gamifikasi membuat siswa lebih termotivasi.',
  5
),

(
  'Ir. Bambang Suryanto',
  'Ketua Komite Sekolah',
  'SD Islam Terpadu Al-Hikmah Jakarta',
  'assets/images/testimonials/testimonial-4.jpg',
  'Kami sangat puas dengan PPDB Online. Proses penerimaan siswa baru yang biasanya ribet dan antri panjang, sekarang bisa dilakukan secara online dengan mudah. Orang tua sangat mengapresiasi kemudahan ini.',
  4
),

(
  'Rina Wahyuni, S.Psi.',
  'Guru BK',
  'SMAN 5 Semarang',
  'assets/images/testimonials/testimonial-5.jpg',
  'E-Counseling membantu saya memberikan layanan BK yang lebih baik. Siswa yang awalnya malu untuk konseling tatap muka, kini lebih terbuka melalui fitur chat. Pencatatan sesi juga menjadi lebih rapi dan terdokumentasi.',
  5
),

(
  'Drs. Muhammad Ridwan, M.M.',
  'Kepala Sekolah',
  'MAN 1 Medan',
  'assets/images/testimonials/testimonial-6.jpg',
  'QuizMaster Pro sangat membantu kami dalam menyelenggarakan ujian secara digital. Fitur anti-cheating-nya canggih dan hasil ujian langsung bisa dianalisis. Sangat cocok untuk sekolah yang menerapkan CBT.',
  5
);


-- =====================================================
-- SEED DATA: Reviews (10 entries across products)
-- =====================================================
INSERT INTO `reviews` (`product_id`, `reviewer_name`, `reviewer_email`, `reviewer_avatar`, `rating`, `comment`, `is_verified`) VALUES

-- Reviews for EduSchool Pro (product_id: 1)
(1, 'Budi Santoso', 'budi.santoso@gmail.com', NULL, 5, 'Aplikasi yang sangat lengkap dan mudah digunakan. Fitur manajemen keuangan dan SPP sangat membantu bendahara sekolah kami. Support-nya juga cepat tanggap. Worth every penny!', TRUE),

(1, 'Ratna Dewi', 'ratna.dewi@yahoo.com', NULL, 5, 'Sudah 2 tahun menggunakan EduSchool Pro dan sangat puas. Update-nya rutin dan selalu menambah fitur baru yang bermanfaat. Dashboard analitiknya sangat membantu untuk laporan ke dinas.', TRUE),

(1, 'Hendri Kurniawan', 'hendri.k@gmail.com', NULL, 4, 'Overall bagus, fiturnya lengkap. Saran saya, tambahkan fitur integrasi dengan Dapodik supaya tidak perlu input data dua kali. Semoga segera direalisasikan.', TRUE),

-- Reviews for SmartLearn LMS (product_id: 2)
(2, 'Anisa Rahmawati', 'anisa.r@gmail.com', NULL, 5, 'Platform e-learning terbaik yang pernah kami gunakan. Video conference-nya lancar dan fitur quiz builder-nya sangat lengkap. Siswa-siswa kami jadi lebih semangat belajar online.', TRUE),

(2, 'Fajar Pratama', 'fajar.p@outlook.com', NULL, 4, 'Fiturnya oke banget, tapi butuh server yang lumayan spec-nya kalau siswa banyak. Overall puas, terutama fitur gamifikasi yang bikin anak-anak excited belajar.', FALSE),

-- Reviews for RaporDigital (product_id: 4)
(4, 'Sri Wahyuni', 'sri.wahyuni@gmail.com', NULL, 5, 'Raport yang biasanya bikin pusing sekarang jadi gampang banget. Tinggal input nilai, deskripsi capaian otomatis tergenerate. Cetak PDF-nya juga rapi dan profesional. Terima kasih!', TRUE),

(4, 'Agus Hermawan', 'agus.h@yahoo.com', NULL, 5, 'Sebagai wali kelas, RaporDigital sangat membantu pekerjaan saya. Proses yang dulunya berminggu-minggu sekarang cuma butuh 2-3 hari. Highly recommended!', TRUE),

-- Reviews for QuizMaster Pro (product_id: 5)
(5, 'Linda Permata', 'linda.p@gmail.com', NULL, 5, 'Anti-cheating system-nya benar-benar works! Ujian jadi lebih fair dan hasilnya bisa dipercaya. Analisis butir soal juga sangat berguna untuk evaluasi kualitas soal kami.', TRUE),

-- Reviews for PPDB Online (product_id: 7)
(7, 'Hasan Basri', 'hasan.b@gmail.com', NULL, 4, 'PPDB tahun ini berjalan sangat lancar berkat PPDB Online. Calon siswa dan orang tua sangat terbantu karena tidak perlu antri. Verifikasi dokumen online juga efisien.', TRUE),

-- Reviews for Perpustakaan Digital (product_id: 6)
(6, 'Nurul Hidayah', 'nurul.h@gmail.com', NULL, 5, 'Perpustakaan sekolah kami jadi lebih tertata dan modern. Siswa bisa cari buku lewat HP dan langsung reservasi. Fitur barcode scanning juga mempercepat proses peminjaman.', TRUE);

-- =====================================================
-- Table: admins
-- Stores admin credentials & profile for management
-- =====================================================
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `role` ENUM('superadmin', 'admin', 'editor') DEFAULT 'admin',
  `avatar` VARCHAR(255) NULL,
  `last_login` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default Admin (username: admin, password: admin123)
INSERT INTO `admins` (`username`, `email`, `password_hash`, `name`, `role`)
VALUES ('admin', 'admin@edumarket.id', '$2y$10$wOqZp9Z0jQoM07/B0kL0b.oG5bC1/K4M4m/h3jK.N4.t4p1Z1W2yq', 'Super Administrator EduMarket', 'superadmin')
ON DUPLICATE KEY UPDATE `username` = `username`;

-- =====================================================
-- Table: payment_settings
-- Stores payment method configurations
-- =====================================================
CREATE TABLE IF NOT EXISTS `payment_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `method_key` VARCHAR(50) NOT NULL UNIQUE,
  `method_type` ENUM('qris', 'bank', 'ewallet') NOT NULL,
  `method_title` VARCHAR(100) NOT NULL,
  `account_number` VARCHAR(100) NULL,
  `account_holder` VARCHAR(100) NULL,
  `qr_image` VARCHAR(255) NULL,
  `instructions` TEXT NULL,
  `is_active` BOOLEAN DEFAULT TRUE,
  `sort_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `payment_settings` (`method_key`, `method_type`, `method_title`, `account_number`, `account_holder`, `qr_image`, `instructions`, `is_active`, `sort_order`) VALUES
('qris', 'qris', 'QRIS All Payment (BCA, Mandiri, Dana, GoPay, OVO, ShopeePay)', '0001-QRIS-EDUMKT', 'EduMarket Digital Indonesia', 'assets/images/qris-sample.svg', 'Scan kode QRIS menggunakan aplikasi perbankan atau e-wallet apa saja. Pembayaran akan terverifikasi otomatis.', 1, 1),
('bank_bca', 'bank', 'Bank Central Asia (BCA)', '8270-1928-3341', 'PT EduMarket Digital Indonesia', NULL, 'Transfer ke nomor rekening BCA di atas melalui ATM, m-BCA, atau KlikBCA. Masukkan nomor invoice pada berita transfer.', 1, 2),
('bank_mandiri', 'bank', 'Bank Mandiri', '140-00-9876543-2', 'PT EduMarket Digital Indonesia', NULL, 'Transfer melalui Livin by Mandiri atau ATM Mandiri. Simpan bukti transfer Anda.', 1, 3),
('bank_bri', 'bank', 'Bank Rakyat Indonesia (BRI)', '0341-01-002891-30-5', 'PT EduMarket Digital Indonesia', NULL, 'Transfer melalui BRImo atau ATM BRI. Konfirmasi setelah melakukan pembayaran.', 1, 4),
('ewallet_dana', 'ewallet', 'DANA ID', '0812-3456-7890', 'EduMarket Official', NULL, 'Kirim saldo DANA ke nomor tertera. Cantumkan nama dan nomor invoice pesanan Anda.', 1, 5),
('ewallet_ovo', 'ewallet', 'OVO Cash', '0812-3456-7890', 'EduMarket Official', NULL, 'Transfer via OVO ke nomor di atas.', 1, 6),
('ewallet_gopay', 'ewallet', 'GoPay ID', '0812-3456-7890', 'EduMarket Official', NULL, 'Transfer saldo GoPay instan ke nomor resmi EduMarket.', 1, 7)
ON DUPLICATE KEY UPDATE `method_key` = `method_key`;

-- =====================================================
-- Table: store_profile
-- Stores marketplace store information & branding
-- =====================================================
CREATE TABLE IF NOT EXISTS `store_profile` (
  `id` INT PRIMARY KEY DEFAULT 1,
  `store_name` VARCHAR(255) NOT NULL DEFAULT 'EduMarket',
  `name_prefix` VARCHAR(100) NOT NULL DEFAULT 'Edu',
  `name_suffix` VARCHAR(100) NOT NULL DEFAULT 'Market',
  `tagline` VARCHAR(255) NOT NULL DEFAULT 'Marketplace Aplikasi Pendidikan & Sekolah Digital',
  `description` TEXT NULL,
  `logo` VARCHAR(255) NULL DEFAULT 'assets/images/favicon.svg',
  `favicon` VARCHAR(255) NULL DEFAULT 'assets/images/favicon.svg',
  `email` VARCHAR(255) NULL DEFAULT 'support@edumarket.id',
  `phone` VARCHAR(50) NULL DEFAULT '+62 812-3456-7890',
  `whatsapp` VARCHAR(50) NULL DEFAULT '6281234567890',
  `address` TEXT NULL,
  `operating_hours` VARCHAR(255) NULL DEFAULT 'Senin - Jumat: 08.00 - 17.00 WIB, Sabtu: 09.00 - 14.00 WIB',
  `instagram` VARCHAR(255) NULL DEFAULT 'https://instagram.com/edumarket',
  `facebook` VARCHAR(255) NULL DEFAULT 'https://facebook.com/edumarket',
  `youtube` VARCHAR(255) NULL DEFAULT 'https://youtube.com/@edumarket',
  `warranty_period` VARCHAR(50) NULL DEFAULT '30 Hari Garansi',
  `support_duration` VARCHAR(50) NULL DEFAULT '1 Tahun Update & Support Gratis',
  `copyright_text` VARCHAR(255) NULL DEFAULT '© 2026 EduAdminSP. Manage With <i class=\"fas fa-heart\" style=\"color: #FF3366;\"></i> by adangsp',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `store_profile` 
(`id`, `store_name`, `name_prefix`, `name_suffix`, `tagline`, `description`, `logo`, `favicon`, `email`, `phone`, `whatsapp`, `address`, `operating_hours`, `instagram`, `facebook`, `youtube`, `warranty_period`, `support_duration`, `copyright_text`)
VALUES 
(1, 'EduMarket', 'Edu', 'Market', 'Marketplace Aplikasi Pendidikan & Sekolah Digital', 'EduMarket adalah penyedia solusi digital dan source code aplikasi web pendidikan berkualitas tinggi untuk sekolah, kampus, dan lembaga kursus di Indonesia.', 'assets/images/favicon.svg', 'assets/images/favicon.svg', 'support@edumarket.id', '+62 812-3456-7890', '6281234567890', 'Jl. Teknologi No. 123, Jakarta Selatan, Indonesia', 'Senin - Jumat: 08.00 - 17.00 WIB, Sabtu: 09.00 - 14.00 WIB', 'https://instagram.com/edumarket', 'https://facebook.com/edumarket', 'https://youtube.com/@edumarket', '30 Hari Garansi', '1 Tahun Update & Support Gratis', '© 2026 EduAdminSP. Manage With <i class=\"fas fa-heart\" style=\"color: #FF3366;\"></i> by adangsp')
ON DUPLICATE KEY UPDATE `id` = `id`;

-- =====================================================
-- Table: whatsapp_settings
-- Configuration for WAHA (WhatsApp HTTP API) Gateway
-- =====================================================
CREATE TABLE IF NOT EXISTS `whatsapp_settings` (
    `id` INT PRIMARY KEY DEFAULT 1,
    `is_enabled` TINYINT(1) NOT NULL DEFAULT 1,
    `api_url` VARCHAR(255) NOT NULL DEFAULT 'http://localhost:3000',
    `api_key` VARCHAR(255) NULL DEFAULT '',
    `session_name` VARCHAR(100) NOT NULL DEFAULT 'default',
    `admin_phone` VARCHAR(50) NULL DEFAULT '6281234567890',
    `notify_admin_on_order` TINYINT(1) NOT NULL DEFAULT 1,
    `notify_admin_on_contact` TINYINT(1) NOT NULL DEFAULT 1,
    `notify_customer_on_order` TINYINT(1) NOT NULL DEFAULT 1,
    `notify_customer_on_paid` TINYINT(1) NOT NULL DEFAULT 1,
    `order_template` TEXT NULL,
    `paid_template` TEXT NULL,
    `contact_template` TEXT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `whatsapp_settings`
(`id`, `is_enabled`, `api_url`, `api_key`, `session_name`, `admin_phone`, `notify_admin_on_order`, `notify_admin_on_contact`, `notify_customer_on_order`, `notify_customer_on_paid`, `order_template`, `paid_template`, `contact_template`)
VALUES
(1, 1, 'http://localhost:3000', '', 'default', '6281234567890', 1, 1, 1, 1,
 'Halo *{customer_name}*, terima kasih telah memesan di *{store_name}*! 🎉\n\nNomor Pesanan: *#{order_number}*\nProduk: *{product_name}*\nTotal: *Rp {amount}*\nMetode Pembayaran: *{payment_method}*\n\nSilakan selesaikan pembayaran sesuai petunjuk invoice Anda. Hubungi kami jika butuh bantuan. Terima kasih! 🙏',
 'Pembayaran Dikonfirmasi! ✅\n\nHalo *{customer_name}*, pembayaran untuk pesanan *#{order_number}* (*{product_name}*) telah kami verifikasi dengan sukses!\n\nLink Akses / Download:\n🔗 {download_link}\nKode Lisensi: `{license_key}`\n\nTerima kasih telah mempercayai *{store_name}*! 🚀',
 '📩 *Pesan Baru dari Website {store_name}*\n\nDari: *{contact_name}*\nEmail: {contact_email}\nNo. HP: {contact_phone}\nSubjek: *{contact_subject}*\n\nPesan:\n\"{contact_message}\"')
ON DUPLICATE KEY UPDATE `id` = `id`;



