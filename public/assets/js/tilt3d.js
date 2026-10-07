/* 3D Tilt pro max — kartu koleksi miring ngikutin mouse, ada glare. */
(function () {
    if (matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    if (matchMedia('(hover: none)').matches) return; // HP: skip, pakai tap aja

    document.querySelectorAll('.flip-card').forEach(function (card) {
        var glare = document.createElement('span');
        glare.className = 'tilt-glare';
        card.appendChild(glare);

        card.addEventListener('pointermove', function (e) {
            var r = card.getBoundingClientRect();
            var x = (e.clientX - r.left) / r.width - 0.5;
            var y = (e.clientY - r.top) / r.height - 0.5;
            /* tilt di outer card, flip tetap di inner — ga tabrakan */
            card.style.transform =
                'perspective(900px) rotateY(' + (x * 8) + 'deg) rotateX(' + (-y * 8) + 'deg)';
            glare.style.opacity = '1';
            glare.style.background =
                'radial-gradient(circle at ' + ((x + 0.5) * 100) + '% ' + ((y + 0.5) * 100) + '%, rgba(255,240,200,.22), transparent 60%)';
        });
        card.addEventListener('pointerleave', function () {
            card.style.transform = '';
            glare.style.opacity = '0';
        });
    });

    /* Hero: parallax 3D tipis ngikutin mouse */
    var hero = document.querySelector('.gallery-hero');
    var fig = document.querySelector('.gallery-fig');
    if (hero && fig) {
        hero.addEventListener('pointermove', function (e) {
            var x = (e.clientX / window.innerWidth - 0.5);
            var y = (e.clientY / window.innerHeight - 0.5);
            fig.style.transform =
                'perspective(1200px) rotateY(' + (x * 6) + 'deg) rotateX(' + (-y * 6) + 'deg)';
        });
        hero.addEventListener('pointerleave', function () { fig.style.transform = ''; });
    }
})();
