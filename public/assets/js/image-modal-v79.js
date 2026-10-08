// Lightbox universal: tiap <img data-zoom> bisa diketuk untuk tampil fullscreen.
export default function initImageModal() {
    const modal = document.getElementById("imgModal");
    const modalImg = document.getElementById("imgZoom");
    const caption = document.getElementById("imgCaption");
    const closeBtn = document.querySelector(".img-close");

    if (!modal || !modalImg || !closeBtn) return;

    function open(src, alt) {
        modalImg.src = src;
        modalImg.alt = alt || "";
        if (caption) caption.textContent = alt || "";
        modal.classList.add("open");
        modal.setAttribute("aria-hidden", "false");
        document.body.style.overflow = "hidden";
    }

    var scale = 1, startDist = 0, startScale = 1;
    var lastTap = 0;

    function setTransform() {
        modalImg.style.transform = 'scale(' + scale + ')';
    }

    function close() {
        modal.classList.remove("open");
        modal.setAttribute("aria-hidden", "true");
        document.body.style.overflow = "";
        scale = 1; setTransform();
    }

    // Pinch zoom
    modalImg.addEventListener('touchstart', function(e) {
        if (e.touches.length === 2) {
            startDist = Math.hypot(
                e.touches[0].clientX - e.touches[1].clientX,
                e.touches[0].clientY - e.touches[1].clientY
            );
            startScale = scale;
        }
    }, { passive: true });
    modalImg.addEventListener('touchmove', function(e) {
        if (e.touches.length === 2) {
            e.preventDefault();
            var d = Math.hypot(
                e.touches[0].clientX - e.touches[1].clientX,
                e.touches[0].clientY - e.touches[1].clientY
            );
            scale = Math.min(Math.max(startScale * (d / startDist), 1), 4);
            setTransform();
        }
    }, { passive: false });

    // Double-tap zoom
    modalImg.addEventListener('touchend', function(e) {
        var now = Date.now();
        if (now - lastTap < 300 && e.touches.length === 0) {
            scale = scale > 1 ? 1 : 2.5;
            setTransform();
            e.preventDefault();
        }
        lastTap = now;
    });

    // Swipe down tutup (mobile)
    var startY = 0;
    modal.addEventListener('touchstart', function(e) {
        startY = e.touches[0].clientY;
    }, { passive: true });
    modal.addEventListener('touchend', function(e) {
        var dy = e.changedTouches[0].clientY - startY;
        if (dy > 100 && scale === 1) close();
    });

    document.querySelectorAll("img[data-zoom]").forEach(function (img) {
        img.style.cursor = "zoom-in";
        img.addEventListener("click", function () {
            open(img.currentSrc || img.src, img.alt);
        });
    });

    closeBtn.addEventListener("click", close);
    modal.addEventListener("click", function (e) {
        if (e.target === modal) close();
    });
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape" && modal.classList.contains("open")) close();
    });
}
