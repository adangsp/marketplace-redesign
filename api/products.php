<?php
/**
 * =====================================================
 * Products API Endpoint
 * =====================================================
 * Handles product listing and detail retrieval.
 *
 * GET  /api/products.php              - List all products
 * GET  /api/products.php?id=1         - Get single product
 * GET  /api/products.php?category=... - Filter by category
 * GET  /api/products.php?search=...   - Search products
 * GET  /api/products.php?sort=...     - Sort results
 * GET  /api/products.php?page=1&limit=8 - Pagination
 * GET  /api/products.php?featured=1   - Featured products only
 * GET  /api/products.php?bestseller=1 - Bestseller products only
 * =====================================================
 */

require_once __DIR__ . '/../config/database.php';

// Set CORS and content-type headers
setCorsHeaders();

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    jsonResponse(false, 'Method not allowed. Use GET.', null, 405);
}

try {
    $pdo = getConnection();

    // ── Single Product Detail ─────────────────────────
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];

        if ($id <= 0) {
            jsonResponse(false, 'ID produk tidak valid.', null, 400);
        }

        // Fetch the product
        $stmt = $pdo->prepare("
            SELECT * FROM products 
            WHERE id = :id AND status = 'active'
        ");
        $stmt->execute([':id' => $id]);
        $product = $stmt->fetch();

        if (!$product) {
            jsonResponse(false, 'Produk tidak ditemukan.', null, 404);
        }

        // Decode JSON fields for the response
        $product['gallery']    = json_decode($product['gallery'], true) ?? [];
        $product['features']   = json_decode($product['features'], true) ?? [];
        $product['tech_stack'] = json_decode($product['tech_stack'], true) ?? [];

        // Cast numeric/boolean fields
        $product['price']          = (float) $product['price'];
        $product['discount_price'] = $product['discount_price'] ? (float) $product['discount_price'] : null;
        $product['rating']         = (float) $product['rating'];
        $product['total_reviews']  = (int) $product['total_reviews'];
        $product['total_sales']    = (int) $product['total_sales'];
        $product['is_featured']    = (bool) $product['is_featured'];
        $product['is_bestseller']  = (bool) $product['is_bestseller'];

        // Fetch associated reviews
        $reviewStmt = $pdo->prepare("
            SELECT id, reviewer_name, reviewer_avatar, rating, comment, 
                   is_verified, created_at
            FROM reviews 
            WHERE product_id = :product_id 
            ORDER BY created_at DESC
        ");
        $reviewStmt->execute([':product_id' => $id]);
        $reviews = $reviewStmt->fetchAll();

        // Cast review fields
        foreach ($reviews as &$review) {
            $review['rating']      = (int) $review['rating'];
            $review['is_verified'] = (bool) $review['is_verified'];
        }
        unset($review);

        $product['reviews'] = $reviews;

        jsonResponse(true, 'Detail produk berhasil dimuat.', $product);
    }

    // ── Product Listing with Filters ──────────────────

    // Build the base query
    $where      = ["p.status = 'active'"];
    $params     = [];
    $orderBy    = 'p.created_at DESC'; // Default sort

    // -- Category filter --
    if (!empty($_GET['category'])) {
        $allowedCategories = ['aplikasi-sekolah', 'aplikasi-pembelajaran', 'sistem-informasi', 'e-learning'];
        $category = sanitizeInput($_GET['category']);

        if (in_array($category, $allowedCategories, true)) {
            $where[]            = 'p.category = :category';
            $params[':category'] = $category;
        }
    }

    // -- Search filter --
    if (!empty($_GET['search'])) {
        $search = sanitizeInput($_GET['search']);
        $where[]           = '(p.name LIKE :search OR p.short_description LIKE :search2)';
        $params[':search']  = "%{$search}%";
        $params[':search2'] = "%{$search}%";
    }

    // -- Featured filter --
    if (isset($_GET['featured']) && $_GET['featured'] == '1') {
        $where[] = 'p.is_featured = 1';
    }

    // -- Bestseller filter --
    if (isset($_GET['bestseller']) && $_GET['bestseller'] == '1') {
        $where[] = 'p.is_bestseller = 1';
    }

    // -- Sorting --
    if (!empty($_GET['sort'])) {
        $sortMap = [
            'newest'       => 'p.created_at DESC',
            'oldest'       => 'p.created_at ASC',
            'price_low'    => 'COALESCE(p.discount_price, p.price) ASC',
            'price_high'   => 'COALESCE(p.discount_price, p.price) DESC',
            'popular'      => 'p.total_sales DESC',
            'rating'       => 'p.rating DESC',
            'name_asc'     => 'p.name ASC',
            'name_desc'    => 'p.name DESC',
        ];
        $sortKey = sanitizeInput($_GET['sort']);
        if (isset($sortMap[$sortKey])) {
            $orderBy = $sortMap[$sortKey];
        }
    }

    // -- Pagination --
    $page  = max(1, (int) ($_GET['page'] ?? 1));
    $limit = max(1, min(50, (int) ($_GET['limit'] ?? 12))); // Cap at 50
    $offset = ($page - 1) * $limit;

    // Build WHERE clause
    $whereClause = implode(' AND ', $where);

    // Count total matching records
    $countSql  = "SELECT COUNT(*) FROM products p WHERE {$whereClause}";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $totalItems = (int) $countStmt->fetchColumn();
    $totalPages = (int) ceil($totalItems / $limit);

    // Fetch products
    $sql = "
        SELECT p.id, p.name, p.slug, p.short_description, p.price, 
               p.discount_price, p.category, p.image, p.features, p.tech_stack,
               p.rating, p.total_reviews, p.total_sales, 
               p.is_featured, p.is_bestseller, p.demo_url, p.created_at
        FROM products p
        WHERE {$whereClause}
        ORDER BY {$orderBy}
        LIMIT {$limit} OFFSET {$offset}
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();

    // Process each product for the response
    foreach ($products as &$product) {
        $product['features']       = json_decode($product['features'], true) ?? [];
        $product['tech_stack']     = json_decode($product['tech_stack'], true) ?? [];
        $product['price']          = (float) $product['price'];
        $product['discount_price'] = $product['discount_price'] ? (float) $product['discount_price'] : null;
        $product['rating']         = (float) $product['rating'];
        $product['total_reviews']  = (int) $product['total_reviews'];
        $product['total_sales']    = (int) $product['total_sales'];
        $product['is_featured']    = (bool) $product['is_featured'];
        $product['is_bestseller']  = (bool) $product['is_bestseller'];
    }
    unset($product);

    // Build pagination metadata
    $pagination = [
        'current_page' => $page,
        'per_page'     => $limit,
        'total_items'  => $totalItems,
        'total_pages'  => $totalPages,
        'has_prev'     => $page > 1,
        'has_next'     => $page < $totalPages,
    ];

    jsonResponse(true, 'Daftar produk berhasil dimuat.', [
        'products'   => $products,
        'pagination' => $pagination,
    ]);

} catch (PDOException $e) {
    error_log('Products API Error: ' . $e->getMessage());
    jsonResponse(false, 'Terjadi kesalahan server. Silakan coba lagi nanti.', null, 500);
}
