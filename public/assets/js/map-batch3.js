/* v101 — Tour mode aja (zoom/pan dihapus biar scroll lancar) */
(function() {
    var svg = document.querySelector('.map-svg');
    var container = document.querySelector('.map-container');
    if (!svg || !container) return;
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* --- TOUR MODE --- */
    var touring = false, tourTimer = null, tourIdx = 0;
    var tourOrder = ['sumatra','jawa','kalimantan','sulawesi','bali-nusa-tenggara','maluku','papua'];
    var tip = document.getElementById('island-tip');

    var ctrl = document.createElement('div');
    ctrl.className = 'map-controls';
    ctrl.innerHTML = '<button data-act="tour" title="Tour">&#9654; Tour</button>';
    container.appendChild(ctrl);

    ctrl.addEventListener('click', function(e) {
        var btn = e.target.closest('button');
        if (!btn) return;
        if (touring) stopTour(); else startTour(btn);
    });

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
    svg.addEventListener('click', function() { if (touring) stopTour(); }, true);
})();
