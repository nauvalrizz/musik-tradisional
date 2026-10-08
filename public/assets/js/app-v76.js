import './modules/scroll.js';
import './modules/loader.js';
import './modules/search.js';
import initImageModal from './image-modal.js';

// ================= INIT =================
document.addEventListener('DOMContentLoaded', () => {
    initImageModal();
});

// ================= NAVIGATION =================
document.addEventListener('click', function (e) {
    const link = e.target.closest('a');
    if (!link) return;

    const href = link.getAttribute('href');

    if (
        !href ||
        href.startsWith('#') ||
        link.target === '_blank' ||
        link.hasAttribute('download') ||
        link.hasAttribute('data-no-transition')
    ) return;

    if (link.hostname !== window.location.hostname) return;

    e.preventDefault();

    const loader = document.getElementById('topLoader');

    if (loader) {
        loader.style.width = '0%';
        loader.style.transition = 'none';

        setTimeout(() => {
            loader.style.transition = 'width 0.4s ease';
            loader.style.width = '70%';
        }, 10);
    }

    setTimeout(() => {
        if (loader) loader.style.width = '100%';
        window.location.href = href;
    }, 250);
});

// ================= RESET =================
window.addEventListener('load', () => {
    const loader = document.getElementById('topLoader');

    if (loader) {
        loader.style.width = '100%';

        setTimeout(() => {
            loader.style.width = '0%';
        }, 300);
    }

});

// ================= BACK BUTTON FIX =================
window.addEventListener('pageshow', () => {

    const loader = document.getElementById('topLoader');
    if (loader) loader.style.width = '0%';
});

// ================= AOS =================
AOS.init({
    duration: 800,
    once: false
});

setTimeout(() => {
}, 1000);

// ================= TABUHAN PEMBUKA =================
// Hapus overlay intro setelah animasi selesai; pengaman bila
// animationend tak menyala, dan klik untuk lewati.
(function () {
    var t = document.getElementById('tabuhan');
    if (!t) return;
    var done = false;
    function finish() {
        if (done) return;
        done = true;
        try { sessionStorage.setItem('tabuhan_shown', '1'); } catch (e) {}
        if (t.parentNode) t.parentNode.removeChild(t);
    }
    t.addEventListener('animationend', function (e) {
        if (e.target === t) finish();
    });
    t.addEventListener('click', finish);
    setTimeout(finish, 3500);
})();

