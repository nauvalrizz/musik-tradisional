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

// ================= OMBAK PARALLAX =================
// Lapisan ombak bergeser halus mengikuti kursor (beda kedalaman
// tiap lapis). Hanya transform — ringan untuk GPU.
(function () {
    var layers = document.querySelectorAll('.ombak-layer');
    if (!layers.length) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    if (window.matchMedia('(hover: none)').matches) return;

    var tx = 0, ty = 0, cx = 0, cy = 0, raf = null;

    document.addEventListener('pointermove', function (e) {
        tx = e.clientX / window.innerWidth - 0.5;
        ty = e.clientY / window.innerHeight - 0.5;
        if (!raf) raf = requestAnimationFrame(tick);
    }, { passive: true });

    function tick() {
        cx += (tx - cx) * 0.045;
        cy += (ty - cy) * 0.045;
        layers.forEach(function (layer, i) {
            var depth = (i + 1) * 26;
            layer.style.translate = (cx * depth).toFixed(1) + 'px ' + (cy * depth * 0.6).toFixed(1) + 'px';
        });
        if (Math.abs(tx - cx) > 0.0005 || Math.abs(ty - cy) > 0.0005) {
            raf = requestAnimationFrame(tick);
        } else {
            raf = null;
        }
    }
})();

// ================= v15 — PARALLAX SCROLL SINEMATIK =================
// Latar batik & ombak tertinggal halus saat scroll -> rasa kedalaman.
(function () {
    var batik = document.querySelector('.batik-bg');
    var ombak = document.querySelector('.ombak-bg');
    if (!batik && !ombak) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var target = window.scrollY || 0, cur = target, ticking = false;

    function update() {
        cur += (target - cur) * 0.07;
        if (batik) batik.style.translate = '0 ' + (cur * 0.05).toFixed(1) + 'px';
        if (ombak) ombak.style.translate = '0 ' + (cur * 0.11).toFixed(1) + 'px';
        if (Math.abs(target - cur) > 0.4) {
            requestAnimationFrame(update);
        } else { ticking = false; }
    }
    window.addEventListener('scroll', function () {
        target = window.scrollY || 0;
        if (!ticking) { ticking = true; requestAnimationFrame(update); }
    }, { passive: true });
})();

// ================= v19 — AWAN TERSEBAR =================
// Satu motif awan diduplikat acak: posisi, ukuran, rotasi, opacity.
(function () {
    var bg = document.querySelector('.awan-bg');
    if (!bg) return;
    var seed = 11;
    function rnd() { seed = (seed * 16807) % 2147483647; return (seed - 1) / 2147483646; }
    var N = 13;
    for (var i = 0; i < N; i++) {
        var el = document.createElement('span');
        el.className = 'awan';
        var w = 200 + rnd() * 340;
        el.style.width = w.toFixed(0) + 'px';
        el.style.height = (w * 0.475).toFixed(0) + 'px';
        el.style.left = (rnd() * 100).toFixed(1) + '%';
        el.style.top = (rnd() * 100).toFixed(1) + '%';
        el.style.opacity = (0.05 + rnd() * 0.08).toFixed(2);
        el.style.transform = 'translate(-50%,-50%) rotate(' + ((rnd() * 40) - 20).toFixed(1) + 'deg)' + (rnd() > 0.5 ? ' scaleX(-1)' : '');
        el.style.animationDuration = (90 + rnd() * 80).toFixed(0) + 's';
        el.style.animationDelay = (-rnd() * 60).toFixed(0) + 's';
        bg.appendChild(el);
    }
})();

// ================= v19 — RIAK BUNYI =================
// Kursor meninggalkan riak lingkaran mengembang seperti bunyi gong.
// Nempel di background, khas MuSantara (bukan tiruan porto).
(function () {
    var canvas = document.getElementById('ripple-field');
    if (!canvas) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    if (window.matchMedia('(hover: none)').matches) return;

    var ctx = canvas.getContext('2d');
    var W = 0, H = 0;
    var ripples = [];
    var lastX = -999, lastY = -999;

    function resize() {
        W = window.innerWidth; H = window.innerHeight;
        var dpr = Math.min(2, window.devicePixelRatio || 1);
        canvas.width = Math.round(W * dpr); canvas.height = Math.round(H * dpr);
        canvas.style.width = W + 'px'; canvas.style.height = H + 'px';
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    }

    function spawn(x, y, big) {
        var n = big ? 3 : 1;
        for (var i = 0; i < n; i++) {
            ripples.push({ x: x, y: y, r: 6 + i * 10, a: (big ? 0.4 : 0.28) - i * 0.09 });
        }
        if (ripples.length > 40) ripples.splice(0, ripples.length - 40);
    }

    document.addEventListener('pointermove', function (e) {
        var dx = e.clientX - lastX, dy = e.clientY - lastY;
        if (dx * dx + dy * dy > 70 * 70) {
            lastX = e.clientX; lastY = e.clientY;
            spawn(e.clientX, e.clientY, false);
        }
    }, { passive: true });
    document.addEventListener('pointerdown', function (e) {
        spawn(e.clientX, e.clientY, true);
    }, { passive: true });

    (function draw() {
        ctx.clearRect(0, 0, W, H);
        for (var i = ripples.length - 1; i >= 0; i--) {
            var rp = ripples[i];
            rp.r += 1.7; rp.a *= 0.965;
            if (rp.a < 0.012) { ripples.splice(i, 1); continue; }
            ctx.strokeStyle = 'rgba(201,151,63,' + rp.a.toFixed(3) + ')';
            ctx.lineWidth = 1.2;
            ctx.beginPath(); ctx.arc(rp.x, rp.y, rp.r, 0, 6.2832); ctx.stroke();
        }
        requestAnimationFrame(draw);
    })();

    resize();
    window.addEventListener('resize', resize);
})();
