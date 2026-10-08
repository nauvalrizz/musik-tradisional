@extends('layouts.app')
@section('content')

{{-- HERO: exhibition room --}}
<section class="hero">
<div class="wrap">
<div class="hero-top">
<p class="hero-kicker">MuSantara · Arsip Bunyi Nusantara</p>
<h1 class="hero-title">Ruang Arsip yang Hidup</h1>
</div>
@if($hero)
<div class="hero-stage" id="heroStage">
<div class="hero-object gamelan-pad" id="heroObject" data-instrument="{{ strtolower(str_replace(' ','-',$hero->nama)) }}" data-freq="220" role="button" tabindex="0" aria-label="Putar {{ $hero->nama }}">
<img src="{{ gambar_alat($hero->gambar) }}" alt="{{ $hero->nama }}">
<div class="hero-shadow" aria-hidden="true"></div>
</div>
</div>
<div class="hero-meta">
<p class="obj-num">01</p>
<h2>{{ $hero->nama }}</h2>
<p class="obj-origin">{{ optional($hero->pulau)->nama ?? 'Nusantara' }}{{ $hero->sumber_bunyi ? ' — '.$hero->sumber_bunyi : '' }}</p>
<button class="hero-play magnet gamelan-pad" data-instrument="gong" data-freq="98" aria-label="Putar bunyi">
<span class="pdot">▶</span><span>Putar Bunyi</span>
</button>
<div class="hero-wave" aria-hidden="true">
@for($i=0;$i<32;$i++)<i style="height:{{ 18+($i*53%82) }}%"></i>@endfor
</div>
</div>
@endif
</div>
<p class="scroll-hint" aria-hidden="true">GULIR</p>
</section>

{{-- 01 BUNYI --}}
<section class="sect" id="bunyi">
<div class="wrap">
<div class="sect-label reveal"><span class="n">01</span><span class="t">Ruang Bunyi</span></div>
<div class="stage-grid">
<div class="stage-disc reveal">
<button type="button" class="clay-disc gamelan-pad" data-instrument="gong" data-freq="98" aria-label="Pukul gong besar">
<span class="nm">Gong</span>
</button>
</div>
<div class="stage-panel reveal">
<p style="color:var(--text2);max-width:26rem">Ketuk objek untuk mendengar karakternya. Setiap instrumen disintesis langsung — tanpa file audio.</p>
<p class="time" id="soundStatus" aria-live="polite" style="margin-top:.8rem;min-height:1.4rem"></p>
<div class="pad-list">
<button type="button" class="pad-row gamelan-pad" data-instrument="kempul" data-freq="147"><span class="pi">02</span><span class="pn">Kempul</span><span class="pf">147 Hz</span><span class="pa">→</span></button>
<button type="button" class="pad-row gamelan-pad" data-instrument="kenong" data-freq="220"><span class="pi">03</span><span class="pn">Kenong</span><span class="pf">220 Hz</span><span class="pa">→</span></button>
<button type="button" class="pad-row gamelan-pad" data-instrument="saron" data-freq="392"><span class="pi">04</span><span class="pn">Saron</span><span class="pf">392 Hz</span><span class="pa">→</span></button>
<button type="button" class="pad-row gamelan-pad" data-instrument="bonang" data-freq="523.25"><span class="pi">05</span><span class="pn">Bonang</span><span class="pf">523 Hz</span><span class="pa">→</span></button>
</div>
</div>
</div>
</div>
</section>

{{-- 02 PULAU --}}
<section class="sect" id="pulau">
<div class="wrap">
<div class="sect-label reveal"><span class="n">02</span><span class="t">Kepulauan</span></div>
<div class="map-wrap reveal">
@include('partials.map')
<div id="regionBar"><div><span class="rn">Jelajahi</span> <span class="rc" style="margin-left:.8rem"></span></div><span class="go">Buka wilayah →</span></div>
</div>
<div id="island-data" data-counts='@json($islandCounts ?? [])' hidden></div>
</div>
</section>

{{-- 03 KOLEKSI --}}
<section class="sect" id="koleksi">
<div class="wrap">
<div class="sect-label reveal"><span class="n">03</span><span class="t">Koleksi</span></div>
@foreach($regions as $ri => $rg)
<div class="region-sep reveal"><span class="rn">{{ $rg['pulau']->nama }}</span><span class="rc">{{ $rg['items']->count() }} objek</span></div>
<div class="objects">
@foreach($rg['items'] as $idx => $item)
<article class="obj">
<a href="{{ url('/alat/'.$item->id) }}" aria-label="{{ $item->nama }}">
<figure class="obj-fig">
<img src="{{ gambar_alat($item->gambar) }}" alt="{{ $item->nama }}" loading="lazy" data-zoom>
</figure>
<div class="obj-meta">
<span class="n">{{ str_pad($idx+1,2,'0',STR_PAD_LEFT) }}</span>
<span class="t">{{ $item->nama }}<small>{{ $item->sumber_bunyi ?? '—' }}</small></span>
<span class="a">Dengarkan →</span>
</div>
<div class="obj-accent" aria-hidden="true"></div>
</a>
</article>
@endforeach
</div>
@endforeach
<div class="reveal" style="margin-top:3rem;text-align:center">
<a href="{{ url('/search') }}" class="btn-line magnet">Lihat Semua Arsip <span class="arw">→</span></a>
</div>
</div>
</section>

{{-- 04 KUIS --}}
<section class="sect" id="kuis" style="padding-bottom:6rem">
<div class="wrap" style="text-align:center">
<div class="sect-label reveal" style="justify-content:center"><span class="n">04</span><span class="t">Uji Telinga</span></div>
<p class="reveal" style="color:var(--text2);max-width:28rem;margin:0 auto 1.6rem">Sepuluh soal singkat tentang instrumen Nusantara.</p>
<a href="/quiz-global" class="btn-line magnet reveal">Mulai Kuis <span class="arw">→</span></a>
</div>
</section>

@endsection
