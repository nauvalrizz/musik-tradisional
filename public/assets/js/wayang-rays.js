/* v96 — Wayang shadow + light rays */
(function() {
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduceMotion) return;

    var hero = document.querySelector('.gallery-hero');
    if (!hero) return;

    /* 1. WAYANG SHADOW — siluet gunungan */
    var shadow = document.createElement('div');
    shadow.className = 'wayang-shadow';
    shadow.setAttribute('aria-hidden', 'true');
    // Gunungan SVG (simplified)
    shadow.innerHTML =
        '<svg viewBox="0 0 400 600" preserveAspectRatio="xMidYMax meet">' +
        '<path d="M200,20 C260,80 300,160 300,260 C300,340 260,420 200,580 ' +
        'C140,420 100,340 100,260 C100,160 140,80 200,20 Z" />' +
        '<path d="M200,60 C230,110 250,170 250,250 C250,310 230,370 200,480 ' +
        'C170,370 150,310 150,250 C150,170 170,110 200,60 Z" fill-opacity="0.5"/>' +
        '<circle cx="200" cy="150" r="18" fill-opacity="0.6"/>' +
        '<path d="M185,200 L215,200 L200,260 Z" fill-opacity="0.4"/>' +
        '</svg>';
    hero.insertBefore(shadow, hero.firstChild);

    /* 2. LIGHT RAYS */
    var rays = document.createElement('div');
    rays.className = 'light-rays';
    rays.setAttribute('aria-hidden', 'true');
    rays.innerHTML = '<i></i><i></i><i></i>';
    hero.insertBefore(rays, hero.firstChild);

    /* Gerak halus ngikutin mouse (dalang effect) */
    var targetX = 0, curX = 0;
    document.addEventListener('mousemove', function(e) {
        targetX = (e.clientX / window.innerWidth - 0.5) * 30;
    });
    (function sway() {
        curX += (targetX - curX) * 0.03;
        var t = Date.now() / 1000;
        var swayY = Math.sin(t * 0.4) * 8;
        shadow.style.transform = 'translateX(' + curX.toFixed(1) + 'px) translateY(' + swayY.toFixed(1) + 'px) rotate(' + (curX * 0.05).toFixed(2) + 'deg)';
        requestAnimationFrame(sway);
    })();
})();
