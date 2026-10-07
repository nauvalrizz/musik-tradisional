document.querySelectorAll('.map-svg path').forEach(el => {
    el.addEventListener('click', function () {
        const slug = this.dataset.slug;

        if (!slug) {
            console.warn('Slug tidak ditemukan:', this);
            return;
        }

        window.location.href = '/pulau/' + slug;
    });
});