<?php
/**
 * =====================================================
 * Public Store Profile API Endpoint
 * =====================================================
 */

require_once __DIR__ . '/../config/database.php';
setCorsHeaders();

header('Content-Type: application/json; charset=utf-8');

$defaultProfile = [
    'store_name' => 'EduMarket',
    'name_prefix' => 'Edu',
    'name_suffix' => 'Market',
    'tagline' => 'Marketplace Aplikasi Pendidikan & Sekolah Digital',
    'description' => 'EduMarket adalah penyedia solusi digital dan source code aplikasi web pendidikan berkualitas tinggi untuk sekolah, kampus, dan lembaga kursus di Indonesia.',
    'logo' => 'assets/images/favicon.svg',
    'favicon' => 'assets/images/favicon.svg',
    'email' => 'support@edumarket.id',
    'phone' => '+62 812-3456-7890',
    'whatsapp' => '6281234567890',
    'address' => 'Jl. Teknologi No. 123, Jakarta Selatan, Indonesia',
    'operating_hours' => 'Senin - Jumat: 08.00 - 17.00 WIB, Sabtu: 09.00 - 14.00 WIB',
    'instagram' => 'https://instagram.com/edumarket',
    'facebook' => 'https://facebook.com/edumarket',
    'youtube' => 'https://youtube.com/@edumarket',
    'warranty_period' => '30 Hari Garansi',
    'support_duration' => '1 Tahun Update & Support Gratis',
    'copyright_text' => '© 2026 EduAdminSP. Manage With <i class="fas fa-heart" style="color: #FF3366;"></i> by adangsp'
];

$pdo = getSafeConnection();

// Check if any logo has been uploaded to uploads/logos/
$latestUploadedLogo = null;
$logoDir = __DIR__ . '/../uploads/logos';
if (is_dir($logoDir)) {
    $files = glob($logoDir . '/*.*');
    if (!empty($files)) {
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        $latestUploadedLogo = 'uploads/logos/' . basename($files[0]);
    }
}

if ($latestUploadedLogo) {
    $defaultProfile['logo'] = $latestUploadedLogo;
}

if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM `store_profile` WHERE `id` = 1 LIMIT 1");
        $profile = $stmt->fetch();
        if ($profile) {
            // Auto-heal: If DB still has default favicon.svg or empty, but an uploaded logo exists, adopt it
            if ($latestUploadedLogo && (empty($profile['logo']) || $profile['logo'] === 'assets/images/favicon.svg')) {
                try {
                    $up = $pdo->prepare("UPDATE `store_profile` SET `logo` = ? WHERE `id` = 1");
                    $up->execute([$latestUploadedLogo]);
                    $profile['logo'] = $latestUploadedLogo;
                } catch (\Throwable $e) {}
            }

            // Auto-heal: If DB still has default copyright text, update it
            if (empty($profile['copyright_text']) || str_contains($profile['copyright_text'], 'EduMarket. Semua hak dilindungi')) {
                $newCopyright = '© 2026 EduAdminSP. Manage With <i class="fas fa-heart" style="color: #FF3366;"></i> by adangsp';
                try {
                    $upCr = $pdo->prepare("UPDATE `store_profile` SET `copyright_text` = ? WHERE `id` = 1");
                    $upCr->execute([$newCopyright]);
                    $profile['copyright_text'] = $newCopyright;
                } catch (\Throwable $e) {}
            }

            if (empty($profile['name_prefix']) && empty($profile['name_suffix'])) {
                $sn = $profile['store_name'] ?? 'EduMarket';
                $parts = preg_split('/\s+/', trim($sn));
                if (count($parts) > 1) {
                    $profile['name_suffix'] = array_pop($parts);
                    $profile['name_prefix'] = implode(' ', $parts);
                } elseif (strlen($sn) >= 6) {
                    $mid = (int)ceil(strlen($sn) / 2);
                    $profile['name_prefix'] = substr($sn, 0, $mid);
                    $profile['name_suffix'] = substr($sn, $mid);
                } else {
                    $profile['name_prefix'] = $sn;
                    $profile['name_suffix'] = '';
                }
            }

            echo json_encode(['success' => true, 'data' => $profile]);
            exit;
        }
    } catch (\Throwable $e) {}
}

echo json_encode(['success' => true, 'data' => $defaultProfile]);
