/* Reveal on scroll untuk [data-reveal].
   Anti-stuck: state tersembunyi hanya aktif kalau JS jalan (html.js),
   plus fallback yang memaksa semua tampil setelah 2.5 detik. */
document.documentElement.classList.add('js');

document.addEventListener('DOMContentLoaded', function () {
    var els = document.querySelectorAll('[data-reveal]');
    if (!els.length) return;

    function show(el) { el.classList.add('in'); }

    /* Stagger: anak [data-stagger] muncul berurutan. */
    document.querySelectorAll('[data-stagger]').forEach(function (group) {
        var kids = group.querySelectorAll('[data-reveal]');
        kids.forEach(function (kid, i) {
            kid.style.transitionDelay = Math.min(i * 90, 900) + 'ms';
        });
    });

    /* Fallback: tidak ada yang boleh stuck tak terlihat. */
    setTimeout(function () {
        els.forEach(show);
    }, 2500);

    if (!('IntersectionObserver' in window) ||
        window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        els.forEach(show);
        return;
    }

    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
            if (en.isIntersecting) {
                show(en.target);
                io.unobserve(en.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -36px 0px' });

    els.forEach(function (el) { io.observe(el); });
});