// ================= NAVBAR SCROLL =================
// v66: navbar selalu tampil — bersihkan semua sisa hide/show
(function () {
    var nav = document.getElementById('mainNav');
    if (!nav) return;
    nav.classList.remove('nav-hidden');
    nav.style.transform = '';
    nav.style.top = '';
    nav.style.animation = 'none';
    function onScroll() {
        nav.classList.toggle('scrolled', (window.scrollY || 0) > 40);
        // Pastikan tidak ada yang menyembunyikan lagi
        if (nav.classList.contains('nav-hidden')) nav.classList.remove('nav-hidden');
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
})();

/* v60 — Buku 3D realistis */
(function() {
    var book = document.getElementById('kisah-book');
    if (!book) return;
    var pages = Array.prototype.slice.call(book.querySelectorAll('.book-page'));
    var prev = document.getElementById('book-prev');
    var next = document.getElementById('book-next');
    var current = 0;
    var animating = false;
    var TURN_MS = 1600;

    function render() {
        pages.forEach(function(pg, i) {
            var shouldFlip = i < current;
            if (pg.classList.contains('flipped') !== shouldFlip) {
                pg.classList.toggle('flipped', shouldFlip);
            }
            // z-index hanya di-set saat TIDAK animasi (biar ga kedip)
            if (!animating) {
                pg.style.zIndex = i < current ? i + 1 : (pages.length - i + 10);
            }
        });
        prev.disabled = current === 0 || animating;
        next.disabled = current === pages.length - 1 || animating;
    }

    function go(dir) {
        if (animating) return;
        var target = current + dir;
        if (target < 0 || target >= pages.length) return;
        animating = true;
        current = target;
        render();
        prev.disabled = true;
        next.disabled = true;
        setTimeout(function() {
            animating = false;
            render();
        }, TURN_MS + 60);
    }

    prev.addEventListener('click', function() { go(-1); });
    next.addEventListener('click', function() { go(1); });
    // Swipe untuk HP
    var sx = 0;
    book.addEventListener('touchstart', function(e) {
        sx = e.touches[0].clientX;
    }, { passive: true });
    book.addEventListener('touchend', function(e) {
        var dx = e.changedTouches[0].clientX - sx;
        if (Math.abs(dx) > 50) go(dx < 0 ? 1 : -1);
    }, { passive: true });
    // z-index awal
    pages.forEach(function(pg, i) {
        pg.style.zIndex = pages.length - i + 10;
    });
    render();
})();

/* v56 — timeline sweep */
(function() {
    var track = document.querySelector('.timeline-track');
    if (!track) return;
    var dots = track.querySelectorAll('.tl-dot');
    if (!dots.length) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var sweep = document.createElement('div');
    sweep.className = 'tl-sweep';
    track.appendChild(sweep);

    var dotPos = [];
    function measure() {
        var r = track.getBoundingClientRect();
        dotPos = [];
        dots.forEach(function(d) {
            var dr = d.getBoundingClientRect();
            dotPos.push(dr.left - r.left + dr.width / 2);
        });
    }
    measure();
    window.addEventListener('resize', measure);

    var DURATION = 5000;
    var start = null;
    function tick(ts) {
        if (!start) start = ts;
        var t = ((ts - start) % DURATION) / DURATION;
        var trackW = track.getBoundingClientRect().width;
        var x = t * trackW;
        sweep.style.opacity = (t > 0.02 && t < 0.98) ? '1' : '0';
        sweep.style.left = (x - 40) + 'px';

        dots.forEach(function(d, i) {
            var hit = Math.abs(x - dotPos[i]) < 30;
            d.classList.toggle('hit', hit);
        });
        requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
})();

/* v70 — typing effect untuk quote */
(function() {
    var quote = document.querySelector('.paper-quote');
    if (!quote) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var fullText = quote.textContent;
    var started = false;

    function typeText() {
        if (started) return;
        started = true;
        quote.textContent = '';
        quote.classList.add('typing');
        var i = 0;
        var speed = 45; // ms per karakter
        function type() {
            if (i < fullText.length) {
                quote.textContent = fullText.substring(0, i + 1);
                i++;
                setTimeout(type, speed);
            } else {
                // Selesai: hilangkan cursor setelah 2 detik
                setTimeout(function() {
                    quote.classList.remove('typing');
                }, 2000);
            }
        }
        type();
    }

    // Mulai saat quote masuk viewport
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function(entries) {
            entries.forEach(function(en) {
                if (en.isIntersecting) {
                    typeText();
                    io.disconnect();
                }
            });
        }, { threshold: 0.5 });
        io.observe(quote);
    } else {
        typeText();
    }
})();

