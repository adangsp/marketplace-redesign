<?php
/**
 * =====================================================
 * Dynamic Marketplace Landing Page
 * =====================================================
 * Server-side entry point for instant, zero-delay rendering 
 * of custom store profile, logo, and brand typography.
 * =====================================================
 */

require_once __DIR__ . '/config/database.php';

$defaultProfile = [
    'store_name' => 'EduMarket',
    'name_prefix' => 'Edu',
    'name_suffix' => 'Market',
    'tagline' => 'Marketplace Aplikasi Pendidikan & Sekolah Digital',
    'logo' => '',
    'favicon' => 'assets/images/favicon.svg'
];

$pdo = getSafeConnection();
$profile = $defaultProfile;

// Detect latest logo in uploads/logos/ if any
$latestLogo = null;
$logoDir = __DIR__ . '/uploads/logos';
if (is_dir($logoDir)) {
    $files = glob($logoDir . '/*.*');
    if (!empty($files)) {
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        $latestLogo = 'uploads/logos/' . basename($files[0]);
    }
}
if ($latestLogo) {
    $profile['logo'] = $latestLogo;
}

if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM `store_profile` WHERE `id` = 1 LIMIT 1");
        $row = $stmt->fetch();
        if ($row) {
            $profile = array_merge($profile, $row);
            if ($latestLogo && (empty($profile['logo']) || $profile['logo'] === 'assets/images/favicon.svg')) {
                $profile['logo'] = $latestLogo;
            }
        }
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

// Load index.html content
$html = file_get_contents(__DIR__ . '/index.html');

$prefix = htmlspecialchars($profile['name_prefix']);
$suffix = htmlspecialchars($profile['name_suffix']);
$brandHtml = $prefix . ($suffix ? '<span class="text-gradient">' . $suffix . '</span>' : '');
$storeName = htmlspecialchars($profile['store_name']);
$logo = htmlspecialchars($profile['logo']);
$hasCustomLogo = !empty($logo) && !str_ends_with($logo, 'favicon.svg');

// Replace preloader brand text directly in HTML
$html = str_replace(
    '<span class="logo-text" id="preloaderLogoText">Edu<span class="text-gradient">Market</span></span>',
    '<span class="logo-text" id="preloaderLogoText">' . $brandHtml . '</span>',
    $html
);

// Replace navbar brand text directly in HTML
$html = str_replace(
    '<span class="logo-text" id="mainNavLogoText">Edu<span class="text-gradient">Market</span></span>',
    '<span class="logo-text" id="mainNavLogoText">' . $brandHtml . '</span>',
    $html
);

// Replace footer brand text directly in HTML
$html = str_replace(
    '<span class="logo-text" id="footerLogoText">Edu<span class="text-gradient">Market</span></span>',
    '<span class="logo-text" id="footerLogoText">' . $brandHtml . '</span>',
    $html
);

// If custom logo exists, replace style on logo img & icon
if ($hasCustomLogo) {
    // Preloader logo
    $html = str_replace(
        '<img src="" alt="Logo" class="site-logo-img" id="preloaderLogoImg" style="display: none;">',
        '<img src="' . $logo . '" alt="' . $storeName . '" class="site-logo-img" id="preloaderLogoImg" style="display: inline-block;">',
        $html
    );
    $html = str_replace(
        '<span class="logo-icon" id="preloaderLogoIcon"><i class="fas fa-graduation-cap"></i></span>',
        '<span class="logo-icon" id="preloaderLogoIcon" style="display: none;"><i class="fas fa-graduation-cap"></i></span>',
        $html
    );

    // Navbar logo
    $html = str_replace(
        '<img src="" alt="Logo" class="site-logo-img" id="mainNavLogoImg" style="display: none;">',
        '<img src="' . $logo . '" alt="' . $storeName . '" class="site-logo-img" id="mainNavLogoImg" style="display: inline-block;">',
        $html
    );
    $html = str_replace(
        '<span class="logo-icon" id="mainNavLogoIcon"><i class="fas fa-graduation-cap"></i></span>',
        '<span class="logo-icon" id="mainNavLogoIcon" style="display: none;"><i class="fas fa-graduation-cap"></i></span>',
        $html
    );

    // Footer logo
    $html = str_replace(
        '<img src="" alt="Logo" class="site-logo-img" id="footerLogoImg" style="display: none;">',
        '<img src="' . $logo . '" alt="' . $storeName . '" class="site-logo-img" id="footerLogoImg" style="display: inline-block;">',
        $html
    );
    $html = str_replace(
        '<span class="logo-icon" id="footerLogoIcon"><i class="fas fa-graduation-cap"></i></span>',
        '<span class="logo-icon" id="footerLogoIcon" style="display: none;"><i class="fas fa-graduation-cap"></i></span>',
        $html
    );
}

// Update Page Title if customized
if (!empty($profile['store_name']) && $profile['store_name'] !== 'EduMarket') {
    $tagline = !empty($profile['tagline']) ? ' - ' . htmlspecialchars($profile['tagline']) : '';
    $html = preg_replace(
        '/<title>.*?<\/title>/',
        '<title>' . $storeName . $tagline . '</title>',
        $html
    );
}

// Inject JSON cache directly into HTML so JS immediately has it
$jsonScript = '<script>window.__INITIAL_STORE_PROFILE__ = ' . json_encode($profile) . '; try { localStorage.setItem("edumarket_store_profile", JSON.stringify(' . json_encode($profile) . ')); } catch(e){} </script>';
$html = str_replace('</head>', $jsonScript . "\n</head>", $html);

echo $html;
