(() => {
    // ── Starfield ──────────────────────────────────────
    const canvas = document.createElement('canvas');
    canvas.id = 'starfield';
    document.body.prepend(canvas);
    const ctx = canvas.getContext('2d');
    let stars = [];

    function resize() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    function initStars(count = 180) {
        stars = Array.from({ length: count }, () => ({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            r: Math.random() * 1.2 + 0.2,
            alpha: Math.random() * 0.6 + 0.1,
            speed: Math.random() * 0.3 + 0.05,
            twinkle: Math.random() * Math.PI * 2,
        }));
    }

    function drawStars(ts) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        stars.forEach(s => {
            s.twinkle += 0.012;
            const a = s.alpha * (0.6 + 0.4 * Math.sin(s.twinkle));
            ctx.beginPath();
            ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(232,244,255,${a})`;
            ctx.fill();
            s.y -= s.speed;
            if (s.y < -2) { s.y = canvas.height + 2; s.x = Math.random() * canvas.width; }
        });
        requestAnimationFrame(drawStars);
    }

    resize();
    initStars();
    requestAnimationFrame(drawStars);
    window.addEventListener('resize', () => { resize(); initStars(); }, { passive: true });

    // ── Nav scroll ──────────────────────────────────────
    const nav = document.getElementById('nav');
    if (nav) {
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 20);
        }, { passive: true });
    }

    // ── Mobile menu ─────────────────────────────────────
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', () => {
            const isOpen = navMenu.classList.toggle('open');
            navToggle.setAttribute('aria-expanded', String(isOpen));
        });
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 860) {
                    navMenu.classList.remove('open');
                    navToggle.setAttribute('aria-expanded', 'false');
                }
            });
        });
    }

    // ── Modal ───────────────────────────────────────────
    function openModal(id) {
        const overlay = document.getElementById('modal-' + id);
        if (!overlay) return;
        overlay.classList.add('open');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        overlay.querySelector('.modal-close')?.focus();
    }
    function closeModal(id) {
        const overlay = document.getElementById('modal-' + id);
        if (!overlay) return;
        overlay.classList.remove('open');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }
    document.querySelectorAll('[data-open-modal]').forEach(btn => {
        btn.addEventListener('click', () => openModal(btn.dataset.openModal));
    });
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.querySelector('.modal-close')?.addEventListener('click', () => closeModal(overlay.id.replace('modal-', '')));
        overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(overlay.id.replace('modal-', '')); });
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.open').forEach(o => closeModal(o.id.replace('modal-', '')));
    });
    // Auto-open if form was submitted (sent/error state)
    const statusModal = document.querySelector('.modal-overlay .form-status')?.closest('.modal-overlay');
    if (statusModal) openModal(statusModal.id.replace('modal-', ''));

    // ── Smooth scroll ───────────────────────────────────
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', e => {
            const target = document.querySelector(anchor.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ── Reveal on scroll ────────────────────────────────
    const revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => entry.target.classList.add('in'), (index % 5) * 90);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        revealEls.forEach(el => observer.observe(el));
    } else {
        revealEls.forEach(el => el.classList.add('in'));
    }

    // ── Live telemetry simulation ────────────────────────
    const speedEl = document.getElementById('telemSpeed');
    const altEl   = document.getElementById('telemAlt');
    const barEls  = document.querySelectorAll('.telem-bar');

    let speed = 17851;
    let alt   = 248.3;
    let activeBar = 0;

    if (speedEl && altEl) {
        setInterval(() => {
            speed += Math.floor(Math.random() * 12 - 3);
            alt   += (Math.random() * 0.6 - 0.15);
            speedEl.textContent = speed.toLocaleString();
            altEl.textContent   = alt.toFixed(1);
        }, 1400);
    }

    if (barEls.length) {
        const bars = [72, 58, 65, 80, 74, 90, 68, 82, 77, 88, 93, 86];
        barEls.forEach((b, i) => {
            b.style.height = bars[i % bars.length] + '%';
        });
        setInterval(() => {
            barEls[activeBar].classList.remove('active');
            activeBar = (activeBar + 1) % barEls.length;
            barEls[activeBar].classList.add('active');
        }, 600);
        barEls[activeBar].classList.add('active');
    }

    // ── Animated stat counters ────────────────────────────
    function animateCount(el, target, suffix = '', duration = 1800) {
        const start = performance.now();
        const update = (now) => {
            const t = Math.min((now - start) / duration, 1);
            const ease = 1 - Math.pow(1 - t, 3);
            el.textContent = Math.round(target * ease) + suffix;
            if (t < 1) requestAnimationFrame(update);
        };
        requestAnimationFrame(update);
    }

    const statNums = document.querySelectorAll('.stat-num[data-count]');
    if (statNums.length && 'IntersectionObserver' in window) {
        const countObs = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    animateCount(el, +el.dataset.count, el.dataset.suffix || '');
                    countObs.unobserve(el);
                }
            });
        }, { threshold: 0.5 });
        statNums.forEach(el => countObs.observe(el));
    }
})();
