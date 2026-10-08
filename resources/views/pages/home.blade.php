@extends('layouts.app')
@section('content')

{{-- HERO --}}
<section class="hero">
<div class="wrap hero-grid">
<div class="reveal">
<p class="eyebrow">001 — Arsip Terbuka — Indonesia</p>
<h1>ARSIP<em>Bunyi</em>NUSANTARA</h1>
</div>
<aside class="hero-card reveal">
<p>Jejak bunyi dari logam, kayu, kulit, bambu, dan dawai — ditata sebagai ruang dengar untuk warisan yang terus hidup.</p>
<div class="cta-row">
<a class="btn btn-primary" href="#bunyi">Mulai Mendengar →</a>
<a class="btn" href="#koleksi">Buka Koleksi</a>
</div>
</aside>
</div>
</section>

{{-- DIVIDER --}}
<div class="divider" aria-hidden="true">
<svg viewBox="0 0 1200 120"><path d="M0 60C30 10 90 10 120 60S210 110 240 60 330 10 360 60 450 110 480 60 570 10 600 60 690 110 720 60 810 10 840 60 930 110 960 60 1050 10 1080 60 1170 110 1200 60"/></svg>
</div>

{{-- BUNYI --}}
<section class="section" id="bunyi">
<div class="wrap">
<header class="section-head reveal">
<div class="sec-num">01</div>
<div class="sec-title">
<p class="eyebrow">Ruang Dengar</p>
<h2>Ketuk dan dengarkan.</h2>
<p>Lima instrumen gamelan yang bisa dimainkan langsung — tanpa file audio, murni sintesis Web Audio.</p>
</div>
</header>
<div class="pads reveal">
<button type="button" class="pad gamelan-pad" data-instrument="gong" data-freq="98" aria-label="Gong"><span>Gong</span><small>98 Hz</small></button>
<button type="button" class="pad gamelan-pad" data-instrument="kempul" data-freq="147" aria-label="Kempul"><span>Kempul</span><small>147 Hz</small></button>
<button type="button" class="pad gamelan-pad" data-instrument="kenong" data-freq="220" aria-label="Kenong"><span>Kenong</span><small>220 Hz</small></button>
<button type="button" class="pad gamelan-pad" data-instrument="saron" data-freq="392" aria-label="Saron"><span>Saron</span><small>392 Hz</small></button>
<button type="button" class="pad gamelan-pad" data-instrument="bonang" data-freq="523.25" aria-label="Bonang"><span>Bonang</span><small>523 Hz</small></button>
</div>
<p class="sound-status" id="soundStatus" aria-live="polite"></p>
</div>
</section>

{{-- TELUSURI --}}
<section class="section" id="telusuri">
<div class="wrap">
<header class="section-head reveal">
<div class="sec-num">02</div>
<div class="sec-title">
<p class="eyebrow">Indeks — Telusuri</p>
<h2>Cari lewat nama, bahan, atau wilayah.</h2>
<p>Ketik nama alat musik lalu tekan Cari.</p>
</div>
</header>
<div class="search-card reveal">
<form action="{{ route('search') }}" method="GET">
<div class="search-box">
<input type="text" name="q" placeholder="Mis. sasando, logam, Jawa…" autocomplete="off" aria-label="Cari alat musik">
<button type="submit">Cari</button>
</div>
</form>
<div class="results" id="liveSearchResult" aria-live="polite"></div>
</div>
</div>
</section>

{{-- PULAU --}}
<section class="section" id="pulau">
<div class="wrap">
<header class="section-head reveal">
<div class="sec-num">03</div>
<div class="sec-title">
<p class="eyebrow">Peta — Pulau</p>
<h2>Tujuh wilayah, tujuh warna bunyi.</h2>
<p>Ketuk pulau untuk melihat alat musik dari daerah tersebut.</p>
</div>
</header>
<div class="map-card reveal">
@include('partials.map')
</div>
<div id="island-data" data-counts='@json($islandCounts ?? [])' hidden></div>
</div>
</section>

{{-- KOLEKSI --}}
<section class="section" id="koleksi">
<div class="wrap">
<header class="section-head reveal">
<div class="sec-num">04</div>
<div class="sec-title">
<p class="eyebrow">Koleksi — Pilihan</p>
<h2>Enam artefak pilihan.</h2>
<p>Diacak setiap kunjungan. Ketuk kartu untuk detail lengkap.</p>
</div>
</header>
<div class="cards">
@foreach($featured as $i => $item)
<a href="{{ url('/alat/'.$item->id) }}" class="card reveal">
@if($item->gambar)
<img src="{{ gambar_alat($item->gambar) }}" alt="{{ $item->nama }}" loading="lazy" data-zoom>
@endif
<div class="card-body">
<p class="card-meta">Fig. {{ str_pad($i+1,2,'0',STR_PAD_LEFT) }} — {{ $item->pulau->nama ?? 'Nusantara' }}</p>
<h3>{{ $item->nama }}</h3>
<p>{{ Str::limit(strip_tags($item->deskripsi ?? ''), 90) }}</p>
</div>
</a>
@endforeach
</div>
</div>
</section>

{{-- QUIZ --}}
<section class="section">
<div class="wrap">
<div class="quiz-card reveal">
<h2>Seberapa kenal kamu dengan bunyi Nusantara?</h2>
<p>Ikuti kuis singkat dan uji pengetahuanmu tentang alat musik tradisional.</p>
<a class="btn" href="/quiz-global">Mulai Kuis →</a>
</div>
</div>
</section>

@endsection
