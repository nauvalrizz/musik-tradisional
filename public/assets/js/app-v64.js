import './modules/scroll.js';
import './modules/loader.js';
import './modules/search.js';
import initImageModal from './image-modal.js';

// ================= INIT =================
document.addEventListener('DOMContentLoaded', () => {
    initImageModal();
});

// ================= NAVIGATION =================
document.addEventListener('click', function (e) {
    const link = e.target.closest('a');
    if (!link) return;

    const href = link.getAttribute('href');

    if (
        !href ||
        href.startsWith('#') ||
        link.target === '_blank' ||
        link.hasAttribute('download') ||
        link.hasAttribute('data-no-transition')
    ) return;

    if (link.hostname !== window.location.hostname) return;

    e.preventDefault();

    const loader = document.getElementById('topLoader');

    if (loader) {
        loader.style.width = '0%';
        loader.style.transition = 'none';

        setTimeout(() => {
            loader.style.transition = 'width 0.4s ease';
            loader.style.width = '70%';
        }, 10);
    }

    setTimeout(() => {
        if (loader) loader.style.width = '100%';
        window.location.href = href;
    }, 250);
});

// ================= RESET =================
window.addEventListener('load', () => {
    const loader = document.getElementById('topLoader');

    if (loader) {
        loader.style.width = '100%';

        setTimeout(() => {
            loader.style.width = '0%';
        }, 300);
    }

});

// ================= BACK BUTTON FIX =================
window.addEventListener('pageshow', () => {

    const loader = document.getElementById('topLoader');
    if (loader) loader.style.width = '0%';
});

// ================= AOS =================
AOS.init({
    duration: 800,
    once: false
});

setTimeout(() => {
}, 1000);

// ================= TABUHAN PEMBUKA =================
// Hapus overlay intro setelah animasi selesai; pengaman bila
// animationend tak menyala, dan klik untuk lewati.
(function () {
    var t = document.getElementById('tabuhan');
    if (!t) return;
    var done = false;
    function finish() {
        if (done) return;
        done = true;
        try { sessionStorage.setItem('tabuhan_shown', '1'); } catch (e) {}
        if (t.parentNode) t.parentNode.removeChild(t);
    }
    t.addEventListener('animationend', function (e) {
        if (e.target === t) finish();
    });
    t.addEventListener('click', finish);
    setTimeout(finish, 3500);
})();

// ================= NAVBAR SCROLL =================
// v64: set style langsung via JS (bulletproof, ga bergantung CSS class)
(function () {
    var nav = document.getElementById('mainNav');
    if (!nav) return;
    nav.style.transition = 'transform 0.4s cubic-bezier(0.22,1,0.36,1)';
    nav.style.animation = 'none';
    var lastY = window.scrollY || 0;
    var ticking = false;
    var hidden = false;
    function onScroll() {
        var y = window.scrollY || 0;
        nav.classList.toggle('scrolled', y > 40);
        if (y > 120 && y > lastY + 4 && !hidden) {
            nav.style.transform = 'translateY(-110%)';
            hidden = true;
        } else if ((y < lastY - 4 || y <= 120) && hidden) {
            nav.style.transform = 'translateY(0)';
            hidden = false;
        }
        lastY = y;
        ticking = false;
    }
    window.addEventListener('scroll', function () {
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(onScroll);
        }
    }, { passive: true });
    onScroll();
})();

/* v60 — Buku 3D realistis */
(function() {
    var book = document.getElementById('kisah-book');
    if (!book) return;
    var pages = Array.prototype.slice.call(book.querySelectorAll('.book-page'));
    var prev = document.getElementById('book-prev');
    var next = document.getElementById('book-next');
    var current = 0;
    var animating = false;
    var TURN_MS = 1600;

    function render() {
        pages.forEach(function(pg, i) {
            var shouldFlip = i < current;
            if (pg.classList.contains('flipped') !== shouldFlip) {
                pg.classList.toggle('flipped', shouldFlip);
            }
            // z-index hanya di-set saat TIDAK animasi (biar ga kedip)
            if (!animating) {
                pg.style.zIndex = i < current ? i + 1 : (pages.length - i + 10);
            }
        });
        prev.disabled = current === 0 || animating;
        next.disabled = current === pages.length - 1 || animating;
    }

    function go(dir) {
        if (animating) return;
        var target = current + dir;
        if (target < 0 || target >= pages.length) return;
        animating = true;
        current = target;
        render();
        prev.disabled = true;
        next.disabled = true;
        setTimeout(function() {
            animating = false;
            render();
        }, TURN_MS + 60);
    }

    prev.addEventListener('click', function() { go(-1); });
    next.addEventListener('click', function() { go(1); });
    // Swipe untuk HP
    var sx = 0;
    book.addEventListener('touchstart', function(e) {
        sx = e.touches[0].clientX;
    }, { passive: true });
    book.addEventListener('touchend', function(e) {
        var dx = e.changedTouches[0].clientX - sx;
        if (Math.abs(dx) > 50) go(dx < 0 ? 1 : -1);
    }, { passive: true });
    // z-index awal
    pages.forEach(function(pg, i) {
        pg.style.zIndex = pages.length - i + 10;
    });
    render();
})();

/* v56 — timeline sweep */
(function() {
    var track = document.querySelector('.timeline-track');
    if (!track) return;
    var dots = track.querySelectorAll('.tl-dot');
    if (!dots.length) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var sweep = document.createElement('div');
    sweep.className = 'tl-sweep';
    track.appendChild(sweep);

    var dotPos = [];
    function measure() {
        var r = track.getBoundingClientRect();
        dotPos = [];
        dots.forEach(function(d) {
            var dr = d.getBoundingClientRect();
            dotPos.push(dr.left - r.left + dr.width / 2);
        });
    }
    measure();
    window.addEventListener('resize', measure);

    var DURATION = 5000;
    var start = null;
    function tick(ts) {
        if (!start) start = ts;
        var t = ((ts - start) % DURATION) / DURATION;
        var trackW = track.getBoundingClientRect().width;
        var x = t * trackW;
        sweep.style.opacity = (t > 0.02 && t < 0.98) ? '1' : '0';
        sweep.style.left = (x - 40) + 'px';

        dots.forEach(function(d, i) {
            var hit = Math.abs(x - dotPos[i]) < 30;
            d.classList.toggle('hit', hit);
        });
        requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
})();
