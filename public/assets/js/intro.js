// Intro interaktif: kata bergaris bawah menampilkan definisinya.
document.addEventListener('DOMContentLoaded', () => {
    const pop = document.getElementById('defPop');
    if (!pop) return;
    const words = document.querySelectorAll('.w-def');

    const hide = () => {
        pop.hidden = true;
        words.forEach(w => w.classList.remove('active'));
    };
    words.forEach(w => {
        w.addEventListener('click', (e) => {
            e.stopPropagation();
            const wasActive = w.classList.contains('active');
            hide();
            if (wasActive) return;
            w.classList.add('active');
            pop.textContent = w.dataset.def || '';
            pop.hidden = false;
            const r = w.getBoundingClientRect();
            const host = w.closest('.hero-full, .intro');
            const hr = (host || document.body).getBoundingClientRect();
            pop.style.left = (r.left - hr.left + r.width / 2) + 'px';
            pop.style.top = (r.bottom - hr.top + 12) + 'px';
        });
    });
    document.addEventListener('click', hide);
});
