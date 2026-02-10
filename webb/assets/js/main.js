/**
 * SmartCampus — Premium JavaScript Engine ✨
 * Particles · Scroll Reveals · Animated Counters · Dark Mode · Micro-interactions
 */

(function () {
    'use strict';

    // ──────────────────────────────────────────
    // 1. Particle Star-Field Background
    // ──────────────────────────────────────────
    class ParticleField {
        constructor(canvasId) {
            this.canvas = document.getElementById(canvasId);
            if (!this.canvas) return;
            this.ctx = this.canvas.getContext('2d');
            this.particles = [];
            this.mouse = { x: null, y: null };
            this.count = Math.min(window.innerWidth / 8, 120);
            this.resize();
            this.init();
            this.animate();

            window.addEventListener('resize', () => this.resize());
            window.addEventListener('mousemove', (e) => {
                this.mouse.x = e.clientX;
                this.mouse.y = e.clientY;
            });
        }

        resize() {
            this.w = this.canvas.width = window.innerWidth;
            this.h = this.canvas.height = window.innerHeight;
        }

        init() {
            this.particles = [];
            for (let i = 0; i < this.count; i++) {
                this.particles.push({
                    x: Math.random() * this.w,
                    y: Math.random() * this.h,
                    r: Math.random() * 1.5 + 0.3,
                    dx: (Math.random() - 0.5) * 0.4,
                    dy: (Math.random() - 0.5) * 0.4,
                    opacity: Math.random() * 0.5 + 0.2,
                });
            }
        }

        animate() {
            this.ctx.clearRect(0, 0, this.w, this.h);

            for (const p of this.particles) {
                p.x += p.dx;
                p.y += p.dy;

                if (p.x < 0 || p.x > this.w) p.dx *= -1;
                if (p.y < 0 || p.y > this.h) p.dy *= -1;

                // Draw particle
                this.ctx.beginPath();
                this.ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                this.ctx.fillStyle = `rgba(129, 140, 248, ${p.opacity})`;
                this.ctx.fill();

                // Draw connecting lines to nearby particles
                for (const q of this.particles) {
                    const dist = Math.hypot(p.x - q.x, p.y - q.y);
                    if (dist < 120) {
                        this.ctx.beginPath();
                        this.ctx.moveTo(p.x, p.y);
                        this.ctx.lineTo(q.x, q.y);
                        this.ctx.strokeStyle = `rgba(99, 102, 241, ${0.08 * (1 - dist / 120)})`;
                        this.ctx.lineWidth = 0.5;
                        this.ctx.stroke();
                    }
                }

                // Mouse interaction — push particles away gently
                if (this.mouse.x !== null) {
                    const md = Math.hypot(p.x - this.mouse.x, p.y - this.mouse.y);
                    if (md < 150) {
                        const angle = Math.atan2(p.y - this.mouse.y, p.x - this.mouse.x);
                        p.x += Math.cos(angle) * 0.5;
                        p.y += Math.sin(angle) * 0.5;
                    }
                }
            }

            requestAnimationFrame(() => this.animate());
        }
    }

    // ──────────────────────────────────────────
    // 2. Scroll Reveal (IntersectionObserver)
    // ──────────────────────────────────────────
    function initScrollReveal() {
        const reveals = document.querySelectorAll('.reveal');
        if (!reveals.length) return;

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.1, rootMargin: '0px 0px -40px 0px' }
        );

        reveals.forEach((el) => observer.observe(el));
    }

    // ──────────────────────────────────────────
    // 3. Animated Number Counters
    // ──────────────────────────────────────────
    function initCounters() {
        const counters = document.querySelectorAll('[data-count]');
        if (!counters.length) return;

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.3 }
        );

        counters.forEach((el) => observer.observe(el));
    }

    function animateCounter(el) {
        const target = parseInt(el.getAttribute('data-count'), 10);
        const suffix = el.getAttribute('data-suffix') || '';
        const prefix = el.getAttribute('data-prefix') || '';
        const duration = 2000;
        const start = performance.now();

        function update(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            // Ease-out cubic
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(eased * target);
            el.textContent = prefix + current.toLocaleString() + suffix;
            if (progress < 1) requestAnimationFrame(update);
        }

        requestAnimationFrame(update);
    }

    // ──────────────────────────────────────────
    // 4. Dark Mode Toggle
    // ──────────────────────────────────────────
    function initDarkMode() {
        const toggle = document.getElementById('theme-toggle');
        if (!toggle) return;

        const saved = localStorage.getItem('sc-theme');
        if (saved) {
            document.documentElement.setAttribute('data-theme', saved);
            updateToggleIcon(toggle, saved);
        }

        toggle.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme');
            const next = current === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('sc-theme', next);
            updateToggleIcon(toggle, next);
        });
    }

    function updateToggleIcon(btn, theme) {
        btn.innerHTML = theme === 'light'
            ? '<i class="fas fa-moon"></i>'
            : '<i class="fas fa-sun"></i>';
    }

    // ──────────────────────────────────────────
    // 5. Mobile Navigation Toggle
    // ──────────────────────────────────────────
    function initMobileNav() {
        const toggle = document.getElementById('mobile-nav-toggle');
        const links = document.getElementById('nav-links');
        if (!toggle || !links) return;

        toggle.addEventListener('click', () => {
            links.classList.toggle('open');
            const isOpen = links.classList.contains('open');
            toggle.innerHTML = isOpen
                ? '<i class="fas fa-times"></i>'
                : '<i class="fas fa-bars"></i>';
        });

        // Close on link click
        links.querySelectorAll('a').forEach((a) => {
            a.addEventListener('click', () => {
                links.classList.remove('open');
                toggle.innerHTML = '<i class="fas fa-bars"></i>';
            });
        });
    }

    // ──────────────────────────────────────────
    // 6. Button Ripple Effect
    // ──────────────────────────────────────────
    function initRipple() {
        document.querySelectorAll('.btn, .btn-submit, .nav-cta').forEach((btn) => {
            btn.addEventListener('click', function (e) {
                const rect = this.getBoundingClientRect();
                const ripple = document.createElement('span');
                ripple.className = 'ripple';
                const size = Math.max(rect.width, rect.height);
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = e.clientX - rect.left - size / 2 + 'px';
                ripple.style.top = e.clientY - rect.top - size / 2 + 'px';
                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        });
    }

    // ──────────────────────────────────────────
    // 7. Active Nav Link Highlighting
    // ──────────────────────────────────────────
    function initActiveNav() {
        const path = window.location.pathname;
        document.querySelectorAll('.nav-link').forEach((link) => {
            if (link.getAttribute('href') === path) {
                link.classList.add('active');
            }
        });
    }

    // ──────────────────────────────────────────
    // 8. Cursor-Follow Glow Effect on Cards
    // ──────────────────────────────────────────
    function initCardGlow() {
        document.querySelectorAll('.glass-card').forEach((card) => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;
                card.style.setProperty('--mouse-x', x + '%');
                card.style.setProperty('--mouse-y', y + '%');
            });
        });
    }

    // ──────────────────────────────────────────
    // 9. Progress Rings (Student Dashboard)
    // ──────────────────────────────────────────
    function initProgressRings() {
        document.querySelectorAll('.progress-ring-fill').forEach((ring) => {
            const pct = parseFloat(ring.getAttribute('data-progress') || 0);
            const circumference = parseFloat(ring.getAttribute('data-circumference'));
            const offset = circumference - (pct / 100) * circumference;

            // Start fully offset, will animate
            ring.style.strokeDasharray = circumference;
            ring.style.strokeDashoffset = circumference;

            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            setTimeout(() => {
                                ring.style.strokeDashoffset = offset;
                            }, 300);
                            observer.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.3 }
            );

            observer.observe(ring);
        });
    }

    // ──────────────────────────────────────────
    // 10. Auto-hide alerts
    // ──────────────────────────────────────────
    function initAlerts() {
        document.querySelectorAll('.alert:not(.alert-error)').forEach((alert) => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s, transform 0.5s';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
    }

    // ──────────────────────────────────────────
    // 11. Typing Animation
    // ──────────────────────────────────────────
    function initTyping() {
        const el = document.querySelector('[data-typing]');
        if (!el) return;

        const words = el.getAttribute('data-typing').split('|');
        let wordIdx = 0;
        let charIdx = 0;
        let isDeleting = false;
        let pause = false;

        function type() {
            const word = words[wordIdx];

            if (pause) {
                pause = false;
                setTimeout(type, 1500);
                return;
            }

            if (!isDeleting) {
                el.textContent = word.substring(0, charIdx + 1);
                charIdx++;
                if (charIdx === word.length) {
                    isDeleting = true;
                    pause = true;
                }
            } else {
                el.textContent = word.substring(0, charIdx - 1);
                charIdx--;
                if (charIdx === 0) {
                    isDeleting = false;
                    wordIdx = (wordIdx + 1) % words.length;
                }
            }

            const speed = isDeleting ? 40 : 80;
            setTimeout(type, speed);
        }

        setTimeout(type, 500);
    }

    // ──────────────────────────────────────────
    // Master Init
    // ──────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        // Particles (only on pages that have the canvas)
        new ParticleField('particle-canvas');

        initScrollReveal();
        initCounters();
        initDarkMode();
        initMobileNav();
        initRipple();
        initActiveNav();
        initCardGlow();
        initProgressRings();
        initAlerts();
        initTyping();

        console.log('%c✨ SmartCampus Premium Engine Loaded', 'color: #818cf8; font-weight: bold; font-size: 14px;');
    });
})();
