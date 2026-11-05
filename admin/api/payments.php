<?php
/**
 * =====================================================
 * Admin Payment Settings API
 * =====================================================
 */

require_once __DIR__ . '/../auth.php';
requireAdminAuth(true);

header('Content-Type: application/json; charset=utf-8');

$pdo = getSafeConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if ($pdo) {
        try {
            $stmt = $pdo->query("SELECT * FROM payment_settings ORDER BY sort_order ASC, id ASC");
            $payments = $stmt->fetchAll();
            echo json_encode(['success' => true, 'data' => $payments]);
            exit;
        } catch (\Throwable $e) {
            error_log('Payment settings fetch error: ' . $e->getMessage());
        }
    }

    // Default fallback payment methods
    $fallbackPayments = [
        ['id' => 1, 'method_key' => 'qris', 'method_type' => 'qris', 'method_title' => 'QRIS All Payment', 'account_number' => '0001-QRIS-EDUMKT', 'account_holder' => 'EduMarket Digital Indonesia', 'qr_image' => 'assets/images/qris-sample.svg', 'instructions' => 'Scan QRIS untuk bayar instan', 'is_active' => 1],
        ['id' => 2, 'method_key' => 'bank_bca', 'method_type' => 'bank', 'method_title' => 'Bank Central Asia (BCA)', 'account_number' => '8270-1928-3341', 'account_holder' => 'PT EduMarket Digital Indonesia', 'qr_image' => null, 'instructions' => 'Transfer ATM/m-BCA', 'is_active' => 1],
        ['id' => 3, 'method_key' => 'bank_mandiri', 'method_type' => 'bank', 'method_title' => 'Bank Mandiri', 'account_number' => '140-00-9876543-2', 'account_holder' => 'PT EduMarket Digital Indonesia', 'qr_image' => null, 'instructions' => 'Transfer Livin/ATM Mandiri', 'is_active' => 1],
        ['id' => 4, 'method_key' => 'bank_bri', 'method_type' => 'bank', 'method_title' => 'Bank Rakyat Indonesia (BRI)', 'account_number' => '0341-01-002891-30-5', 'account_holder' => 'PT EduMarket Digital Indonesia', 'qr_image' => null, 'instructions' => 'Transfer BRImo/ATM BRI', 'is_active' => 1],
        ['id' => 5, 'method_key' => 'ewallet_dana', 'method_type' => 'ewallet', 'method_title' => 'DANA ID', 'account_number' => '0812-3456-7890', 'account_holder' => 'EduMarket Official', 'qr_image' => null, 'instructions' => 'Kirim saldo DANA', 'is_active' => 1],
        ['id' => 6, 'method_key' => 'ewallet_ovo', 'method_type' => 'ewallet', 'method_title' => 'OVO Cash', 'account_number' => '0812-3456-7890', 'account_holder' => 'EduMarket Official', 'qr_image' => null, 'instructions' => 'Transfer via OVO', 'is_active' => 1],
        ['id' => 7, 'method_key' => 'ewallet_gopay', 'method_type' => 'ewallet', 'method_title' => 'GoPay ID', 'account_number' => '0812-3456-7890', 'account_holder' => 'EduMarket Official', 'qr_image' => null, 'instructions' => 'Transfer saldo GoPay', 'is_active' => 1]
    ];
    echo json_encode(['success' => true, 'data' => $fallbackPayments]);
    exit;
}

if ($method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);
    if (!$data) $data = $_POST;

    if (!$pdo) {
        echo json_encode(['success' => false, 'message' => 'Database belum aktif.']);
        exit;
    }

    $action = $data['action'] ?? 'update';

    if ($action === 'toggle') {
        $id = (int)($data['id'] ?? 0);
        $val = !empty($data['is_active']) ? 1 : 0;
        $stmt = $pdo->prepare("UPDATE payment_settings SET is_active = :val WHERE id = :id");
        $stmt->execute([':val' => $val, ':id' => $id]);
        echo json_encode(['success' => true, 'message' => 'Status metode pembayaran berhasil diperbarui.']);
        exit;
    }

    if ($action === 'update') {
        $id = (int)($data['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID metode pembayaran tidak valid.']);
            exit;
        }

        $methodTitle = trim($data['method_title'] ?? '');
        $accountNumber = trim($data['account_number'] ?? '');
        $accountHolder = trim($data['account_holder'] ?? '');
        $instructions = trim($data['instructions'] ?? '');
        $qrImage = trim($data['qr_image'] ?? '');
        $isActive = isset($data['is_active']) ? (int)$data['is_active'] : 1;

        $stmt = $pdo->prepare("
            UPDATE payment_settings SET
                method_title = :mt,
                account_number = :an,
                account_holder = :ah,
                instructions = :ins,
                qr_image = :qr,
                is_active = :ia
            WHERE id = :id
        ");
        $stmt->execute([
            ':mt' => $methodTitle,
            ':an' => $accountNumber,
            ':ah' => $accountHolder,
            ':ins' => $instructions,
            ':qr' => $qrImage ?: null,
            ':ia' => $isActive,
            ':id' => $id
        ]);

        echo json_encode(['success' => true, 'message' => 'Pengaturan metode pembayaran berhasil disimpan!']);
        exit;
    }

    if ($action === 'create') {
        $methodKey = trim($data['method_key'] ?? ('method_' . time()));
        $methodType = trim($data['method_type'] ?? 'bank');
        $methodTitle = trim($data['method_title'] ?? '');
        $accountNumber = trim($data['account_number'] ?? '');
        $accountHolder = trim($data['account_holder'] ?? '');
        $instructions = trim($data['instructions'] ?? '');
        $qrImage = trim($data['qr_image'] ?? '');

        $stmt = $pdo->prepare("
            INSERT INTO payment_settings
            (method_key, method_type, method_title, account_number, account_holder, qr_image, instructions, is_active)
            VALUES (:mk, :mt, :title, :an, :ah, :qr, :ins, 1)
        ");
        $stmt->execute([
            ':mk' => $methodKey,
            ':mt' => $methodType,
            ':title' => $methodTitle,
            ':an' => $accountNumber,
            ':ah' => $accountHolder,
            ':qr' => $qrImage ?: null,
            ':ins' => $instructions
        ]);

        echo json_encode(['success' => true, 'message' => 'Metode pembayaran baru berhasil ditambahkan!']);
        exit;
    }
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
