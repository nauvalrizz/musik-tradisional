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
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}?v=19">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}?v=19">
    <link rel="stylesheet" href="{{ asset('assets/css/hero.css') }}?v=19">
    <link rel="stylesheet" href="{{ asset('assets/css/search.css') }}?v=19">
    <link rel="stylesheet" href="{{ asset('assets/css/card.css') }}?v=19">
    <link rel="stylesheet" href="{{ asset('assets/css/intro.css') }}?v=19">
    <link rel="stylesheet" href="{{ asset('assets/css/cursor.css') }}?v=19">
    <link rel="stylesheet" href="{{ asset('assets/css/gamelan.css') }}?v=19">
    <link rel="stylesheet" href="{{ asset('assets/css/map.css') }}?v=19">
    <link rel="stylesheet" href="{{ asset('assets/css/detail.css') }}?v=19">
    <link rel="stylesheet" href="{{ asset('assets/css/quiz-modal.css') }}?v=19">
    <link rel="stylesheet" href="{{ asset('assets/css/quiz-global.css') }}?v=19">
    <link rel="stylesheet" href="{{ asset('assets/css/quiz-result.css') }}?v=19">
    <link rel="stylesheet" href="{{ asset('assets/css/animation.css') }}?v=19">
    <link rel="stylesheet" href="{{ asset('assets/css/tutorial.css') }}?v=19">
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css') }}?v=19">
</head>

<body>
    <!-- OMBAK NUSANTARA: lapisan gelombang beranimasi -->
    <div class="awan-bg" aria-hidden="true"></div>
    <canvas id="ripple-field" aria-hidden="true"></canvas>
    <div class="ombak-bg" aria-hidden="true">
        <svg class="ombak-layer l1" viewBox="0 0 2400 200" preserveAspectRatio="none">
            <path d="M0,100 C100,40 200,40 300,100 C400,160 500,160 600,100 C700,40 800,40 900,100 C1000,160 1100,160 1200,100 C1300,40 1400,40 1500,100 C1600,160 1700,160 1800,100 C1900,40 2000,40 2100,100 C2200,160 2300,160 2400,100" />
        </svg>
        <svg class="ombak-layer l2" viewBox="0 0 2400 200" preserveAspectRatio="none">
            <path d="M0,100 C133,160 267,160 400,100 C533,40 667,40 800,100 C933,160 1067,160 1200,100 C1333,40 1467,40 1600,100 C1733,160 1867,160 2000,100 C2133,40 2267,40 2400,100" />
        </svg>
        <svg class="ombak-layer l3" viewBox="0 0 2400 200" preserveAspectRatio="none">
            <path d="M0,100 C80,60 160,60 240,100 C320,140 400,140 480,100 C560,60 640,60 720,100 C800,140 880,140 960,100 C1040,60 1120,60 1200,100 C1280,140 1360,140 1440,100 C1520,60 1600,60 1680,100 C1760,140 1840,140 1920,100 C2000,60 2080,60 2160,100 C2240,140 2320,140 2400,100" />
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
    <script src="{{ asset('assets/js/tooltip.js') }}?v=19" defer></script>
    <script src="{{ asset('assets/js/quiz.js') }}?v=19" defer></script>

    <!-- ================= MAIN ================= -->
    <script type="module" src="{{ asset('assets/js/app.js') }}?v=19"></script>
    <!-- ================= TUTORIAL ================= -->
    <script src="{{ asset('assets/js/tutorial.js') }}?v=19" defer></script>
    <!-- ================= MAP ================= -->
    <script src="{{ asset('assets/js/map.js') }}?v=19" defer></script>

    <script src="{{ asset('assets/js/search.js') }}?v=19"></script>
    <script src="{{ asset('assets/js/intro.js') }}?v=19" defer></script>
    <script src="{{ asset('assets/js/cursor.js') }}?v=19" defer></script>
    <script src="{{ asset('assets/js/reveal.js') }}?v=19" defer></script>
    <script src="{{ asset('assets/js/gamelan.js') }}?v=19" defer></script>

    @stack('scripts')

    @include('components.tutorial')

</body>

</html>
