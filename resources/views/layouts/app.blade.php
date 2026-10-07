<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Musik Nusantara')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ✅ BOOTSTRAP (HARUS DULU) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- ✅ CSS LU (HARUS TERAKHIR BIAR MENANG) -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('assets/css/hero.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('assets/css/search.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('assets/css/card.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('assets/css/intro.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('assets/css/cursor.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('assets/css/gamelan.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('assets/css/map.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('assets/css/detail.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('assets/css/quiz-modal.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('assets/css/quiz-global.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('assets/css/quiz-result.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('assets/css/animation.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('assets/css/tutorial.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css') }}?v=5">
</head>

<body>
    <!-- ================= CURSOR GLOW ================= -->

    <!-- GLOBAL ELEMENT -->
    <div id="tooltip"></div>
    <div id="topLoader"></div>

    @include('partials.navbar')

    <div class="main-wrapper">
        @yield('content')
    </div>

    @include('partials.footer')

    <!-- GLOBAL UI -->
    <div id="toast" class="custom-toast"></div>

    <!-- ================= LIBRARY ================= -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js" defer></script>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js" defer></script>

    <!-- ================= CUSTOM JS ================= -->
    <script src="{{ asset('assets/js/tooltip.js') }}?v=5" defer></script>
    <script src="{{ asset('assets/js/quiz.js') }}?v=5" defer></script>

    <!-- ================= MAIN ================= -->
    <script type="module" src="{{ asset('assets/js/app.js') }}?v=5"></script>
    <!-- ================= TUTORIAL ================= -->
    <script src="{{ asset('assets/js/tutorial.js') }}?v=5" defer></script>
    <!-- ================= MAP ================= -->
    <script src="{{ asset('assets/js/map.js') }}?v=5" defer></script>

    <script src="{{ asset('assets/js/search.js') }}?v=5"></script>
    <script src="{{ asset('assets/js/intro.js') }}?v=5" defer></script>
    <script src="{{ asset('assets/js/cursor.js') }}?v=5" defer></script>
    <script src="{{ asset('assets/js/reveal.js') }}?v=5" defer></script>
    <script src="{{ asset('assets/js/gamelan.js') }}?v=5" defer></script>

    @stack('scripts')

    @include('components.tutorial')

</body>

</html>
