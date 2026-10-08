@extends('layouts.app')
@section('content')

{{-- HERO: clay exhibition --}}
<section class="hero-clay" id="jelajahi">
<div class="wrap">
<div class="hero-grid">
<div class="reveal">
<p class="hero-kicker">MUSANTARA · ARSIP BUNYI NUSANTARA</p>
<h1 class="hero-title">Ruang Arsip<br><em>yang Hidup</em></h1>
<p class="hero-sub">Museum tanah liat digital untuk alat musik tradisional Indonesia. Sentuh, tekan, dengarkan — setiap objek punya bunyi.</p>
<div class="hero-ctas">
<a href="#arsip" class="clay-btn primary">Jelajahi Arsip <span class="arw">→</span></a>
<a href="#pulau" class="clay-btn">Peta Kepulauan</a>
</div>
<div class="hero-meta">
<div><b>{{ array_sum($islandCounts ?? []) }}</b><span>INSTRUMEN</span></div>
<div><b>7</b><span>KEPULAUAN</span></div>
<div><b>∞</b><span>BUNYI</span></div>
</div>
</div>
@if($hero)
<div class="pedestal reveal">
<div class="pring" aria-hidden="true"></div>
<div class="pbase" aria-hidden="true"></div>
<div class="pobj gamelan-pad" data-instrument="{{ strtolower(str_replace(' ','-',$hero->nama)) }}" data-freq="220" role="button" tabindex="0" aria-label="Putar {{ $hero->nama }}">
<img src="{{ gambar_alat($hero->gambar) }}" alt="{{ $hero->nama }}">
</div>
<div class="hero-tag">
<p class="tn">{{ $hero->nama }}</p>
<p class="ts">{{ optional($hero->pulau)->nama ?? 'Nusantara' }}{{ $hero->sumber_bunyi ? ' · '.$hero->sumber_bunyi : '' }}</p>
</div>
<button class="hero-play gamelan-pad" data-instrument="gong" data-freq="98" aria-label="Putar bunyi">▶</button>
</div>
@endif
</div>
</div>
</section>

{{-- 01 ARSIP BUNYI --}}
<section class="sect" id="arsip">
<div class="wrap">
<div class="sect-label reveal"><span class="n">01 / ARSIP</span><span class="t">Ruang Bunyi</span><span class="ln"></span></div>
<div class="gamelan gamelan-sec">
<div class="gamelan-frame reveal">
<div class="gamelan-pads">
<button type="button" class="gamelan-pad" data-instrument="gong" data-freq="98" aria-label="Pukul gong besar"><b>Gong</b><span>98 Hz</span></button>
<button type="button" class="gamelan-pad" data-instrument="kempul" data-freq="147" aria-label="Pukul kempul"><b>Kempul</b><span>147 Hz</span></button>
<button type="button" class="gamelan-pad" data-instrument="kenong" data-freq="220" aria-label="Pukul kenong"><b>Kenong</b><span>220 Hz</span></button>
<button type="button" class="gamelan-pad" data-instrument="saron" data-freq="392" aria-label="Pukul saron"><b>Saron</b><span>392 Hz</span></button>
<button type="button" class="gamelan-pad" data-instrument="bonang" data-freq="523.25" aria-label="Pukul bonang"><b>Bonang</b><span>523 Hz</span></button>
<button type="button" class="gamelan-pad" data-instrument="gambang" data-freq="659.25" aria-label="Pukul gambang"><b>Gambang</b><span>659 Hz</span></button>
<button type="button" class="gamelan-pad" data-instrument="demung" data-freq="174.61" aria-label="Pukul demung"><b>Demung</b><span>175 Hz</span></button>
<button type="button" class="gamelan-pad" data-instrument="peking" data-freq="880" aria-label="Pukul peking"><b>Peking</b><span>880 Hz</span></button>
</div>
<p class="gamelan-status" id="soundStatus" aria-live="polite">KETUK OBJEK UNTUK MENDENGAR</p>
</div>
</div>
</div>
</section>

{{-- 02 PULAU --}}
<section class="map-sec" id="pulau">
<div class="wrap">
<div class="sect-label reveal"><span class="n">02 / PULAU</span><span class="t">Kepulauan</span><span class="ln"></span></div>
<div class="map-frame reveal">
<div class="map-wrap">
@include('partials.map')
</div>
<div class="map-bar" id="regionBar"></div>
<div id="island-data" data-counts='@json($islandCounts ?? [])' hidden></div>
</div>
</div>
</section>

{{-- 03 KOLEKSI --}}
<section class="sect" id="koleksi">
<div class="wrap">
<div class="sect-label reveal"><span class="n">03 / ARSIP</span><span class="t">Koleksi</span><span class="ln"></span></div>
@foreach($regions as $ri => $rg)
<div class="sect-label reveal" style="margin:2.4rem 0 1.6rem"><span class="n">{{ $rg['pulau']->nama }}</span><span class="t" style="font-size:1rem">{{ $rg['items']->count() }} objek</span><span class="ln"></span></div>
<div class="objects shelf">
@foreach($rg['items'] as $idx => $item)
<article class="obj">
<div class="clay-card">
<a href="{{ url('/alat/'.$item->id) }}" aria-label="{{ $item->nama }}">
<div class="fig">
<span class="idx">{{ str_pad($idx+1,2,'0',STR_PAD_LEFT) }}</span>
@if($item->gambar)<img src="{{ gambar_alat($item->gambar) }}" alt="{{ $item->nama }}" loading="lazy" data-zoom>@endif
</div>
<div class="meta">
<h3>{{ $item->nama }}</h3>
<p class="rg">{{ $rg['pulau']->nama }}{{ $item->sumber_bunyi ? ' · '.$item->sumber_bunyi : '' }}</p>
<div class="row">
<span class="cat">{{ strtoupper($item->sumber_bunyi ?? 'TRADISIONAL') }}</span>
<button type="button" class="clay-play gamelan-pad" data-instrument="{{ strtolower(str_replace(' ','-',$item->nama)) }}" data-freq="{{ 196 + ($item->id % 8) * 49 }}" aria-label="Dengarkan {{ $item->nama }}">▶</button>
</div>
</div>
</a>
</div>
</article>
@endforeach
</div>
@endforeach
<div class="reveal" style="margin:3.5rem 0 1rem;text-align:center">
<a href="{{ url('/search') }}" class="clay-btn primary">Lihat Semua Arsip <span class="arw">→</span></a>
</div>
</div>
</section>

{{-- 04 KUIS --}}
<section class="sect" id="kuis" style="padding-bottom:6rem">
<div class="wrap">
<div class="sect-label reveal"><span class="n">04 / KUIS</span><span class="t">Uji Telinga</span><span class="ln"></span></div>
<div class="quiz-card reveal" style="max-width:560px;margin:0 auto">
<p style="font-family:var(--mono);font-size:.62rem;letter-spacing:.24em;color:var(--terra);margin-bottom:.8rem">SEMBILAN SOAL</p>
<p style="font-size:1.05rem;color:var(--muted);margin-bottom:1.6rem">Dengarkan bunyinya, tebak instrumennya. Sepuluh soal singkat tentang Nusantara.</p>
<a href="{{ route('quiz.global') }}" class="clay-btn primary">Mulai Kuis <span class="arw">→</span></a>
</div>
</div>
</section>

@endsection
