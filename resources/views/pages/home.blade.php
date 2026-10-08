@extends('layouts.app')

@section('content')

<main id="main">
    {{-- ═══════════ HERO ═══════════ --}}
    <section class="hero" id="top">
        <div class="frame hero-layout">
            <div class="hero-copy">
                <div class="archive-label">001 — Arsip Terbuka — Indonesia</div>
                <h1>ARSIP <em>Bunyi</em><span class="title-nusantara">NUSANTARA</span></h1>
            </div>
            <aside class="hero-side">
                <p>Jejak bunyi dari logam, kayu, kulit, bambu, dan dawai — ditata sebagai ruang dengar untuk warisan yang terus hidup.</p>
                <div class="cta-row">
                    <a class="btn btn-primary" href="#bunyi">Mulai mendengar <span class="arrow" aria-hidden="true">&rarr;</span></a>
                    <a class="btn" href="#koleksi">Buka koleksi</a>
                </div>
            </aside>
        </div>
        <div class="scroll-mark" aria-hidden="true">GULIR UNTUK MENELUSURI</div>
    </section>

    {{-- ═══════════ BATIK DIVIDER ═══════════ --}}
    <div class="batik-band" aria-hidden="true">
        <svg viewBox="0 0 1200 120" role="presentation">
            <path d="M0 60C30 10 90 10 120 60S210 110 240 60 330 10 360 60 450 110 480 60 570 10 600 60 690 110 720 60 810 10 840 60 930 110 960 60 1050 10 1080 60 1170 110 1200 60M0 60C30 110 90 110 120 60S210 10 240 60 330 110 360 60 450 10 480 60 570 110 600 60 690 10 720 60 810 110 840 60 930 10 960 60 1050 110 1080 60 1170 10 1200 60M60 0C10 30 10 90 60 120M60 0C110 30 110 90 60 120M180 0C130 30 130 90 180 120M180 0C230 30 230 90 180 120M300 0C250 30 250 90 300 120M300 0C350 30 350 90 300 120M420 0C370 30 370 90 420 120M420 0C470 30 470 90 420 120M540 0C490 30 490 90 540 120M540 0C590 30 590 90 540 120M660 0C610 30 610 90 660 120M660 0C710 30 710 90 660 120M780 0C730 30 730 90 780 120M780 0C830 30 830 90 780 120M900 0C850 30 850 90 900 120M900 0C950 30 950 90 900 120M1020 0C970 30 970 90 1020 120M1020 0C1070 30 1070 90 1020 120M1140 0C1090 30 1090 90 1140 120M1140 0C1190 30 1190 90 1140 120"/>
        </svg>
    </div>

    {{-- ═══════════ KISAH ═══════════ --}}
    <section class="section" id="kisah" aria-labelledby="kisah-title">
        <div class="frame">
            <header class="section-head reveal">
                <div class="section-index" aria-hidden="true">00</div>
                <div>
                    <div class="archive-label">Prolog — Kisah</div>
                    <h2 id="kisah-title">Bunyi adalah cara ingatan bergerak.</h2>
                    <p>Instrumen tidak berdiri sendiri. Ia hidup melalui pembuat, pemain, upacara, panggung, dan ruang sosialnya.</p>
                </div>
            </header>
            <div class="story-grid">
                <aside class="story-note reveal">
                    <div class="archive-label">Catatan kurator / 001</div>
                    <p>Setiap alat musik adalah ingatan yang bisa disentuh.</p>
                    <span class="source-link">Arsip MuSantara</span>
                </aside>
                <article class="story-copy reveal">
                    <p>Di Nusantara, musik diwariskan lewat tubuh: pola pukulan diingat tangan, laras dikenali telinga, dan permainan dipelajari bersama.</p>
                    <p>Arsip ini menata jejak — nama, bahan, wilayah, fungsi, dan hubungan antarbunyi — agar pembaca dapat masuk dari rasa ingin tahu.</p>
                </article>
            </div>
        </div>
    </section>

    {{-- ═══════════ RUANG BUNYI (GAMELAN) ═══════════ --}}
    <section class="section sound-section" id="bunyi" aria-labelledby="bunyi-title">
        <div class="frame">
            <header class="section-head reveal">
                <div class="section-index" aria-hidden="true">01</div>
                <div>
                    <div class="archive-label">Ruang Dengar — Gamelan</div>
                    <h2 id="bunyi-title">Sentuh satu. Dengarkan ruangnya.</h2>
                    <p>Lima warna bunyi disusun sebagai sketsa sonik. Ketuk untuk memicu nada.</p>
                </div>
            </header>
            <div class="ensemble reveal">
                <div class="instrument-stage">
                    <div class="stage-rings" aria-hidden="true"></div>
                    <div class="sound-readout" aria-live="polite">
                        <span id="sound-kicker">SIAP DIDENGARKAN</span>
                        <strong id="sound-name">Gamelan</strong>
                        <div class="wave" id="wave" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
                    </div>
                </div>
                <div class="pads" role="group" aria-label="Lima alat gamelan yang bisa diketuk">
                    <button type="button" class="pad gamelan-pad" data-instrument="gong" data-freq="98" aria-label="Gong">
                        <span class="pad-number">01</span><span class="pad-name">Gong</span><span class="pad-note">DALAM</span>
                    </button>
                    <button type="button" class="pad gamelan-pad" data-instrument="kempul" data-freq="147" aria-label="Kempul">
                        <span class="pad-number">02</span><span class="pad-name">Kempul</span><span class="pad-note">BULAT</span>
                    </button>
                    <button type="button" class="pad gamelan-pad" data-instrument="kenong" data-freq="220" aria-label="Kenong">
                        <span class="pad-number">03</span><span class="pad-name">Kenong</span><span class="pad-note">TEGAS</span>
                    </button>
                    <button type="button" class="pad gamelan-pad" data-instrument="saron" data-freq="392" aria-label="Saron">
                        <span class="pad-number">04</span><span class="pad-name">Saron</span><span class="pad-note">TERANG</span>
                    </button>
                    <button type="button" class="pad gamelan-pad" data-instrument="bonang" data-freq="523.25" aria-label="Bonang">
                        <span class="pad-number">05</span><span class="pad-name">Bonang</span><span class="pad-note">RINCI</span>
                    </button>
                </div>
            </div>
            <p class="audio-note">Nyalakan suara perangkatmu, lalu ketuk alatnya.</p>
        </div>
    </section>

    {{-- ═══════════ TELUSURI ═══════════ --}}
    <section class="section" id="telusuri" aria-labelledby="telusuri-title">
        <div class="frame">
            <header class="section-head reveal">
                <div class="section-index" aria-hidden="true">02</div>
                <div>
                    <div class="archive-label">Indeks — Telusuri</div>
                    <h2 id="telusuri-title">Cari lewat nama, bahan, atau wilayah.</h2>
                    <p>Ketik nama alat musik — misalnya <b>sasando</b> atau <b>gamelan</b> — lalu tekan Cari.</p>
                </div>
            </header>
            <div class="search-shell reveal">
                <div class="search-copy">
                    <h3>Satu pintu untuk banyak bunyi.</h3>
                    <p>Telusuri seluruh arsip alat musik tradisional Indonesia.</p>
                </div>
                <div class="search-area">
                    @include('components.search-box')
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════ PULAU ═══════════ --}}
    <section class="section" id="pulau" aria-labelledby="pulau-title">
        <div class="frame">
            <header class="section-head reveal">
                <div class="section-index" aria-hidden="true">03</div>
                <div>
                    <div class="archive-label">Peta Dengar — 07 Titik</div>
                    <h2 id="pulau-title">Bentang bunyi lintas pulau.</h2>
                    <p>Pilih satu pulau untuk menelusuri alat musik dari wilayah tersebut.</p>
                </div>
            </header>
            <div class="reveal">
                @include('partials.map')
                <div id="island-data" data-counts='@json($islandCounts ?? [])' style="display:none"></div>
            </div>
        </div>
    </section>

    {{-- ═══════════ KOLEKSI ═══════════ --}}
    <section class="section" id="koleksi" aria-labelledby="koleksi-title">
        <div class="frame">
            <header class="section-head reveal">
                <div class="section-index" aria-hidden="true">04</div>
                <div>
                    <div class="archive-label">Pilihan Koleksi</div>
                    <h2 id="koleksi-title">Buka arsipnya.</h2>
                    <p>Ketuk kartu untuk membaca kisah tiap alat musik.</p>
                </div>
            </header>
            <div class="collection-grid reveal">
                @foreach($featured as $item)
                    @php
                        $url = '/alat/' . $item->id;
                        $origin = optional($item->pulau)->nama;
                    @endphp
                    <a href="{{ url($url) }}" class="artifact-card" aria-label="{{ $item->nama }}">
                        <div class="artifact-figure">
                            <img src="{{ $item->gambar ? gambar_alat($item->gambar) : asset('assets/img/default.png') }}"
                                alt="{{ $item->nama }}" loading="lazy">
                        </div>
                        <div class="artifact-meta">
                            <div class="archive-label">FIG. {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}{{ $origin ? ' — ' . $origin : '' }}</div>
                            <h3>{{ $item->nama }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($item->deskripsi, 120) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════ QUIZ ═══════════ --}}
    <section class="quiz" aria-labelledby="quiz-title">
        <div class="frame quiz-copy reveal">
            <div class="archive-label">Uji Telinga</div>
            <h2 id="quiz-title">Seberapa jauh kamu <em>mendengar?</em></h2>
            <p>Tujuh pertanyaan. Satu arsip penuh bunyi.</p>
            <a href="/quiz-global" class="btn btn-primary">Mulai kuis <span class="arrow" aria-hidden="true">&rarr;</span></a>
        </div>
    </section>
</main>

@endsection
