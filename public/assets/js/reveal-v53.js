/* Reveal on scroll untuk [data-reveal] — animasi dua arah (masuk & keluar).
   Elemen tampil saat masuk viewport, memudar saat keluar.
   Anti-stuck: state tersembunyi hanya aktif kalau JS jalan (html.js). */
document.documentElement.classList.add('js');

document.addEventListener('DOMContentLoaded', function () {
    var els = document.querySelectorAll('[data-reveal]');
    if (!els.length) return;

    /* Stagger: anak [data-stagger] muncul berurutan. */
    document.querySelectorAll('[data-stagger]').forEach(function (group) {
        var kids = group.querySelectorAll('[data-reveal]');
        kids.forEach(function (kid, i) {
            kid.style.transitionDelay = Math.min(i * 80, 800) + 'ms';
        });
    });

    if (!('IntersectionObserver' in window) ||
        window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        els.forEach(function (el) { el.classList.add('in'); });
        return;
    }

    /* Masuk saat 15% terlihat, langsung hilang saat <30% (tidak nunggu ketutup) */
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
            var r = en.intersectionRatio;
            if (r >= 0.15) en.target.classList.add('in');
            else if (r < 0.10) en.target.classList.remove('in');
        });
    }, { threshold: [0, 0.10, 0.15, 0.5, 1], rootMargin: '0px 0px -5% 0px' });

    els.forEach(function (el) { io.observe(el); });
});
