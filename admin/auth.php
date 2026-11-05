<?php
/**
 * =====================================================
 * Admin Authentication Helper & Session Middleware
 * =====================================================
 */

if (session_status() === PHP_SESSION_NONE) {
    // Session cookie parameters for security
    session_set_cookie_params([
        'lifetime' => 86400 * 7, // 7 days
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

require_once __DIR__ . '/../config/database.php';

/**
 * Check if admin is currently authenticated.
 */
function isAdminLoggedIn(): bool
{
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true && !empty($_SESSION['admin_id']);
}

/**
 * Get logged-in admin data from session.
 */
function getLoggedInAdmin(): ?array
{
    if (!isAdminLoggedIn()) {
        return null;
    }
    return [
        'id'       => $_SESSION['admin_id'] ?? null,
        'username' => $_SESSION['admin_username'] ?? 'admin',
        'email'    => $_SESSION['admin_email'] ?? '',
        'name'     => $_SESSION['admin_name'] ?? 'Administrator',
        'role'     => $_SESSION['admin_role'] ?? 'admin',
        'avatar'   => $_SESSION['admin_avatar'] ?? null
    ];
}

/**
 * Guard middleware: require authentication.
 */
function requireAdminAuth(bool $isApi = false): void
{
    if (!isAdminLoggedIn()) {
        if ($isApi) {
            http_response_code(401);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'message' => 'Sesi login telah berakhir atau Anda belum login sebagai admin.',
                'redirect' => 'login.php'
            ]);
            exit;
        } else {
            header('Location: login.php');
            exit;
        }
    }
}

/**
 * Log in admin and store session variables.
 */
function loginAdmin(array $admin): void
{
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_id']        = $admin['id'];
    $_SESSION['admin_username']  = $admin['username'];
    $_SESSION['admin_email']     = $admin['email'];
    $_SESSION['admin_name']      = $admin['name'];
    $_SESSION['admin_role']      = $admin['role'];
    $_SESSION['admin_avatar']    = $admin['avatar'] ?? null;
    $_SESSION['admin_login_at']  = date('Y-m-d H:i:s');
}

/**
 * Log out admin and destroy session.
 */
function logoutAdmin(): void
{
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}
