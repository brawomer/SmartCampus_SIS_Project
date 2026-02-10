<?php
/**
 * Login Page — Premium Glassmorphism Design
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/helpers.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: /login.php');
    exit;
}

// If already logged in, redirect to appropriate dashboard
if (isLoggedIn()) {
    $role = $_SESSION['role'];
    switch ($role) {
        case 'admin':
            redirect('/admin/dashboard.php');
            break;
        case 'teacher':
        case 'head_teacher':
            redirect('/head/dashboard.php');
            break;
        case 'student':
            redirect('/student/dashboard.php');
            break;
        case 'technician':
            redirect('/technician/dashboard.php');
            break;
        case 'parent':
            redirect('/student/dashboard.php');
            break;
        default:
            redirect('/home.php');
    }
}

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // Find user by username
        $users = $pdo->getAll('users');
        $user = null;

        foreach ($users as $u) {
            if ($u['username'] === $username) {
                $user = $u;
                break;
            }
        }

        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['last_activity'] = time();
            $_SESSION['login_time'] = time();

            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    redirect('/admin/dashboard.php');
                    break;
                case 'teacher':
                case 'head_teacher':
                    redirect('/head/dashboard.php');
                    break;
                case 'student':
                    redirect('/student/dashboard.php');
                    break;
                case 'technician':
                    redirect('/technician/dashboard.php');
                    break;
                case 'parent':
                    redirect('/student/dashboard.php');
                    break;
                default:
                    redirect('/home.php');
            }
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

$pageTitle = "Login";
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — SmartCampus</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<canvas id="particle-canvas"></canvas>

<div class="login-wrapper">
    <!-- Floating Orbs -->
    <div class="hero-bg-orbs">
        <div class="hero-orb"></div>
        <div class="hero-orb"></div>
        <div class="hero-orb"></div>
    </div>

    <div class="glass-card login-card reveal">
        <div class="login-logo">
            <div class="login-logo-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h1 class="login-title">Welcome back</h1>
            <p class="login-subtitle">Sign in to SmartCampus</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username" class="form-label">
                    <i class="fas fa-user"></i> Username
                </label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    required
                    autocomplete="username"
                    class="form-input"
                    placeholder="Enter your username"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                >
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="form-input"
                    placeholder="Enter your password"
                >
            </div>

            <div class="form-row">
                <label class="form-checkbox">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <a href="#" class="form-link">Forgot password?</a>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-sign-in-alt"></i> Sign in
            </button>
        </form>

        <div class="test-credentials">
            <div class="cred-title"><i class="fas fa-key"></i> Demo Credentials</div>
            <div class="cred-item"><strong>Admin:</strong> admin_user / password123</div>
            <div class="cred-item"><strong>Teacher:</strong> teacher_jane / password123</div>
            <div class="cred-item"><strong>Student:</strong> student_alex / password123</div>
        </div>
    </div>

    <!-- Back to Home -->
    <div style="position: fixed; bottom: 2rem; left: 50%; transform: translateX(-50%); z-index: 10;">
        <a href="/index.php" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to homepage
        </a>
    </div>
</div>

<script src="/assets/js/main.js"></script>
</body>
</html>