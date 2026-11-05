<?php
/**
 * =====================================================
 * Database Configuration
 * =====================================================
 * Central database connection configuration using PDO.
 * All API endpoints include this file to access the DB.
 * =====================================================
 */

// ── Environment Variables / .env Support ─────────────
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile) && is_readable($envFile)) {
    $envLines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envLines as $envLine) {
        $envLine = trim($envLine);
        if ($envLine === '' || str_starts_with($envLine, '#')) continue;
        if (strpos($envLine, '=') !== false) {
            [$envKey, $envVal] = explode('=', $envLine, 2);
            $envKey = trim($envKey);
            $envVal = trim($envVal, " \t\n\r\0\x0B\"'");
            if (getenv($envKey) === false) {
                putenv("{$envKey}={$envVal}");
                $_ENV[$envKey] = $envVal;
            }
        }
    }
}

// ── Database Credentials ──────────────────────────────
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'marketplace_db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

/**
 * Get a PDO database connection instance.
 *
 * Uses persistent connections for performance and sets
 * PDO to throw exceptions on errors for easier debugging.
 *
 * @return PDO Active database connection
 * @throws PDOException If connection fails
 */
function getConnection(): PDO
{
    static $pdo = null;

    // Return existing connection if available (singleton pattern)
    if ($pdo !== null) {
        return $pdo;
    }

    // Build DSN string
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        DB_HOST,
        DB_NAME,
        DB_CHARSET
    );

    // PDO connection options
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,    // Throw exceptions on errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,          // Return associative arrays
        PDO::ATTR_EMULATE_PREPARES   => false,                     // Use real prepared statements
        PDO::ATTR_PERSISTENT         => true,                      // Persistent connections
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        // If request is from API, return JSON
        if (strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') !== false) {
            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'message' => 'Koneksi database gagal. Silakan pastikan MySQL di Laragon sudah aktif.',
                'data'    => null
            ]);
            exit;
        }
        // If web page, rethrow so caller can display friendly notice or fallback
        throw $e;
    }
}

/**
 * Get a PDO connection or null without throwing fatal exits.
 * Useful for auth pages and fallback handlers.
 */
function getSafeConnection(): ?PDO
{
    try {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        ensureTablesExist($pdo);
        return $pdo;
    } catch (\Throwable $e) {
        return null;
    }
}

/**
 * Automatically create admins and payment_settings tables if they don't exist yet.
 */