/* v71 — brand typing loop dari logo M */
(function() {
    var brandText = document.querySelector('.brand-text');
    if (!brandText) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    // Ambil text "MuSantara" (node pertama, sebelum <em>)
    var textNode = null;
    for (var i = 0; i < brandText.childNodes.length; i++) {
        if (brandText.childNodes[i].nodeType === 3 && brandText.childNodes[i].textContent.trim()) {
            textNode = brandText.childNodes[i];
            break;
        }
    }
    if (!textNode) return;
    var word = textNode.textContent;
    // "M" pertama digabung ke stamp, animasikan sisanya ("uSantara")
    var startIdx = word.charAt(0) === 'M' ? 1 : 0;
    var staticPart = word.substring(0, startIdx);
    var animPart = word.substring(startIdx);

    // Bangun ulang: static + span per huruf
    brandText.innerHTML = '';
    if (staticPart) {
        brandText.appendChild(document.createTextNode(staticPart));
    }
    var letters = [];
    for (var j = 0; j < animPart.length; j++) {
        var sp = document.createElement('span');
        sp.className = 'type-letter';
        sp.textContent = animPart[j];
        brandText.appendChild(sp);
        letters.push(sp);
    }
    // Kembalikan <em>Arsip</em>
    var em = document.createElement('em');
    em.textContent = 'Arsip';
    brandText.appendChild(em);

    // Loop: ketik -> tahan -> hapus -> jeda
    var TYPE_MS = 220, HOLD_MS = 4000, DEL_MS = 90, PAUSE_MS = 1800;
    function typeLoop() {
        var idx = 0;
        function showNext() {
            if (idx < letters.length) {
                letters[idx].classList.add('shown');
                idx++;
                setTimeout(showNext, TYPE_MS);
            } else {
                setTimeout(hideAll, HOLD_MS);
            }
        }
        function hideAll() {
            var ridx = letters.length - 1;
            function hideNext() {
                if (ridx >= 0) {
                    letters[ridx].classList.remove('shown');
                    ridx--;
                    setTimeout(hideNext, DEL_MS);
                } else {
                    setTimeout(typeLoop, PAUSE_MS);
                }
            }
            hideNext();
        }
        showNext();
    }
    // Mulai setelah 1 detik
    setTimeout(typeLoop, 1000);
})();

/* v72 — hero title char reveal */
(function() {
    var heroTitle = document.querySelector('.hero-title');
    if (!heroTitle) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    // Split jadi per huruf (jaga <em> dan <br>)
    function splitChars(el) {
        var nodes = Array.prototype.slice.call(el.childNodes);
        nodes.forEach(function(node) {
            if (node.nodeType === 3) {
                var text = node.textContent;
                var frag = document.createDocumentFragment();
                for (var i = 0; i < text.length; i++) {
                    var sp = document.createElement('span');
                    sp.className = 'char';
                    sp.textContent = text[i] === ' ' ? '\u00A0' : text[i];
                    frag.appendChild(sp);
                }
                el.replaceChild(frag, node);
            } else if (node.nodeType === 1 && node.tagName !== 'BR') {
                splitChars(node);
            }
        });
    }
    splitChars(heroTitle);
    var chars = heroTitle.querySelectorAll('.char');
    // Reveal stagger pas load
    setTimeout(function() {
        chars.forEach(function(c, i) {
            setTimeout(function() { c.classList.add('shown'); }, i * 35);
        });
    }, 400);
})();

