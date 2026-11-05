<?php
/**
 * =====================================================
 * Orders API Endpoint
 * =====================================================
 * Handles order creation for product purchases.
 *
 * POST /api/orders.php - Create a new order
 *
 * Required POST body (JSON):
 *   - product_id      (int)    : ID of the product
 *   - customer_name   (string) : Customer's full name
 *   - customer_email  (string) : Customer's email
 *   - customer_phone  (string) : Customer's phone number
 *   - payment_method  (string) : 'bank_transfer', 'ewallet', or 'qris'
 *   - notes           (string) : Optional order notes
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
    $productId     = isset($input['product_id']) ? (int) $input['product_id'] : 0;
    $customerName  = sanitizeInput($input['customer_name'] ?? '');
    $customerEmail = sanitizeInput($input['customer_email'] ?? '');
    $customerPhone = sanitizeInput($input['customer_phone'] ?? '');
    $paymentMethod = sanitizeInput($input['payment_method'] ?? '');
    $notes         = sanitizeInput($input['notes'] ?? '');

    // ── Validate Required Fields ──────────────────────
    $errors = [];

    if ($productId <= 0) {
        $errors[] = 'Product ID tidak valid.';
    }

    if (empty($customerName) || strlen($customerName) < 3) {
        $errors[] = 'Nama lengkap harus diisi (minimal 3 karakter).';
    }

    if (empty($customerEmail) || !isValidEmail($customerEmail)) {
        $errors[] = 'Alamat email tidak valid.';
    }

    if (empty($customerPhone) || strlen($customerPhone) < 10 || strlen($customerPhone) > 20) {
        $errors[] = 'Nomor telepon harus diisi (10-20 digit).';
    }

    // Validate phone contains only digits, +, -, spaces
    if (!empty($customerPhone) && !preg_match('/^[\d\s\+\-()]+$/', $customerPhone)) {
        $errors[] = 'Format nomor telepon tidak valid.';
    }

    $allowedPaymentMethods = ['bank_transfer', 'ewallet', 'qris'];
    if (!in_array($paymentMethod, $allowedPaymentMethods, true)) {
        $errors[] = 'Metode pembayaran tidak valid. Pilih: bank_transfer, ewallet, atau qris.';
    }

    if (!empty($errors)) {
        jsonResponse(false, 'Validasi gagal.', ['errors' => $errors], 422);
    }

    // ── Verify Product Exists & Is Active ─────────────
    $productStmt = $pdo->prepare("
        SELECT id, name, price, discount_price 
        FROM products 
        WHERE id = :id AND status = 'active'
    ");
    $productStmt->execute([':id' => $productId]);
    $product = $productStmt->fetch();

    if (!$product) {
        jsonResponse(false, 'Produk tidak ditemukan atau sudah tidak tersedia.', null, 404);
    }

    // Use discount price if available, otherwise regular price
    $amount = $product['discount_price'] ?? $product['price'];
    $amount = (float) $amount;

    // ── Generate Unique Order Number ──────────────────
    // Format: ORD-YYYYMMDD-XXXXX (e.g., ORD-20261103-A3F8K)
    $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

    // ── Insert Order into Database ────────────────────
    $insertStmt = $pdo->prepare("
        INSERT INTO orders 
            (order_number, product_id, customer_name, customer_email, 
             customer_phone, amount, payment_method, notes)
        VALUES 
            (:order_number, :product_id, :customer_name, :customer_email,
             :customer_phone, :amount, :payment_method, :notes)
    ");

    $insertStmt->execute([
        ':order_number'   => $orderNumber,
        ':product_id'     => $productId,
        ':customer_name'  => $customerName,
        ':customer_email' => $customerEmail,
        ':customer_phone' => $customerPhone,
        ':amount'         => $amount,
        ':payment_method' => $paymentMethod,
        ':notes'          => $notes ?: null,
    ]);

    $orderId = (int) $pdo->lastInsertId();

    // ── Update Product Sales Count ────────────────────
    $updateSalesStmt = $pdo->prepare("
        UPDATE products 
        SET total_sales = total_sales + 1 
        WHERE id = :id
    ");
    $updateSalesStmt->execute([':id' => $productId]);

    // ── Return Success Response ───────────────────────
    $orderData = [
        'order_id'       => $orderId,
        'order_number'   => $orderNumber,
        'product_name'   => $product['name'],
        'customer_name'  => $customerName,
        'customer_email' => $customerEmail,
        'customer_phone' => $customerPhone,
        'amount'         => $amount,
        'formatted_amount' => 'Rp ' . number_format($amount, 0, ',', '.'),
        'payment_method' => $paymentMethod,
        'payment_status' => 'pending',
        'created_at'     => date('Y-m-d H:i:s'),
    ];

    // ── Send WhatsApp Notification via WAHA ─────────
    try {
        require_once __DIR__ . '/../services/WhatsAppService.php';
        WhatsAppService::sendOrderNotification($orderData);
    } catch (\Throwable $waErr) {
        error_log('WA Order notification error: ' . $waErr->getMessage());
    }

    jsonResponse(true, 'Order berhasil dibuat! Silakan lakukan pembayaran.', $orderData, 201);

} catch (PDOException $e) {
    error_log('Orders API Error: ' . $e->getMessage());
    jsonResponse(false, 'Terjadi kesalahan server. Silakan coba lagi nanti.', null, 500);
}
