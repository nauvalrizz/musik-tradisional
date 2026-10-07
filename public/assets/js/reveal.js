/* Reveal on scroll untuk [data-reveal] (pengganti stagger generik). */
document.addEventListener('DOMContentLoaded', function () {
    var els = document.querySelectorAll('[data-reveal]');
    if (!els.length) return;

    function show(el) { el.classList.add('in'); }

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
