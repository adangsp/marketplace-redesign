<?php
/**
 * =====================================================
 * Contact API Endpoint
 * =====================================================
 * Handles contact form message submissions.
 *
 * POST /api/contact.php - Submit a contact message
 *
 * Required POST body (JSON):
 *   - name    (string) : Sender's full name
 *   - email   (string) : Sender's email address
 *   - subject (string) : Message subject
 *   - message (string) : Message body
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

    // ── Extract & Sanitize Fields ─────────────────────
    $name    = sanitizeInput($input['name'] ?? '');
    $email   = sanitizeInput($input['email'] ?? '');
    $phone   = sanitizeInput($input['phone'] ?? '');
    $subject = sanitizeInput($input['subject'] ?? '');
    $message = sanitizeInput($input['message'] ?? '');

    // ── Validate Required Fields ──────────────────────
    $errors = [];

    if (empty($name) || strlen($name) < 2) {
        $errors[] = 'Nama harus diisi (minimal 2 karakter).';
    }

    if (strlen($name) > 255) {
        $errors[] = 'Nama terlalu panjang (maksimal 255 karakter).';
    }

    if (empty($email) || !isValidEmail($email)) {
        $errors[] = 'Alamat email tidak valid.';
    }

    if (empty($subject) || strlen($subject) < 5) {
        $errors[] = 'Subjek harus diisi (minimal 5 karakter).';
    }

    if (strlen($subject) > 255) {
        $errors[] = 'Subjek terlalu panjang (maksimal 255 karakter).';
    }

    if (empty($message) || strlen($message) < 10) {
        $errors[] = 'Pesan harus diisi (minimal 10 karakter).';
    }

    if (strlen($message) > 5000) {
        $errors[] = 'Pesan terlalu panjang (maksimal 5000 karakter).';
    }

    if (!empty($errors)) {
        jsonResponse(false, 'Validasi gagal.', ['errors' => $errors], 422);
    }

    // ── Rate Limiting (simple: max 5 messages per email per hour) ──
    $rateLimitStmt = $pdo->prepare("
        SELECT COUNT(*) FROM contacts 
        WHERE email = :email 
        AND created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
    ");
    $rateLimitStmt->execute([':email' => $email]);
    $recentMessages = (int) $rateLimitStmt->fetchColumn();

    if ($recentMessages >= 5) {
        jsonResponse(false, 'Terlalu banyak pesan. Silakan coba lagi nanti.', null, 429);
    }

    // ── Insert Contact Message ────────────────────────
    $stmt = $pdo->prepare("
        INSERT INTO contacts (name, email, subject, message)
        VALUES (:name, :email, :subject, :message)
    ");

    $stmt->execute([
        ':name'    => $name,
        ':email'   => $email,
        ':subject' => $subject,
        ':message' => $message,
    ]);

    $contactId = (int) $pdo->lastInsertId();

    // ── Send WhatsApp Notification to Admin via WAHA ──
    try {
        require_once __DIR__ . '/../services/WhatsAppService.php';
        WhatsAppService::sendContactNotification([
            'id' => $contactId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message
        ]);
    } catch (\Throwable $waErr) {
        error_log('WA Contact notification error: ' . $waErr->getMessage());
    }

    // ── Return Success Response ───────────────────────
    jsonResponse(true, 'Pesan Anda berhasil dikirim! Kami akan merespons dalam 1x24 jam.', [
        'contact_id' => $contactId,
        'name'       => $name,
        'email'      => $email,
        'subject'    => $subject,
        'created_at' => date('Y-m-d H:i:s'),
    ], 201);

} catch (PDOException $e) {
    error_log('Contact API Error: ' . $e->getMessage());
    jsonResponse(false, 'Terjadi kesalahan server. Silakan coba lagi nanti.', null, 500);
}
