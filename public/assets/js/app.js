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

// ================= v15 — CAHAYA LENTERA =================
// Cahaya hangat mengikuti kursor dengan lerp halus.
(function () {
    var glow = document.querySelector('.cursor-glow');
    if (!glow) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    if (window.matchMedia('(hover: none)').matches) return;

    var tx = innerWidth / 2, ty = innerHeight / 3, cx = tx, cy = ty, raf = null, shown = false;

    document.addEventListener('pointermove', function (e) {
        tx = e.clientX; ty = e.clientY;
        if (!shown) { shown = true; glow.classList.add('on'); cx = tx; cy = ty; }
        if (!raf) raf = requestAnimationFrame(tick);
    }, { passive: true });

    function tick() {
        cx += (tx - cx) * 0.08;
        cy += (ty - cy) * 0.08;
        glow.style.translate = cx.toFixed(1) + 'px ' + cy.toFixed(1) + 'px';
        if (Math.abs(tx - cx) > 0.4 || Math.abs(ty - cy) > 0.4) {
            raf = requestAnimationFrame(tick);
        } else { raf = null; }
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
