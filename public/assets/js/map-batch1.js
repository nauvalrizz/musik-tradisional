/* v90 — Peta: tooltip simple, tanpa suara */
(function() {
    var NAMES = {
        'sumatra': 'Sumatra', 'jawa': 'Jawa', 'kalimantan': 'Kalimantan',
        'sulawesi': 'Sulawesi', 'bali-nusa-tenggara': 'Bali & Nusa Tenggara',
        'maluku': 'Maluku', 'papua': 'Papua'
    };
    // Baca jumlah asli dari database (di-inject via data-counts)
    var countsEl = document.getElementById('island-data');
    var DB_COUNTS = {};
    try { DB_COUNTS = JSON.parse(countsEl ? countsEl.dataset.counts : '{}'); } catch(e) {}
    var ISLANDS = {};
    Object.keys(NAMES).forEach(function(slug) {
        ISLANDS[slug] = { nama: NAMES[slug], jml: parseInt(DB_COUNTS[slug] || 0, 10) };
    });

    // Buat tooltip langsung di body (bukan di dalam section)
    var tip = document.createElement('div');
    tip.id = 'island-tip';
    document.body.appendChild(tip);

    var maxJml = Math.max.apply(null, Object.keys(ISLANDS).map(function(k){ return ISLANDS[k].jml; }).concat([1]));

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