/* v73 — BRUTAL: 3D tilt, scramble, particles */
(function() {
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduceMotion) return;

    /* 1. Hero 3D tilt */
    var heroVisual = document.querySelector('.hero-visual');
    if (heroVisual) {
        var heroImg = heroVisual.querySelector('img');
        if (heroImg) {
            document.addEventListener('mousemove', function(e) {
                var x = (e.clientX / window.innerWidth - 0.5) * 20;
                var y = (e.clientY / window.innerHeight - 0.5) * -20;
                heroImg.style.transform = 'rotateY(' + x + 'deg) rotateX(' + y + 'deg) scale(1.05)';
            });
        }
    }

    /* 2. Scramble decoder untuk hero title */
    var heroTitle = document.querySelector('.hero-title');
    if (heroTitle) {
        var chars = '!<>-_\\/[]{}—=+*^?#';
        var original = heroTitle.textContent;
        var frame = 0;
        function scramble() {
            var out = '';
            for (var i = 0; i < original.length; i++) {
                if (original[i] === ' ' || original[i] === '\n') {
                    out += original[i];
                } else if (i < frame / 3) {
                    out += original[i];
                } else {
                    out += chars[Math.floor(Math.random() * chars.length)];
                }
            }
            heroTitle.textContent = out;
            frame++;
            if (frame / 3 < original.length) {
                setTimeout(scramble, 30);
            } else {
                heroTitle.textContent = original;
            }
        }
        setTimeout(scramble, 600);
    }

    /* 3. Particle burst canvas */
    var canvas = document.createElement('canvas');
    canvas.id = 'particle-canvas';
    document.body.appendChild(canvas);
    var ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    var particles = [];

    function burst(x, y, color) {
        for (var i = 0; i < 24; i++) {
            particles.push({
                x: x, y: y,
                vx: (Math.random() - 0.5) * 10,
                vy: (Math.random() - 0.5) * 10 - 3,
                life: 1,
                color: color || '#c9973f',
                size: Math.random() * 4 + 2
            });
        }
    }

    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particles = particles.filter(function(p) { return p.life > 0; });
        particles.forEach(function(p) {
            p.x += p.vx; p.y += p.vy; p.vy += 0.25; p.life -= 0.02;
            ctx.globalAlpha = p.life;
            ctx.fillStyle = p.color;
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.size * p.life, 0, Math.PI * 2);
            ctx.fill();
        });
        ctx.globalAlpha = 1;
        requestAnimationFrame(animate);
    }
    animate();

    // Trigger burst pas gamelan dipukul
    document.addEventListener('click', function(e) {
        var pad = e.target.closest('.gamelan-pad');
        if (pad) {
            var r = pad.getBoundingClientRect();
            burst(r.left + r.width/2, r.top + r.height/2);
            pad.classList.remove('struck');
            void pad.offsetWidth; // restart animasi
            pad.classList.add('struck');
        }
    });

    window.addEventListener('resize', function() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    });
})();

/* v74 — loader, counter */
(function() {
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* Loader: hilang pas page load */
    window.addEventListener('load', function() {
        setTimeout(function() {
            var loader = document.getElementById('loader');
            if (loader) loader.classList.add('done');
        }, reduceMotion ? 0 : 900);
    });
    // Fallback: paksa hilang setelah 3 detik
    setTimeout(function() {
        var loader = document.getElementById('loader');
        if (loader) loader.classList.add('done');
    }, 3000);

    /* Counter: angka naik pas terlihat */
    var counters = document.querySelectorAll('[data-count]');
    if (counters.length && !reduceMotion && 'IntersectionObserver' in window) {
        var cio = new IntersectionObserver(function(entries) {
            entries.forEach(function(en) {
                if (!en.isIntersecting) return;
                var el = en.target;
                cio.unobserve(el);
                var target = parseInt(el.dataset.count, 10) || 0;
                var dur = 1500, start = null;
                function step(ts) {
                    if (!start) start = ts;
                    var p = Math.min((ts - start) / dur, 1);
                    var eased = 1 - Math.pow(1 - p, 3);
                    el.textContent = Math.floor(eased * target);
                    if (p < 1) requestAnimationFrame(step);
                    else el.textContent = target;
                }
                requestAnimationFrame(step);
            });
        }, { threshold: 0.5 });
        counters.forEach(function(c) { cio.observe(c); });
    }
})();

