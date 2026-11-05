<?php
/**
 * =====================================================
 * Admin Image Upload Handler
 * =====================================================
 * Handles secure image uploads for products and payments
 * =====================================================
 */

require_once __DIR__ . '/../auth.php';
requireAdminAuth(true);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tidak ada file yang diunggah atau terjadi kesalahan upload.']);
    exit;
}

$file = $_FILES['file'];
$targetType = $_POST['type'] ?? 'products'; // 'products', 'payments', 'avatars', or 'logos'

if (!in_array($targetType, ['products', 'payments', 'avatars', 'logos'])) {
    $targetType = 'products';
}

$uploadBaseDir = __DIR__ . '/../../uploads/' . $targetType . '/';
if (!is_dir($uploadBaseDir)) {
    mkdir($uploadBaseDir, 0755, true);
}

// Validate file size (max 5MB)
$maxSize = 5 * 1024 * 1024;
if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ukuran file melebihi batas maksimal 5MB.']);
    exit;
}

// Validate mime type & extension
$allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

if (!in_array($mimeType, $allowedMimes) && !in_array($ext, $allowedExts)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Format file tidak didukung. Harap upload format JPG, PNG, WEBP, atau SVG.']);
    exit;
}

// Generate unique filename
$uniqueName = $targetType . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . ($ext ?: 'jpg');
$targetPath = $uploadBaseDir . $uniqueName;

if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    $publicUrl = 'uploads/' . $targetType . '/' . $uniqueName;
    echo json_encode([
        'success' => true,
        'message' => 'Foto berhasil diunggah!',
        'url' => $publicUrl,
        'filename' => $uniqueName
    ]);
    exit;
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal memindahkan file yang diunggah ke direktori server.']);
    exit;
}
