/* v91 Batch 3 — Zoom & pan + filter + tour */
(function() {
    var svg = document.querySelector('.map-svg');
    var container = document.querySelector('.map-container');
    if (!svg || !container) return;
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* Kategori dominan per pulau */
    var CATS = {
        'sumatra': 'pukul', 'jawa': 'pukul', 'kalimantan': 'petik',
        'sulawesi': 'tiup', 'bali-nusa-tenggara': 'pukul',
        'maluku': 'pukul', 'papua': 'tiup'
    };
    var CAT_COLORS = {
        'pukul': 'rgba(201,151,63,',   // emas
        'tiup':  'rgba(180,120,80,',   // perunggu
        'petik': 'rgba(160,140,100,',  // tembaga terang
        'gesek': 'rgba(140,110,70,'    // coklat emas
    };

    /* --- 7. ZOOM & PAN --- */
    var vb = { x: 0, y: 0, w: 2000, h: 1000 };
    var origVB = { x: 0, y: 0, w: 2000, h: 1000 };
    function applyVB() {
        svg.setAttribute('viewBox', vb.x + ' ' + vb.y + ' ' + vb.w + ' ' + vb.h);
    }
    // Wheel zoom (desktop)
    svg.addEventListener('wheel', function(e) {
        e.preventDefault();
        var factor = e.deltaY > 0 ? 1.15 : 0.87;
        var nw = Math.min(Math.max(vb.w * factor, 400), 2000);
        var nh = nw * 0.5;
        // Zoom ke posisi mouse
        var rect = svg.getBoundingClientRect();
        var mx = (e.clientX - rect.left) / rect.width;
        var my = (e.clientY - rect.top) / rect.height;
        vb.x = vb.x + (vb.w - nw) * mx;
        vb.y = vb.y + (vb.h - nh) * my;
        vb.w = nw; vb.h = nh;
        applyVB();
    }, { passive: false });

    // Drag pan
    var panning = false, sx = 0, sy = 0, svbx = 0, svby = 0;
    svg.addEventListener('pointerdown', function(e) {
        // Jangan pan kalo klik pulau (biar klik tetep jalan)
        if (e.target.closest('path[data-slug]')) return;
        panning = true; sx = e.clientX; sy = e.clientY;
        svbx = vb.x; svby = vb.y;
        svg.setPointerCapture(e.pointerId);
    });
    svg.addEventListener('pointermove', function(e) {
        if (!panning) return;
        var rect = svg.getBoundingClientRect();
        var dx = (e.clientX - sx) / rect.width * vb.w;
        var dy = (e.clientY - sy) / rect.height * vb.h;
        vb.x = svbx - dx; vb.y = svby - dy;
        applyVB();
    });
    svg.addEventListener('pointerup', function() { panning = false; });

    // Tombol kontrol
    var ctrl = document.createElement('div');
    ctrl.className = 'map-controls';
    ctrl.innerHTML =
        '<button data-act="zin" title="Zoom in">+</button>' +
        '<button data-act="zout" title="Zoom out">&minus;</button>' +
        '<button data-act="reset" title="Reset">&#10226;</button>' +
        '<button data-act="tour" title="Tour">&#9654; Tour</button>';
    container.appendChild(ctrl);

    ctrl.addEventListener('click', function(e) {
        var btn = e.target.closest('button');
        if (!btn) return;
        var act = btn.dataset.act;
        if (act === 'zin' || act === 'zout') {
            var f = act === 'zin' ? 0.7 : 1.43;
            var nw = Math.min(Math.max(vb.w * f, 400), 2000);
            vb.w = nw; vb.h = nw * 0.5;
            vb.x = Math.max(0, Math.min(2000 - vb.w, vb.x));
            vb.y = Math.max(0, Math.min(1000 - vb.h, vb.y));
            applyVB();
        } else if (act === 'reset') {
            vb = Object.assign({}, origVB); applyVB();
            stopTour();
        } else if (act === 'tour') {
            if (touring) stopTour(); else startTour(btn);
        }
    });

    /* --- 8. FILTER WARNA --- */
    var filterBar = document.createElement('div');
    filterBar.className = 'map-filter';
    filterBar.innerHTML =
        '<button data-cat="all" class="active">Semua</button>' +
        '<button data-cat="pukul">Pukul</button>' +
        '<button data-cat="tiup">Tiup</button>' +
        '<button data-cat="petik">Petik</button>' +
        '<button data-cat="gesek">Gesek</button>';
    container.appendChild(filterBar);

    filterBar.addEventListener('click', function(e) {
        var btn = e.target.closest('button');
        if (!btn) return;
        filterBar.querySelectorAll('button').forEach(function(b) { b.classList.remove('active'); });
        btn.classList.add('active');
        var cat = btn.dataset.cat;
        svg.querySelectorAll('path[data-slug]').forEach(function(path) {
            var slug = path.dataset.slug;
            if (cat === 'all' || CATS[slug] === cat) {
                path.style.opacity = '1';
                path.style.filter = '';
            } else {
                path.style.opacity = '0.18';
                path.style.filter = 'grayscale(0.7)';
            }
        });
    });

    /* --- 9. TOUR MODE --- */
    var touring = false, tourTimer = null, tourIdx = 0;
    var tourOrder = ['sumatra','jawa','kalimantan','sulawesi','bali-nusa-tenggara','maluku','papua'];
    var tip = document.getElementById('island-tip');

    function startTour(btn) {
        if (reduceMotion) return;
        touring = true;
        btn.innerHTML = '&#9632; Stop';
        btn.classList.add('touring');
        tourIdx = 0;
        nextTourStop();
    }
    function stopTour() {
        touring = false;
        if (tourTimer) clearTimeout(tourTimer);
        var btn = ctrl.querySelector('[data-act="tour"]');
        if (btn) { btn.innerHTML = '&#9654; Tour'; btn.classList.remove('touring'); }
        svg.querySelectorAll('path[data-slug]').forEach(function(p) { p.classList.remove('tour-active'); });
        if (tip) tip.style.display = 'none';
    }
    function nextTourStop() {
        if (!touring) return;
        svg.querySelectorAll('path[data-slug]').forEach(function(p) { p.classList.remove('tour-active'); });
        var slug = tourOrder[tourIdx % tourOrder.length];
        var path = svg.querySelector('path[data-slug="' + slug + '"]');
        if (path) {
            path.classList.add('tour-active');
            // Tampilkan tooltip di tengah pulau
            try {
                var bb = path.getBBox();
                var pt = svg.createSVGPoint();
                pt.x = bb.x + bb.width/2; pt.y = bb.y + bb.height/2;
                var screen = pt.matrixTransform(svg.getScreenCTM());
                if (tip) {
                    var names = {sumatra:'Sumatra',jawa:'Jawa',kalimantan:'Kalimantan',sulawesi:'Sulawesi','bali-nusa-tenggara':'Bali & NTT',maluku:'Maluku',papua:'Papua'};
                    tip.innerHTML = '<b>' + (names[slug]||slug) + '</b>';
                    tip.style.display = 'block';
                    tip.style.left = (screen.x + 20) + 'px';
                    tip.style.top = (screen.y - 10) + 'px';
                }
            } catch(e) {}
        }
        tourIdx++;
        tourTimer = setTimeout(nextTourStop, 2200);
    }
    // Klik mana aja stop tour
    svg.addEventListener('click', function() { if (touring) stopTour(); }, true);
})();
