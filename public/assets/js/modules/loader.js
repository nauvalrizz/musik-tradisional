// ================= CORE =================
function showContentInstant() {
    const loader = document.getElementById('pageLoader');
    const content = document.getElementById('realContent');

    if (!loader || !content) return;

    loader.style.display = 'none';
    loader.style.opacity = '0';

    content.style.display = 'block';
    content.classList.add('show');

    if (typeof AOS !== 'undefined') {
        AOS.refreshHard();
    }
}

// ================= NORMAL LOAD =================
function initLoaderNormal() {
    const loader = document.getElementById('pageLoader');
    const content = document.getElementById('realContent');

    if (!loader || !content) return;

    loader.style.display = 'block';
    loader.style.opacity = '1';

    content.style.display = 'none';
    content.classList.remove('show');

    setTimeout(() => {
        loader.style.opacity = '0';

        setTimeout(() => {
            loader.style.display = 'none';

            content.style.display = 'block';
            content.classList.add('show');

            if (typeof AOS !== 'undefined') {
                AOS.refreshHard();
            }

        }, 200);
    }, 300);
}

// ================= LOAD =================
window.addEventListener('load', () => {
    initLoaderNormal();
});

// ================= BACK / FORWARD FIX =================
window.addEventListener('pageshow', () => {

    // 🔥 deteksi tipe navigation
    const navType = performance.getEntriesByType("navigation")[0]?.type;

    if (navType === "back_forward") {
        // 👉 kalau back browser → tetap pakai loader (bukan instant)
        initLoaderNormal();
    } else {
        // 👉 kalau normal → langsung tampil
        showContentInstant();
    }
});