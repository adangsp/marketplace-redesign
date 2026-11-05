<?php
/**
 * Reviews API Endpoint
 * GET: Retrieve reviews for a specific product
 * POST: Submit a new review for a product
 */

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getReviews();
        break;
    case 'POST':
        createReview();
        break;
    default:
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
        break;
}

/**
 * Get reviews for a specific product
 */
function getReviews() {
    try {
        $conn = getConnection();
        
        $productId = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
        
        if ($productId <= 0) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Parameter product_id diperlukan'
            ]);
            return;
        }

        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = isset($_GET['limit']) ? min(50, max(1, intval($_GET['limit']))) : 10;
        $offset = ($page - 1) * $limit;

        // Get total count
        $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM reviews WHERE product_id = ?");
        $countStmt->execute([$productId]);
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Get reviews
        $stmt = $conn->prepare("
            SELECT id, product_id, reviewer_name, reviewer_avatar, rating, comment, is_verified, created_at
            FROM reviews 
            WHERE product_id = ? 
            ORDER BY created_at DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$productId, $limit, $offset]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get rating distribution
        $distStmt = $conn->prepare("
            SELECT rating, COUNT(*) as count 
            FROM reviews 
            WHERE product_id = ? 
            GROUP BY rating 
            ORDER BY rating DESC
        ");
        $distStmt->execute([$productId]);
        $distribution = $distStmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate average rating
        $avgStmt = $conn->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE product_id = ?");
        $avgStmt->execute([$productId]);
        $avgRating = round($avgStmt->fetch(PDO::FETCH_ASSOC)['avg_rating'], 1);

        echo json_encode([
            'success' => true,
            'data' => [
                'reviews' => $reviews,
                'total' => intval($total),
                'avg_rating' => $avgRating,
                'distribution' => $distribution,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($total / $limit)
            ]
        ]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan server'
        ]);
    }
}

/**
 * Create a new review
 */
function createReview() {
    try {
        $conn = getConnection();
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            $data = $_POST;
        }

        // Validate required fields
        $required = ['product_id', 'reviewer_name', 'reviewer_email', 'rating', 'comment'];
        $errors = [];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[] = "Field '{$field}' wajib diisi";
            }
        }

        // Validate rating
        if (isset($data['rating'])) {
            $rating = intval($data['rating']);
            if ($rating < 1 || $rating > 5) {
                $errors[] = 'Rating harus antara 1 dan 5';
            }
        }

        // Validate email
        if (!empty($data['reviewer_email']) && !filter_var($data['reviewer_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid';
        }

        // Validate comment length
        if (!empty($data['comment']) && strlen($data['comment']) < 10) {
            $errors[] = 'Komentar minimal 10 karakter';
        }

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $errors
            ]);
            return;
        }

        // Check if product exists
        $checkStmt = $conn->prepare("SELECT id FROM products WHERE id = ? AND status = 'active'");
        $checkStmt->execute([intval($data['product_id'])]);
        if (!$checkStmt->fetch()) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ]);
            return;
        }

        // Insert review
        $stmt = $conn->prepare("
            INSERT INTO reviews (product_id, reviewer_name, reviewer_email, rating, comment, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            intval($data['product_id']),
            htmlspecialchars(strip_tags($data['reviewer_name'])),
            filter_var($data['reviewer_email'], FILTER_SANITIZE_EMAIL),
            intval($data['rating']),
            htmlspecialchars(strip_tags($data['comment']))
        ]);

        $reviewId = $conn->lastInsertId();

        // Update product rating
        $updateStmt = $conn->prepare("
            UPDATE products 
            SET rating = (SELECT AVG(rating) FROM reviews WHERE product_id = ?),
                total_reviews = (SELECT COUNT(*) FROM reviews WHERE product_id = ?)
            WHERE id = ?
        ");
        $updateStmt->execute([
            intval($data['product_id']),
            intval($data['product_id']),
            intval($data['product_id'])
        ]);

        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Review berhasil ditambahkan! Terima kasih atas ulasan Anda.',
            'data' => [
                'id' => intval($reviewId)
            ]
        ]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan server'
        ]);
    }
}
