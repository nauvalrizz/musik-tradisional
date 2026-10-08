@extends('layouts.app')
@section('title', $pulau->nama . ' — MuSantara')
@section('content')
<div class="wrap" style="padding:3rem 0 1rem">
<a href="{{ url('/') }}#pulau" class="btn-line magnet reveal">← Kepulauan</a>
</div>

@if($featured)
<section style="padding:0 0 3rem">
<div class="wrap">
<div class="stage-grid">
<div class="stage-disc reveal">
<div class="hero-object" style="cursor:default">
<img src="{{ gambar_alat($featured->gambar) }}" alt="{{ $featured->nama }}" data-zoom style="width:min(320px,64vw);aspect-ratio:1;object-fit:contain;filter:drop-shadow(0 28px 38px rgba(0,0,0,.5))">
</div>
</div>
<div class="stage-panel reveal">
<p class="hero-kicker" style="font-family:var(--mono);font-size:.62rem;letter-spacing:.28em;color:var(--muted)">WILAYAH · PILIHAN KURATOR</p>
<h1 style="font-family:var(--display);font-size:clamp(2.2rem,5vw,3.4rem);font-weight:500;margin:.6rem 0">{{ $pulau->nama }}</h1>
<p style="color:var(--text2);margin-bottom:.4rem">{{ $alat->count() }} instrumen terarsip</p>
<div style="margin-top:1.4rem;padding-top:1.4rem;border-top:1px solid var(--line)">
<p style="font-family:var(--mono);font-size:.62rem;letter-spacing:.2em;color:var(--muted)">SOROTAN</p>
<p style="font-weight:600;font-size:1.1rem;margin:.3rem 0">{{ $featured->nama }}</p>
<a href="{{ url('/alat/'.$featured->id.'?from=pulau&slug='.$pulau->slug) }}" class="btn-line magnet">Buka Arsip <span class="arw">→</span></a>
</div>
</div>
</div>
</div>
</section>
@else
<div class="wrap" style="padding:3rem 0 1rem">
<div class="search-head reveal">
<p class="hero-kicker">Wilayah</p>
<h1>{{ $pulau->nama }}</h1>
<p class="cnt">{{ $alat->count() }} instrumen terarsip</p>
</div>
</div>
@endif

<div class="wrap" style="padding-bottom:4rem">
<div class="sect-label reveal"><span class="n">§</span><span class="t">Arsip {{ $pulau->nama }}</span></div>
@if($alat->isEmpty())
<div class="quiz-card reveal" style="text-align:center">
<p style="font-family:var(--mono);font-size:.7rem;letter-spacing:.2em;color:var(--muted)">BELUM ADA KOLEKSI</p>
<p style="color:var(--text2);margin:.8rem 0">Wilayah ini belum memiliki instrumen yang tersedia.</p>
<a href="{{ url('/') }}#pulau" class="btn-line magnet">Kembali ke Pulau <span class="arw">→</span></a>
</div>
@else
<div class="objects">
@foreach($alat as $idx => $item)
<article class="obj">
<a href="{{ url('/alat/'.$item->id.'?from=pulau&slug='.$pulau->slug) }}" aria-label="{{ $item->nama }}">
<figure class="obj-fig">
@if($item->gambar)<img src="{{ gambar_alat($item->gambar) }}" alt="{{ $item->nama }}" loading="lazy" data-zoom>@endif
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
@endif
</div>
@endsection
