<?php
/**
 * =====================================================
 * Admin Products Management API
 * =====================================================
 */

require_once __DIR__ . '/../auth.php';
requireAdminAuth(true);

header('Content-Type: application/json; charset=utf-8');

$pdo = getSafeConnection();
$method = $_SERVER['REQUEST_METHOD'];

// Helper to generate slug
function generateSlug($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'produk-' . time() : $text;
}

// -----------------------------------------------------
// 1. GET: Retrieve products
// -----------------------------------------------------
if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        if ($pdo) {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $product = $stmt->fetch();
            if ($product) {
                $product['gallery'] = json_decode($product['gallery'], true) ?? [];
                $product['features'] = json_decode($product['features'], true) ?? [];
                $product['tech_stack'] = json_decode($product['tech_stack'], true) ?? [];
                echo json_encode(['success' => true, 'data' => $product]);
                exit;
            }
        }
        echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan.']);
        exit;
    }

    $category = $_GET['category'] ?? '';
    $search = trim($_GET['search'] ?? '');
    $status = $_GET['status'] ?? '';

    if ($pdo) {
        try {
            $sql = "SELECT * FROM products WHERE 1=1";
            $params = [];

            if (!empty($category) && $category !== 'all') {
                $sql .= " AND category = :cat";
                $params[':cat'] = $category;
            }
            if (!empty($status) && $status !== 'all') {
                $sql .= " AND status = :st";
                $params[':st'] = $status;
            }
            if (!empty($search)) {
                $sql .= " AND (name LIKE :q OR short_description LIKE :q OR slug LIKE :q)";
                $params[':q'] = "%$search%";
            }

            $sql .= " ORDER BY id DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $products = $stmt->fetchAll();

            foreach ($products as &$p) {
                $p['gallery'] = json_decode($p['gallery'], true) ?? [];
                $p['features'] = json_decode($p['features'], true) ?? [];
                $p['tech_stack'] = json_decode($p['tech_stack'], true) ?? [];
                $p['price'] = (float)$p['price'];
                $p['discount_price'] = $p['discount_price'] ? (float)$p['discount_price'] : null;
                $p['is_featured'] = (bool)$p['is_featured'];
                $p['is_bestseller'] = (bool)$p['is_bestseller'];
            }

            echo json_encode(['success' => true, 'data' => $products]);
            exit;
        } catch (\Throwable $e) {
            error_log('Admin products fetch error: ' . $e->getMessage());
        }
    }

    // Fallback data if database is not connected
    echo json_encode([
        'success' => true,
        'data' => [],
        'message' => 'Server database belum aktif.'
    ]);
    exit;
}

