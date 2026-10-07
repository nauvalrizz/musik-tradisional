// Hujan not balok: kursor menabur simbol notasi musik yang melayang.
document.addEventListener('DOMContentLoaded', () => {
    if (!window.matchMedia('(pointer: fine)').matches) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const GLYPHS = ['\u266A', '\u266B', '\uD834\uDD5F', '\uD834\uDD60', '\u266A', '\u266B'];
    const MAX = 24;
    let last = 0, live = 0;

    document.addEventListener('pointermove', (e) => {
        const now = performance.now();
        if (now - last < 80 || live >= MAX) return;
        last = now;

        const s = document.createElement('span');
        s.className = 'note-trail';
        s.textContent = GLYPHS[Math.floor(Math.random() * GLYPHS.length)];
        s.style.left = e.clientX + 'px';
        s.style.top = e.clientY + 'px';
        s.style.fontSize = (15 + Math.random() * 13) + 'px';
        s.style.setProperty('--tilt', (Math.random() * 30 - 15) + 'deg');
        document.body.appendChild(s);
        live++;
        s.addEventListener('animationend', () => { s.remove(); live--; });
    }, { passive: true });
});