function ensureTablesExist(PDO $pdo): void
{
    try {
        // 1. Table admins
        $pdo->exec("
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
        ");

        // Seed default admin if table is empty
        $chkAdmin = $pdo->query("SELECT COUNT(*) FROM `admins`")->fetchColumn();
        if ($chkAdmin == 0) {
            $defaultHash = password_hash('admin123', PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("
                INSERT INTO `admins` (`username`, `email`, `password_hash`, `name`, `role`)
                VALUES (:u, :e, :p, :n, 'superadmin')
            ");
            $stmt->execute([
                ':u' => 'admin',
                ':e' => 'admin@edumarket.id',
                ':p' => $defaultHash,
                ':n' => 'Super Administrator EduMarket'
            ]);
        }

        // 2. Table payment_settings
        $pdo->exec("
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
        ");

        // Seed default payment methods if empty
        $chkPay = $pdo->query("SELECT COUNT(*) FROM `payment_settings`")->fetchColumn();
        if ($chkPay == 0) {
            $payments = [
                ['qris', 'qris', 'QRIS All Payment (BCA, Mandiri, Dana, GoPay, OVO, ShopeePay)', '0001-QRIS-EDUMKT', 'EduMarket Digital Indonesia', 'assets/images/qris-sample.svg', 'Scan kode QRIS menggunakan aplikasi perbankan atau e-wallet apa saja. Pembayaran akan terverifikasi otomatis.', 1, 1],
                ['bank_bca', 'bank', 'Bank Central Asia (BCA)', '8270-1928-3341', 'PT EduMarket Digital Indonesia', null, 'Transfer ke nomor rekening BCA di atas melalui ATM, m-BCA, atau KlikBCA. Masukkan nomor invoice pada berita transfer.', 1, 2],
                ['bank_mandiri', 'bank', 'Bank Mandiri', '140-00-9876543-2', 'PT EduMarket Digital Indonesia', null, 'Transfer melalui Livin by Mandiri atau ATM Mandiri. Simpan bukti transfer Anda.', 1, 3],
                ['bank_bri', 'bank', 'Bank Rakyat Indonesia (BRI)', '0341-01-002891-30-5', 'PT EduMarket Digital Indonesia', null, 'Transfer melalui BRImo atau ATM BRI. Konfirmasi setelah melakukan pembayaran.', 1, 4],
                ['ewallet_dana', 'ewallet', 'DANA ID', '0812-3456-7890', 'EduMarket Official', null, 'Kirim saldo DANA ke nomor tertera. Cantumkan nama dan nomor invoice pesanan Anda.', 1, 5],
                ['ewallet_ovo', 'ewallet', 'OVO Cash', '0812-3456-7890', 'EduMarket Official', null, 'Transfer via OVO ke nomor di atas.', 1, 6],
                ['ewallet_gopay', 'ewallet', 'GoPay ID', '0812-3456-7890', 'EduMarket Official', null, 'Transfer saldo GoPay instan ke nomor resmi EduMarket.', 1, 7],
            ];
            $payStmt = $pdo->prepare("
                INSERT INTO `payment_settings` 
                (`method_key`, `method_type`, `method_title`, `account_number`, `account_holder`, `qr_image`, `instructions`, `is_active`, `sort_order`)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            foreach ($payments as $p) {
                $payStmt->execute($p);
            }
        }

        // 3. Table store_profile
        $pdo->exec("
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
                `operating_hours` VARCHAR(255) NULL DEFAULT 'Senin - Jumat: 08.00 - 17.00 WIB',
                `instagram` VARCHAR(255) NULL DEFAULT 'https://instagram.com/edumarket',
                `facebook` VARCHAR(255) NULL DEFAULT 'https://facebook.com/edumarket',
                `youtube` VARCHAR(255) NULL DEFAULT 'https://youtube.com/@edumarket',
                `warranty_period` VARCHAR(50) NULL DEFAULT '30 Hari Garansi',
                `support_duration` VARCHAR(50) NULL DEFAULT '1 Tahun Update & Support Gratis',
                `copyright_text` VARCHAR(255) NULL DEFAULT '© 2026 EduAdminSP. Manage With <i class=\"fas fa-heart\" style=\"color: #FF3366;\"></i> by adangsp',
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Ensure name_prefix and name_suffix columns exist if table was created previously
        try {
            $pdo->exec("ALTER TABLE `store_profile` ADD COLUMN `name_prefix` VARCHAR(100) NOT NULL DEFAULT 'Edu' AFTER `store_name`");
        } catch (\Throwable $e) {}
        try {
            $pdo->exec("ALTER TABLE `store_profile` ADD COLUMN `name_suffix` VARCHAR(100) NOT NULL DEFAULT 'Market' AFTER `name_prefix`");
        } catch (\Throwable $e) {}

        // Ensure copyright_text is updated to EduAdminSP format if still default
        try {
            $pdo->exec("UPDATE `store_profile` SET `copyright_text` = '© 2026 EduAdminSP. Manage With <i class=\"fas fa-heart\" style=\"color: #FF3366;\"></i> by adangsp' WHERE `copyright_text` LIKE '%EduMarket. Semua hak dilindungi%' OR `copyright_text` IS NULL");
        } catch (\Throwable $e) {}

        $chkStore = $pdo->query("SELECT COUNT(*) FROM `store_profile`")->fetchColumn();
        if ($chkStore == 0) {
            $pdo->exec("
                INSERT INTO `store_profile` 
                (`id`, `store_name`, `name_prefix`, `name_suffix`, `tagline`, `description`, `logo`, `favicon`, `email`, `phone`, `whatsapp`, `address`, `operating_hours`, `instagram`, `facebook`, `youtube`, `warranty_period`, `support_duration`, `copyright_text`)
                VALUES 
                (1, 'EduMarket', 'Edu', 'Market', 'Marketplace Aplikasi Pendidikan & Sekolah Digital', 'EduMarket adalah penyedia solusi digital dan source code aplikasi web pendidikan berkualitas tinggi untuk sekolah, kampus, dan lembaga kursus di Indonesia.', 'assets/images/favicon.svg', 'assets/images/favicon.svg', 'support@edumarket.id', '+62 812-3456-7890', '6281234567890', 'Jl. Teknologi No. 123, Jakarta Selatan, Indonesia', 'Senin - Jumat: 08.00 - 17.00 WIB, Sabtu: 09.00 - 14.00 WIB', 'https://instagram.com/edumarket', 'https://facebook.com/edumarket', 'https://youtube.com/@edumarket', '30 Hari Garansi', '1 Tahun Update & Support Gratis', '© 2026 EduAdminSP. Manage With <i class=\"fas fa-heart\" style=\"color: #FF3366;\"></i> by adangsp')
            ");
        }

        // 9. Table whatsapp_settings (WAHA Gateway)
        $pdo->exec("
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
        ");

        $chkWa = $pdo->query("SELECT COUNT(*) FROM `whatsapp_settings`")->fetchColumn();
        if ($chkWa == 0) {
            $defaultOrderTpl = "Halo *{customer_name}*, terima kasih telah memesan di *{store_name}*! 🎉\n\nNomor Pesanan: *#{order_number}*\nProduk: *{product_name}*\nTotal: *Rp {amount}*\nMetode Pembayaran: *{payment_method}*\n\nSilakan selesaikan pembayaran sesuai petunjuk invoice Anda. Hubungi kami jika butuh bantuan. Terima kasih! 🙏";
            $defaultPaidTpl = "Pembayaran Dikonfirmasi! ✅\n\nHalo *{customer_name}*, pembayaran untuk pesanan *#{order_number}* (*{product_name}*) telah kami verifikasi dengan sukses!\n\nLink Akses / Download:\n🔗 {download_link}\nKode Lisensi: `{license_key}`\n\nTerima kasih telah mempercayai *{store_name}*! 🚀";
            $defaultContactTpl = "📩 *Pesan Baru dari Website {store_name}*\n\nDari: *{contact_name}*\nEmail: {contact_email}\nNo. HP: {contact_phone}\nSubjek: *{contact_subject}*\n\nPesan:\n\"{contact_message}\"";

            $stmtWa = $pdo->prepare("
                INSERT INTO `whatsapp_settings`
                (`id`, `is_enabled`, `api_url`, `api_key`, `session_name`, `admin_phone`, `notify_admin_on_order`, `notify_admin_on_contact`, `notify_customer_on_order`, `notify_customer_on_paid`, `order_template`, `paid_template`, `contact_template`)
                VALUES
                (1, 1, 'http://localhost:3000', '', 'default', '6281234567890', 1, 1, 1, 1, :order_tpl, :paid_tpl, :contact_tpl)
            ");
            $stmtWa->execute([
                ':order_tpl' => $defaultOrderTpl,
                ':paid_tpl' => $defaultPaidTpl,
                ':contact_tpl' => $defaultContactTpl
            ]);
        }
    } catch (\Throwable $e) {
        error_log('Error ensuring tables: ' . $e->getMessage());
    }
}

/**
 * Send a JSON response with proper headers and status code.
 *
 * @param bool        $success  Whether the operation was successful
 * @param string      $message  Human-readable status message
 * @param mixed|null  $data     Response payload data
 * @param int         $code     HTTP status code (default: 200)
 * @return void
 */
function jsonResponse(bool $success, string $message, $data = null, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data'    => $data
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Set CORS headers to allow cross-origin requests.
 * Call this at the top of every API endpoint.
 *
 * @return void
 */
function setCorsHeaders(): void
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Access-Control-Max-Age: 86400'); // Cache preflight for 24 hours

    // Handle preflight OPTIONS request
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
        http_response_code(204);
        exit;
    }
}

/**
 * Sanitize a string input by trimming whitespace and 
 * removing HTML tags.
 *
 * @param string|null $input Raw input string
 * @return string Sanitized string
 */
function sanitizeInput(?string $input): string
{
    if ($input === null) {
        return '';
    }
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate an email address format.
 *
 * @param string $email Email to validate
 * @return bool Whether the email is valid
 */
function isValidEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}
