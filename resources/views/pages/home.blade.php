@extends('layouts.app')

@section('content')

{{-- ═══════════ HERO — alat bunyi di awal ═══════════ --}}
<section class="hero hero--play">
    <div class="container">
        <p class="hero-eyebrow">Arsip Bunyi Nusantara</p>
        <h1 class="hero-title-sm">Ketuk. Dengar. <em>Kenali.</em></h1>
        <p class="hero-sub">MuSantara mendokumentasikan alat musik tradisional Indonesia — bunyinya, asalnya, ceritanya.</p>

        <div class="gamelan" role="group" aria-label="Ensemble gamelan yang bisa diketuk">
            <button class="pad pad--gong" data-pad="gong" aria-label="Gong Ageng">
                <span class="pad-shape"></span><span class="pad-name">Gong Ageng</span>
            </button>
            <button class="pad pad--kempul" data-pad="kempul" aria-label="Kempul">
                <span class="pad-shape"></span><span class="pad-name">Kempul</span>
            </button>
            <button class="pad pad--kenong" data-pad="kenong" aria-label="Kenong">
                <span class="pad-shape"></span><span class="pad-name">Kenong</span>
            </button>
            <button class="pad pad--saron" data-pad="saron" aria-label="Saron">
                <span class="pad-shape"></span><span class="pad-name">Saron</span>
            </button>
            <button class="pad pad--bonang" data-pad="bonang" aria-label="Bonang">
                <span class="pad-shape"></span><span class="pad-name">Bonang</span>
            </button>
        </div>
        <p class="gamelan-hint">Nyalakan suara perangkatmu, lalu ketuk alatnya.</p>

        <p class="scroll-cue"><span></span>Gulir ke bawah</p>
    </div>
</section>

{{-- ═══════════ PAPER BAND ═══════════ --}}
<section class="paper-band">
    <div class="container container-narrow">
        <p class="paper-quote">"Setiap alat musik adalah ingatan yang bisa disentuh."</p>
        <p class="paper-cap">Arsip MuSantara</p>
    </div>
</section>

{{-- ═══════════ TELUSURI ═══════════ --}}
<section class="search-section" id="telusuri">
    <div class="container container-narrow">
        <p class="section-eyebrow">Telusuri Arsip</p>
        <h2 class="section-head">Cari alatnya <em>langsung.</em></h2>
        @include('components.search-box')
    </div>
</section>

{{-- ═══════════ PULAU ═══════════ --}}
<section class="map-section" id="pulau">
    <div class="container">
        <p class="section-eyebrow">Jelajah Wilayah</p>
        <h2 class="section-head">Pilih <em>Pulau.</em></h2>
        @include('partials.map')
        <div id="tooltip" class="map-tooltip"></div>
    </div>
</section>

{{-- ═══════════ KOLEKSI ═══════════ --}}
<section class="koleksi" id="koleksi">
    <div class="container">
        <p class="section-eyebrow">Koleksi Pilihan</p>
        <h2 class="section-head">Buka <em>arsipnya.</em></h2>
        <div class="koleksi-grid">
            @foreach($alatUnggulan as $alat)
                @include('components.card', ['alat' => $alat])
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════ QUIZ ═══════════ --}}
<section class="quiz-invite">
    <div class="container container-narrow">
        <p class="section-eyebrow">Uji Telinga</p>
        <h2 class="section-head">Seberapa kenal kamu <em>dengannya?</em></h2>
        <p class="quiz-sub">Tujuh pertanyaan. Satu arsip penuh bunyi.</p>
        <div class="quiz-cta">
            <a href="/quiz-global" class="btn-quiz-hero" id="quizLocked">Mulai Kuis</a>
            <p class="quiz-note" id="quizHint">Selesaikan tur alat dulu untuk membuka kuis.</p>
        </div>
    </div>
</section>

@endsection
