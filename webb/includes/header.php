<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SmartCampus — The most advanced Student Information System for modern educational institutions.">
    <title>SmartCampus | <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Welcome'; ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- SmartCampus Design System -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <!-- Glassmorphism Navigation -->
    <nav class="glass-nav">
        <div class="nav-container">
            <a href="/index.php" class="nav-brand">
                <span class="brand-icon"><i class="fas fa-graduation-cap"></i></span>
                Smart<span class="gradient-text">Campus</span>
            </a>

            <div class="nav-links" id="nav-links">
                <a href="/index.php" class="nav-link">Home</a>
                <a href="/home.php" class="nav-link">Portal</a>
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="/home.php" class="nav-link">Dashboard</a>
                    <a href="/login.php?logout=1" class="nav-link" style="color: var(--rose) !important;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                <?php else: ?>
                    <a href="/login.php" class="nav-cta">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                <?php endif; ?>
                <button id="theme-toggle" class="theme-toggle" title="Toggle theme">
                    <i class="fas fa-sun"></i>
                </button>
            </div>

            <button id="mobile-nav-toggle" class="mobile-toggle" title="Menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <main class="main-content">
