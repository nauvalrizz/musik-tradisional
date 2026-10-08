/* v95 — Batik divider: pola kawung yang menggambar dirinya sendiri */
(function() {
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* Pola kawung: 4 elips berpotongan (disederhanakan jadi 3 baris) */
    function kawungSVG(id) {
        var s = '<svg viewBox="0 0 1200 120" class="batik-svg" preserveAspectRatio="xMidYMid meet">';
        s += '<g fill="none" stroke="rgba(201,151,63,0.55)" stroke-width="1.5">';
        // Baris pola kawung
        for (var row = 0; row < 2; row++) {
            for (var i = 0; i < 12; i++) {
                var cx = 50 + i * 100;
                var cy = 30 + row * 60;
                // 4 petal kawung
                s += '<ellipse cx="' + cx + '" cy="' + cy + '" rx="32" ry="20" class="kawung-petal"/>';
                s += '<ellipse cx="' + cx + '" cy="' + cy + '" rx="20" ry="32" class="kawung-petal"/>';
                // Titik tengah
                s += '<circle cx="' + cx + '" cy="' + cy + '" r="3" fill="rgba(201,151,63,0.7)" stroke="none" class="kawung-dot"/>';
            }
        }
        // Garis tepi
        s += '<line x1="0" y1="8" x2="1200" y2="8" class="kawung-line"/>';
        s += '<line x1="0" y1="112" x2="1200" y2="112" class="kawung-line"/>';
        s += '</g></svg>';
        return s;
    }

    // Sisipkan divider di antara sections (setelah tiap section kecuali terakhir)
    var sections = document.querySelectorAll('main section, .content section');
    if (!sections.length) sections = document.querySelectorAll('section');

    var inserted = 0;
    sections.forEach(function(sec, idx) {
        if (idx === sections.length - 1) return;
        // Skip yang sudah ada divider atau section kecil
        if (sec.querySelector('.batik-divider')) return;
        if (inserted >= 4) return; // max 4 divider

        var div = document.createElement('div');
        div.className = 'batik-divider';
        div.setAttribute('aria-hidden', 'true');
        div.innerHTML = kawungSVG();
        sec.after(div);
        inserted++;
    });

    if (reduceMotion) return;

    // Draw-on-scroll: stroke-dashoffset animasi
    document.querySelectorAll('.batik-divider').forEach(function(div) {
        var paths = div.querySelectorAll('.kawung-petal, .kawung-line');
        paths.forEach(function(p) {
            try {
                var len = p.getTotalLength();
                p.style.strokeDasharray = len;
                p.style.strokeDashoffset = len;
                p.style.transition = 'stroke-dashoffset 1.8s cubic-bezier(0.22,1,0.36,1)';
            } catch(e) {}
        });
        var dots = div.querySelectorAll('.kawung-dot');
        dots.forEach(function(d) {
            d.style.opacity = '0';
            d.style.transition = 'opacity 0.6s ease';
        });
    });

    // IntersectionObserver: gambar pas masuk viewport
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function(entries) {
            entries.forEach(function(en) {
                if (!en.isIntersecting) return;
                var div = en.target;
                io.unobserve(div);
                var paths = div.querySelectorAll('.kawung-petal, .kawung-line');
                paths.forEach(function(p, i) {
                    setTimeout(function() { p.style.strokeDashoffset = '0'; }, i * 40);
                });
                var dots = div.querySelectorAll('.kawung-dot');
                setTimeout(function() {
                    dots.forEach(function(d) { d.style.opacity = '1'; });
                }, paths.length * 40 + 300);
            });
        }, { threshold: 0.3 });
        document.querySelectorAll('.batik-divider').forEach(function(d) { io.observe(d); });
    } else {
        // Fallback: tampilkan langsung
        document.querySelectorAll('.kawung-petal, .kawung-line').forEach(function(p) {
            p.style.strokeDashoffset = '0';
        });
        document.querySelectorAll('.kawung-dot').forEach(function(d) { d.style.opacity = '1'; });
    }
})();
