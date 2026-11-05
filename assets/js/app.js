/**
 * ====================================================================
 * EduMarket - Marketplace Aplikasi Pendidikan & Sekolah Digital
 * Interactive JavaScript Application (ES6+)
 * ====================================================================
 */

'use strict';

(function () {
    // ----------------------------------------------------------------
    // 1. DATA FALLBACK (Digunakan jika database/API belum terkoneksi)
    // ----------------------------------------------------------------
    const FALLBACK_PRODUCTS = [
        {
            id: 1,
            name: "EduSchool Pro",
            slug: "eduschool-pro",
            category: "aplikasi-sekolah",
            price: 2500000,
            discount_price: 1999000,
            rating: 4.9,
            total_reviews: 48,
            total_sales: 320,
            is_featured: true,
            is_bestseller: true,
            short_description: "Sistem Informasi Manajemen Sekolah Terpadu mencakup akademik, keuangan SPP, absensi, hingga rekapitulasi data siswa.",
            description: "EduSchool Pro adalah solusi terlengkap manajemen institusi sekolah modern tingkat SD, SMP, SMA/SMK. Dibangun dengan arsitektur modular yang mempermudah tata kelola administrasi akademik, pembayaran SPP terintegrasi payment gateway/manual, pencatatan presensi guru dan siswa, serta penerbitan laporan berkala.",
            image: "https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=800&auto=format&fit=crop&q=80",
            gallery: [
                "https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=800&auto=format&fit=crop&q=80",
                "https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=800&auto=format&fit=crop&q=80",
                "https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&auto=format&fit=crop&q=80"
            ],
            tech_stack: ["PHP 8.x", "MySQL", "Bootstrap 5", "Chart.js", "jQuery"],
            features: [
                "Multi-Role: Administrator, Guru, Tata Usaha, Siswa, dan Orang Tua",
                "Manajemen SPP & Tagihan Siswa dengan Cetak Kuitansi Otomatis",
                "Manajemen Jadwal Pelajaran & Ruangan Kelas Interaktif",
                "Portal Nilai & Unduh Rapor Berformat PDF",
                "Dashboard Statistik Kehadiran dan Keuangan Interaktif",
                "Source Code 100% Bersih & Mudah Disesuaikan"
            ],
            demo_url: "#"
        },
        {
            id: 2,
            name: "SmartLearn LMS",
            slug: "smartlearn-lms",
            category: "e-learning",
            price: 1800000,
            discount_price: 1499000,
            rating: 4.8,
            total_reviews: 36,
            total_sales: 215,
            is_featured: true,
            is_bestseller: false,
            short_description: "Platform E-Learning interaktif dengan kelas virtual, video materi, forum diskusi, dan monitoring progres belajar.",
            description: "SmartLearn LMS menyajikan pengalaman belajar daring dan hybrid yang dinamis. Dilengkapi manajemen modul pelajaran multimedia, kuis terstruktur, penugasan tugas berbatas waktu, dan rekap nilai otomatis yang siap diintegrasikan ke rapor.",
            image: "https://images.unsplash.com/photo-1501504905252-473c47e087f8?w=800&auto=format&fit=crop&q=80",
            gallery: [
                "https://images.unsplash.com/photo-1501504905252-473c47e087f8?w=800&auto=format&fit=crop&q=80",
                "https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&auto=format&fit=crop&q=80"
            ],
            tech_stack: ["PHP", "MySQL", "SweetAlert2", "VideoJS", "AJAX"],
            features: [
                "Modul Pembelajaran Video, PDF, & Slide Interaktif",
                "Forum Diskusi & Tanya Jawab Guru-Siswa Realtime",
                "Sistem Pengumpulan Tugas dengan Deteksi Deadline",
                "Rekap Progres Belajar Siswa Berbasis Persentase",
                "Sertifikat Kelulusan Kursus Otomatis Siap Cetak"
            ],
            demo_url: "#"
        },
        {
            id: 3,
            name: "AbsensiKu Digital",
            slug: "absensiku-digital",
            category: "aplikasi-sekolah",
            price: 750000,
            discount_price: 599000,
            rating: 4.7,
            total_reviews: 29,
            total_sales: 410,
            is_featured: false,
            is_bestseller: true,
            short_description: "Aplikasi presensi online sekolah berbasis QR Code & Geolocation GPS untuk guru, staf, dan siswa.",
            description: "AbsensiKu Digital menggantikan absensi manual berbasis kertas dengan pemindaian barcode / QR Code dinamis dan verifikasi radius GPS sekolah. Menghindari kecurangan dan memudahkan pembuatan laporan bulanan.",
            image: "https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&auto=format&fit=crop&q=80",
            gallery: [
                "https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&auto=format&fit=crop&q=80"
            ],
            tech_stack: ["HTML5 Geolocation", "PHP", "MySQL", "QR Scanner JS"],
            features: [
                "Presensi via QR Code Scanner & Titik Koordinat GPS",
                "Deteksi Radius Sekolah Anti Fake-GPS",
                "Rekapitulasi Izin, Sakit, Alpa, dan Keterlambatan",
                "Ekspor Laporan Bulanan ke Excel & PDF Sekali Klik",
                "Notifikasi WhatsApp Gateway untuk Orang Tua (Opsional)"
            ],
            demo_url: "#"
        },
        {
            id: 4,
            name: "RaporDigital Kurikulum Merdeka",
            slug: "rapor-digital-merdeka",
            category: "aplikasi-sekolah",
            price: 1200000,
            discount_price: 999000,
            rating: 4.9,
            total_reviews: 42,
            total_sales: 185,
            is_featured: false,
            is_bestseller: true,
            short_description: "Sistem penginputan nilai dan pencetakan Rapor Kurikulum Merdeka & K13 dengan kalkulasi deskripsi otomatis.",
            description: "RaporDigital dirancang khusus untuk mempermudah guru dan wali kelas dalam mengolah nilai sumatif, formatif, tujuan pembelajaran (TP), dan Projek Penguatan Profil Pelajar Pancasila (P5) menjadi lembar rapor resmi.",
            image: "https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&auto=format&fit=crop&q=80",
            gallery: [
                "https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&auto=format&fit=crop&q=80"
            ],
            tech_stack: ["PHP Native / OOP", "MySQL", "DOMPDF", "Bootstrap 5"],
            features: [
                "Sesuai Panduan Penilaian Kurikulum Merdeka Kemendikbud",
                "Generator Deskripsi Capaian Kompetensi Otomatis",
                "Modul Khusus Penilaian Projek P5 & Ekstrakurikuler",
                "Cetak Rapor Siswa & Rekap Buku Induk Siap Tanda Tangan",
                "Fitur Import & Export Nilai melalui File Excel"
            ],
            demo_url: "#"
        },
        {
            id: 5,
            name: "QuizMaster Pro - Platform CBT",
            slug: "quizmaster-pro",
            category: "aplikasi-pembelajaran",
            price: 1500000,
            discount_price: 1299000,
            rating: 4.9,
            total_reviews: 55,
            total_sales: 290,
            is_featured: true,
            is_bestseller: true,
            short_description: "Sistem Ujian Berbasis Komputer (CBT) tangguh dengan fitur anti-curang, acak soal, dan analisis butir soal.",
            description: "QuizMaster Pro adalah engine ujian online dan asesmen sekolah berdaya tahan tinggi. Dilengkapi perlindungan browser lock, acak nomor dan opsi jawaban, timer otomatis, serta grafik analisis ketuntasan belajar secara instan.",
            image: "https://images.unsplash.com/photo-1488190211105-8b0e65b80b4e?w=800&auto=format&fit=crop&q=80",
            gallery: [
                "https://images.unsplash.com/photo-1488190211105-8b0e65b80b4e?w=800&auto=format&fit=crop&q=80"
            ],
            tech_stack: ["PHP", "MySQL", "JavaScript ES6", "SweetAlert2"],
            features: [
                "Mode Anti-Kecurangan: Deteksi Pindah Tab & Layar Penuh",
                "Acak Soal dan Acak Opsi Pilihan Ganda Per Peserta",
                "Dukungan Ragam Soal: Pilihan Ganda, Essay, Menjodohkan",
                "Koreksi Nilai Otomatis & Analisis Daya Beda Soal",
                "Mampu Menampung Ratusan Peserta Bersamaan dengan Ringan"
            ],
            demo_url: "#"
        },
        {
            id: 6,
            name: "Perpustakaan Digital (DigiLib)",
            slug: "perpustakaan-digital",
            category: "sistem-informasi",
            price: 850000,
            discount_price: 699000,
            rating: 4.6,
            total_reviews: 21,
            total_sales: 140,
            is_featured: false,
            is_bestseller: false,
            short_description: "Katalog perpustakaan sekolah digital, sirkulasi peminjaman barcode, dan pembaca E-Book terintegrasi.",
            description: "DigiLib memudahkan pengelolaan koleksi pustaka fisik dan digital. Dilengkapi pencetakan kartu anggota berbarcode, kalkulasi denda keterlambatan otomatis, serta portal baca e-book online untuk siswa.",
            image: "https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=800&auto=format&fit=crop&q=80",
            gallery: [
                "https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=800&auto=format&fit=crop&q=80"
            ],
            tech_stack: ["PHP", "MySQL", "PDF.js", "Barcode Generator"],
            features: [
                "Sistem OPAC (Online Public Access Catalog) Ramah Siswa",
                "Manajemen Peminjaman, Pengembalian, & Denda Otomatis",
                "Cetak Barcode Buku dan Kartu Anggota Perpustakaan",
                "E-Reader Buku Digital Terproteksi Anti-Download Ilegal",
                "Laporan Statistik Kunjungan & Buku Terpopuler"
            ],
            demo_url: "#"
        },
        {
            id: 7,
            name: "PPDB Online Pro",
            slug: "ppdb-online-pro",
            category: "aplikasi-sekolah",
            price: 1000000,
            discount_price: 849000,
            rating: 4.8,
            total_reviews: 31,
            total_sales: 175,
            is_featured: true,
            is_bestseller: false,
            short_description: "Portal Penerimaan Peserta Didik Baru online dengan seleksi zonasi, afirmasi, prestasi, dan verifikasi berkas.",
            description: "PPDB Online Pro mempermudah kepanitiaan tahun ajaran baru sekolah. Calon siswa mendaftar secara mandiri, mengunggah berkas ijazah/KK, memilih jalur pendaftaran, dan memantau status seleksi secara real-time.",
            image: "https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=800&auto=format&fit=crop&q=80",
            gallery: [
                "https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=800&auto=format&fit=crop&q=80"
            ],
            tech_stack: ["PHP", "MySQL", "Bootstrap 5", "SweetAlert2"],
            features: [
                "Formulir Pendaftaran Multi-Step yang Simpel dan Jelas",
                "Upload & Verifikasi Berkas (Kartu Keluarga, Akta, Rapor)",
                "Dukungan Jalur Zonasi, Prestasi, Afirmasi, dan Mutasi",
                "Pengumuman Hasil Seleksi Online dengan Nomor Registrasi",
                "Cetak Bukti Pendaftaran & Kartu Ujian Berkode QR"
            ],
            demo_url: "#"
        },
        {
            id: 8,
            name: "E-Counseling BK Sekolah",
            slug: "e-counseling-bk",
            category: "aplikasi-pembelajaran",
            price: 900000,
            discount_price: 749000,
            rating: 4.7,
            total_reviews: 18,
            total_sales: 98,
            is_featured: false,
            is_bestseller: false,
            short_description: "Aplikasi Bimbingan & Konseling Sekolah untuk pencatatan poin pelanggaran, konseling rahasia, dan prestasi siswa.",
            description: "E-Counseling menjembatani guru BK dengan siswa dan orang tua. Memiliki fitur konsultasi digital terenkripsi, pencatatan rekam jejak poin pelanggaran dan pembinaan, serta laporan profil karakter peserta didik.",
            image: "https://images.unsplash.com/photo-1577495508048-b635879837f1?w=800&auto=format&fit=crop&q=80",
            gallery: [
                "https://images.unsplash.com/photo-1577495508048-b635879837f1?w=800&auto=format&fit=crop&q=80"
            ],
            tech_stack: ["PHP", "MySQL", "ChartJS", "Bootstrap 5"],
            features: [
                "Pencatatan Poin Pelanggaran dan Poin Penghargaan Siswa",
                "Jadwal & Riwayat Sesi Konseling Rahasia Siswa",
                "Surat Panggilan Orang Tua Otomatis Berdasarkan Akumulasi Poin",
                "Grafik Analisis Tren Masalah dan Penanganan Siswa",
                "Hak Akses Terpisah Khusus Tim Bimbingan Konseling"
            ],
            demo_url: "#"
        }
    ];

    const TESTIMONIALS_DATA = [
        {
            name: "Drs. H. Bambang Suherman, M.Pd",
            position: "Kepala Sekolah",
            institution: "SMK Negeri 1 Surabaya",
            rating: 5,
            content: "EduSchool Pro telah mentransformasi tata kelola sekolah kami menjadi serba terstruktur. Pencatatan SPP dan akademik yang sebelumnya memakan waktu berhari-hari kini selesai dalam hitungan menit. Source code sangat rapi dan mudah dimodifikasi!"
        },
        {
            name: "Siti Rahmawati, S.Kom, M.T",
            position: "Koordinator IT & Kurikulum",
            institution: "SMA Islam Terpadu Al-Falah",
            rating: 5,
            content: "QuizMaster Pro sangat stabil digunakan saat asesmen serentak untuk 600 siswa lebih. Tidak ada lag sama sekali dan fitur anti-curangnya sangat efektif. Dukungan teknis dari tim EduMarket luar biasa ramah dan responsif!"
        },
        {
            name: "Ir. Ahmad Fauzi",
            position: "Ketua Yayasan Pendidikan",
            institution: "Yayasan Bina Bangsa Cendekia",
            rating: 5,
            content: "Investasi terbaik untuk 3 sekolah di bawah naungan yayasan kami. Dengan paket Professional, kami mendapatkan panduan lengkap dan source code yang benar-benar siap pakai. Sangat direkomendasikan untuk institusi pendidikan modern."
        },
        {
            name: "Nurul Hidayati, S.Pd",
            position: "Wali Kelas & Guru Penggerak",
            institution: "SMP Negeri 4 Bandung",
            rating: 5,
            content: "Aplikasi Rapor Kurikulum Merdeka sangat menolong kami para guru! Deskripsi capaian terisi otomatis dan sinkronisasi nilainya tepat. Beban administrasi berkurang drastis sehingga kami bisa fokus mengajar."
        },
        {
            name: "Rian Pratama, S.Kom",
            position: "Software Developer & Konsultan",
            institution: "Solusi Edutech Nusantara",
            rating: 5,
            content: "Sebagai developer yang sering menggarap proyek sekolah, produk digital di EduMarket memberikan boilerplate dan arsitektur yang sangat solid. Memangkas waktu pengerjaan project hingga 70%. Dokumentasinya sangat jelas!"
        },
        {
            name: "Endah Sulistyo, M.M",
            position: "Wakil Kepala Bagian Kesiswaan",
            institution: "SMA Negeri 8 Yogyakarta",
            rating: 5,
            content: "Aplikasi AbsensiKu dan PPDB Online sangat memuaskan orang tua siswa. Semuanya transparan, cepat, dan antarmukanya ramah pengguna di ponsel. EduMarket memang marketplace edutech nomor 1 di Indonesia!"
        }
    ];

    const FAQ_DATA = [
        {
            question: "Apa saja yang saya dapatkan setelah melakukan pembelian?",
            answer: "Setiap pembelian produk mencakup <strong>Full Source Code</strong> (tanpa enkripsi/obfuscation), file database SQL lengkap dengan sample data, dokumentasi petunjuk instalasi berformat PDF & Video, aset grafis, dan hak kustomisasi penuh sesuai kebutuhan sekolah Anda."
        },
        {
            question: "Apakah aplikasi dapat diuji coba sebelum membeli (Demo Online)?",
            answer: "Ya, tentu saja! Anda dapat mengeklik tombol <em>'Demo'</em> atau <em>'Lihat Detail'</em> pada setiap kartu produk untuk mencoba langsung fungsionalitas dan tampilan dashboard aplikasi secara interaktif."
        },
        {
            question: "Bagaimana jika saya memerlukan bantuan saat proses instalasi?",
            answer: "Kami menyediakan panduan instalasi langkah-demi-langkah yang sangat detail untuk berbagai lingkungan (Laragon, XAMPP, VPS cPanel, maupun Docker). Selain itu, tim support kami siap membantu memandu Anda via WhatsApp atau remote desktop (AnyDesk/TeamViewer)."
        },
        {
            question: "Apakah ada garansi uang kembali?",
            answer: "Kami memberikan garansi 30 hari uang kembali jika terdapat kerusakan fatal (error kode bawaan) yang tidak dapat diperbaiki oleh tim pengembang kami sesuai spesifikasi produk yang dijanjikan."
        },
        {
            question: "Bisakah aplikasi dikustomisasi dengan logo dan fitur khusus sekolah kami?",
            answer: "Tentu bisa! Source code yang kami serahkan 100% open source untuk Anda kembangkan secara mandiri, atau Anda juga dapat memanfaatkan layanan custom development langsung dari tim pengembang EduMarket."
        },
        {
            question: "Bagaimana alur pembayaran dan pengiriman file produk?",
            answer: "Pembayaran dapat dilakukan melalui Transfer Bank (BCA, Mandiri, BRI, BNI), E-Wallet (Dana, OVO, GoPay), serta QRIS instan. Setelah pembayaran terkonfirmasi, tautan download source code dan lisensi langsung dikirim ke email Anda secara otomatis."
        },
        {
            question: "Apakah tersedia update fitur di masa mendatang?",
            answer: "Ya! Anda mendapatkan update gratis perbaikan bug, penyesuaian regulasi kementerian pendidikan, serta peningkatan fitur selama 1 tahun penuh sejak tanggal pembelian."
        },
        {
            question: "Bagaimana lisensi penggunaan aplikasi ini?",
            answer: "Anda mendapatkan lisensi kepemilikan komersial untuk digunakan di institusi atau sekolah Anda sendiri tanpa batas waktu (lifetime use) dan tanpa pungutan biaya langganan bulanan."
        }
    ];

    // ----------------------------------------------------------------
    // 2. STATE APLIKASI
    // ----------------------------------------------------------------
    const state = {
        products: [],
        activeCategory: 'all',
        searchQuery: '',
        sortBy: 'featured',
        currentTestimonialIndex: 0,
        testimonialInterval: null,
        isDarkMode: false
    };

    // ----------------------------------------------------------------
    // 3. UTILITY FUNCTIONS
    // ----------------------------------------------------------------
    function formatRupiah(amount) {
        if (!amount && amount !== 0) return 'Rp 0';
        return 'Rp ' + Number(amount).toLocaleString('id-ID');
    }

    function renderStars(rating) {
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        let html = '';
        for (let i = 0; i < fullStars; i++) {
            html += '<i class="fas fa-star"></i>';
        }
        if (hasHalfStar) {
            html += '<i class="fas fa-star-half-alt"></i>';
        }
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
        for (let i = 0; i < emptyStars; i++) {
            html += '<i class="far fa-star"></i>';
        }
        return html;
    }

    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    function showToast(message, type = 'info') {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        let icon = 'info-circle';
        if (type === 'success') icon = 'check-circle';
        if (type === 'error') icon = 'exclamation-circle';
        if (type === 'warning') icon = 'exclamation-triangle';

        toast.innerHTML = `
            <i class="fas fa-${icon}"></i>
            <span>${message}</span>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('fade-out');
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    }

    // ----------------------------------------------------------------
    // 4. PRELOADER & INITIALIZATION
    // ----------------------------------------------------------------
    function initPreloader() {
        const preloader = document.getElementById('preloader');
        if (!preloader) return;

        window.addEventListener('load', () => {
            setTimeout(() => {
                document.body.classList.add('loaded');
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 600);
            }, 600);
        });

        // Fallback jika window.load lambat
        setTimeout(() => {
            document.body.classList.add('loaded');
            if (preloader) preloader.style.display = 'none';
        }, 2500);
    }

    // ----------------------------------------------------------------
    // 5. NAVBAR, SCROLLSPY & MOBILE MENU
    // ----------------------------------------------------------------
    function initNavbar() {
        const navbar = document.getElementById('navbar');
        const hamburger = document.getElementById('hamburger');
        const navMenu = document.getElementById('navMenu');
        const navLinks = document.querySelectorAll('.nav-link');

        // Scroll event untuk navbar styling
        window.addEventListener('scroll', () => {
            if (window.scrollY > 80) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile Hamburger Toggle
        if (hamburger && navMenu) {
            hamburger.addEventListener('click', (e) => {
                e.stopPropagation();
                hamburger.classList.toggle('active');
                navMenu.classList.toggle('active');
            });

            // Klik link menutup menu
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    hamburger.classList.remove('active');
                    navMenu.classList.remove('active');
                });
            });

            // Klik di luar menutup menu
            document.addEventListener('click', (e) => {
                if (!navMenu.contains(e.target) && !hamburger.contains(e.target)) {
                    hamburger.classList.remove('active');
                    navMenu.classList.remove('active');
                }
            });
        }

        // Active link Scrollspy
        const sections = document.querySelectorAll('section[id]');
        window.addEventListener('scroll', () => {
            const scrollY = window.pageYOffset;
            sections.forEach(current => {
                const sectionHeight = current.offsetHeight;
                const sectionTop = current.offsetTop - 120;
                const sectionId = current.getAttribute('id');
                const targetLink = document.querySelector(`.nav-link[href*="${sectionId}"]`);

                if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                    if (targetLink) {
                        navLinks.forEach(l => l.classList.remove('active'));
                        targetLink.classList.add('active');
                    }
                }
            });
        });
    }

    // ----------------------------------------------------------------
    // 6. DARK MODE TOGGLE
    // ----------------------------------------------------------------
    function initDarkMode() {
        const toggles = [
            document.getElementById('darkModeToggle'),
            document.getElementById('darkModeToggleMobile')
        ].filter(Boolean);

        const savedTheme = localStorage.getItem('edumarket_theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
            state.isDarkMode = true;
            updateDarkModeIcons(true);
        }

        toggles.forEach(btn => {
            btn.addEventListener('click', () => {
                document.body.classList.toggle('dark-mode');
                const isDark = document.body.classList.contains('dark-mode');
                state.isDarkMode = isDark;
                localStorage.setItem('edumarket_theme', isDark ? 'dark' : 'light');
                updateDarkModeIcons(isDark);
            });
        });

        function updateDarkModeIcons(isDark) {
            toggles.forEach(btn => {
                const icon = btn.querySelector('i');
                if (icon) {
                    if (isDark) {
                        icon.classList.remove('fa-moon');
                        icon.classList.add('fa-sun');
                        icon.style.color = '#D97706';
                    } else {
                        icon.classList.remove('fa-sun');
                        icon.classList.add('fa-moon');
                        icon.style.color = '';
                    }
                }
            });
        }
    }

    // ----------------------------------------------------------------
    // 7. STATS COUNTERS
    // ----------------------------------------------------------------
    function initStatsCounters() {
        function animateSingleCounter(counter) {
            const target = parseFloat(counter.getAttribute('data-target')) || 0;
            const isDecimal = counter.getAttribute('data-decimals') === '1';
            const duration = 1800;
            const startTime = performance.now();

            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Smooth easeOutCubic
                const easeOut = 1 - Math.pow(1 - progress, 3);
                const currentVal = target * easeOut;

                if (isDecimal) {
                    counter.textContent = currentVal.toFixed(1);
                } else {
                    counter.textContent = Math.floor(currentVal).toLocaleString('id-ID');
                }

                if (progress < 1) {
                    requestAnimationFrame(update);
                } else {
                    if (isDecimal) {
                        counter.textContent = target.toFixed(1);
                    } else {
                        counter.textContent = target.toLocaleString('id-ID');
                    }
                }
            }

            requestAnimationFrame(update);
        }

        // 1. Hero stats counter: trigger automatically with short delay
        const heroCounters = document.querySelectorAll('.hero-stats .stat-number');
        if (heroCounters.length) {
            let heroDone = false;
            const startHeroStats = () => {
                if (heroDone) return;
                heroDone = true;
                heroCounters.forEach(animateSingleCounter);
            };

            setTimeout(startHeroStats, 600);
            window.addEventListener('load', () => setTimeout(startHeroStats, 200));
        }

        // 2. Secondary stats counters on scroll
        const secondaryCounters = document.querySelectorAll('.stats-number');
        if (secondaryCounters.length) {
            let secondaryDone = false;
            const secObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !secondaryDone) {
                        secondaryDone = true;
                        secondaryCounters.forEach(animateSingleCounter);
                    }
                });
            }, { threshold: 0.1 });

            const secSection = document.querySelector('.stats-grid') || document.querySelector('.why-section');
            if (secSection) secObserver.observe(secSection);
        }
    }

    // ----------------------------------------------------------------
    // 7B. STORE PROFILE SETTINGS
    // ----------------------------------------------------------------
    let storeSettings = {
        store_name: 'EduMarket',
        whatsapp: '6281234567890',
        email: 'support@edumarket.id'
    };

    async function loadStoreProfile() {
        try {
            const res = await fetch('api/store-profile.php?v=' + Date.now());
            if (!res.ok) return;
            const json = await res.json();
            if (json.success && json.data) {
                const d = json.data;
                storeSettings = { ...storeSettings, ...d };

                const storeName = d.store_name || 'EduMarket';
                const logoUrl = d.logo || '';
                const faviconUrl = d.favicon || '';

                // Save to localStorage for instant zero-delay hydration on subsequent loads
                try {
                    localStorage.setItem('edumarket_store_profile', JSON.stringify(d));
                } catch (e) {}

                // Helper to format brand name with gradient on the second half/last word
                const formatBrandHtml = (name) => {
                    if (!name) return 'Edu<span class="text-gradient">Market</span>';
                    const parts = name.trim().split(/\s+/);
                    if (parts.length > 1) {
                        const lastWord = parts.pop();
                        return `${parts.join(' ')} <span class="text-gradient">${lastWord}</span>`;
                    }
                    if (name.length >= 6) {
                        const mid = Math.ceil(name.length / 2);
                        return `${name.substring(0, mid)}<span class="text-gradient">${name.substring(mid)}</span>`;
                    }
                    return name;
                };

                let brandHtml = '';
                if (d.name_prefix || d.name_suffix) {
                    brandHtml = (d.name_prefix || '') + (d.name_suffix ? '<span class="text-gradient">' + d.name_suffix + '</span>' : '');
                } else {
                    brandHtml = formatBrandHtml(storeName);
                }

                // Helper to update a logo container (icon + img + text)
                const applyLogoTo = (imgId, iconId, textId) => {
                    const imgEl = document.getElementById(imgId);
                    const iconEl = document.getElementById(iconId);
                    const textEl = document.getElementById(textId);

                    if (textEl) {
                        textEl.innerHTML = brandHtml;
                    }

                    const hasCustomLogo = logoUrl && !logoUrl.endsWith('favicon.svg') && logoUrl.trim() !== '';

                    if (imgEl && iconEl) {
                        if (hasCustomLogo) {
                            imgEl.src = logoUrl;
                            imgEl.alt = storeName;
                            imgEl.style.display = 'inline-block';
                            iconEl.style.display = 'none';

                            imgEl.onerror = () => {
                                imgEl.style.display = 'none';
                                iconEl.style.display = 'inline-flex';
                            };
                        } else {
                            imgEl.style.display = 'none';
                            iconEl.style.display = 'inline-flex';
                        }
                    }
                };

                // Apply to Navbar, Footer, and Preloader
                applyLogoTo('mainNavLogoImg', 'mainNavLogoIcon', 'mainNavLogoText');
                applyLogoTo('footerLogoImg', 'footerLogoIcon', 'footerLogoText');
                applyLogoTo('preloaderLogoImg', 'preloaderLogoIcon', 'preloaderLogoText');

                // Update Browser Tab Favicon
                if (faviconUrl) {
                    const favEl = document.getElementById('siteFavicon') || document.querySelector("link[rel*='icon']");
                    if (favEl) favEl.href = faviconUrl;
                }

                // Update Document Title
                if (d.store_name) {
                    const tagline = d.tagline ? ` - ${d.tagline}` : ' - Marketplace Aplikasi Pendidikan';
                    document.title = `${d.store_name}${tagline}`;
                }

                // Update Footer About Text
                const footerAbout = document.querySelector('.footer-about');
                if (footerAbout && d.description) {
                    footerAbout.textContent = d.description;
                }

                // Update Copyright Text
                const cpEl = document.querySelector('.copyright');
                if (cpEl && d.copyright_text) {
                    cpEl.innerHTML = d.copyright_text;
                }

                // Update Contact Links (WhatsApp, Email)
                if (d.whatsapp) {
                    const waClean = d.whatsapp.replace(/[^0-9]/g, '');
                    const waElements = document.querySelectorAll("a[href*='wa.me']");
                    waElements.forEach(el => {
                        el.href = `https://wa.me/${waClean}`;
                    });
                }
            }
        } catch (e) {
            console.warn('Store profile load warning:', e);
        }
    }

    // ----------------------------------------------------------------
    // 8. PRODUCTS LISTING, FILTERS, SEARCH & SORT
    // ----------------------------------------------------------------
    async function loadProducts() {
        const grid = document.getElementById('productsGrid');
        if (!grid) return;

        try {
            const response = await fetch('api/products.php');
            if (!response.ok) throw new Error('API offline/error');
            const result = await response.json();
            if (result.success && Array.isArray(result.data) && result.data.length > 0) {
                state.products = result.data;
            } else {
                state.products = FALLBACK_PRODUCTS;
            }
        } catch (err) {
            console.info('Menggunakan fallback dataset produk edukasi:', err.message);
            state.products = FALLBACK_PRODUCTS;
        }

        renderProducts();
    }

    function renderProducts() {
        const grid = document.getElementById('productsGrid');
        if (!grid) return;

        let filtered = [...state.products];

        // Filter Kategori
        if (state.activeCategory !== 'all') {
            filtered = filtered.filter(p => p.category === state.activeCategory);
        }

        // Filter Pencarian
        if (state.searchQuery.trim() !== '') {
            const q = state.searchQuery.toLowerCase();
            filtered = filtered.filter(p => 
                p.name.toLowerCase().includes(q) ||
                p.short_description.toLowerCase().includes(q) ||
                (Array.isArray(p.tech_stack) && p.tech_stack.some(t => t.toLowerCase().includes(q)))
            );
        }

        // Pengurutan (Sorting)
        if (state.sortBy === 'price-low') {
            filtered.sort((a, b) => (a.discount_price || a.price) - (b.discount_price || b.price));
        } else if (state.sortBy === 'price-high') {
            filtered.sort((a, b) => (b.discount_price || b.price) - (a.discount_price || a.price));
        } else if (state.sortBy === 'rating') {
            filtered.sort((a, b) => b.rating - a.rating);
        } else if (state.sortBy === 'newest') {
            filtered.sort((a, b) => b.id - a.id);
        } else {
            // Featured
            filtered.sort((a, b) => (b.is_featured ? 1 : 0) - (a.is_featured ? 1 : 0));
        }

        if (filtered.length === 0) {
            grid.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                    <div style="font-size: 3rem; color: var(--gray-400); margin-bottom: 16px;">
                        <i class="fas fa-search-minus"></i>
                    </div>
                    <h3 style="margin-bottom: 8px;">Produk Tidak Ditemukan</h3>
                    <p style="color: var(--gray-500);">Tidak ada produk yang cocok dengan pencarian atau filter yang Anda pilih.</p>
                    <button class="btn btn-outline btn-sm" id="resetFilterBtn" style="margin-top: 12px;">
                        <i class="fas fa-undo"></i> Reset Filter
                    </button>
                </div>
            `;
            const resetBtn = document.getElementById('resetFilterBtn');
            if (resetBtn) {
                resetBtn.addEventListener('click', () => {
                    state.activeCategory = 'all';
                    state.searchQuery = '';
                    document.getElementById('productSearch').value = '';
                    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                    const allTab = document.querySelector('.filter-tab[data-category="all"]');
                    if (allTab) allTab.classList.add('active');
                    renderProducts();
                });
            }
            return;
        }

        grid.innerHTML = filtered.map(product => {
            const currentPrice = product.discount_price || product.price;
            const hasDiscount = product.discount_price && product.discount_price < product.price;
            const discountPercent = hasDiscount ? Math.round(((product.price - product.discount_price) / product.price) * 100) : 0;

            const categoryLabels = {
                'aplikasi-sekolah': 'Aplikasi Sekolah',
                'e-learning': 'E-Learning',
                'sistem-informasi': 'Sistem Informasi',
                'aplikasi-pembelajaran': 'Pembelajaran'
            };

            const techTags = Array.isArray(product.tech_stack) 
                ? product.tech_stack.slice(0, 3).map(tech => `<span class="tech-tag">${tech}</span>`).join('') 
                : '';

            return `
                <div class="product-card" data-id="${product.id}" data-aos="fade-up">
                    <div class="product-image-wrapper">
                        <img 
                            src="${product.image}" 
                            alt="${product.name}" 
                            class="product-image"
                            onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&auto=format&fit=crop&q=80';"
                        >
                        <div class="product-overlay">
                            <button class="btn btn-primary btn-sm view-detail-btn" data-id="${product.id}">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                            <button class="btn btn-ghost btn-sm quick-buy-btn" data-id="${product.id}">
                                <i class="fas fa-shopping-bag"></i> Beli
                            </button>
                        </div>
                        <div class="product-badges">
                            ${product.is_bestseller ? '<span class="badge badge-bestseller"><i class="fas fa-fire"></i> Terlaris</span>' : ''}
                            ${product.is_featured ? '<span class="badge badge-featured"><i class="fas fa-star"></i> Pilihan</span>' : ''}
                            ${hasDiscount ? `<span class="badge badge-discount">Diskon ${discountPercent}%</span>` : ''}
                        </div>
                        <span class="product-category-badge">${categoryLabels[product.category] || product.category}</span>
                    </div>
                    <div class="product-body">
                        <h3 class="product-title">${product.name}</h3>
                        <p class="product-short-desc">${product.short_description}</p>
                        <div class="product-tech-stack">${techTags}</div>
                        <div class="product-rating">
                            <div class="stars">${renderStars(product.rating)}</div>
                            <span class="rating-text">(${product.rating} / ${product.total_reviews} ulasan)</span>
                        </div>
                        <div class="product-footer">
                            <div class="product-price">
                                ${hasDiscount ? `<span class="price-original">${formatRupiah(product.price)}</span>` : ''}
                                <span class="price-current">${formatRupiah(currentPrice)}</span>
                            </div>
                            <button class="btn btn-primary btn-sm product-buy-btn quick-buy-btn" data-id="${product.id}">
                                <i class="fas fa-shopping-cart"></i> Beli
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        // Pasang event listener untuk tombol di dalam kartu produk
        grid.querySelectorAll('.view-detail-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const id = parseInt(btn.getAttribute('data-id'));
                openProductModal(id);
            });
        });

        grid.querySelectorAll('.quick-buy-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const id = parseInt(btn.getAttribute('data-id'));
                const product = state.products.find(p => p.id === id);
                if (product) openOrderModal(product);
            });
        });

        // Klik pada seluruh kartu juga membuka detail
        grid.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', () => {
                const id = parseInt(card.getAttribute('data-id'));
                openProductModal(id);
            });
        });
    }

    function initProductFilters() {
        const tabs = document.querySelectorAll('.filter-tab');
        const searchInput = document.getElementById('productSearch');
        const sortSelect = document.getElementById('productSort');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                state.activeCategory = tab.getAttribute('data-category');
                renderProducts();
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', debounce((e) => {
                state.searchQuery = e.target.value;
                renderProducts();
            }, 300));
        }

        if (sortSelect) {
            sortSelect.addEventListener('change', (e) => {
                state.sortBy = e.target.value;
                renderProducts();
            });
        }
    }

    // ----------------------------------------------------------------
    // 9. PRODUCT DETAIL MODAL
    // ----------------------------------------------------------------
    async function openProductModal(productId) {
        let product = state.products.find(p => p.id === productId);

        // Coba fetch detail lengkap dari API jika ada
        try {
            const res = await fetch(`api/products.php?id=${productId}`);
            if (res.ok) {
                const json = await res.json();
                if (json.success && json.data) {
                    product = json.data;
                }
            }
        } catch (e) {
            // Gunakan state.products atau fallback
        }

        if (!product) return;

        const modal = document.getElementById('productModal');
        const modalBody = document.getElementById('modalBody');
        if (!modal || !modalBody) return;

        const currentPrice = product.discount_price || product.price;
        const hasDiscount = product.discount_price && product.discount_price < product.price;

        const gallery = Array.isArray(product.gallery) && product.gallery.length > 0 
            ? product.gallery 
            : [product.image];

        const featuresList = Array.isArray(product.features)
            ? product.features.map(f => `<li><i class="fas fa-check-circle"></i> ${f}</li>`).join('')
            : '<li><i class="fas fa-check-circle"></i> Source Code Lengkap & Database</li>';

        const techList = Array.isArray(product.tech_stack)
            ? product.tech_stack.map(t => `<span class="tech-tag">${t}</span>`).join('')
            : '';

        modalBody.innerHTML = `
            <div class="modal-product">
                <div class="modal-gallery">
                    <div class="modal-main-image">
                        <img id="modalMainImg" src="${gallery[0] || product.image}" alt="${product.name}">
                    </div>
                    ${gallery.length > 1 ? `
                        <div class="modal-thumbnails">
                            ${gallery.map((img, idx) => `
                                <div class="modal-thumb ${idx === 0 ? 'active' : ''}" data-src="${img}">
                                    <img src="${img}" alt="Thumbnail ${idx + 1}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            `).join('')}
                        </div>
                    ` : ''}
                </div>
                <div class="modal-info">
                    <h2 class="modal-product-title">${product.name}</h2>
                    <span class="modal-product-category"><i class="fas fa-tag"></i> ${product.category}</span>
                    <p class="modal-product-description">${product.description || product.short_description}</p>
                    
                    <h4 class="modal-section-title"><i class="fas fa-shield-alt"></i> Fitur & Keunggulan Utama</h4>
                    <ul class="modal-features">${featuresList}</ul>

                    <h4 class="modal-section-title"><i class="fas fa-laptop-code"></i> Teknologi (Tech Stack)</h4>
                    <div class="modal-tech-tags">${techList}</div>

                    <div class="modal-price-section">
                        <div>
                            ${hasDiscount ? `<div class="modal-price-original">${formatRupiah(product.price)}</div>` : ''}
                            <div class="modal-price-current">${formatRupiah(currentPrice)}</div>
                        </div>
                        ${hasDiscount ? `<span class="modal-price-discount">Hemat ${formatRupiah(product.price - product.discount_price)}</span>` : ''}
                    </div>

                    <div class="modal-actions">
                        <button class="btn btn-primary btn-lg" id="modalBuyBtn">
                            <i class="fas fa-shopping-cart"></i> Beli Sekarang
                        </button>
                        <button class="btn btn-outline btn-lg" id="modalLiveDemoBtn">
                            <i class="fas fa-external-link-alt"></i> Coba Demo
                        </button>
                    </div>
                </div>

                <div class="modal-reviews">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h4 style="margin: 0;"><i class="fas fa-star" style="color: #D97706;"></i> Ulasan Pengguna (${product.rating}/5.0)</h4>
                        <button class="btn btn-outline btn-sm" id="modalAddReviewBtn">
                            <i class="fas fa-pen"></i> Tulis Ulasan
                        </button>
                    </div>
                    <div class="reviews-list">
                        <div class="review-item">
                            <div class="review-avatar">A</div>
                            <div class="review-body">
                                <h5>Agus Prasetyo - Guru TIK</h5>
                                <div class="review-meta">
                                    <div class="review-stars">${renderStars(5)}</div>
                                    <span class="review-date">Terverifikasi Pembeli</span>
                                </div>
                                <p class="review-text">Aplikasi ini berjalan sangat mulus di server sekolah kami. Dokumentasi instalasinya luar biasa jelas. Sangat membantu tugas kami!</p>
                            </div>
                        </div>
                        <div class="review-item">
                            <div class="review-avatar">M</div>
                            <div class="review-body">
                                <h5>Maya Indriani - Tim IT Yayasan</h5>
                                <div class="review-meta">
                                    <div class="review-stars">${renderStars(5)}</div>
                                    <span class="review-date">Terverifikasi Pembeli</span>
                                </div>
                                <p class="review-text">Sangat responsif dan desain UI-nya modern sekali. Siswa dan guru tidak kesulitan beradaptasi dengan sistem ini.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Thumbnail switcher
        modalBody.querySelectorAll('.modal-thumb').forEach(thumb => {
            thumb.addEventListener('click', () => {
                modalBody.querySelectorAll('.modal-thumb').forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');
                const mainImg = document.getElementById('modalMainImg');
                if (mainImg) mainImg.src = thumb.getAttribute('data-src');
            });
        });

        // Beli sekarang button
        const buyBtn = document.getElementById('modalBuyBtn');
        if (buyBtn) {
            buyBtn.addEventListener('click', () => {
                closeProductModal();
                openOrderModal(product);
            });
        }

        // Live demo button
        const demoBtn = document.getElementById('modalLiveDemoBtn');
        if (demoBtn) {
            demoBtn.addEventListener('click', () => {
                Swal.fire({
                    title: 'Akses Demo Online',
                    html: `
                        <div style="text-align: left; font-size: 0.95rem;">
                            <p>Anda akan diarahkan ke server simulasi demo untuk <strong>${product.name}</strong>.</p>
                            <div style="background: #F7F3EC; padding: 12px; border-radius: 8px; margin: 12px 0;">
                                <strong>Akun Demo:</strong><br>
                                Username: <code>admin_demo</code><br>
                                Password: <code>demo12345</code>
                            </div>
                            <p style="font-size: 0.85rem; color: #5A6178;">Catatan: Database demo di-reset otomatis setiap 12 jam.</p>
                        </div>
                    `,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-play"></i> Buka Server Demo',
                    cancelButtonText: 'Tutup',
                    confirmButtonColor: '#1E2A5A'
                }).then((res) => {
                    if (res.isConfirmed) {
                        showToast(`Membuka demo ${product.name}...`, 'info');
                    }
                });
            });
        }

        // Tulis ulasan button
        const addReviewBtn = document.getElementById('modalAddReviewBtn');
        if (addReviewBtn) {
            addReviewBtn.addEventListener('click', () => {
                openReviewModal(product);
            });
        }

        modal.classList.add('active');
        document.body.classList.add('no-scroll');
    }

    function closeProductModal() {
        const modal = document.getElementById('productModal');
        if (modal) {
            modal.classList.remove('active');
            document.body.classList.remove('no-scroll');
        }
    }

    function initProductModal() {
        const modal = document.getElementById('productModal');
        const closeBtn = document.getElementById('modalClose');

        if (closeBtn) {
            closeBtn.addEventListener('click', closeProductModal);
        }

        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeProductModal();
                }
            });
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal && modal.classList.contains('active')) {
                closeProductModal();
            }
        });
    }

    // ----------------------------------------------------------------
    // 10. ORDER & PURCHASE FLOW DENGAN SWEETALERT2
    // ----------------------------------------------------------------
    function openOrderModal(product) {
        const currentPrice = product.discount_price || product.price;

        Swal.fire({
            title: `<div style="font-size: 1.35rem; font-weight: 800;">Beli ${product.name}</div>`,
            html: `
                <div style="text-align: left; font-size: 0.9rem;">
                    <div style="background: #F7F3EC; padding: 14px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #D8D2C2;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-weight: 600; color: #333;">Total Pembayaran:</span>
                            <span style="font-size: 1.3rem; font-weight: 800; color: #1E2A5A;">${formatRupiah(currentPrice)}</span>
                        </div>
                        <small style="color: #6c757d;">Sudah termasuk Full Source Code, Database SQL, dan Video Tutorial.</small>
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 4px;">Nama Lengkap / Sekolah <span style="color: red;">*</span></label>
                        <input id="swalOrderName" class="swal2-input" placeholder="Contoh: Budi Santoso / SMK Negeri 1" style="width: 100%; margin: 0; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 4px;">Alamat Email (untuk pengiriman file) <span style="color: red;">*</span></label>
                        <input id="swalOrderEmail" type="email" class="swal2-input" placeholder="nama@gmail.com" style="width: 100%; margin: 0; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 4px;">Nomor WhatsApp Aktif <span style="color: red;">*</span></label>
                        <input id="swalOrderPhone" type="tel" class="swal2-input" placeholder="081234567890" style="width: 100%; margin: 0; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 4px;">Metode Pembayaran <span style="color: red;">*</span></label>
                        <select id="swalOrderPayment" class="swal2-select" style="width: 100%; margin: 0; box-sizing: border-box;">
                            <option value="qris">QRIS (BCA, Mandiri, OVO, Dana, GoPay, ShopeePay)</option>
                            <option value="bank_transfer">Transfer Bank Virtual Account (BCA / Mandiri / BRI / BNI)</option>
                            <option value="ewallet">E-Wallet Langsung (Dana / OVO / GoPay)</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 8px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 4px;">Catatan Tambahan (Opsional)</label>
                        <input id="swalOrderNotes" class="swal2-input" placeholder="Misal: Mohon sertakan invoice atas nama sekolah" style="width: 100%; margin: 0; box-sizing: border-box;">
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-lock"></i> Lanjutkan Pemesanan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#1E2A5A',
            cancelButtonColor: '#6c757d',
            focusConfirm: false,
            preConfirm: () => {
                const name = document.getElementById('swalOrderName').value.trim();
                const email = document.getElementById('swalOrderEmail').value.trim();
                const phone = document.getElementById('swalOrderPhone').value.trim();
                const payment_method = document.getElementById('swalOrderPayment').value;
                const notes = document.getElementById('swalOrderNotes').value.trim();

                if (!name) {
                    Swal.showValidationMessage('Nama lengkap wajib diisi!');
                    return false;
                }
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!email || !emailRegex.test(email)) {
                    Swal.showValidationMessage('Alamat email valid wajib diisi!');
                    return false;
                }
                if (!phone || phone.length < 9) {
                    Swal.showValidationMessage('Nomor WhatsApp valid wajib diisi!');
                    return false;
                }

                return {
                    product_id: product.id,
                    product_name: product.name,
                    amount: currentPrice,
                    customer_name: name,
                    customer_email: email,
                    customer_phone: phone,
                    payment_method: payment_method,
                    notes: notes
                };
            }
        }).then(async (result) => {
            if (result.isConfirmed && result.value) {
                const orderData = result.value;

                // Tampilkan loading state
                Swal.fire({
                    title: 'Memproses Pesanan...',
                    html: 'Mohon tunggu sejenak, sistem sedang menerbitkan nomor invoice Anda.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                let orderNumber = `EDU-${new Date().getFullYear()}${String(new Date().getMonth() + 1).padStart(2, '0')}-${Math.floor(1000 + Math.random() * 9000)}`;

                try {
                    const response = await fetch('api/orders.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(orderData)
                    });
                    const resJson = await response.json();
                    if (resJson.success && resJson.data && resJson.data.order_number) {
                        orderNumber = resJson.data.order_number;
                    }
                } catch (e) {
                    // Gunakan generated order number
                }

                // Tampilkan sukses popup
                Swal.fire({
                    title: '<span style="color: #28a745;"><i class="fas fa-check-circle"></i> Pesanan Berhasil Dibuat!</span>',
                    html: `
                        <div style="text-align: left; font-size: 0.95rem; line-height: 1.6;">
                            <p>Terima kasih <strong>${orderData.customer_name}</strong>! Pesanan Anda untuk produk <strong>${orderData.product_name}</strong> telah kami terima.</p>
                            
                            <div style="background: #F7F3EC; border-left: 4px solid #1E2A5A; padding: 14px; margin: 16px 0; border-radius: 4px;">
                                <div><strong>Nomor Invoice:</strong> <code>${orderNumber}</code></div>
                                <div><strong>Total Tagihan:</strong> <span style="font-weight: 700; color: #1E2A5A;">${formatRupiah(orderData.amount)}</span></div>
                                <div><strong>Metode:</strong> ${orderData.payment_method.toUpperCase()}</div>
                            </div>

                            <p style="font-size: 0.9rem; color: #555;">
                                Instruksi pembayaran lengkap beserta link akses download telah dikirimkan ke email: <strong>${orderData.customer_email}</strong>.
                            </p>
                            
                            <div style="background: #E8F5E9; padding: 12px; border-radius: 8px; font-size: 0.85rem; color: #2E7D32;">
                                <i class="fas fa-info-circle"></i> Ingin konfirmasi instan via WhatsApp admin kami?
                            </div>
                        </div>
                    `,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fab fa-whatsapp"></i> Chat WhatsApp Admin',
                    cancelButtonText: 'Tutup',
                    confirmButtonColor: '#25D366'
                }).then((confirmRes) => {
                    if (confirmRes.isConfirmed) {
                        const cleanWa = (storeSettings.whatsapp || '6281234567890').replace(/[^0-9]/g, '');
                        const waText = encodeURIComponent(`Halo Admin ${storeSettings.store_name || 'EduMarket'}, saya ingin konfirmasi pesanan dengan No. Invoice: ${orderNumber} (${orderData.product_name}) sebesar ${formatRupiah(orderData.amount)}. Terima kasih!`);
                        window.open(`https://wa.me/${cleanWa}?text=${waText}`, '_blank');
                    }
                });
            }
        });
    }

    // Modal untuk submit review
    function openReviewModal(product) {
        Swal.fire({
            title: `Beri Ulasan: ${product.name}`,
            html: `
                <div style="text-align: left; font-size: 0.9rem;">
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 4px;">Nama Anda <span style="color: red;">*</span></label>
                        <input id="swalReviewName" class="swal2-input" placeholder="Nama Lengkap / Gelar" style="width: 100%; margin: 0; box-sizing: border-box;">
                    </div>
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 4px;">Email Anda <span style="color: red;">*</span></label>
                        <input id="swalReviewEmail" type="email" class="swal2-input" placeholder="email@sekolah.sch.id" style="width: 100%; margin: 0; box-sizing: border-box;">
                    </div>
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 4px;">Rating Bintang <span style="color: red;">*</span></label>
                        <select id="swalReviewRating" class="swal2-select" style="width: 100%; margin: 0; box-sizing: border-box;">
                            <option value="5">5 — Sangat memuaskan</option>
                            <option value="4">4 — Bagus</option>
                            <option value="3">3 — Cukup</option>
                            <option value="2">2 — Kurang</option>
                            <option value="1">1 — Sangat kurang</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 4px;">Ulasan Anda <span style="color: red;">*</span></label>
                        <textarea id="swalReviewComment" class="swal2-textarea" placeholder="Ceritakan pengalaman Anda menggunakan aplikasi ini..." style="width: 100%; margin: 0; box-sizing: border-box;"></textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-paper-plane"></i> Kirim Ulasan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#1E2A5A',
            preConfirm: () => {
                const name = document.getElementById('swalReviewName').value.trim();
                const email = document.getElementById('swalReviewEmail').value.trim();
                const rating = document.getElementById('swalReviewRating').value;
                const comment = document.getElementById('swalReviewComment').value.trim();

                if (!name || !email || !comment) {
                    Swal.showValidationMessage('Semua kolom ulasan wajib diisi!');
                    return false;
                }
                return { product_id: product.id, reviewer_name: name, reviewer_email: email, rating: parseInt(rating), comment: comment };
            }
        }).then(async (res) => {
            if (res.isConfirmed && res.value) {
                try {
                    await fetch('api/reviews.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(res.value)
                    });
                } catch (e) {}

                Swal.fire({
                    title: 'Ulasan Terkirim!',
                    text: 'Terima kasih atas masukan berharga Anda untuk pengembangan produk ini.',
                    icon: 'success',
                    confirmButtonColor: '#1E2A5A'
                });
            }
        });
    }

    // ----------------------------------------------------------------
    // 11. TESTIMONIALS SLIDER
    // ----------------------------------------------------------------
    function initTestimonials() {
        const slider = document.getElementById('testimonialsSlider');
        const prevBtn = document.getElementById('testimonialPrev');
        const nextBtn = document.getElementById('testimonialNext');
        const dotsContainer = document.getElementById('testimonialDots');
        if (!slider) return;

        slider.innerHTML = TESTIMONIALS_DATA.map(t => `
            <div class="testimonial-card">
                <div class="testimonial-quote-icon">
                    <i class="fas fa-quote-left"></i>
                </div>
                <p class="testimonial-content">"${t.content}"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">
                        ${t.name.charAt(0)}
                    </div>
                    <div class="testimonial-info">
                        <h4>${t.name}</h4>
                        <p>${t.position} - ${t.institution}</p>
                        <div class="testimonial-rating">
                            ${renderStars(t.rating)}
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        const cards = slider.querySelectorAll('.testimonial-card');
        const totalCards = cards.length;

        function getVisibleCards() {
            if (window.innerWidth <= 768) return 1;
            if (window.innerWidth <= 992) return 2;
            return 3;
        }

        function getMaxIndex() {
            return Math.max(0, totalCards - getVisibleCards());
        }

        function updateSlider() {
            const cardWidthPercent = 100 / getVisibleCards();
            const offset = state.currentTestimonialIndex * cardWidthPercent;
            slider.style.transform = `translateX(-${offset}%)`;

            // Update Dots
            if (dotsContainer) {
                const totalDots = getMaxIndex() + 1;
                dotsContainer.innerHTML = Array.from({ length: totalDots }).map((_, i) => `
                    <div class="slider-dot ${i === state.currentTestimonialIndex ? 'active' : ''}" data-index="${i}"></div>
                `).join('');

                dotsContainer.querySelectorAll('.slider-dot').forEach(dot => {
                    dot.addEventListener('click', () => {
                        state.currentTestimonialIndex = parseInt(dot.getAttribute('data-index'));
                        updateSlider();
                    });
                });
            }
        }

        function nextSlide() {
            if (state.currentTestimonialIndex >= getMaxIndex()) {
                state.currentTestimonialIndex = 0;
            } else {
                state.currentTestimonialIndex++;
            }
            updateSlider();
        }

        function prevSlide() {
            if (state.currentTestimonialIndex <= 0) {
                state.currentTestimonialIndex = getMaxIndex();
            } else {
                state.currentTestimonialIndex--;
            }
            updateSlider();
        }

        if (nextBtn) nextBtn.addEventListener('click', nextSlide);
        if (prevBtn) prevBtn.addEventListener('click', prevSlide);

        // Auto play slider
        function startAutoPlay() {
            state.testimonialInterval = setInterval(nextSlide, 5000);
        }
        function stopAutoPlay() {
            clearInterval(state.testimonialInterval);
        }

        slider.addEventListener('mouseenter', stopAutoPlay);
        slider.addEventListener('mouseleave', startAutoPlay);

        window.addEventListener('resize', debounce(() => {
            if (state.currentTestimonialIndex > getMaxIndex()) {
                state.currentTestimonialIndex = getMaxIndex();
            }
            updateSlider();
        }, 200));

        updateSlider();
        startAutoPlay();
    }

    // ----------------------------------------------------------------
    // 12. FAQ ACCORDION
    // ----------------------------------------------------------------
    function initFAQ() {
        const faqList = document.getElementById('faqList');
        if (!faqList) return;

        faqList.innerHTML = FAQ_DATA.map((item, index) => `
            <div class="faq-item ${index === 0 ? 'active' : ''}" data-index="${index}">
                <div class="faq-question">
                    <span><i class="fas fa-question-circle" style="color: var(--primary); margin-right: 8px;"></i> ${item.question}</span>
                    <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                </div>
                <div class="faq-answer" style="${index === 0 ? 'max-height: 250px;' : 'max-height: 0;'}">
                    <div class="faq-answer-content">
                        ${item.answer}
                    </div>
                </div>
            </div>
        `).join('');

        const items = faqList.querySelectorAll('.faq-item');
        items.forEach(item => {
            const question = item.querySelector('.faq-question');
            const answer = item.querySelector('.faq-answer');

            question.addEventListener('click', () => {
                const isActive = item.classList.contains('active');

                // Tutup item lain
                items.forEach(otherItem => {
                    otherItem.classList.remove('active');
                    const otherAnswer = otherItem.querySelector('.faq-answer');
                    if (otherAnswer) otherAnswer.style.maxHeight = '0';
                });

                if (!isActive) {
                    item.classList.add('active');
                    answer.style.maxHeight = answer.scrollHeight + 30 + 'px';
                }
            });
        });
    }

    // ----------------------------------------------------------------
    // 13. CONTACT FORM SUBMISSION
    // ----------------------------------------------------------------
    function initContactForm() {
        const form = document.getElementById('contactForm');
        if (!form) return;

        const nameInput = document.getElementById('contactName');
        const emailInput = document.getElementById('contactEmail');
        const subjectInput = document.getElementById('contactSubject');
        const messageInput = document.getElementById('contactMessage');
        const submitBtn = document.getElementById('contactSubmitBtn');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Validasi
            let isValid = true;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!nameInput.value.trim()) {
                document.getElementById('nameError').textContent = 'Nama lengkap wajib diisi';
                isValid = false;
            } else {
                document.getElementById('nameError').textContent = '';
            }

            if (!emailInput.value.trim() || !emailRegex.test(emailInput.value.trim())) {
                document.getElementById('emailError').textContent = 'Format email tidak valid';
                isValid = false;
            } else {
                document.getElementById('emailError').textContent = '';
            }

            if (!subjectInput.value.trim()) {
                document.getElementById('subjectError').textContent = 'Subjek pesan wajib diisi';
                isValid = false;
            } else {
                document.getElementById('subjectError').textContent = '';
            }

            if (!messageInput.value.trim() || messageInput.value.trim().length < 10) {
                document.getElementById('messageError').textContent = 'Pesan minimal 10 karakter';
                isValid = false;
            } else {
                document.getElementById('messageError').textContent = '';
            }

            if (!isValid) return;

            // Loading state
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');
            if (btnText) btnText.style.display = 'none';
            if (btnLoading) btnLoading.style.display = 'inline-block';
            submitBtn.disabled = true;

            const payload = {
                name: nameInput.value.trim(),
                email: emailInput.value.trim(),
                subject: subjectInput.value.trim(),
                message: messageInput.value.trim()
            };

            try {
                const response = await fetch('api/contact.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                await response.json();
            } catch (err) {
                // Silently handle offline
            }

            // Reset form & pulihkan tombol
            form.reset();
            if (btnText) btnText.style.display = 'inline-block';
            if (btnLoading) btnLoading.style.display = 'none';
            submitBtn.disabled = false;

            Swal.fire({
                title: 'Pesan Terkirim!',
                text: 'Terima kasih telah menghubungi EduMarket. Tim kami akan segera merespons pesan Anda via email atau WhatsApp.',
                icon: 'success',
                confirmButtonColor: '#1E2A5A'
            });
        });
    }

    // ----------------------------------------------------------------
    // 14. NEWSLETTER FORM
    // ----------------------------------------------------------------
    function initNewsletter() {
        const form = document.getElementById('newsletterForm');
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const input = document.getElementById('newsletterEmail');
            const email = input ? input.value.trim() : '';

            if (!email) return;

            try {
                await fetch('api/subscribe.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: email })
                });
            } catch (err) {}

            form.reset();

            Swal.fire({
                title: 'Berhasil Berlangganan!',
                text: 'Selamat! Anda akan menjadi yang pertama menerima promo kupon dan rilis aplikasi pendidikan terbaru.',
                icon: 'success',
                confirmButtonColor: '#1E2A5A'
            });
        });
    }

    // ----------------------------------------------------------------
    // 15. BACK TO TOP BUTTON
    // ----------------------------------------------------------------
    function initBackToTop() {
        const btn = document.getElementById('backToTop');
        if (!btn) return;

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 400) {
                btn.classList.add('visible');
            } else {
                btn.classList.remove('visible');
            }
        });

        btn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // ----------------------------------------------------------------
    // 18. INITIALIZE AOS ANIMATIONS
    // ----------------------------------------------------------------
    function initAOS() {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 500,
                once: true,
                offset: 24,
                easing: 'ease-out-cubic'
            });
        }
    }

    // ----------------------------------------------------------------
    // ENTRY POINT SAAT DOM READY
    // ----------------------------------------------------------------
    document.addEventListener('DOMContentLoaded', () => {
        initPreloader();
        initNavbar();
        initDarkMode();
        initStatsCounters();
        loadStoreProfile();
        loadProducts();
        initProductFilters();
        initProductModal();
        initTestimonials();
        initFAQ();
        initContactForm();
        initNewsletter();
        initBackToTop();
        initAOS();
    });

})();
