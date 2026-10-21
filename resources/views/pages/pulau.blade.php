@extends('layouts.app')
@section('title', $pulau->nama . ' — MuSantara')
@section('content')
<style>
.isl-solo{width:min(370px,74vw);height:auto;display:block}
.isl-solo path{fill:#d9c096;stroke:rgba(24,17,12,.4);stroke-width:2.5;stroke-linejoin:round}
.tentang-label{font-family:var(--mono);font-size:.62rem;letter-spacing:.2em;color:var(--muted)}
.tentang-txt{color:var(--text2);line-height:1.65;margin-top:.35rem;max-width:46ch}
.scroll-cue{display:flex;flex-direction:column;align-items:center;gap:.15rem;margin-top:2.6rem;color:var(--muted);font-family:var(--mono);font-size:.62rem;letter-spacing:.26em;text-decoration:none}
.scroll-cue .cue-arw{font-size:1.05rem;line-height:1;color:var(--terra);animation:cueBob 1.8s ease-in-out infinite}
@keyframes cueBob{0%,100%{transform:translateY(0)}50%{transform:translateY(7px)}}
@media (prefers-reduced-motion: reduce){.scroll-cue .cue-arw{animation:none}}
</style>
<div class="wrap" style="padding:3rem 0 1rem">
<a href="{{ url('/') }}#pulau" class="clay-btn reveal">← Kepulauan</a>
</div>

<section style="padding:0 0 3rem">
<div class="wrap">
<div class="hero-grid">
<div class="pedestal reveal">
<div class="pring" aria-hidden="true"></div>
<div class="pbase" aria-hidden="true"></div>
<div class="pobj">
@if($pathD)
<svg class="isl-solo" viewBox="0 0 2000 1000" preserveAspectRatio="xMidYMid meet" role="img" aria-label="Siluet Pulau {{ $pulau->nama }}"><path d="{!! $pathD !!}"/></svg>
@endif
</div>
</div>
<div class="reveal">
<p class="hero-kicker" style="font-family:var(--mono);font-size:.62rem;letter-spacing:.28em;color:var(--muted)">WILAYAH · KEPULAUAN NUSANTARA</p>
<h1 style="font-family:var(--display);font-size:clamp(2.2rem,5vw,3.4rem);font-weight:500;margin:.6rem 0">{{ $pulau->nama }}</h1>
<p style="color:var(--text2);margin-bottom:.4rem">{{ $alat->count() }} instrumen terarsip</p>
<div style="margin-top:1.4rem;padding-top:1.4rem;border-top:1px solid var(--line)">
<p class="tentang-label">TENTANG {{ strtoupper($pulau->nama) }}</p>
<p class="tentang-txt">{{ $tentang }}</p>
</div>
</div>
</div>
<a href="#arsip-pulau" class="scroll-cue reveal">GULIR KE ARSIP<span class="cue-arw">↓</span></a>
</div>
</section>

<div class="wrap" id="arsip-pulau" style="padding-bottom:4rem">
<div class="sect-label reveal"><span class="n">§</span><span class="t">Arsip {{ $pulau->nama }}</span></div>
@if($alat->isEmpty())
<div class="quiz-card reveal" style="text-align:center">
<p style="font-family:var(--mono);font-size:.7rem;letter-spacing:.2em;color:var(--muted)">BELUM ADA KOLEKSI</p>
<p style="color:var(--text2);margin:.8rem 0">Wilayah ini belum memiliki instrumen yang tersedia.</p>
<a href="{{ url('/') }}#pulau" class="clay-btn">Kembali ke Pulau <span class="arw">→</span></a>
</div>
@else
<div class="pods">
@foreach($alat as $idx => $item)
@include('components.card',['item'=>$item,'idx'=>$idx])
@endforeach
</div>
@endif
</div>
<script>
// Potong viewBox pas ke bentuk pulau ini saja (dari peta utuh 2000x1000)
(function(){var svg=document.querySelector('.isl-solo');if(!svg)return;var p=svg.querySelector('path');if(!p)return;try{var b=p.getBBox();var pad=Math.max(b.width,b.height)*0.07;svg.setAttribute('viewBox',(b.x-pad).toFixed(1)+' '+(b.y-pad).toFixed(1)+' '+(b.width+pad*2).toFixed(1)+' '+(b.height+pad*2).toFixed(1));}catch(e){}})();
</script>
@endsection
