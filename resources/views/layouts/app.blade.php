<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'MuSantara — Arsip Bunyi Nusantara')</title>
    <meta name="description" content="Arsip bunyi alat musik tradisional Indonesia.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Fraunces:opsz,wght@9..144,300;9..144,500;9..144,600&family=Manrope:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/app-v110.css') }}">
</head>
<body>
    <a class="skip" href="#main">Lewati ke konten</a>
    @include('partials.navbar')
    <main id="main">
        @yield('content')
    </main>
    @include('partials.footer')

    <!-- Lightbox (v83 simple) -->
    <div id="imgModal" class="img-modal" aria-hidden="true">
        <span class="img-close" role="button" aria-label="Tutup">&times;</span>
        <img class="img-modal-content" id="imgZoom" alt="">
        <p class="img-caption" id="imgCaption"></p>
    </div>

    <script src="{{ asset('assets/js/gamelan.js') }}?v=110" defer></script>
    <script src="{{ asset('assets/js/search.js') }}?v=110" defer></script>
    @stack('scripts')
<script>
(function() {
    function openModal(src, alt) {
        var m = document.getElementById('imgModal');
        var im = document.getElementById('imgZoom');
        var cap = document.getElementById('imgCaption');
        if (!m || !im) return;
        im.src = src; im.alt = alt || '';
        if (cap) cap.textContent = alt || '';
        m.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeModal() {
        var m = document.getElementById('imgModal');
        if (!m) return;
        m.classList.remove('open');
        document.body.style.overflow = '';
    }
    document.addEventListener('click', function(e) {
        var img = e.target.closest('img[data-zoom]');
        if (img) { openModal(img.currentSrc || img.src, img.alt); return; }
        if (e.target.closest('.img-close') || e.target.id === 'imgModal') closeModal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
    // Reveal on scroll
    var io = new IntersectionObserver(function(es) {
        es.forEach(function(en) {
            if (en.isIntersecting) { en.target.classList.add('visible'); io.unobserve(en.target); }
        });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal').forEach(function(el) { io.observe(el); });
    // Batik draw
    var bb = document.querySelector('.batik-band');
    if (bb) {
        new IntersectionObserver(function(es, o) {
            es.forEach(function(en) { if (en.isIntersecting) { bb.classList.add('drawn'); o.disconnect(); } });
        }, { threshold: 0.3 }).observe(bb);
    }
})();
</script>
</body>
</html>
