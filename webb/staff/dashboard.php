<?php 
require __DIR__ . '/../db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['username'])) {
    header('Location: /login.php');
    exit;
}

$pageTitle = "Staff Portal";
include __DIR__ . '/../includes/header.php';
?>

<div class="dashboard">
    <div class="dash-header reveal">
        <h1 class="dash-greeting">
            Staff <span class="gradient-text">Dashboard</span>
        </h1>
        <p class="dash-subtext">Staff management and operations portal</p>
    </div>

    <div class="glass-card p-4 mb-4 reveal">
        <h2 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">
            <i class="fas fa-tasks" style="color: var(--primary-light);"></i> Current Tasks
        </h2>
        <div class="alert alert-warning" style="animation: none;">
            <i class="fas fa-check-circle"></i>
            <span><strong>No pending tasks</strong> — All systems operational</span>
        </div>
    </div>

    <div class="glass-card p-4 reveal">
        <h2 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">
            <i class="fas fa-id-badge" style="color: var(--accent);"></i> Department Information
        </h2>
        <div style="padding: 1rem; background: var(--bg-glass); border-left: 4px solid var(--primary); border-radius: var(--radius-sm);">
            <p style="color: var(--text-primary);"><strong>Staff Member:</strong> <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']) ?></p>
            <p style="margin-top: 0.5rem; color: var(--text-secondary);"><strong>Role:</strong> <?= htmlspecialchars($_SESSION['role'] ?? 'staff') ?></p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
