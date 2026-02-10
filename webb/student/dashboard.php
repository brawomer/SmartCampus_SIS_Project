<?php
require __DIR__ . '/../db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: /login.php');
    exit;
}

$pageTitle = "Student Dashboard";
include __DIR__ . '/../includes/header.php';

$studentName = htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']);

// Mock data for the beautiful dashboard
$attendance = 85;
$gpa = 3.8;
$coursesCount = 0;
$circumference = 2 * 3.14159 * 40; // radius = 40
?>

<div class="dashboard">
    <!-- Welcome Header -->
    <div class="dash-header reveal">
        <h1 class="dash-greeting">
            Welcome back, <span class="gradient-text"><?= $studentName ?></span> 🎓
        </h1>
        <p class="dash-subtext">Here's your academic progress at a glance.</p>
    </div>

    <!-- Metric Cards with Progress Rings -->
    <div class="metric-cards stagger-children">
        <!-- Courses Enrolled -->
        <div class="glass-card metric-card reveal">
            <div class="metric-icon" style="background: rgba(99, 102, 241, 0.15); color: var(--primary-light);">
                <i class="fas fa-book"></i>
            </div>
            <div class="metric-value" data-count="<?= $coursesCount ?>">0</div>
            <div class="metric-label">Courses Enrolled</div>
        </div>

        <!-- Attendance with Progress Ring -->
        <div class="glass-card metric-card reveal">
            <div class="progress-ring-wrapper" style="margin-bottom: 0.5rem;">
                <svg class="progress-ring" width="90" height="90">
                    <circle class="progress-ring-bg" cx="45" cy="45" r="40" stroke-width="5" />
                    <circle
                        class="progress-ring-fill"
                        cx="45" cy="45" r="40"
                        stroke-width="5"
                        stroke="url(#grad-green)"
                        data-progress="<?= $attendance ?>"
                        data-circumference="<?= $circumference ?>"
                    />
                    <defs>
                        <linearGradient id="grad-green" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" style="stop-color:#10b981" />
                            <stop offset="100%" style="stop-color:#06b6d4" />
                        </linearGradient>
                    </defs>
                </svg>
                <span class="progress-ring-text"><?= $attendance ?>%</span>
            </div>
            <div class="metric-label">Attendance</div>
        </div>

        <!-- GPA with Progress Ring -->
        <div class="glass-card metric-card reveal">
            <div class="progress-ring-wrapper" style="margin-bottom: 0.5rem;">
                <svg class="progress-ring" width="90" height="90">
                    <circle class="progress-ring-bg" cx="45" cy="45" r="40" stroke-width="5" />
                    <circle
                        class="progress-ring-fill"
                        cx="45" cy="45" r="40"
                        stroke-width="5"
                        stroke="url(#grad-gold)"
                        data-progress="<?= ($gpa / 4.0) * 100 ?>"
                        data-circumference="<?= $circumference ?>"
                    />
                    <defs>
                        <linearGradient id="grad-gold" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" style="stop-color:#f59e0b" />
                            <stop offset="100%" style="stop-color:#f43f5e" />
                        </linearGradient>
                    </defs>
                </svg>
                <span class="progress-ring-text"><?= $gpa ?></span>
            </div>
            <div class="metric-label">GPA</div>
        </div>

        <!-- Credits -->
        <div class="glass-card metric-card reveal">
            <div class="metric-icon" style="background: rgba(139, 92, 246, 0.15); color: var(--violet-light);">
                <i class="fas fa-award"></i>
            </div>
            <div class="metric-value" data-count="42">0</div>
            <div class="metric-label">Credits Earned</div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="glass-card p-4 mb-4 reveal">
        <h2 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">
            <i class="fas fa-bolt" style="color: var(--amber);"></i> Quick Access
        </h2>
        <div class="quick-actions-grid">
            <a href="/student/courses.php" class="glass-card action-card">
                <div class="action-icon"><i class="fas fa-book-open"></i></div>
                <div>
                    <div class="action-title">My Courses</div>
                    <div class="action-desc">View your enrolled courses</div>
                </div>
            </a>

            <a href="/student/grades.php" class="glass-card action-card">
                <div class="action-icon"><i class="fas fa-chart-line"></i></div>
                <div>
                    <div class="action-title">View Grades</div>
                    <div class="action-desc">Check your academic performance</div>
                </div>
            </a>

            <a href="#" class="glass-card action-card">
                <div class="action-icon"><i class="fas fa-calendar-alt"></i></div>
                <div>
                    <div class="action-title">Schedule</div>
                    <div class="action-desc">View your class schedule</div>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Grades Section -->
    <div class="glass-card reveal" style="overflow: hidden;">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border);">
            <h2 style="font-size: 1.1rem; font-weight: 600;">
                <i class="fas fa-chart-line" style="color: var(--primary-light);"></i> Recent Grades
            </h2>
        </div>
        <div style="padding: 2rem; text-align: center;">
            <div class="alert alert-warning" style="max-width: 400px; margin: 0 auto; animation: none;">
                <i class="fas fa-info-circle"></i>
                <span>No grades have been posted for this semester yet.</span>
            </div>
            <p style="margin-top: 1rem; color: var(--text-muted); font-size: 0.85rem;">
                Grades will appear here once your instructors post them.
            </p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
