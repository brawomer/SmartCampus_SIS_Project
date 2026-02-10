<?php
/**
 * auth.php - Authentication Middleware
 * Protects routes and enforces role-based access control
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/helpers.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path' => '/',
        'secure' => false, // Set to true in production with HTTPS
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_name(SESSION_NAME);
    session_start();
}

// Check session timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_LIFETIME)) {
    session_unset();
    session_destroy();
    redirect('/login.php?timeout=1');
}
$_SESSION['last_activity'] = time();

/**
 * Protect route - require authentication
 */
function protectRoute() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        redirect('/login.php');
    }
}

/**
 * Protect route with role requirement
 */
function protectRouteWithRole($allowedRoles) {
    protectRoute();
    
    if (!is_array($allowedRoles)) {
        $allowedRoles = [$allowedRoles];
    }
    
    $userRole = $_SESSION['role'] ?? '';
    if (!in_array($userRole, $allowedRoles)) {
        http_response_code(403);
        include __DIR__ . '/../errors/403.php';
        exit;
    }
}

/**
 * Login user
 */
function loginUser($user) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['last_activity'] = time();
    $_SESSION['login_time'] = time();
}

/**
 * Logout user
 */
function logoutUser() {
    session_unset();
    session_destroy();
    redirect('/login.php');
}
?>