// -----------------------------------------------------
// 2. POST: Actions (create, update, delete, toggle)
// -----------------------------------------------------
if ($method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);
    if (!$data) {
        $data = $_POST;
    }

    $action = $data['action'] ?? 'create';

    if (!$pdo) {
        echo json_encode([
            'success' => false,
            'message' => 'Koneksi database MySQL di Laragon belum aktif. Pastikan tombol "Start All" di Laragon sudah dinyalakan!'
        ]);
        exit;
    }

    // ACTION: TOGGLE STATUS
    if ($action === 'toggle_status') {
        $id = (int)($data['id'] ?? 0);
        $newStatus = ($data['status'] ?? 'active') === 'active' ? 'active' : 'inactive';
        $stmt = $pdo->prepare("UPDATE products SET status = :st WHERE id = :id");
        $stmt->execute([':st' => $newStatus, ':id' => $id]);
        echo json_encode(['success' => true, 'message' => "Status produk berhasil diubah menjadi {$newStatus}."]);
        exit;
    }

    // ACTION: TOGGLE BESTSELLER
    if ($action === 'toggle_bestseller') {
        $id = (int)($data['id'] ?? 0);
        $val = !empty($data['is_bestseller']) ? 1 : 0;
        $stmt = $pdo->prepare("UPDATE products SET is_bestseller = :val WHERE id = :id");
        $stmt->execute([':val' => $val, ':id' => $id]);
        echo json_encode(['success' => true, 'message' => "Status Bestseller berhasil diperbarui."]);
        exit;
    }

    // ACTION: TOGGLE FEATURED
    if ($action === 'toggle_featured') {
        $id = (int)($data['id'] ?? 0);
        $val = !empty($data['is_featured']) ? 1 : 0;
        $stmt = $pdo->prepare("UPDATE products SET is_featured = :val WHERE id = :id");
        $stmt->execute([':val' => $val, ':id' => $id]);
        echo json_encode(['success' => true, 'message' => "Status Pilihan/Featured berhasil diperbarui."]);
        exit;
    }

    // ACTION: DELETE
    if ($action === 'delete') {
        $id = (int)($data['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID produk tidak valid.']);
            exit;
        }
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        echo json_encode(['success' => true, 'message' => 'Produk berhasil dihapus dari sistem!']);
        exit;
    }

    // ACTION: CREATE & UPDATE
    $name = trim($data['name'] ?? '');
    $price = (float)($data['price'] ?? 0);
    $discountPrice = !empty($data['discount_price']) ? (float)$data['discount_price'] : null;
    $category = trim($data['category'] ?? 'aplikasi-sekolah');
    $shortDesc = trim($data['short_description'] ?? '');
    $description = trim($data['description'] ?? '');
    $image = trim($data['image'] ?? 'assets/images/favicon.svg');
    $demoUrl = trim($data['demo_url'] ?? '#');
    $isFeatured = !empty($data['is_featured']) ? 1 : 0;
    $isBestseller = !empty($data['is_bestseller']) ? 1 : 0;
    $status = in_array($data['status'] ?? 'active', ['active', 'inactive']) ? $data['status'] : 'active';

    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Nama produk wajib diisi!']);
        exit;
    }
    if ($price <= 0) {
        echo json_encode(['success' => false, 'message' => 'Harga produk harus lebih dari 0!']);
        exit;
    }

    // Format features & tech_stack JSON
    $features = $data['features'] ?? [];
    if (is_string($features)) {
        // Explode by newline or comma
        $features = array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $features))));
    }
    $featuresJson = json_encode($features, JSON_UNESCAPED_UNICODE);

    $techStack = $data['tech_stack'] ?? [];
    if (is_string($techStack)) {
        $techStack = array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $techStack))));
    }
    $techStackJson = json_encode($techStack, JSON_UNESCAPED_UNICODE);

    $gallery = $data['gallery'] ?? [$image];
    if (is_string($gallery)) {
        $gallery = array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $gallery))));
    }
    $galleryJson = json_encode($gallery, JSON_UNESCAPED_UNICODE);

    if ($action === 'create') {
        $slug = generateSlug($name);
        // Check unique slug
        $chk = $pdo->prepare("SELECT COUNT(*) FROM products WHERE slug = :s");
        $chk->execute([':s' => $slug]);
        if ($chk->fetchColumn() > 0) {
            $slug .= '-' . time();
        }

        $stmt = $pdo->prepare("
            INSERT INTO products 
            (name, slug, description, short_description, price, discount_price, category, image, gallery, features, tech_stack, demo_url, is_featured, is_bestseller, status, rating, total_reviews, total_sales)
            VALUES 
            (:name, :slug, :description, :short_desc, :price, :discount_price, :category, :image, :gallery, :features, :tech_stack, :demo_url, :is_featured, :is_bestseller, :status, 5.0, 0, 0)
        ");
        $stmt->execute([
            ':name' => $name,
            ':slug' => $slug,
            ':description' => $description,
            ':short_desc' => $shortDesc,
            ':price' => $price,
            ':discount_price' => $discountPrice,
            ':category' => $category,
            ':image' => $image,
            ':gallery' => $galleryJson,
            ':features' => $featuresJson,
            ':tech_stack' => $techStackJson,
            ':demo_url' => $demoUrl,
            ':is_featured' => $isFeatured,
            ':is_bestseller' => $isBestseller,
            ':status' => $status
        ]);

        $newId = $pdo->lastInsertId();
        echo json_encode([
            'success' => true,
            'message' => 'Produk baru berhasil ditambahkan!',
            'id' => $newId
        ]);
        exit;
    }

    if ($action === 'update') {
        $id = (int)($data['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID produk tidak valid untuk diperbarui.']);
            exit;
        }

        $stmt = $pdo->prepare("
            UPDATE products SET
                name = :name,
                description = :description,
                short_description = :short_desc,
                price = :price,
                discount_price = :discount_price,
                category = :category,
                image = :image,
                gallery = :gallery,
                features = :features,
                tech_stack = :tech_stack,
                demo_url = :demo_url,
                is_featured = :is_featured,
                is_bestseller = :is_bestseller,
                status = :status
            WHERE id = :id
        ");
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':short_desc' => $shortDesc,
            ':price' => $price,
            ':discount_price' => $discountPrice,
            ':category' => $category,
            ':image' => $image,
            ':gallery' => $galleryJson,
            ':features' => $featuresJson,
            ':tech_stack' => $techStackJson,
            ':demo_url' => $demoUrl,
            ':is_featured' => $isFeatured,
            ':is_bestseller' => $isBestseller,
            ':status' => $status,
            ':id' => $id
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Perubahan produk berhasil disimpan!'
        ]);
        exit;
    }
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