/* v75 — BRONZE UNIVERSE: partikel yang hidup, bereaksi sama suara */
(function() {
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduceMotion) return;

    var hero = document.querySelector('.gallery-hero');
    if (!hero) return;

    var canvas = document.createElement('canvas');
    canvas.id = 'universe-canvas';
    hero.insertBefore(canvas, hero.firstChild);
    var ctx = canvas.getContext('2d');

    function resize() {
        canvas.width = hero.offsetWidth;
        canvas.height = hero.offsetHeight;
    }
    resize();
    window.addEventListener('resize', resize);

    // Energy level (naik pas gamelan dimainkan)
    var energy = 0;
    window.__universeEnergy = function(v) { energy = Math.min(1, energy + v); };

    // Trigger dari gamelan pad clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.gamelan-pad')) {
            window.__universeEnergy(0.35);
        }
    });

    var N = 90;
    var parts = [];
    for (var i = 0; i < N; i++) {
        parts.push({
            x: Math.random(), y: Math.random(),
            vx: (Math.random() - 0.5) * 0.0006,
            vy: (Math.random() - 0.5) * 0.0006,
            r: Math.random() * 2.2 + 0.6,
            hue: 35 + Math.random() * 15, // bronze range
            tw: Math.random() * Math.PI * 2, // twinkle phase
            depth: Math.random() // untuk parallax
        });
    }

    var mouse = { x: 0.5, y: 0.5 };
    hero.addEventListener('mousemove', function(e) {
        var r = hero.getBoundingClientRect();
        mouse.x = (e.clientX - r.left) / r.width;
        mouse.y = (e.clientY - r.top) / r.height;
    });

    function frame(t) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        energy *= 0.965; // decay

        parts.forEach(function(p) {
            // Gerak + tarikan halus ke mouse
            var dx = mouse.x - p.x, dy = mouse.y - p.y;
            var dist = Math.sqrt(dx*dx + dy*dy) || 0.001;
            var pull = 0.00008 * (1 - Math.min(dist * 2, 1));
            p.vx += (dx / dist) * pull;
            p.vy += (dy / dist) * pull;

            // Energy: partikel bergerak lebih cepat & membesar
            var boost = 1 + energy * 4;
            p.x += p.vx * boost;
            p.y += p.vy * boost;

            // Wrap
            if (p.x < -0.05) p.x = 1.05; if (p.x > 1.05) p.x = -0.05;
            if (p.y < -0.05) p.y = 1.05; if (p.y > 1.05) p.y = -0.05;

            var px = p.x * canvas.width, py = p.y * canvas.height;
            p.tw += 0.03 + energy * 0.1;
            var alpha = 0.25 + Math.sin(p.tw) * 0.2 + energy * 0.5;
            var radius = p.r * (1 + energy * 1.5) * (0.7 + p.depth * 0.6);

            // Glow
            var g = ctx.createRadialGradient(px, py, 0, px, py, radius * 4);
            g.addColorStop(0, 'hsla(' + p.hue + ', 60%, 55%, ' + Math.min(alpha, 1) + ')');
            g.addColorStop(1, 'hsla(' + p.hue + ', 60%, 55%, 0)');
            ctx.fillStyle = g;
            ctx.beginPath();
            ctx.arc(px, py, radius * 4, 0, Math.PI * 2);
            ctx.fill();

            // Core
            ctx.fillStyle = 'hsla(' + p.hue + ', 70%, 65%, ' + Math.min(alpha + 0.2, 1) + ')';
            ctx.beginPath();
            ctx.arc(px, py, radius, 0, Math.PI * 2);
            ctx.fill();
        });

        // Koneksi antar partikel dekat (constellation)
        ctx.strokeStyle = 'rgba(201,151,63,0.08)';
        ctx.lineWidth = 1;
        for (var i = 0; i < parts.length; i += 3) {
            for (var j = i + 3; j < parts.length; j += 3) {
                var a = parts[i], b = parts[j];
                var ddx = (a.x - b.x) * canvas.width;
                var ddy = (a.y - b.y) * canvas.height;
                var d = Math.sqrt(ddx*ddx + ddy*ddy);
                if (d < 120) {
                    ctx.globalAlpha = (1 - d / 120) * 0.5;
                    ctx.beginPath();
                    ctx.moveTo(a.x * canvas.width, a.y * canvas.height);
                    ctx.lineTo(b.x * canvas.width, b.y * canvas.height);
                    ctx.stroke();
                }
            }
        }
        ctx.globalAlpha = 1;

        requestAnimationFrame(frame);
    }
    requestAnimationFrame(frame);
})();
