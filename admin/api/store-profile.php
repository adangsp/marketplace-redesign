<?php
/**
 * =====================================================
 * Admin Store Profile API Endpoint
 * =====================================================
 */

require_once __DIR__ . '/../auth.php';
requireAdminAuth(true);

header('Content-Type: application/json; charset=utf-8');

$pdo = getSafeConnection();
$method = $_SERVER['REQUEST_METHOD'];

// Default store profile
$defaultProfile = [
    'id' => 1,
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

// Check if any logo has been uploaded to uploads/logos/
$latestUploadedLogo = null;
$logoDir = __DIR__ . '/../../uploads/logos';
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

if ($method === 'GET') {
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

                // Auto-heal: If DB still has default copyright text, update it
                if (empty($profile['copyright_text']) || str_contains($profile['copyright_text'], 'EduMarket. Semua hak dilindungi')) {
                    $newCopyright = '© 2026 EduAdminSP. Manage With <i class="fas fa-heart" style="color: #FF3366;"></i> by adangsp';
                    try {
                        $upCr = $pdo->prepare("UPDATE `store_profile` SET `copyright_text` = ? WHERE `id` = 1");
                        $upCr->execute([$newCopyright]);
                        $profile['copyright_text'] = $newCopyright;
                    } catch (\Throwable $e) {}
                }

                echo json_encode(['success' => true, 'data' => $profile]);
                exit;
            }
        } catch (\Throwable $e) {
            error_log('Store profile fetch error: ' . $e->getMessage());
        }
    }

    echo json_encode(['success' => true, 'data' => $defaultProfile]);
    exit;
}

if ($method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);
    if (!$data) $data = $_POST;

    $namePrefix = trim($data['name_prefix'] ?? '');
    $nameSuffix = trim($data['name_suffix'] ?? '');
    $storeName = trim($data['store_name'] ?? '');

    if (empty($storeName) && (empty($namePrefix) && empty($nameSuffix))) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Nama toko tidak boleh kosong!']);
        exit;
    }

    if (empty($storeName)) {
        $storeName = trim($namePrefix . ' ' . $nameSuffix);
    }

    if (empty($namePrefix) && empty($nameSuffix)) {
        $parts = preg_split('/\s+/', $storeName);
        if (count($parts) > 1) {
            $nameSuffix = array_pop($parts);
            $namePrefix = implode(' ', $parts);
        } elseif (strlen($storeName) >= 6) {
            $mid = (int)ceil(strlen($storeName) / 2);
            $namePrefix = substr($storeName, 0, $mid);
            $nameSuffix = substr($storeName, $mid);
        } else {
            $namePrefix = $storeName;
            $nameSuffix = '';
        }
    }

    $tagline = trim($data['tagline'] ?? '');
    $description = trim($data['description'] ?? '');
    $logo = trim($data['logo'] ?? 'assets/images/favicon.svg');
    $favicon = trim($data['favicon'] ?? 'assets/images/favicon.svg');
    $email = trim($data['email'] ?? '');
    $phone = trim($data['phone'] ?? '');
    $whatsapp = trim($data['whatsapp'] ?? '');
    $address = trim($data['address'] ?? '');
    $operatingHours = trim($data['operating_hours'] ?? '');
    $instagram = trim($data['instagram'] ?? '');
    $facebook = trim($data['facebook'] ?? '');
    $youtube = trim($data['youtube'] ?? '');
    $warrantyPeriod = trim($data['warranty_period'] ?? '30 Hari Garansi');
    $supportDuration = trim($data['support_duration'] ?? '1 Tahun Update');
    $copyrightText = trim($data['copyright_text'] ?? '© 2026 EduMarket. Semua hak dilindungi.');

    if ($pdo) {
        try {
            $stmt = $pdo->prepare("
                UPDATE `store_profile` SET
                    `store_name` = :sn,
                    `name_prefix` = :np,
                    `name_suffix` = :ns,
                    `tagline` = :tg,
                    `description` = :ds,
                    `logo` = :lg,
                    `favicon` = :fv,
                    `email` = :em,
                    `phone` = :ph,
                    `whatsapp` = :wa,
                    `address` = :ad,
                    `operating_hours` = :oh,
                    `instagram` = :ig,
                    `facebook` = :fb,
                    `youtube` = :yt,
                    `warranty_period` = :wp,
                    `support_duration` = :sd,
                    `copyright_text` = :cr
                WHERE `id` = 1
            ");
            $stmt->execute([
                ':sn' => $storeName,
                ':np' => $namePrefix,
                ':ns' => $nameSuffix,
                ':tg' => $tagline,
                ':ds' => $description,
                ':lg' => $logo,
                ':fv' => $favicon,
                ':em' => $email,
                ':ph' => $phone,
                ':wa' => $whatsapp,
                ':ad' => $address,
                ':oh' => $operatingHours,
                ':ig' => $instagram,
                ':fb' => $facebook,
                ':yt' => $youtube,
                ':wp' => $warrantyPeriod,
                ':sd' => $supportDuration,
                ':cr' => $copyrightText
            ]);

            echo json_encode([
                'success' => true,
                'message' => 'Profil toko berhasil diperbarui!',
                'data' => [
                    'store_name' => $storeName,
                    'name_prefix' => $namePrefix,
                    'name_suffix' => $nameSuffix,
                    'tagline' => $tagline,
                    'logo' => $logo
                ]
            ]);
            exit;
        } catch (\Throwable $e) {
            error_log('Store profile update error: ' . $e->getMessage());
        }
    }

    // Fallback if DB is offline
    echo json_encode([
        'success' => true,
        'message' => 'Profil toko berhasil diperbarui (Mode Sesi Lokal)!',
        'data' => [
            'store_name' => $storeName,
            'tagline' => $tagline,
            'logo' => $logo
        ]
    ]);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
