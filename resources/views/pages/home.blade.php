@extends('layouts.app')

@section('content')

{{-- ═══════════ TABUHAN PEMBUKA — intro homepage ═══════════ --}}
<div id="tabuhan" aria-hidden="true">
    <span class="tabuhan-medal m1"><img src="{{ asset('storage/img/angklung.jpg') }}" alt=""></span>
    <span class="tabuhan-flash f1"></span>
    <span class="tabuhan-medal m2"><img src="{{ asset('storage/img/bonang.jpg') }}" alt=""></span>
    <span class="tabuhan-flash f2"></span>
    <span class="tabuhan-medal m3"><img src="{{ asset('storage/img/gong.jpg') }}" alt=""></span>
    <span class="tabuhan-flash f3"></span>
    <span class="tabuhan-burst"></span>
    <span class="tabuhan-brand">MUSANTARA</span>
    <span class="tabuhan-skip">ketuk untuk lewati</span>
</div>

{{-- ═══════════ HERO — satu viewport penuh: penjelasan + alat ═══════════ --}}
<section class="gallery-hero">
    <p class="exhibit-no hero-anim" style="--d:.05s"><b>001</b><i></i>Arsip Terbuka &mdash; Indonesia</p>

    <figure class="gallery-fig hero-anim" style="--d:.35s">
        <img src="{{ asset('assets/img/hero-gong.jpg') }}?v=24" alt="Kenong perunggu dalam sorotan museum" fetchpriority="high">
        <figcaption><b>Fig. 01</b> &mdash; Kenong perunggu, Jawa Tengah</figcaption>
    </figure>

    <div class="gallery-type">
        <h1 class="gallery-title">
            <span class="hero-anim" style="--d:.15s">ARSIP</span>
            <span class="hero-anim" style="--d:.3s"><em>Bunyi</em></span>
            <span class="hero-anim" style="--d:.45s">NUSANTARA</span>
        </h1>
        <p class="gallery-sub hero-anim" style="--d:.6s">
            Arsip hidup alat musik tradisional Indonesia.
            <a href="#arsip-bunyi">Ketuk</a> bunyinya,
            <a href="#telusuri">telusuri</a> asalnya,
            <a href="#koleksi">baca</a> ceritanya.
        </p>
    </div>

    <p class="scroll-cue" aria-hidden="true"><span></span>Gulir</p>
</section>

