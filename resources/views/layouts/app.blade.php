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
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}?v=13">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}?v=13">
    <link rel="stylesheet" href="{{ asset('assets/css/hero.css') }}?v=13">
    <link rel="stylesheet" href="{{ asset('assets/css/search.css') }}?v=13">
    <link rel="stylesheet" href="{{ asset('assets/css/card.css') }}?v=13">
    <link rel="stylesheet" href="{{ asset('assets/css/intro.css') }}?v=13">
    <link rel="stylesheet" href="{{ asset('assets/css/cursor.css') }}?v=13">
    <link rel="stylesheet" href="{{ asset('assets/css/gamelan.css') }}?v=13">
    <link rel="stylesheet" href="{{ asset('assets/css/map.css') }}?v=13">
    <link rel="stylesheet" href="{{ asset('assets/css/detail.css') }}?v=13">
    <link rel="stylesheet" href="{{ asset('assets/css/quiz-modal.css') }}?v=13">
    <link rel="stylesheet" href="{{ asset('assets/css/quiz-global.css') }}?v=13">
    <link rel="stylesheet" href="{{ asset('assets/css/quiz-result.css') }}?v=13">
    <link rel="stylesheet" href="{{ asset('assets/css/animation.css') }}?v=13">
    <link rel="stylesheet" href="{{ asset('assets/css/tutorial.css') }}?v=13">
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css') }}?v=13">
</head>

<body>
    <!-- OMBAK NUSANTARA: lapisan gelombang beranimasi -->
    <div class="ombak-bg" aria-hidden="true">
        <svg class="ombak-layer l1" viewBox="0 0 2400 200" preserveAspectRatio="none">
            <path d="M0,100 C200,40 400,160 600,100 C800,40 1000,160 1200,100 C1400,40 1600,160 1800,100 C2000,40 2200,160 2400,100" />
        </svg>
        <svg class="ombak-layer l2" viewBox="0 0 2400 200" preserveAspectRatio="none">
            <path d="M0,120 C240,60 480,180 720,120 C960,60 1200,180 1440,120 C1680,60 1920,180 2160,120 C2280,90 2340,150 2400,120" />
        </svg>
        <svg class="ombak-layer l3" viewBox="0 0 2400 200" preserveAspectRatio="none">
            <path d="M0,80 C300,130 600,30 900,80 C1200,130 1500,30 1800,80 C2100,130 2250,50 2400,80" />
        </svg>
    </div>
    <!-- ================= CURSOR GLOW ================= -->

    <!-- GLOBAL ELEMENT -->
    <div id="tooltip"></div>
    <div id="topLoader"></div>
    <div class="page-sweep" aria-hidden="true"></div>

    @include('partials.navbar')

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
    <script src="{{ asset('assets/js/tooltip.js') }}?v=13" defer></script>
    <script src="{{ asset('assets/js/quiz.js') }}?v=13" defer></script>

    <!-- ================= MAIN ================= -->
    <script type="module" src="{{ asset('assets/js/app.js') }}?v=13"></script>
    <!-- ================= TUTORIAL ================= -->
    <script src="{{ asset('assets/js/tutorial.js') }}?v=13" defer></script>
    <!-- ================= MAP ================= -->
    <script src="{{ asset('assets/js/map.js') }}?v=13" defer></script>

    <script src="{{ asset('assets/js/search.js') }}?v=13"></script>
    <script src="{{ asset('assets/js/intro.js') }}?v=13" defer></script>
    <script src="{{ asset('assets/js/cursor.js') }}?v=13" defer></script>
    <script src="{{ asset('assets/js/reveal.js') }}?v=13" defer></script>
    <script src="{{ asset('assets/js/gamelan.js') }}?v=13" defer></script>

    @stack('scripts')

    @include('components.tutorial')

</body>

</html>
