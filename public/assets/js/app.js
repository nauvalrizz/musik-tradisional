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

// ================= v18 — MEDAN SINYAL =================
// Titik-titik perunggu yang menjauh & menyala saat kursor dekat,
// garis konstelasi + jejak halus. (ala porto, palet MuSantara)
(function () {
    var canvas = document.getElementById('signal-field');
    if (!canvas) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var ctx = canvas.getContext('2d');
    var W = 0, H = 0, dpr = 1;
    var points = [], trail = [], bursts = [];
    var target = { x: window.innerWidth * 0.5, y: window.innerHeight * 0.4 };
    var pos = { x: target.x, y: target.y };
    var visible = false, lastMove = 0;
    var BRONZE = '201,151,63', BRONZE_HI = '238,196,116';

    function seed() {
        points = [];
        var spacing = Math.max(120, Math.min(165, W / 9));
        var r, c;
        for (r = 0; r * spacing < H + spacing; r++) {
            for (c = 0; c * spacing < W + spacing; c++) {
                var jx = (Math.sin(c * 12.9898 + r * 78.233) * 43758.5453 % 1) * 26;
                var jy = (Math.sin(c * 39.346 + r * 11.135) * 24634.6345 % 1) * 26;
                points.push({ bx: c * spacing + spacing * 0.3 + jx, by: r * spacing + spacing * 0.3 + jy, c: c, r: r });
            }
        }
    }
    function resize() {
        W = window.innerWidth; H = window.innerHeight;
        dpr = Math.min(2, window.devicePixelRatio || 1);
        canvas.width = Math.round(W * dpr); canvas.height = Math.round(H * dpr);
        canvas.style.width = W + 'px'; canvas.style.height = H + 'px';
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        seed();
    }

    document.addEventListener('pointermove', function (e) {
        target.x = e.clientX; target.y = e.clientY;
        visible = true; lastMove = performance.now();
        trail.unshift({ x: target.x, y: target.y, life: 1 });
        if (trail.length > 12) trail.length = 12;
    }, { passive: true });
    document.addEventListener('pointerdown', function (e) {
        target.x = e.clientX; target.y = e.clientY;
        visible = true; lastMove = performance.now();
        bursts.push({ x: target.x, y: target.y, life: 1 });
        if (bursts.length > 5) bursts.shift();
    }, { passive: true });
    document.addEventListener('pointerleave', function () { visible = false; });

    function draw(t) {
        ctx.clearRect(0, 0, W, H);
        pos.x += (target.x - pos.x) * 0.14;
        pos.y += (target.y - pos.y) * 0.14;
        var active = visible && (t - lastMove < 2200);
        var radius = active ? 210 : 110;
        var i, j, p, dx, dy, d, pull, x, y;
        var rp = [];

        for (i = 0; i < points.length; i++) {
            p = points[i];
            x = p.bx + Math.sin(t * 0.0004 + i * 1.7) * 5;
            y = p.by + Math.cos(t * 0.00033 + i * 2.3) * 5;
            dx = x - pos.x; dy = y - pos.y;
            d = Math.sqrt(dx * dx + dy * dy) || 1;
            pull = Math.max(0, 1 - d / radius);
            if (active && pull > 0) { x += (dx / d) * pull * 20; y += (dy / d) * pull * 20; }
            rp.push({ x: x, y: y, pull: pull, c: p.c, r: p.r });
        }

        ctx.lineWidth = 1;
        for (i = 0; i < rp.length; i++) {
            for (j = i + 1; j < rp.length; j++) {
                var a = rp[i], b = rp[j];
                if (Math.abs(a.c - b.c) > 1 || Math.abs(a.r - b.r) > 1) continue;
                var ddx = b.x - a.x, ddy = b.y - a.y;
                if (ddx * ddx + ddy * ddy > 170 * 170) continue;
                var glow = Math.max(a.pull, b.pull);
                ctx.strokeStyle = 'rgba(' + BRONZE + ',' + (0.05 + glow * 0.24).toFixed(3) + ')';
                ctx.beginPath(); ctx.moveTo(a.x, a.y); ctx.lineTo(b.x, b.y); ctx.stroke();
            }
        }
        for (i = 0; i < rp.length; i++) {
            p = rp[i];
            var sz = 1.4 + p.pull * 2.6;
            ctx.fillStyle = 'rgba(' + (p.pull > 0.35 ? BRONZE_HI : BRONZE) + ',' + (0.22 + p.pull * 0.6).toFixed(3) + ')';
            ctx.beginPath(); ctx.arc(p.x, p.y, sz, 0, 6.2832); ctx.fill();
        }
        if (trail.length > 1) {
            ctx.beginPath(); ctx.moveTo(trail[0].x, trail[0].y);
            for (i = 1; i < trail.length; i++) {
                var pr = trail[i - 1], tr = trail[i];
                ctx.quadraticCurveTo(pr.x, pr.y, (pr.x + tr.x) / 2, (pr.y + tr.y) / 2);
            }
            ctx.strokeStyle = 'rgba(' + BRONZE_HI + ',0.28)';
            ctx.lineWidth = 1.4; ctx.stroke();
        }
        for (i = 0; i < trail.length; i++) {
            tr = trail[i]; tr.life *= 0.92;
            ctx.fillStyle = 'rgba(' + BRONZE + ',' + (tr.life * 0.32).toFixed(3) + ')';
            ctx.beginPath(); ctx.arc(tr.x, tr.y, 2 + i * 0.5, 0, 6.2832); ctx.fill();
        }
        for (i = bursts.length - 1; i >= 0; i--) {
            var bu = bursts[i]; bu.life *= 0.94;
            if (bu.life < 0.02) { bursts.splice(i, 1); continue; }
            ctx.strokeStyle = 'rgba(' + BRONZE_HI + ',' + (bu.life * 0.5).toFixed(3) + ')';
            ctx.lineWidth = 1.5;
            ctx.beginPath(); ctx.arc(bu.x, bu.y, (1 - bu.life) * 90 + 8, 0, 6.2832); ctx.stroke();
        }
        requestAnimationFrame(draw);
    }

    resize();
    window.addEventListener('resize', resize);
    requestAnimationFrame(draw);
})();
