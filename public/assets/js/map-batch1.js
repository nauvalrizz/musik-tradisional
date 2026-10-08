/* v90 — Peta: tooltip simple, tanpa suara */
(function() {
    var ISLANDS = {
        'sumatra':            { nama: 'Sumatra',               jml: 12 },
        'jawa':               { nama: 'Jawa',                  jml: 28 },
        'kalimantan':         { nama: 'Kalimantan',            jml: 8 },
        'sulawesi':           { nama: 'Sulawesi',              jml: 10 },
        'bali-nusa-tenggara': { nama: 'Bali & Nusa Tenggara',  jml: 15 },
        'maluku':             { nama: 'Maluku',                jml: 6 },
        'papua':              { nama: 'Papua',                 jml: 7 }
    };

    // Buat tooltip langsung di body (bukan di dalam section)
    var tip = document.createElement('div');
    tip.id = 'island-tip';
    document.body.appendChild(tip);

    var maxJml = 28;

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.map-svg path[data-slug]').forEach(function(path) {
            var slug = path.dataset.slug;
            var info = ISLANDS[slug];
            if (!info) return;

            // Heat glow
            var heat = (info.jml / maxJml).toFixed(2);
            path.style.setProperty('--heat', heat);

            path.addEventListener('mouseenter', function() {
                tip.innerHTML = '<b>' + info.nama + '</b><span>' + info.jml + ' alat musik</span>';
                tip.style.display = 'block';
            });
            path.addEventListener('mousemove', function(e) {
                tip.style.left = (e.clientX + 18) + 'px';
                tip.style.top = (e.clientY + 18) + 'px';
            });
            path.addEventListener('mouseleave', function() {
                tip.style.display = 'none';
            });
            // Klik biasa kaya awal
            path.addEventListener('click', function() {
                window.location.href = '/pulau/' + slug;
            });
        });
    });
})();
