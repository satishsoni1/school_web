/* PPGMIS "Modern" frontend theme — shared interactions (vanilla JS, no framework). */
(function () {
    'use strict';

    /* Sticky header shadow */
    var header = document.querySelector('.pg-header');
    if (header) {
        var onScroll = function () {
            header.classList.toggle('is-scrolled', window.scrollY > 8);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    /* Mobile off-canvas menu */
    var toggle = document.querySelector('.pg-nav-toggle');
    var panel = document.querySelector('.pg-mobile-panel');
    var backdrop = document.querySelector('.pg-mobile-backdrop');
    var closeBtn = document.querySelector('.pg-mobile-close');

    function openMenu() {
        if (!panel) { return; }
        panel.classList.add('is-open');
        backdrop.classList.add('is-open');
        toggle.classList.add('is-active');
        toggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }
    function closeMenu() {
        if (!panel) { return; }
        panel.classList.remove('is-open');
        backdrop.classList.remove('is-open');
        toggle.classList.remove('is-active');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }
    if (toggle) {
        toggle.addEventListener('click', function () {
            panel.classList.contains('is-open') ? closeMenu() : openMenu();
        });
    }
    if (closeBtn) { closeBtn.addEventListener('click', closeMenu); }
    if (backdrop) { backdrop.addEventListener('click', closeMenu); }
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closeMenu(); }
    });

    /* Hero slider — lightweight fade carousel over the CMS slider images */
    var hero = document.querySelector('.pg-hero');
    if (hero) {
        var slides = Array.prototype.slice.call(hero.querySelectorAll('.pg-hero-slide'));
        if (slides.length > 1) {
            var dotsWrap = hero.querySelector('.pg-hero-dots');
            var current = 0;
            var timer = null;

            var dots = slides.map(function (_, i) {
                var b = document.createElement('button');
                b.type = 'button';
                b.setAttribute('aria-label', 'Go to slide ' + (i + 1));
                if (i === 0) { b.classList.add('is-active'); }
                b.addEventListener('click', function () { show(i); restart(); });
                if (dotsWrap) { dotsWrap.appendChild(b); }
                return b;
            });

            function show(index) {
                slides[current].classList.remove('is-active');
                dots[current] && dots[current].classList.remove('is-active');
                current = (index + slides.length) % slides.length;
                slides[current].classList.add('is-active');
                dots[current] && dots[current].classList.add('is-active');
            }
            function next() { show(current + 1); }
            function prev() { show(current - 1); }
            function restart() {
                if (timer) { clearInterval(timer); }
                timer = setInterval(next, 6000);
            }

            var nextBtn = hero.querySelector('.pg-hero-arrow--next');
            var prevBtn = hero.querySelector('.pg-hero-arrow--prev');
            if (nextBtn) { nextBtn.addEventListener('click', function () { next(); restart(); }); }
            if (prevBtn) { prevBtn.addEventListener('click', function () { prev(); restart(); }); }

            if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                restart();
            }
        }
    }

    /* Scroll-to-top button + footer "Back to top" link */
    var scrollTop = document.querySelector('.pg-scroll-top');
    if (scrollTop) {
        window.addEventListener('scroll', function () {
            scrollTop.classList.toggle('is-visible', window.scrollY > 480);
        }, { passive: true });
        scrollTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
    var footerToTop = document.getElementById('pgFooterToTop');
    if (footerToTop) {
        footerToTop.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* Announcement popup — shows once per (title/text/image/link) version.
       Dismissing it is remembered per-browser via localStorage, keyed by a hash
       of the content, so editing the announcement later shows it again even to
       visitors who already closed the previous one. */
    var announceOverlay = document.getElementById('pgAnnounceOverlay');
    if (announceOverlay) {
        var STORAGE_KEY = 'pg_announce_dismissed';
        var hash = announceOverlay.getAttribute('data-hash');
        var dismissed = null;
        try { dismissed = window.localStorage.getItem(STORAGE_KEY); } catch (e) { dismissed = null; }

        function closeAnnounce() {
            announceOverlay.classList.remove('is-open');
            document.body.style.overflow = '';
            try { window.localStorage.setItem(STORAGE_KEY, hash); } catch (e) { /* ignore */ }
        }

        if (dismissed !== hash) {
            window.setTimeout(function () {
                announceOverlay.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            }, 500);
        }

        var closeBtnEl = document.getElementById('pgAnnounceClose');
        var dismissBtnEl = document.getElementById('pgAnnounceDismiss');
        if (closeBtnEl) { closeBtnEl.addEventListener('click', closeAnnounce); }
        if (dismissBtnEl) { dismissBtnEl.addEventListener('click', closeAnnounce); }
        announceOverlay.addEventListener('click', function (e) {
            if (e.target === announceOverlay) { closeAnnounce(); }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && announceOverlay.classList.contains('is-open')) { closeAnnounce(); }
        });
    }
})();
