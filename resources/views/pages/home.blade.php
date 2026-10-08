@extends('layouts.app')
@section('content')

{{-- HERO: exhibition entrance --}}
<section class="hero">
<div class="wrap hero-grid">
<div class="hero-text reveal">
<p class="hero-kicker">MuSantara — Arsip Musik Tradisional Indonesia</p>
<h1>Instrumen, suara, <em>tempat</em> dan cerita dari kepulauan Indonesia.</h1>
<p>Arsip digital interaktif — ketuk bunyinya, telusuri asalnya, baca ceritanya.</p>
<a href="#koleksi" class="explore-link">Jelajahi Arsip →</a>
</div>
@if($hero)
<div class="hero-object reveal">
<figure class="hero-figure">
<img src="{{ gambar_alat($hero->gambar) }}" alt="{{ $hero->nama }}" data-zoom>
</figure>
<div class="obj-label">
<span class="obj-num">01</span>
<div>
<p class="obj-name">{{ $hero->nama }}</p>
<p class="obj-meta">{{ optional($hero->pulau)->nama ?? 'Nusantara' }} · {{ $hero->sumber_bunyi ?? '—' }}</p>
<div class="obj-rule"></div>
<a class="obj-play" href="{{ url('/alat/'.$hero->id) }}"><span class="dot">→</span> Lihat Arsip</a>
</div>
</div>
</div>
@endif
</div>
</section>

{{-- 01 BUNYI --}}
<section class="chapter" id="bunyi">
<div class="wrap">
<div class="chapter-head reveal">
<span class="ch-index">01 / 04</span>
<div class="ch-title">
<h2>Ruang Bunyi</h2>
<p>Ketuk objek tanah liat untuk mendengar karakter tiap instrumen — disintesis langsung di peramban.</p>
</div>
</div>
<div class="sound-stage">
<div class="sound-object reveal">
<button type="button" class="clay-disc gamelan-pad" data-instrument="gong" data-freq="98" aria-label="Pukul gong">
<span class="nm">Gong</span>
</button>
<p class="time" id="soundStatus" aria-live="polite" style="margin-top:1rem"></p>
</div>
<div class="sound-panel reveal">
<div class="player">
<div class="player-row">
<button class="play-btn gamelan-pad" data-instrument="gong" data-freq="98" aria-label="Putar">▶</button>
<div>
<p style="font-weight:600">Gong Ageng</p>
<p class="time">Ensemble · Jawa</p>
</div>
</div>
<div class="wave" aria-hidden="true">
@for($i=0;$i<28;$i++)<i style="height:{{ 20+($i*37%80) }}%"></i>@endfor
</div>
<div class="pad-row">
<button type="button" class="pad gamelan-pad" data-instrument="kempul" data-freq="147">Kempul</button>
<button type="button" class="pad gamelan-pad" data-instrument="kenong" data-freq="220">Kenong</button>
<button type="button" class="pad gamelan-pad" data-instrument="saron" data-freq="392">Saron</button>
<button type="button" class="pad gamelan-pad" data-instrument="bonang" data-freq="523.25">Bonang</button>
</div>
</div>
</div>
</div>
</div>
</section>

{{-- 02 PULAU --}}
<section class="chapter" id="pulau">
<div class="wrap">
<div class="chapter-head reveal">
<span class="ch-index">02 / 04</span>
<div class="ch-title">
<h2>Kepulauan</h2>
<p>Tujuh wilayah, tujuh warna bunyi. Arahkan kursor untuk melihat jumlah, klik untuk menjelajah.</p>
</div>
</div>
<div class="map-frame reveal">
@include('partials.map')
<div class="map-legend"><span>— Arahkan untuk info</span><span>— Klik untuk buka wilayah</span></div>
<p id="regionInfo" aria-live="polite"></p>
</div>
<div id="island-data" data-counts='@json($islandCounts ?? [])' hidden></div>
</div>
</section>

{{-- 03 KOLEKSI --}}
<section class="chapter" id="koleksi">
<div class="wrap">
<div class="chapter-head reveal">
<span class="ch-index">03 / 04</span>
<div class="ch-title">
<h2>Koleksi</h2>
<p>Instrumen dikelompokkan menurut wilayah asalnya — seperti lemari arsip museum.</p>
</div>
<a class="ch-link" href="{{ url('/search') }}">Semua →</a>
</div>
@foreach($regions as $ri => $rg)
<div class="region-block reveal">
<div class="region-head">
<span class="region-num">{{ str_pad($ri+1,2,'0',STR_PAD_LEFT) }}</span>
<span class="region-name">{{ $rg['pulau']->nama }}</span>
<span class="region-count">{{ $rg['items']->count() }} objek</span>
</div>
<div class="objects">
@foreach($rg['items'] as $idx => $item)
<article class="object">
<a href="{{ url('/alat/'.$item->id) }}">
<figure class="object-fig">
<img src="{{ gambar_alat($item->gambar) }}" alt="{{ $item->nama }}" loading="lazy" data-zoom>
</figure>
<div class="object-label">
<span class="obj-num">{{ str_pad($idx+1,2,'0',STR_PAD_LEFT) }}</span>
<p class="obj-name">{{ $item->nama }}</p>
<p class="obj-meta">{{ $item->sumber_bunyi ?? '—' }}</p>
</div>
</a>
</article>
@endforeach
</div>
</div>
@endforeach
</div>
</section>

{{-- 04 KUIS --}}
<section class="chapter" id="kuis" style="border-bottom:0">
<div class="wrap">
<div class="chapter-head reveal">
<span class="ch-index">04 / 04</span>
<div class="ch-title">
<h2>Uji Telinga</h2>
<p>Sepuluh soal singkat tentang instrumen Nusantara. Berani coba?</p>
</div>
<a class="ch-link" href="/quiz-global">Mulai Kuis →</a>
</div>
</div>
</section>

@endsection
