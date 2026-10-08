<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Musik Nusantara')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        try {
            if (sessionStorage.getItem('tabuhan_shown')) {
                document.documentElement.classList.add('tabuhan-done');
            }
        } catch (e) {}
    </script>

    <!-- ✅ BOOTSTRAP (HARUS DULU) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- ✅ CSS LU (HARUS TERAKHIR BIAR MENANG) -->
    <link rel="stylesheet" href="{{ asset('assets/css/app-v88.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar-v71.css') }}?v=53">
    <link rel="stylesheet" href="{{ asset('assets/css/hero.css') }}?v=53">
    <link rel="stylesheet" href="{{ asset('assets/css/search.css') }}?v=53">
    <link rel="stylesheet" href="{{ asset('assets/css/card.css') }}?v=53">
    <link rel="stylesheet" href="{{ asset('assets/css/intro.css') }}?v=53">
    <link rel="stylesheet" href="{{ asset('assets/css/cursor.css') }}?v=53">
    <link rel="stylesheet" href="{{ asset('assets/css/gamelan.css') }}?v=53">
    <link rel="stylesheet" href="{{ asset('assets/css/map.css') }}?v=53">
    <link rel="stylesheet" href="{{ asset('assets/css/detail.css') }}?v=53">
    <link rel="stylesheet" href="{{ asset('assets/css/quiz-modal.css') }}?v=53">
    <link rel="stylesheet" href="{{ asset('assets/css/quiz-global.css') }}?v=53">
    <link rel="stylesheet" href="{{ asset('assets/css/quiz-result.css') }}?v=53">
    <link rel="stylesheet" href="{{ asset('assets/css/animation.css') }}?v=53">
    <link rel="stylesheet" href="{{ asset('assets/css/tutorial.css') }}?v=53">
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css') }}?v=53">
</head>

<body>
<div id="loader">
    <div class="loader-m">M</div>
    <div class="loader-bar"><i></i></div>
    <div class="loader-text">Memuat Arsip</div>
</div>
    <!-- OMBAK NUSANTARA: lapisan gelombang beranimasi -->


    <!-- ================= CURSOR GLOW ================= -->

    <!-- GLOBAL ELEMENT -->
    <div id="tooltip"></div>
    <div id="topLoader"></div>
    <div class="page-sweep" aria-hidden="true"></div>

    @include('partials.navbar')

    <div class="grain" aria-hidden="true"></div>
    <div class="main-wrapper">
        @yield('content')
    </div>

    @include('partials.footer')

    <!-- GLOBAL UI -->
    <div id="toast" class="custom-toast"></div>

    <!-- LIGHTBOX GLOBAL -->
    <div id="imgModal" class="img-modal" aria-hidden="true">
        <span class="img-close" role="button" aria-label="Tutup">&times;</span>
        <img class="img-modal-content" id="imgZoom" alt="">
        <p class="img-caption" id="imgCaption"></p>
    </div>

    <!-- ================= LIBRARY ================= -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js" defer></script>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js" defer></script>

    <!-- ================= CUSTOM JS ================= -->
    <script src="{{ asset('assets/js/tooltip.js') }}?v=53" defer></script>
    <script src="{{ asset('assets/js/quiz.js') }}?v=53" defer></script>

    <!-- ================= MAIN ================= -->
    <script type="module" src="{{ asset('assets/js/app-v81.js') }}"></script>
    <!-- ================= TUTORIAL ================= -->
    <script src="{{ asset('assets/js/tutorial.js') }}?v=53" defer></script>
    <!-- ================= MAP ================= -->
    <script src="{{ asset('assets/js/map-batch1.js') }}?v=84" defer></script>
    <script src="{{ asset('assets/js/map-batch2.js') }}?v=85" defer></script>

    <script src="{{ asset('assets/js/search.js') }}?v=53"></script>
    <script src="{{ asset('assets/js/intro.js') }}?v=53" defer></script>
    <script src="{{ asset('assets/js/cursor.js') }}?v=53" defer></script>
    <script src="{{ asset('assets/js/reveal-v60.js') }}" defer></script>
    <script src="{{ asset('assets/js/cinematic-v68.js') }}?v=53" defer></script>
    <script src="{{ asset('assets/js/tilt3d.js') }}?v=53" defer></script>
    <script src="{{ asset('assets/js/gamelan.js') }}?v=53" defer></script>

    @stack('scripts')

    @include('components.tutorial')

<script>
/* v83 — zoom SIMPLE, tanpa module */
(function() {
    function openModal(src, alt) {
        var m = document.getElementById('imgModal');
        var im = document.getElementById('imgZoom');
        var cap = document.getElementById('imgCaption');
        if (!m || !im) return;
        im.src = src;
        im.alt = alt || '';
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
        if (img) {
            openModal(img.currentSrc || img.src, img.alt);
            return;
        }
        if (e.target.closest('.img-close') || e.target.id === 'imgModal') {
            closeModal();
        }
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
})();
</script>
</body>

</html>
