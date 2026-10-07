/* v29 — Sinematik: header hide/show, parallax hero, handoff kenong. */
(function () {
    var lastY = 0;
    var nav = document.querySelector('.navbar');
    var fig = document.querySelector('.gallery-fig');

    function onScroll() {
        var y = window.scrollY || window.pageYOffset;

        /* Header: sembunyi saat scroll bawah, muncul saat scroll atas */
        if (nav) {
            if (y > 140 && y > lastY + 4) {
                nav.classList.add('nav-hidden');
            } else if (y < lastY - 4 || y <= 140) {
                nav.classList.remove('nav-hidden');
            }
            nav.classList.toggle('scrolled', y > 40);
        }

        /* Hero leaving: konten fade saat scroll */
        if (hero) {
            hero.classList.toggle('hero-leaving', y > window.innerHeight * 0.35);
        }

        lastY = y;
        updProg();
    }

    /* Scroll progress bar */
    var prog = document.createElement('div');
    prog.className = 'scroll-progress';
    document.body.appendChild(prog);
    function updProg() {
        var h = document.documentElement;
        var max = h.scrollHeight - h.clientHeight;
        prog.style.width = (max > 0 ? (h.scrollTop / max) * 100 : 0) + '%';
    }

    var ticking = false;
    window.addEventListener('scroll', function () {
        if (!ticking) {
            window.requestAnimationFrame(function () { onScroll(); ticking = false; });
            ticking = true;
        }
    }, { passive: true });
    onScroll();

    /* Hero: keluar dramatis saat di-scroll */
    var hero = document.querySelector('.gallery-hero');
    /* Ruang Bunyi: kenong awakening + wave */
    var bunyi = document.getElementById('arsip-bunyi');
    var kenong = document.querySelector('.pad-kenong');
    var pads = document.querySelectorAll('.gamelan-pad');
    if (bunyi && 'IntersectionObserver' in window) {
        var fired = false;
        new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting && !fired) {
                    fired = true;
                    /* wave: alat masuk bergelombang */
                    pads.forEach(function (pd, i) {
                        pd.style.animationDelay = (i * 90) + 'ms';
                        pd.classList.add('wave-in');
                        setTimeout(function () { pd.classList.remove('wave-in'); pd.style.animationDelay = ''; }, 1400 + i * 90);
                    });
                    /* kenong awakening setelah wave */
                    setTimeout(function () {
                        if (kenong) {
                            kenong.classList.add('awaken');
                            setTimeout(function () { kenong.classList.remove('awaken'); }, 2600);
                        }
                    }, 700);
                } else if (!en.isIntersecting && en.boundingClientRect.top > 0) {
                    fired = false;
                }
            });
        }, { threshold: 0.25 }).observe(bunyi);
    }
})();

/* v33 — KENONG TRAVELER: morph beneran. Foto hero terbang turun jadi tombol kenong. */
(function () {
    var heroFig = document.querySelector('.gallery-fig');
    var heroImg = document.querySelector('.gallery-fig img');
    var kenongBtn = document.querySelector('.pad-kenong');
    var bunyi = document.getElementById('arsip-bunyi');
    if (!heroFig || !heroImg || !kenongBtn || !bunyi) return;

    /* traveler: lingkaran foto kenong yang bisa terbang */
    var tr = document.createElement('div');
    tr.className = 'kenong-traveler';
    var trim = document.createElement('img');
    trim.src = heroImg.src;
    trim.alt = '';
    tr.appendChild(trim);
    document.body.appendChild(tr);

    var state = 'idle'; // idle | flying | arrived
    var raf = null;

    function heroPhotoRect() { return heroFig.getBoundingClientRect(); }
    function btnRect() { return kenongBtn.getBoundingClientRect(); }

    function fly() {
        var hr = heroPhotoRect();
        var br = btnRect();
        var vh = window.innerHeight;

        /* progress: 0 = hero masih penuh, 1 = tombol kenong di tengah layar */
        var start = vh * 0.55;
        var end = vh * 0.45;
        var btnY = br.top + br.height / 2;
        var p = (start - btnY) / (start - end);
        p = Math.max(0, Math.min(1, p));

        if (hr.bottom < vh * 0.7 && state === 'idle') {
            /* lepas landas: sembunyikan foto asli, munculkan traveler */
            state = 'flying';
            heroFig.style.opacity = '0';
            tr.style.opacity = '1';
        }
        if (state === 'flying') {
            /* interpolasi posisi: dari foto hero ke tombol kenong */
            var fx = hr.left + hr.width / 2;
            var fy = Math.max(hr.top + hr.height / 2, vh * 0.3);
            var tx = br.left + br.width / 2;
            var ty = btnY;
            var x = fx + (tx - fx) * p;
            var y = fy + (ty - fy) * p;
            var size = 220 - (220 - Math.max(br.width, 90)) * p;

            tr.style.width = size + 'px';
            tr.style.height = size + 'px';
            tr.style.transform = 'translate(' + (x - size / 2) + 'px,' + (y - size / 2) + 'px)';

            if (p >= 0.98) {
                /* mendarat: traveler fade, tombol awakening */
                state = 'arrived';
                tr.style.opacity = '0';
                kenongBtn.classList.add('awaken');
                setTimeout(function () { kenongBtn.classList.remove('awaken'); }, 2600);
            }
        }
        /* reset kalau balik ke atas */
        if (hr.bottom > vh * 0.85 && state !== 'idle') {
            state = 'idle';
            heroFig.style.opacity = '';
            tr.style.opacity = '0';
        }
        raf = null;
    }

    function onScroll() {
        if (!raf) raf = requestAnimationFrame(fly);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
})();
