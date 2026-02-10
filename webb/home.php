<?php
$pageTitle = "Dashboard Portal";
if (session_status() === PHP_SESSION_NONE) session_start();
include __DIR__ . '/includes/header.php';
?>

<canvas id="particle-canvas"></canvas>

<section class="section" style="position: relative; z-index: 1;">
    <div class="section-container">
        <div class="section-header reveal">
            <span class="section-label"><i class="fas fa-compass"></i> Navigate</span>
            <h2 class="section-title">Welcome to <span class="gradient-text">SmartCampus</span></h2>
            <p class="section-desc">Select your role to access your personalized dashboard with all the tools you need.</p>
        </div>

        <div class="role-grid stagger-children">
            <!-- Admin -->
            <a href="/admin/dashboard.php" class="glass-card role-card reveal">
                <div class="role-icon admin"><i class="fas fa-user-shield"></i></div>
                <div class="role-name">Admin</div>
                <div class="role-desc">System management & oversight</div>
            </a>

            <!-- Head Teacher -->
            <a href="/head/dashboard.php" class="glass-card role-card reveal">
                <div class="role-icon teacher"><i class="fas fa-chalkboard-user"></i></div>
                <div class="role-name">Head Teacher</div>
                <div class="role-desc">Academic leadership</div>
            </a>

            <!-- Student -->
            <a href="/student/dashboard.php" class="glass-card role-card reveal">
                <div class="role-icon student"><i class="fas fa-user-graduate"></i></div>
                <div class="role-name">Student</div>
                <div class="role-desc">Grades, schedule & progress</div>
            </a>

            <!-- Staff -->
            <a href="/staff/dashboard.php" class="glass-card role-card reveal">
                <div class="role-icon staff"><i class="fas fa-users"></i></div>
                <div class="role-name">Staff</div>
                <div class="role-desc">Administrative tasks</div>
            </a>

            <!-- Marketing -->
            <a href="/marketing/dashboard.php" class="glass-card role-card reveal">
                <div class="role-icon marketing"><i class="fas fa-bullhorn"></i></div>
                <div class="role-name">Marketing</div>
                <div class="role-desc">Campaigns & outreach</div>
            </a>

            <!-- Technician -->
            <a href="/technician/dashboard.php" class="glass-card role-card reveal">
                <div class="role-icon technician"><i class="fas fa-wrench"></i></div>
                <div class="role-name">Technician</div>
                <div class="role-desc">Maintenance & support</div>
            </a>
        </div>

        <div class="text-center mt-4 reveal">
            <p style="color: var(--text-muted);">
                Need to sign in?
                <a href="/login.php" class="form-link" style="font-weight: 600;">
                    Login here <i class="fas fa-arrow-right" style="font-size: 0.75rem;"></i>
                </a>
            </p>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
