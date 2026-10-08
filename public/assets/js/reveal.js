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

    /* Toggle dua arah dengan hysteresis anti-flicker:
       masuk saat 15% terlihat, keluar saat sudah <5% (tidak kedip di tepi) */
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
            var el = en.target;
            if (en.intersectionRatio >= 0.15) {
                el.classList.add('in');
            } else if (en.intersectionRatio < 0.05) {
                el.classList.remove('in');
            }
            /* di antara 5-15%: biarkan state terakhir (anti flicker) */
        });
    }, { threshold: [0, 0.05, 0.15, 0.5, 1], rootMargin: '0px 0px -8% 0px' });

    els.forEach(function (el) { io.observe(el); });
});
