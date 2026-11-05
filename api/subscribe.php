<?php
/**
 * =====================================================
 * Subscribe API Endpoint
 * =====================================================
 * Handles newsletter email subscriptions.
 *
 * POST /api/subscribe.php - Subscribe to newsletter
 *
 * Required POST body (JSON):
 *   - email (string) : Subscriber's email address
 * =====================================================
 */

require_once __DIR__ . '/../config/database.php';

// Set CORS headers
setCorsHeaders();

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method not allowed. Use POST.', null, 405);
}

try {
    $pdo = getConnection();

    // ── Parse JSON Input ──────────────────────────────
    $rawInput = file_get_contents('php://input');
    $input    = json_decode($rawInput, true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($input)) {
        jsonResponse(false, 'Format data tidak valid. Kirim data dalam format JSON.', null, 400);
    }

    // ── Extract & Sanitize ────────────────────────────
    $email = sanitizeInput($input['email'] ?? '');

    // ── Validate Email ────────────────────────────────
    if (empty($email)) {
        jsonResponse(false, 'Alamat email harus diisi.', null, 422);
    }

    if (!isValidEmail($email)) {
        jsonResponse(false, 'Format alamat email tidak valid.', null, 422);
    }

    if (strlen($email) > 255) {
        jsonResponse(false, 'Alamat email terlalu panjang.', null, 422);
    }

    // ── Check for Duplicate Subscription ──────────────
    $checkStmt = $pdo->prepare("
        SELECT id, is_active FROM subscribers WHERE email = :email
    ");
    $checkStmt->execute([':email' => $email]);
    $existing = $checkStmt->fetch();

    if ($existing) {
        // If subscriber exists but is inactive, reactivate
        if (!$existing['is_active']) {
            $updateStmt = $pdo->prepare("
                UPDATE subscribers 
                SET is_active = TRUE 
                WHERE id = :id
            ");
            $updateStmt->execute([':id' => $existing['id']]);

            jsonResponse(true, 'Selamat datang kembali! Langganan newsletter Anda telah diaktifkan ulang.', [
                'email'       => $email,
                'reactivated' => true,
            ]);
        }

        // Already subscribed and active
        jsonResponse(false, 'Email ini sudah terdaftar sebagai subscriber.', null, 409);
    }

    // ── Insert New Subscriber ─────────────────────────
    $stmt = $pdo->prepare("
        INSERT INTO subscribers (email) VALUES (:email)
    ");
    $stmt->execute([':email' => $email]);

    $subscriberId = (int) $pdo->lastInsertId();

    // ── Return Success Response ───────────────────────
    jsonResponse(true, 'Terima kasih telah berlangganan newsletter kami! 🎉', [
        'subscriber_id' => $subscriberId,
        'email'         => $email,
        'created_at'    => date('Y-m-d H:i:s'),
    ], 201);

} catch (PDOException $e) {
    error_log('Subscribe API Error: ' . $e->getMessage());
    jsonResponse(false, 'Terjadi kesalahan server. Silakan coba lagi nanti.', null, 500);
}
