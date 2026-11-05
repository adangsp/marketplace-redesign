<?php
/**
 * =====================================================
 * Admin WhatsApp Gateway (WAHA) API Endpoint
 * =====================================================
 */

require_once __DIR__ . '/../auth.php';
requireAdminAuth(true);

require_once __DIR__ . '/../../services/WhatsAppService.php';

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $action = $_GET['action'] ?? 'all';

    if ($action === 'qr') {
        $qrResult = WhatsAppService::getQrCode();
        echo json_encode($qrResult, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    if ($action === 'status') {
        $status = WhatsAppService::checkStatus();
        echo json_encode([
            'success' => true,
            'data' => $status
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    // Default GET: return settings and current status
    $settings = WhatsAppService::getSettings();
    $status = WhatsAppService::checkStatus();

    echo json_encode([
        'success' => true,
        'settings' => $settings,
        'status' => $status
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);
    if (!is_array($data)) {
        $data = $_POST;
    }

    $action = $data['action'] ?? 'save_settings';

    if ($action === 'start_session') {
        $res = WhatsAppService::startSession();
        echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    if ($action === 'stop_session') {
        $res = WhatsAppService::stopSession();
        echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    if ($action === 'get_qr') {
        $res = WhatsAppService::getQrCode();
        echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    if ($action === 'test_message') {
        $phone = trim($data['phone'] ?? '');
        $message = trim($data['message'] ?? '');

        if (empty($phone)) {
            echo json_encode([
                'success' => false,
                'message' => 'Nomor WhatsApp tujuan wajib diisi.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (empty($message)) {
            $message = "Halo! Ini adalah pesan tes pengiriman WhatsApp dari EduAdminSP WhatsApp Gateway (WAHA). Integrasi berhasil! ✅";
        }

        $res = WhatsAppService::sendText($phone, $message);
        if ($res['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Pesan tes berhasil dikirim ke ' . $phone . '!'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . ($res['error'] ?? 'Terjadi kesalahan pada WAHA.')
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }

    if ($action === 'save_settings') {
        $saved = WhatsAppService::saveSettings($data);
        if ($saved) {
            echo json_encode([
                'success' => true,
                'message' => 'Pengaturan WhatsApp Gateway berhasil disimpan!'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menyimpan pengaturan WhatsApp.'
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }

    echo json_encode([
        'success' => false,
        'message' => 'Action tidak dikenali.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
