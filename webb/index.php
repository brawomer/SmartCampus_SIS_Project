<?php
$pageTitle = "Home";
require_once 'includes/header.php';
?>

<!-- Particle Canvas Background -->
<canvas id="particle-canvas"></canvas>

<!-- ========== HERO SECTION ========== -->
<section class="hero-section">
    <div class="hero-bg-orbs">
        <div class="hero-orb"></div>
        <div class="hero-orb"></div>
        <div class="hero-orb"></div>
    </div>

    <div class="hero-content">
        <div class="hero-badge reveal">
            <span class="pulse-dot"></span>
            Now available — Spring 2026 Semester
        </div>

        <h1 class="hero-title reveal reveal-delay-1">
            The Future of<br>
            <span class="gradient-text" data-typing="Education Management|Campus Intelligence|Student Success">Education Management</span>
        </h1>

        <p class="hero-subtitle reveal reveal-delay-2">
            SmartCampus streamlines your entire institution — enrollment, grading, attendance, analytics — in one secure, beautiful platform.
        </p>

        <div class="hero-buttons reveal reveal-delay-3">
            <a href="/login.php" class="btn btn-primary btn-lg">
                <i class="fas fa-rocket"></i> Get Started
            </a>
            <a href="#features" class="btn btn-ghost btn-lg">
                <i class="fas fa-play-circle"></i> Explore Features
            </a>
        </div>
    </div>
</section>

<!-- ========== FEATURES SECTION ========== -->
<section id="features" class="section" style="background: var(--bg-secondary);">
    <div class="section-container">
        <div class="section-header reveal">
            <span class="section-label"><i class="fas fa-sparkles"></i> Capabilities</span>
            <h2 class="section-title">Everything you need to run<br><span class="gradient-text">a world-class campus</span></h2>
            <p class="section-desc">Comprehensive tools for administrators, teachers, students, and parents — all in one unified platform.</p>
        </div>

        <div class="features-grid stagger-children">
            <div class="glass-card feature-card reveal">
                <div class="feature-icon indigo"><i class="fas fa-user-graduate"></i></div>
                <h3 class="feature-title">Student Profiles</h3>
                <p class="feature-desc">Complete academic records, attendance history, extracurricular tracking, and personalized learning paths.</p>
            </div>

            <div class="glass-card feature-card reveal">
                <div class="feature-icon violet"><i class="fas fa-chalkboard-teacher"></i></div>
                <h3 class="feature-title">Teacher Tools</h3>
                <p class="feature-desc">Intuitive gradebook, lesson planning, assignment management, and real-time communication.</p>
            </div>

            <div class="glass-card feature-card reveal">
                <div class="feature-icon cyan"><i class="fas fa-chart-line"></i></div>
                <h3 class="feature-title">Analytics Engine</h3>
                <p class="feature-desc">Real-time insights into performance, enrollment trends, financial health, and institutional KPIs.</p>
            </div>

            <div class="glass-card feature-card reveal">
                <div class="feature-icon emerald"><i class="fas fa-shield-halved"></i></div>
                <h3 class="feature-title">Enterprise Security</h3>
                <p class="feature-desc">Role-based access control, encrypted data, session management, and comprehensive audit trails.</p>
            </div>

            <div class="glass-card feature-card reveal">
                <div class="feature-icon rose"><i class="fas fa-bell"></i></div>
                <h3 class="feature-title">Smart Notifications</h3>
                <p class="feature-desc">Automated alerts for grades, attendance, deadlines, and important campus events.</p>
            </div>

            <div class="glass-card feature-card reveal">
                <div class="feature-icon amber"><i class="fas fa-calendar-days"></i></div>
                <h3 class="feature-title">Academic Calendar</h3>
                <p class="feature-desc">Semester planning, exam scheduling, holiday management, and event coordination.</p>
            </div>
        </div>
    </div>
</section>

<!-- ========== STATS SECTION ========== -->
<section class="section">
    <div class="section-container">
        <div class="section-header reveal">
            <span class="section-label"><i class="fas fa-chart-bar"></i> Impact</span>
            <h2 class="section-title">Trusted by institutions <span class="gradient-text">worldwide</span></h2>
        </div>

        <div class="stats-grid stagger-children">
            <div class="glass-card stat-card reveal">
                <div class="stat-number" data-count="1200" data-suffix="+">0</div>
                <div class="stat-label">Active Students</div>
            </div>
            <div class="glass-card stat-card reveal">
                <div class="stat-number" data-count="85" data-suffix="+">0</div>
                <div class="stat-label">Course Offerings</div>
            </div>
            <div class="glass-card stat-card reveal">
                <div class="stat-number" data-count="98" data-suffix="%">0</div>
                <div class="stat-label">Satisfaction Rate</div>
            </div>
            <div class="glass-card stat-card reveal">
                <div class="stat-number" data-count="24" data-suffix="/7">0</div>
                <div class="stat-label">System Uptime</div>
            </div>
        </div>
    </div>
</section>

<!-- ========== TESTIMONIALS SECTION ========== -->
<section class="section" style="background: var(--bg-secondary);">
    <div class="section-container">
        <div class="section-header reveal">
            <span class="section-label"><i class="fas fa-quote-left"></i> Voices</span>
            <h2 class="section-title">What our users <span class="gradient-text">say</span></h2>
        </div>

        <div class="testimonials-grid stagger-children">
            <div class="glass-card testimonial-card reveal">
                <p class="testimonial-text">"SmartCampus completely transformed how we manage our school. The analytics alone saved us hundreds of hours per semester."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">DR</div>
                    <div class="testimonial-info">
                        <div class="testimonial-name">Dr. Rebecca Chen</div>
                        <div class="testimonial-role">Principal, Meridian Academy</div>
                    </div>
                </div>
            </div>

            <div class="glass-card testimonial-card reveal">
                <p class="testimonial-text">"The grading system is incredibly intuitive. I can post grades, track attendance, and communicate with parents all from one screen."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">MK</div>
                    <div class="testimonial-info">
                        <div class="testimonial-name">Prof. Michael Kovacs</div>
                        <div class="testimonial-role">CS Department Head</div>
                    </div>
                </div>
            </div>

            <div class="glass-card testimonial-card reveal">
                <p class="testimonial-text">"As a student, I love being able to check my grades, attendance, and schedule in real time. The dark mode is gorgeous too!"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">SA</div>
                    <div class="testimonial-info">
                        <div class="testimonial-name">Sarah Almeida</div>
                        <div class="testimonial-role">Computer Science Major</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== CTA BANNER ========== -->
<section class="section">
    <div class="section-container">
        <div class="cta-banner reveal">
            <h2 class="cta-title">Ready to transform your campus?</h2>
            <p class="cta-desc">Join thousands of institutions already using SmartCampus to deliver world-class education management.</p>
            <a href="/login.php" class="btn btn-primary btn-lg" style="position: relative;">
                <i class="fas fa-arrow-right"></i> Start Now — Free
            </a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>