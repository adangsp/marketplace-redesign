<?php
/**
 * =====================================================
 * Admin Statistics API
 * =====================================================
 */

require_once __DIR__ . '/../auth.php';
requireAdminAuth(true);

header('Content-Type: application/json; charset=utf-8');

$pdo = getSafeConnection();

$stats = [
    'total_products' => 8,
    'active_products' => 8,
    'total_orders' => 0,
    'pending_orders' => 0,
    'paid_orders' => 0,
    'total_revenue' => 0,
    'total_reviews' => 250,
    'total_contacts' => 0,
    'recent_orders' => []
];

if ($pdo) {
    try {
        // Products stats
        $stats['total_products'] = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
        $stats['active_products'] = (int)$pdo->query("SELECT COUNT(*) FROM products WHERE status = 'active'")->fetchColumn();

        // Orders stats
        $stats['total_orders'] = (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        $stats['pending_orders'] = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE payment_status = 'pending'")->fetchColumn();
        $stats['paid_orders'] = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE payment_status = 'paid'")->fetchColumn();

        // Revenue (paid orders + total_sales estimate)
        $revenueFromOrders = (float)$pdo->query("SELECT COALESCE(SUM(amount), 0) FROM orders WHERE payment_status = 'paid'")->fetchColumn();
        
        // Sum from existing products sales history
        $revenueFromProducts = (float)$pdo->query("
            SELECT COALESCE(SUM(total_sales * COALESCE(discount_price, price)), 0) 
            FROM products
        ")->fetchColumn();

        $stats['total_revenue'] = $revenueFromOrders > 0 ? $revenueFromOrders : $revenueFromProducts;

        // Reviews & contacts
        $stats['total_reviews'] = (int)$pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
        $stats['total_contacts'] = (int)$pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();

        // Recent 5 orders
        $stmt = $pdo->query("
            SELECT o.*, p.name AS product_name 
            FROM orders o
            LEFT JOIN products p ON o.product_id = p.id
            ORDER BY o.id DESC 
            LIMIT 5
        ");
        $stats['recent_orders'] = $stmt->fetchAll();
    } catch (\Throwable $e) {
        error_log('Stats error: ' . $e->getMessage());
    }
}

echo json_encode(['success' => true, 'data' => $stats]);
