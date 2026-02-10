<?php 
require __DIR__ . '/../db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['username'])) {
    header('Location: /login.php');
    exit;
}

// Get maintenance requests
$maintenanceRequests = [];
try {
    $stmt = $pdo->query("SELECT mr.*, a.asset_name FROM maintenance_requests mr LEFT JOIN assets a ON mr.asset_id = a.id ORDER BY mr.created_at DESC LIMIT 5");
    $maintenanceRequests = $stmt->fetchAll();
} catch (Exception $e) {
    // Handle error silently
}

$pageTitle = "Technician";
include __DIR__ . '/../includes/header.php';
?>

<div class="dashboard">
    <div class="dash-header reveal">
        <h1 class="dash-greeting">
            Technician <span class="gradient-text">Dashboard</span>
        </h1>
        <p class="dash-subtext">Maintenance and asset management</p>
    </div>

    <div class="stats-row stagger-children">
        <div class="glass-card dash-stat reveal">
            <div class="dash-stat-info">
                <p>Critical</p>
                <div class="dash-stat-value" data-count="0">0</div>
            </div>
            <div class="dash-stat-icon rose"><i class="fas fa-exclamation-triangle"></i></div>
        </div>

        <div class="glass-card dash-stat reveal">
            <div class="dash-stat-info">
                <p>In Progress</p>
                <div class="dash-stat-value" data-count="0">0</div>
            </div>
            <div class="dash-stat-icon amber"><i class="fas fa-wrench"></i></div>
        </div>

        <div class="glass-card dash-stat reveal">
            <div class="dash-stat-info">
                <p>Assets</p>
                <div class="dash-stat-value" data-count="2">0</div>
            </div>
            <div class="dash-stat-icon emerald"><i class="fas fa-server"></i></div>
        </div>
    </div>

    <div class="glass-card reveal" style="overflow: hidden;">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border);">
            <h2 style="font-size: 1.1rem; font-weight: 600;">
                <i class="fas fa-tools" style="color: var(--primary-light);"></i> Maintenance Requests
            </h2>
        </div>
        <?php if (empty($maintenanceRequests)): ?>
            <div style="padding: 2rem; text-align: center; color: var(--text-muted);">
                <i class="fas fa-check-circle" style="font-size: 2rem; margin-bottom: 0.5rem; color: var(--emerald);"></i>
                <p>No maintenance requests at this time.</p>
            </div>
        <?php else: ?>
            <div class="data-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Asset</th>
                            <th>Priority</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($maintenanceRequests as $req): ?>
                        <tr>
                            <td style="font-weight: 500; color: var(--text-primary);"><?= htmlspecialchars($req['asset_name'] ?? 'Unknown') ?></td>
                            <td>
                                <span class="badge <?= $req['priority'] === 'critical' ? 'badge-admin' : 'badge-default' ?>">
                                    <?= ucfirst($req['priority']) ?>
                                </span>
                            </td>
                            <td><?= ucfirst($req['status']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
