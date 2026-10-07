export default function initImageModal() {
    const img = document.getElementById("previewImg");
    const modal = document.getElementById("imgModal");
    const modalImg = document.getElementById("imgZoom");
    const closeBtn = document.querySelector(".img-close");

    if (!img || !modal || !modalImg || !closeBtn) return; // 🔥 ini penting

    img.onclick = function () {
        modal.style.display = "flex";
        modalImg.src = this.src;
    };

    closeBtn.onclick = function () {
        modal.style.display = "none";
    };

    modal.onclick = function (e) {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    };
}