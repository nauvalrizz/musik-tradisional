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
