/* v85 Batch 2 — Marker cahaya + garis koneksi + 3D tilt */
(function() {
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduceMotion) return;

    /* Koordinat = tengah asli tiap pulau via getBBox (bukan tebakan) */
    function getCenters() {
        var centers = {};
        svg.querySelectorAll('path[data-slug]').forEach(function(path) {
            try {
                var bb = path.getBBox();
                centers[path.dataset.slug] = [bb.x + bb.width/2, bb.y + bb.height/2];
            } catch(e) {}
        });
        return centers;
    }
    var CENTERS = getCenters();

    /* Koneksi: alat sejenis antar pulau */
    var LINKS = [
        ['jawa', 'sumatra', 'Gong'],
        ['jawa', 'bali-nusa-tenggara', 'Gamelan'],
        ['sumatra', 'kalimantan', 'Gong kecil'],
        ['sulawesi', 'maluku', 'Kolintang'],
        ['maluku', 'papua', 'Tifa'],
        ['kalimantan', 'sulawesi', 'Sape']
    ];

    var svg = document.querySelector('.map-svg');
    var container = document.querySelector('.map-container');
    if (!svg || !container) return;

    var NS = 'http://www.w3.org/2000/svg';

    /* 4. Marker cahaya — titik bronze berdenyut */
    var markerGroup = document.createElementNS(NS, 'g');
    markerGroup.setAttribute('class', 'marker-layer');
    svg.appendChild(markerGroup);

    Object.keys(CENTERS).forEach(function(slug, idx) {
        var c = CENTERS[slug];
        // Ring luar (pulse)
        var ring = document.createElementNS(NS, 'circle');
        ring.setAttribute('cx', c[0]); ring.setAttribute('cy', c[1]);
        ring.setAttribute('r', 14);
        ring.setAttribute('class', 'island-ring');
        ring.style.animationDelay = (idx * 0.4) + 's';
        markerGroup.appendChild(ring);
        // Titik inti
        var dot = document.createElementNS(NS, 'circle');
        dot.setAttribute('cx', c[0]); dot.setAttribute('cy', c[1]);
        dot.setAttribute('r', 6);
        dot.setAttribute('class', 'island-dot');
        markerGroup.appendChild(dot);
        // Klik marker = klik pulau
        [ring, dot].forEach(function(el) {
            el.style.pointerEvents = 'none'; // jangan block hover/tooltip pulau
        });
    });


    /* 5. 3D tilt ngikutin mouse */
    if (container && !window.matchMedia('(pointer: coarse)').matches) {
        container.style.perspective = '1200px';
        svg.style.transition = 'transform 0.2s ease-out';
        svg.style.transformStyle = 'preserve-3d';
        container.addEventListener('mousemove', function(e) {
            var r = container.getBoundingClientRect();
            var x = (e.clientX - r.left) / r.width - 0.5;
            var y = (e.clientY - r.top) / r.height - 0.5;
            svg.style.transform = 'rotateY(' + (x * 8) + 'deg) rotateX(' + (-y * 8) + 'deg)';
        });
        container.addEventListener('mouseleave', function() {
            svg.style.transform = 'rotateY(0deg) rotateX(0deg)';
        });
    }
})();
