<?php 
require __DIR__ . '/../db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['username'])) {
    header('Location: /login.php');
    exit;
}

$pageTitle = "Marketing";
include __DIR__ . '/../includes/header.php';
?>

<div class="dashboard">
    <div class="dash-header reveal">
        <h1 class="dash-greeting">
            Marketing <span class="gradient-text">Dashboard</span>
        </h1>
        <p class="dash-subtext">Campaign analytics and promotion tools</p>
    </div>

    <div class="stats-row stagger-children">
        <div class="glass-card dash-stat reveal">
            <div class="dash-stat-info">
                <p>Active Campaigns</p>
                <div class="dash-stat-value" data-count="0">0</div>
            </div>
            <div class="dash-stat-icon indigo"><i class="fas fa-bullhorn"></i></div>
        </div>

        <div class="glass-card dash-stat reveal">
            <div class="dash-stat-info">
                <p>Reach</p>
                <div class="dash-stat-value" data-count="0">0</div>
            </div>
            <div class="dash-stat-icon emerald"><i class="fas fa-users"></i></div>
        </div>

        <div class="glass-card dash-stat reveal">
            <div class="dash-stat-info">
                <p>Engagement</p>
                <div class="dash-stat-value">0%</div>
            </div>
            <div class="dash-stat-icon violet"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>

    <div class="glass-card p-4 reveal">
        <h2 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">
            <i class="fas fa-clock" style="color: var(--primary-light);"></i> Recent Activity
        </h2>
        <div style="text-align: center; padding: 2rem;">
            <p style="color: var(--text-muted);">No campaigns yet. <a href="#" style="color: var(--primary-light);">Create one now</a></p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