{{-- ═══════════ RUANG BUNYI ═══════════ --}}
<section class="bunyi-section" id="arsip-bunyi">
    <div class="container">
        <p class="section-eyebrow" data-reveal><b>01</b><i></i>Ruang Bunyi</p>
        <h2 class="section-head" data-reveal>Ketuk <em>alatnya.</em></h2>
        <p class="section-sub" data-reveal>Delapan alat gamelan yang bisa kamu mainkan langsung.</p>

        <div class="gamelan-pads" data-stagger role="group" aria-label="Lima alat musik yang bisa diketuk">
            <button type="button" class="gamelan-pad pad-angklung" data-instrument="angklung" data-freq="261.63" data-reveal aria-label="Angklung">
                <span class="angklung-frame">
                    <span class="angklung-bar"></span>
                    <span class="angklung-tubes"><i></i><i></i><i></i></span>
                </span>
                <span class="pad-name">Angklung</span>
            </button>
            <button type="button" class="gamelan-pad pad-kempul" data-instrument="kempul" data-freq="98.00" data-reveal aria-label="Kempul">
                <span class="pad-hang"><span class="pad-disc"><span class="pad-boss"></span></span></span>
                <span class="pad-name">Kempul</span>
            </button>
            <button type="button" class="gamelan-pad pad-kenong" data-instrument="kenong" data-freq="130.81" data-reveal aria-label="Kenong">
                <span class="pad-kettle"><span class="pad-kettle-rim"></span></span>
                <span class="pad-name">Kenong</span>
            </button>
            <button type="button" class="gamelan-pad pad-saron" data-instrument="saron" data-freq="164.81" data-reveal aria-label="Saron">
                <span class="pad-bars"><i></i><i></i><i></i><i></i><i></i><i></i></span>
                <span class="pad-name">Saron</span>
            </button>
            <button type="button" class="gamelan-pad pad-bonang" data-instrument="bonang" data-freq="196.00" data-reveal aria-label="Bonang">
                <span class="pad-pots"><i></i><i></i><i></i><i></i><i></i><i></i></span>
                <span class="pad-name">Bonang</span>
            </button>
            <button type="button" class="gamelan-pad pad-gong" data-instrument="gong" data-freq="65.41" data-reveal aria-label="Gong Ageng">
                <span class="pad-gong-frame"><span class="pad-gong-disc"><span class="pad-gong-boss"></span></span></span>
                <span class="pad-name">Gong</span>
            </button>
            <button type="button" class="gamelan-pad pad-kendang" data-instrument="kendang" data-freq="146.83" data-reveal aria-label="Kendang">
                <span class="pad-drum"><span class="pad-drum-head left"></span><span class="pad-drum-body"></span><span class="pad-drum-head right"></span></span>
                <span class="pad-name">Kendang</span>
            </button>
            <button type="button" class="gamelan-pad pad-suling" data-instrument="suling" data-freq="523.25" data-reveal aria-label="Suling">
                <span class="pad-flute"><i></i><i></i><i></i><i></i><i></i></span>
                <span class="pad-name">Suling</span>
            </button>
        </div>
        <div class="rec-bar" data-reveal>
            <button type="button" id="btnRec" class="rec-btn rec-record" aria-label="Rekam tabuhan">
                <i></i><span>Rekam</span>
            </button>
            <button type="button" id="btnPlay" class="rec-btn rec-play" aria-label="Putar rekaman">
                <b>&#9654;</b><span>Putar</span>
            </button>
            <p class="rec-status" id="recStatus">Ketuk Rekam, mainkan alatnya</p>
        </div>
        <p class="gamelan-hint" data-reveal>Nyalakan suara perangkatmu, lalu ketuk alatnya.</p>
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
        <p class="section-eyebrow" data-reveal><b>02</b><i></i>Telusuri Arsip</p>
        <h2 class="section-head" data-reveal>Cari alatnya <em>langsung.</em></h2>
        <p class="search-sub">Ketik nama alat musik — misalnya <b>sasando</b> atau <b>gamelan</b> — lalu tekan Cari untuk menelusuri arsip.</p>
        @include('components.search-box')
    </div>
</section>

{{-- ═══════════ PULAU ═══════════ --}}
<section class="map-section" id="pulau">
    <div class="container">
        <p class="section-eyebrow" data-reveal><b>03</b><i></i>Jelajah Wilayah</p>
        <h2 class="section-head" data-reveal>Pilih <em>Pulau.</em></h2>
        @include('partials.map')
        <div id="tooltip" class="map-tooltip"></div>
    </div>
</section>

{{-- ═══════════ KOLEKSI ═══════════ --}}
<section class="koleksi" id="koleksi">
    <div class="container">
        <div class="koleksi-head">
            <div>
                <p class="section-eyebrow" data-reveal><b>04</b><i></i>Koleksi Pilihan</p>
                <h2 class="section-head" data-reveal>Buka <em>arsipnya.</em></h2>
            </div>
            <a href="{{ url('/acak') }}" class="acak-btn" data-no-transition>
                <span class="dice" aria-hidden="true">&#9860;</span>
                Jelajah Acak
            </a>
        </div>
        <div class="koleksi-grid" data-stagger>
            @foreach($featured as $item)
                @include('components.card', ['item' => $item])
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════ QUIZ ═══════════ --}}
<section class="quiz-invite">
    <div class="container container-narrow">
        <p class="section-eyebrow" data-reveal><b>05</b><i></i>Uji Telinga</p>
        <h2 class="section-head" data-reveal>Seberapa kenal kamu <em>dengannya?</em></h2>
        <p class="quiz-sub">Tujuh pertanyaan. Satu arsip penuh bunyi.</p>
        <div class="quiz-cta">
            <a href="/quiz-global" class="btn-quiz-hero" id="quizLocked">Mulai Kuis</a>
            <p class="quiz-note" id="quizHint">Selesaikan tur alat dulu untuk membuka kuis.</p>
        </div>
    </div>
</section>

@endsection
