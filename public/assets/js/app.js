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
(function () {
    var nav = document.getElementById('mainNav');
    if (!nav) return;
    function onScroll() {
        nav.classList.toggle('scrolled', window.scrollY > 40);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
})();



// ================= v21 — AWAN & ANGIN =================
// Awan PNG transparan: dibuat setelah gambar preload, fade-in halus.
// Minggir lembut saat kursor dekat seperti tertiup angin.
(function () {
    var bg = document.querySelector('.awan-bg');
    if (!bg) return;
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function build() {
        var seed = 21;
        function rnd() { seed = (seed * 16807) % 2147483647; return (seed - 1) / 2147483646; }

        var clouds = [];
        var cols = 4, rows = 2, r, c;
        for (r = 0; r < rows; r++) {
            for (c = 0; c < cols; c++) {
                var el = document.createElement('span');
                el.className = 'awan';
                var w = 170 + rnd() * 150;
                el.style.width = w.toFixed(0) + 'px';
                el.style.height = (w * 0.475).toFixed(0) + 'px';
                var hx = ((c + 0.5) / cols) * 100 + (rnd() * 10 - 5);
                var hy = ((r + 0.5) / rows) * 100 + (rnd() * 12 - 6);
                el.style.left = hx.toFixed(1) + '%';
                el.style.top = hy.toFixed(1) + '%';
                el.style.setProperty('--o', (0.05 + rnd() * 0.05).toFixed(2));
                var rot = ((rnd() * 30) - 15).toFixed(1);
                var flip = rnd() > 0.5 ? ' scaleX(-1)' : '';
                clouds.push({ el: el, rot: rot, flip: flip, seed: rnd() * 6.28, x: 0, y: 0 });
                bg.appendChild(el);
            }
        }
        // fade-in berurutan setelah semua menempel
        requestAnimationFrame(function () {
            clouds.forEach(function (cl, i) {
                setTimeout(function () { cl.el.classList.add('ready'); }, 150 + i * 120);
            });
        });

        if (reduceMotion || window.matchMedia('(hover: none)').matches) {
            clouds.forEach(function (cl) { cl.el.classList.add('ready'); });
            return;
        }

        var mx = -9999, my = -9999;
        document.addEventListener('pointermove', function (e) {
            mx = e.clientX; my = e.clientY;
        }, { passive: true });
        document.addEventListener('pointerleave', function () { mx = -9999; my = -9999; });

        function homePos() {
            for (var k = 0; k < clouds.length; k++) {
                var cl = clouds[k];
                var rect = cl.el.getBoundingClientRect();
                cl.hx = rect.left + rect.width / 2;
                cl.hy = rect.top + rect.height / 2;
            }
        }
        homePos();
        window.addEventListener('resize', homePos);

        function frame(t) {
            for (var k = 0; k < clouds.length; k++) {
                var cl = clouds[k];
                var dx = cl.hx - mx, dy = cl.hy - my;
                var d = Math.sqrt(dx * dx + dy * dy) || 1;
                var R = 260, push = 0;
                if (d < R) push = (1 - d / R) * 52;
                var tx = (dx / d) * push, ty = (dy / d) * push;
                tx += Math.sin(t * 0.00021 + cl.seed) * 9;
                ty += Math.cos(t * 0.00017 + cl.seed * 1.6) * 7;
                cl.x += (tx - cl.x) * 0.045;
                cl.y += (ty - cl.y) * 0.045;
                cl.el.style.transform = 'translate(-50%,-50%) translate(' +
                    cl.x.toFixed(1) + 'px,' + cl.y.toFixed(1) + 'px) rotate(' + cl.rot + 'deg)' + cl.flip;
            }
            requestAnimationFrame(frame);
        }
        requestAnimationFrame(frame);
    }

    var pre = new Image();
    pre.onload = build;
    pre.onerror = build;
    pre.src = '/assets/img/awan.png?v=21';
    // pengaman: jangan nunggu selamanya
    setTimeout(function () { if (!bg.hasChildNodes()) build(); }, 3000);
})();
