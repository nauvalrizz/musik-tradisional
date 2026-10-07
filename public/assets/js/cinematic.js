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

/* v34 — KENONG TRAVELER simpel: muncul di tengah layar pas scroll, terbang ke tombol */
(function () {
    var kenongBtn = document.querySelector('.pad-kenong');
    var bunyi = document.getElementById('arsip-bunyi');
    var heroImg = document.querySelector('.gallery-fig img');
    if (!kenongBtn || !bunyi || !heroImg) return;

    var tr = document.createElement('div');
    tr.className = 'kenong-traveler';
    tr.innerHTML = '<img src="' + heroImg.src + '" alt="">';
    document.body.appendChild(tr);

    var done = false;
    new IntersectionObserver(function (es) {
        es.forEach(function (en) {
            if (en.isIntersecting && !done) {
                done = true;
                var br = kenongBtn.getBoundingClientRect();
                /* muncul di tengah layar */
                tr.style.left = '50%';
                tr.style.top = '40%';
                tr.style.opacity = '1';
                tr.style.transform = 'translate(-50%,-50%) scale(1)';
                /* terbang ke tombol setelah 800ms */
                setTimeout(function () {
                    var x = br.left + br.width / 2;
                    var y = br.top + br.height / 2;
                    tr.style.transition = 'all 1s cubic-bezier(0.22,1,0.36,1)';
                    tr.style.left = x + 'px';
                    tr.style.top = y + 'px';
                    tr.style.transform = 'translate(-50%,-50%) scale(0.3)';
                    tr.style.opacity = '0.3';
                    setTimeout(function () {
                        tr.style.opacity = '0';
                        kenongBtn.classList.add('awaken');
                        setTimeout(function () { kenongBtn.classList.remove('awaken'); }, 2600);
                    }, 1000);
                }, 800);
            }
            if (!en.isIntersecting && en.boundingClientRect.top > 0) done = false;
        });
    }, { threshold: 0.3 }).observe(bunyi);
})();
