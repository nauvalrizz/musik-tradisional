@extends('layouts.app')

@section('content')

{{-- ═══════════ TABUHAN PEMBUKA — intro homepage ═══════════ --}}
<div id="tabuhan" aria-hidden="true">
    <span class="tabuhan-word w1">ANGKLUNG</span>
    <span class="tabuhan-flash f1"></span>
    <span class="tabuhan-word w2">SARON</span>
    <span class="tabuhan-flash f2"></span>
    <span class="tabuhan-word w3">BONANG</span>
    <span class="tabuhan-flash f3"></span>
    <span class="tabuhan-burst"></span>
    <span class="tabuhan-brand">MUSANTARA</span>
    <span class="tabuhan-skip">ketuk untuk lewati</span>
</div>

{{-- ═══════════ HERO — satu viewport penuh: penjelasan + alat ═══════════ --}}
<section class="hero-full">
    <div class="container hero-full-inner">
        <p class="section-eyebrow hero-anim" style="--d:0.05s">Apa itu MuSantara?</p>
        <p class="manifesto hero-anim" style="--d:0.2s">
            MuSantara adalah arsip hidup alat musik tradisional Indonesia.
            <span class="w-def" data-def="Ketuk lima alat musik di bawah — tiap alat berbunyi beda.">Ketuk</span>
            bunyinya,
            <span class="w-def" data-def="Jelajahi peta dan temukan asal-usul tiap alat.">telusuri</span>
            asalnya,
            <span class="w-def" data-def="Baca kisah dan makna di balik tiap alat musik.">baca</span>
            ceritanya.
        </p>
        <div class="def-pop" id="defPop" hidden></div>

        <p class="hero-ensemble-label hero-anim" style="--d:0.35s">Arsip Bunyi Nusantara</p>

        <div class="gamelan-pads" role="group" aria-label="Lima alat musik yang bisa diketuk">
            <button type="button" class="gamelan-pad pad-angklung" data-instrument="angklung" data-freq="261.63" aria-label="Angklung">
                <span class="angklung-frame">
                    <span class="angklung-bar"></span>
                    <span class="angklung-tubes"><i></i><i></i><i></i></span>
                </span>
                <span class="pad-name">Angklung</span>
            </button>
            <button type="button" class="gamelan-pad pad-kempul" data-instrument="kempul" data-freq="98.00" aria-label="Kempul">
                <span class="pad-hang"><span class="pad-disc"><span class="pad-boss"></span></span></span>
                <span class="pad-name">Kempul</span>
            </button>
            <button type="button" class="gamelan-pad pad-kenong" data-instrument="kenong" data-freq="130.81" aria-label="Kenong">
                <span class="pad-kettle"><span class="pad-kettle-rim"></span></span>
                <span class="pad-name">Kenong</span>
            </button>
            <button type="button" class="gamelan-pad pad-saron" data-instrument="saron" data-freq="164.81" aria-label="Saron">
                <span class="pad-bars"><i></i><i></i><i></i><i></i><i></i><i></i></span>
                <span class="pad-name">Saron</span>
            </button>
            <button type="button" class="gamelan-pad pad-bonang" data-instrument="bonang" data-freq="196.00" aria-label="Bonang">
                <span class="pad-pots"><i></i><i></i><i></i><i></i><i></i><i></i></span>
                <span class="pad-name">Bonang</span>
            </button>
        </div>
        <p class="gamelan-hint hero-anim" style="--d:0.55s">Nyalakan suara perangkatmu, lalu ketuk alatnya.</p>
    </div>
    <p class="scroll-cue" aria-hidden="true"><span></span>Gulir ke bawah</p>
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
        <p class="search-sub">Ketik nama alat musik — misalnya <b>sasando</b> atau <b>gamelan</b> — lalu tekan Cari untuk menelusuri arsip.</p>
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
            @foreach($featured as $item)
                @include('components.card', ['item' => $item])
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
