document.addEventListener("DOMContentLoaded", () => {

    const input = document.getElementById("searchInput");
    const dropdown = document.getElementById("liveSearchResult");
    const kategori = document.getElementById("kategoriInput");

    let timeout;

    function getImagePath(gambar) {
        // 🔥 kalau sudah full URL / path
        if (gambar.startsWith('http') || gambar.startsWith('/')) {
            return gambar;
        }
        // 🔥 kalau cuma filename
        return `/img/alat-musik/${gambar}`;
    }

    function renderItem(item) {
        return `
        <a href="/alat/${item.id}" class="live-item">
            <img src="${getImagePath(item.gambar)}" onerror="this.src='/img/default.png'">
            <div class="live-text">
                <h6>${item.nama}</h6>
                <span>${item.asal_daerah}</span>
            </div>
        </a>
        `;
    }

    function fetchSearch() {
        const q = input.value.trim();

        if (!q) {
            dropdown.style.display = "none";
            document.body.classList.remove("search-active");
            return;
        }

        fetch(`/live-search?q=${q}&kategori=${kategori.value}`)
            .then(res => res.json())
            .then(data => {

                if (!data.length) {
                    dropdown.innerHTML = `<p class="text-center text-muted">Tidak ditemukan</p>`;
                } else {
                    dropdown.innerHTML = data.map(renderItem).join('');
                }

                dropdown.style.display = "block";
                document.body.classList.add("search-active");
            });
    }

    input.addEventListener("input", () => {
        clearTimeout(timeout);
        timeout = setTimeout(fetchSearch, 250);
    });

    // 🔥 INI TARUH DI SINI (PENTING)
    kategori.addEventListener("change", () => {
        fetchSearch();
    });

    document.addEventListener("click", (e) => {
        if (!e.target.closest(".search-box-wrapper")) {
            dropdown.style.display = "none";
            document.body.classList.remove("search-active");
        }
    });

});