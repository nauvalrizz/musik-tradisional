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

    function close() {
        modal.classList.remove("open");
        modal.setAttribute("aria-hidden", "true");
        document.body.style.overflow = "";
    }

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
