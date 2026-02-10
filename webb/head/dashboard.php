<?php 
require __DIR__ . '/../db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['username'])) {
    header('Location: /login.php');
    exit;
}

$pageTitle = "Department Head";
include __DIR__ . '/../includes/header.php';
?>

<div class="dashboard">
    <div class="dash-header reveal">
        <h1 class="dash-greeting">
            Department Head <span class="gradient-text">Dashboard</span>
        </h1>
        <p class="dash-subtext">Overview and management for your department</p>
    </div>

    <div class="stats-row stagger-children">
        <div class="glass-card dash-stat reveal">
            <div class="dash-stat-info">
                <p>Total Students</p>
                <div class="dash-stat-value" data-count="0">0</div>
            </div>
            <div class="dash-stat-icon indigo"><i class="fas fa-user-graduate"></i></div>
        </div>

        <div class="glass-card dash-stat reveal">
            <div class="dash-stat-info">
                <p>Faculty Members</p>
                <div class="dash-stat-value" data-count="0">0</div>
            </div>
            <div class="dash-stat-icon emerald"><i class="fas fa-chalkboard-teacher"></i></div>
        </div>

        <div class="glass-card dash-stat reveal">
            <div class="dash-stat-info">
                <p>Pending Actions</p>
                <div class="dash-stat-value" data-count="0">0</div>
            </div>
            <div class="dash-stat-icon amber"><i class="fas fa-clock"></i></div>
        </div>
    </div>

    <div class="glass-card p-4 mb-4 reveal">
        <h2 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">
            <i class="fas fa-bolt" style="color: var(--amber);"></i> Quick Actions
        </h2>
        <div class="quick-actions-grid">
            <a href="#" class="glass-card action-card" onclick="alert('Coming soon')">
                <div class="action-icon"><i class="fas fa-chart-bar"></i></div>
                <div>
                    <div class="action-title">View Reports</div>
                    <div class="action-desc">Department analytics</div>
                </div>
            </a>

            <a href="#" class="glass-card action-card" onclick="alert('Coming soon')">
                <div class="action-icon"><i class="fas fa-users-cog"></i></div>
                <div>
                    <div class="action-title">Manage Faculty</div>
                    <div class="action-desc">Faculty assignments</div>
                </div>
            </a>

            <a href="/head/gradebook.php" class="glass-card action-card">
                <div class="action-icon"><i class="fas fa-book-open"></i></div>
                <div>
                    <div class="action-title">Review Grades</div>
                    <div class="action-desc">Grade management</div>
                </div>
            </a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
