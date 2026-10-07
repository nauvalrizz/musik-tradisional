/* v28 — Sinematik: header hide/show, parallax hero, handoff kenong. */
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

        /* Parallax: foto hero bergerak lebih lambat */
        if (fig && y < window.innerHeight * 1.2) {
            fig.style.transform = 'translateY(' + Math.round(y * 0.4) + 'px) scale(' + (1 + y * 0.0002) + ')';
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

    /* Handoff: saat Ruang Bunyi masuk, kenong berdenyut sebagai "estafet" dari foto hero */
    var bunyi = document.getElementById('arsip-bunyi');
    var kenong = document.querySelector('.pad-kenong');
    if (bunyi && kenong && 'IntersectionObserver' in window) {
        var fired = false;
        new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting && !fired) {
                    fired = true;
                    setTimeout(function () {
                        kenong.classList.add('handoff');
                        setTimeout(function () { kenong.classList.remove('handoff'); }, 1900);
                    }, 350);
                } else if (!en.isIntersecting) {
                    fired = false;
                }
            });
        }, { threshold: 0.35 }).observe(bunyi);
    }
})();
