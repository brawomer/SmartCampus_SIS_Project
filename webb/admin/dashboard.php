<?php
/**
 * Admin Dashboard — Premium Glassmorphism Design
 */
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../includes/helpers.php';

protectRouteWithRole('admin');

$user = getCurrentUser();

// Get statistics
$allUsers = $pdo->getAll('users');
$allCourses = $pdo->getAll('courses');
$allEnrollments = $pdo->getAll('enrollments');
$allGrades = $pdo->getAll('grades');

$stats = [
    'total_users' => count($allUsers),
    'total_students' => count(array_filter($allUsers, fn($u) => $u['role'] === 'student')),
    'total_teachers' => count(array_filter($allUsers, fn($u) => $u['role'] === 'teacher')),
    'total_courses' => count($allCourses),
    'total_enrollments' => count($allEnrollments),
    'total_grades' => count($allGrades),
];

$pageTitle = "Admin Dashboard";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="dashboard">
    <!-- Welcome Header -->
    <div class="dash-header reveal">
        <h1 class="dash-greeting">
            Good <?= date('H') < 12 ? 'morning' : (date('H') < 17 ? 'afternoon' : 'evening') ?>,
            <span class="gradient-text"><?= htmlspecialchars($user['full_name']) ?></span> 👋
        </h1>
        <p class="dash-subtext">Here's what's happening across your campus today.</p>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-row stagger-children">
        <div class="glass-card dash-stat reveal">
            <div class="dash-stat-info">
                <p>Total Users</p>
                <div class="dash-stat-value" data-count="<?= $stats['total_users'] ?>">0</div>
            </div>
            <div class="dash-stat-icon indigo"><i class="fas fa-users"></i></div>
        </div>

        <div class="glass-card dash-stat reveal">
            <div class="dash-stat-info">
                <p>Students</p>
                <div class="dash-stat-value" data-count="<?= $stats['total_students'] ?>">0</div>
            </div>
            <div class="dash-stat-icon emerald"><i class="fas fa-user-graduate"></i></div>
        </div>

        <div class="glass-card dash-stat reveal">
            <div class="dash-stat-info">
                <p>Teachers</p>
                <div class="dash-stat-value" data-count="<?= $stats['total_teachers'] ?>">0</div>
            </div>
            <div class="dash-stat-icon cyan"><i class="fas fa-chalkboard-teacher"></i></div>
        </div>

        <div class="glass-card dash-stat reveal">
            <div class="dash-stat-info">
                <p>Active Courses</p>
                <div class="dash-stat-value" data-count="<?= $stats['total_courses'] ?>">0</div>
            </div>
            <div class="dash-stat-icon violet"><i class="fas fa-book"></i></div>
        </div>

        <div class="glass-card dash-stat reveal">
            <div class="dash-stat-info">
                <p>Enrollments</p>
                <div class="dash-stat-value" data-count="<?= $stats['total_enrollments'] ?>">0</div>
            </div>
            <div class="dash-stat-icon amber"><i class="fas fa-clipboard-list"></i></div>
        </div>

        <div class="glass-card dash-stat reveal">
            <div class="dash-stat-info">
                <p>Grade Entries</p>
                <div class="dash-stat-value" data-count="<?= $stats['total_grades'] ?>">0</div>
            </div>
            <div class="dash-stat-icon rose"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="glass-card p-4 mb-4 reveal">
        <h2 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">
            <i class="fas fa-bolt" style="color: var(--amber);"></i> Quick Actions
        </h2>
        <div class="quick-actions-grid">
            <a href="/admin/users-manage.php" class="glass-card action-card">
                <div class="action-icon"><i class="fas fa-users-cog"></i></div>
                <div>
                    <div class="action-title">Manage Users</div>
                    <div class="action-desc">Create, edit, delete users</div>
                </div>
            </a>

            <a href="/admin/management.php" class="glass-card action-card">
                <div class="action-icon"><i class="fas fa-book-open"></i></div>
                <div>
                    <div class="action-title">Manage Courses</div>
                    <div class="action-desc">Add and edit courses</div>
                </div>
            </a>

            <a href="#" class="glass-card action-card">
                <div class="action-icon"><i class="fas fa-chart-bar"></i></div>
                <div>
                    <div class="action-title">View Reports</div>
                    <div class="action-desc">Analytics and insights</div>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Users Table -->
    <div class="glass-card reveal" style="overflow: hidden;">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border);">
            <h2 style="font-size: 1.1rem; font-weight: 600;">
                <i class="fas fa-clock" style="color: var(--primary-light);"></i> Recent Users
            </h2>
        </div>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    usort($allUsers, function($a, $b) {
                        return strtotime($b['created_at'] ?? '2020-01-01') - strtotime($a['created_at'] ?? '2020-01-01');
                    });
                    $recentUsers = array_slice($allUsers, 0, 5);
                    foreach ($recentUsers as $recentUser):
                        $roleBadge = match($recentUser['role']) {
                            'admin' => 'badge-admin',
                            'teacher', 'head_teacher' => 'badge-teacher',
                            'student' => 'badge-student',
                            default => 'badge-default'
                        };
                    ?>
                        <tr>
                            <td style="font-weight: 500; color: var(--text-primary);">
                                <?= htmlspecialchars($recentUser['full_name']) ?>
                            </td>
                            <td>
                                <span class="badge <?= $roleBadge ?>">
                                    <?= ucfirst($recentUser['role']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($recentUser['email']) ?></td>
                            <td><?= formatDate($recentUser['created_at'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>