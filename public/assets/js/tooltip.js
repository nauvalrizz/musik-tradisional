const tooltip = document.getElementById('tooltip');
const mapClickSound = new Audio('/assets/sound/map-click.mp3');
mapClickSound.volume = 0.5;
const paths = document.querySelectorAll('.map-svg path');

paths.forEach(path => {

    // ======================
    // TOOLTIP
    // ======================
    path.addEventListener('mouseenter', () => {
        tooltip.textContent = path.dataset.nama || '';
        tooltip.style.opacity = 1;

        // highlight focus
        paths.forEach(p => p.classList.add('dim'));
        path.classList.remove('dim');
    });

    path.addEventListener('mousemove', (e) => {
        tooltip.style.left = e.clientX + 'px';
        tooltip.style.top = e.clientY + 'px';
    });

    path.addEventListener('mouseleave', () => {
        tooltip.style.opacity = 0;

        // reset opacity
        paths.forEach(p => p.classList.remove('dim'));
    });


    // ======================
    // CLICK EFFECT + SOUND
    // ======================
    path.addEventListener('click', (e) => {
        //sound
        if (mapClickSound) {
            mapClickSound.pause();
            mapClickSound.currentTime = 0;
            mapClickSound.play().catch(() => { });
        }

        // 🌊 ripple effect
        const ripple = document.createElement("span");
        ripple.classList.add("ripple");

        document.body.appendChild(ripple);

        ripple.style.left = e.clientX + "px";
        ripple.style.top = e.clientY + "px";

        setTimeout(() => ripple.remove(), 600);

        // ⏱ delay dikit biar efek keliatan
        setTimeout(() => {
            const link = path.dataset.link;
            if (link) window.location.href = link;
        }, 150);
    });

});