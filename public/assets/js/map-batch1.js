/* v84 Batch 1 — Peta interaktif: hover preview + bunyi + heat glow */
(function() {
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* Data pulau: nama, alat khas, jumlah, timbre, frekuensi */
    var ISLANDS = {
        'sumatra':           { nama: 'Sumatra',            alat: 'Talempong',  jml: 12, timbre: 'bonang',  freq: 440 },
        'jawa':              { nama: 'Jawa',               alat: 'Gong Ageng', jml: 28, timbre: 'gong',    freq: 98 },
        'kalimantan':        { nama: 'Kalimantan',         alat: 'Sape',       jml: 8,  timbre: 'suling',  freq: 523 },
        'sulawesi':          { nama: 'Sulawesi',           alat: 'Kolintang',  jml: 10, timbre: 'saron',   freq: 392 },
        'bali-nusa-tenggara':{ nama: 'Bali & Nusa Tenggara', alat: 'Gamelan Bali', jml: 15, timbre: 'kenong', freq: 587 },
        'maluku':            { nama: 'Maluku',             alat: 'Tifa',       jml: 6,  timbre: 'kendang', freq: 196 },
        'papua':             { nama: 'Papua',              alat: 'Tifa Papua', jml: 7,  timbre: 'kendang', freq: 175 }
    };

    /* Timbre definitions (copy dari gamelan.js biar standalone) */
    var TIMBRES = {
        bonang: [[1.00,0.80,2.2],[2.76,0.35,1.1],[5.40,0.15,0.5]],
        gong: [[1.00,0.90,4.5],[1.48,0.40,2.5],[2.09,0.22,1.4]],
        suling: [[1.00,0.75,1.2],[2.00,0.25,0.7],[3.01,0.10,0.4]],
        saron: [[1.00,0.85,1.6],[2.76,0.28,0.8],[5.40,0.10,0.4]],
        kenong: [[1.00,0.85,2.6],[2.76,0.30,1.2],[5.40,0.12,0.5]],
        kendang: [[1.00,0.90,0.35],[1.59,0.45,0.2],[2.14,0.20,0.12]]
    };

    var actx = null;
    function ensureAudio() {
        if (!actx) {
            var AC = window.AudioContext || window.webkitAudioContext;
            if (!AC) return false;
            actx = new AC();
        }
        if (actx.state === 'suspended') actx.resume();
        return true;
    }

    function playIslandSound(slug) {
        if (!ensureAudio()) return;
        var info = ISLANDS[slug];
        if (!info) return;
        var partials = TIMBRES[info.timbre] || TIMBRES.saron;
        var t = actx.currentTime + 0.01;
        partials.forEach(function(p) {
            var o = actx.createOscillator();
            o.type = 'sine';
            o.frequency.value = info.freq * p[0];
            var g = actx.createGain();
            g.gain.setValueAtTime(0.0001, t);
            g.gain.exponentialRampToValueAtTime(p[1] * 0.4, t + 0.01);
            g.gain.exponentialRampToValueAtTime(0.0001, t + p[2]);
            o.connect(g); g.connect(actx.destination);
            o.start(t); o.stop(t + p[2] + 0.1);
        });
    }

    /* Heat glow: opacity berdasarkan jumlah alat */
    var maxJml = 28;
    function applyHeat() {
        document.querySelectorAll('.map-svg path[data-slug]').forEach(function(path) {
            var slug = path.dataset.slug;
            var info = ISLANDS[slug];
            if (!info) return;
            var intensity = info.jml / maxJml; // 0..1
            path.style.setProperty('--heat', intensity.toFixed(2));
        });
    }

    /* Hover preview */
    var tooltip = document.getElementById('tooltip');
    function initHover() {
        document.querySelectorAll('.map-svg path[data-slug]').forEach(function(path) {
            var slug = path.dataset.slug;
            var info = ISLANDS[slug];
            if (!info) return;

            path.addEventListener('mouseenter', function(e) {
                path.classList.add('island-hover');
                if (tooltip) {
                    tooltip.innerHTML =
                        '<b>' + info.nama + '</b>' +
                        '<span>' + info.jml + ' alat</span>' +
                        '<i>Khas: ' + info.alat + '</i>';
                    tooltip.classList.add('show');
                }
                // Bunyi pas hover (sekali aja per hover)
                if (!reduceMotion) playIslandSound(slug);
            });

            path.addEventListener('mousemove', function(e) {
                if (tooltip && tooltip.classList.contains('show')) {
                    tooltip.style.left = (e.clientX + 16) + 'px';
                    tooltip.style.top = (e.clientY + 16) + 'px';
                }
            });

            path.addEventListener('mouseleave', function() {
                path.classList.remove('island-hover');
                if (tooltip) tooltip.classList.remove('show');
            });

            // Klik: bunyi + navigasi (delay dikit biar bunyi kedengeran)
            path.addEventListener('click', function(e) {
                e.preventDefault();
                playIslandSound(slug);
                setTimeout(function() {
                    window.location.href = '/pulau/' + slug;
                }, 350);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        applyHeat();
        initHover();
    });
})();
