<nav class="navbar navbar-expand-lg navbar-dark px-4 fixed-top">
    <a class="navbar-brand" href="/">Musik <span class="brand-bronze">Nusantara</span></a>

    <div class="ms-auto d-flex align-items-center gap-3">

        @if (session('explored_count', 0) >= 3)
            <a href="/quiz-global" class="nav-link">Kuis</a>
        @endif

    </div>
</nav>
