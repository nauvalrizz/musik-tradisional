<nav class="navbar navbar-expand-lg navbar-dark px-4 fixed-top" id="mainNav">
    <a class="navbar-brand" href="{{ url('/') }}">
        <span class="brand-stamp">M</span>
        <span class="brand-text">MuSantara<em>Arsip</em></span>
    </a>

    <div class="ms-auto d-flex align-items-center">
        <div class="nav-sections d-none d-lg-flex">
            <a href="{{ url('/#arsip-bunyi') }}" class="nav-sec"><span>01</span>Bunyi</a>
            <a href="{{ url('/#telusuri') }}" class="nav-sec"><span>02</span>Telusuri</a>
            <a href="{{ url('/#pulau') }}" class="nav-sec"><span>03</span>Pulau</a>
            <a href="{{ url('/#koleksi') }}" class="nav-sec"><span>04</span>Koleksi</a>
        </div>

        @if (session('explored_count', 0) >= 3)
            <a href="/quiz-global" class="nav-quiz">Kuis</a>
        @endif
    </div>
</nav>
