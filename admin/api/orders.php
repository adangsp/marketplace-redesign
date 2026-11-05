<?php
/**
 * =====================================================
 * Admin Orders Management API
 * =====================================================
 */

require_once __DIR__ . '/../auth.php';
requireAdminAuth(true);

header('Content-Type: application/json; charset=utf-8');

$pdo = getSafeConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $status = $_GET['status'] ?? 'all';
    $search = trim($_GET['search'] ?? '');

    if ($pdo) {
        try {
            $sql = "
                SELECT o.*, p.name AS product_name, p.image AS product_image, p.category AS product_category
                FROM orders o
                LEFT JOIN products p ON o.product_id = p.id
                WHERE 1=1
            ";
            $params = [];

            if ($status !== 'all' && !empty($status)) {
                $sql .= " AND o.payment_status = :st";
                $params[':st'] = $status;
            }

            if (!empty($search)) {
                $sql .= " AND (o.order_number LIKE :q OR o.customer_name LIKE :q OR o.customer_email LIKE :q OR o.customer_phone LIKE :q)";
                $params[':q'] = "%$search%";
            }

            $sql .= " ORDER BY o.id DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $orders = $stmt->fetchAll();

            echo json_encode(['success' => true, 'data' => $orders]);
            exit;
        } catch (\Throwable $e) {
            error_log('Orders fetch error: ' . $e->getMessage());
        }
    }

    echo json_encode(['success' => true, 'data' => []]);
    exit;
}

if ($method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);
    if (!$data) $data = $_POST;

    $action = $data['action'] ?? 'update_status';
    $orderId = (int)($data['id'] ?? 0);

    if (!$pdo || $orderId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Pesanan atau koneksi database tidak valid.']);
        exit;
    }

    if ($action === 'update_status') {
        $newStatus = $data['payment_status'] ?? 'paid';
        $allowedStatuses = ['pending', 'paid', 'expired', 'refunded'];

        if (!in_array($newStatus, $allowedStatuses)) {
            echo json_encode(['success' => false, 'message' => 'Status pembayaran tidak valid.']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE orders SET payment_status = :st WHERE id = :id");
        $stmt->execute([':st' => $newStatus, ':id' => $orderId]);

        // If paid, increment product sales count and send WhatsApp notification
        if ($newStatus === 'paid') {
            try {
                $getOrderStmt = $pdo->prepare("
                    SELECT o.*, p.name AS product_name, p.file_path, p.demo_url 
                    FROM orders o
                    LEFT JOIN products p ON o.product_id = p.id
                    WHERE o.id = :id
                ");
                $getOrderStmt->execute([':id' => $orderId]);
                $fullOrder = $getOrderStmt->fetch();

                if ($fullOrder) {
                    if (!empty($fullOrder['product_id'])) {
                        $pdo->prepare("UPDATE products SET total_sales = total_sales + 1 WHERE id = :pid")->execute([':pid' => $fullOrder['product_id']]);
                    }

                    // Send WhatsApp notification
                    try {
                        require_once __DIR__ . '/../../services/WhatsAppService.php';
                        WhatsAppService::sendPaidNotification($fullOrder);
                    } catch (\Throwable $waErr) {
                        error_log('WA Paid notification error: ' . $waErr->getMessage());
                    }
                }
            } catch (\Throwable $t) {
                error_log('Error processing paid status: ' . $t->getMessage());
            }
        }

        echo json_encode(['success' => true, 'message' => "Status pesanan berhasil diubah menjadi: {$newStatus}."]);
        exit;
    }

    if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM orders WHERE id = :id");
        $stmt->execute([':id' => $orderId]);
        echo json_encode(['success' => true, 'message' => 'Pesanan berhasil dihapus.']);
        exit;
    }
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
