@extends('layouts.app')
@section('title', $pulau->nama . ' — MuSantara')
@section('content')
<style>
.isl-solo{width:min(370px,74vw);height:auto;display:block;filter:drop-shadow(0 16px 20px rgba(0,0,0,.5))}
.isl-solo .isl-base{fill:url(#islGrad);shape-rendering:geometricPrecision}
/* Peta relief ga ikut animasi napas pedestal: transform animasi bikin SVG
   ke-raster & tepinya buram. Diam = vektor tajam. */
.pedestal .pobj{animation:none}
/* Tilt 3D + kilau pas hover (pola kartu kuis): pedestal miring ngikutin
   kursor, kilau radial jalan di atas bentuk pulau yang sama. */
.hero-grid{perspective:1100px}
.pedestal{transform-style:preserve-3d;will-change:transform}
.pedestal .pobj{transform:translateZ(34px)}
.isl-shine{fill:url(#islGlare);opacity:0;transition:opacity .35s ease;pointer-events:none}
.pedestal:hover .isl-shine{opacity:1}
.tentang-label{font-family:var(--mono);font-size:.62rem;letter-spacing:.2em;color:var(--muted)}
.tentang-txt{color:var(--text2);line-height:1.65;margin-top:.35rem;max-width:46ch}
.scroll-cue{display:flex;flex-direction:column;align-items:center;gap:.15rem;margin-top:2.6rem;color:var(--muted);font-family:var(--mono);font-size:.62rem;letter-spacing:.26em;text-decoration:none}
.scroll-cue .cue-arw{font-size:1.05rem;line-height:1;color:var(--terra);animation:cueBob 1.8s ease-in-out infinite}
@keyframes cueBob{0%,100%{transform:translateY(0)}50%{transform:translateY(7px)}}
@media (prefers-reduced-motion: reduce){.scroll-cue .cue-arw{animation:none}}
</style>
<div class="wrap" style="padding-top:3rem;padding-bottom:1rem">
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
<svg class="isl-solo" viewBox="{{ $viewBox }}" preserveAspectRatio="xMidYMid meet" role="img" aria-label="Siluet Pulau {{ $pulau->nama }}"><defs><linearGradient id="islGrad" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#e8d2a6"/><stop offset="1" stop-color="#c7a671"/></linearGradient><radialGradient id="islGlare" cx="0.5" cy="0.5" r="0.75"><stop offset="0" stop-color="#ffffff" stop-opacity=".5"/><stop offset=".45" stop-color="#ffffff" stop-opacity=".13"/><stop offset="1" stop-color="#ffffff" stop-opacity="0"/></radialGradient></defs><path class="isl-base" d="{!! $pathD !!}"/><path class="isl-shine" d="{!! $pathD !!}" aria-hidden="true"/></svg>
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
(function(){var svg=document.querySelector('.isl-solo');if(!svg)return;var p=svg.querySelector('.isl-base');if(!p)return;try{var b=p.getBBox();var pad=Math.max(b.width,b.height)*0.07;svg.setAttribute('viewBox',(b.x-pad).toFixed(1)+' '+(b.y-pad).toFixed(1)+' '+(b.width+pad*2).toFixed(1)+' '+(b.height+pad*2).toFixed(1));}catch(e){}})();
// Tilt 3D + kilau: pedestal miring ngikutin kursor, pusat kilau ikut pointer.
(function(){
  var ped=document.querySelector('.pedestal');
  if(!ped) return;
  if(!window.matchMedia('(hover:hover)').matches) return;
  if(window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  var glare=document.getElementById('islGlare');
  var rx=0, ry=0, tx=0, ty=0, raf=null, hovering=false;
  function loop(){
    rx+=(tx-rx)*0.11; ry+=(ty-ry)*0.11;
    ped.style.transform='rotateX('+rx.toFixed(2)+'deg) rotateY('+ry.toFixed(2)+'deg)';
    if(hovering || Math.abs(tx-rx)>0.05 || Math.abs(ty-ry)>0.05){ raf=requestAnimationFrame(loop); }
    else { raf=null; ped.style.transform=''; }
  }
  function kick(){ if(!raf) raf=requestAnimationFrame(loop); }
  ped.addEventListener('pointermove',function(e){
    var r=ped.getBoundingClientRect();
    var px=(e.clientX-r.left)/r.width-0.5, py=(e.clientY-r.top)/r.height-0.5;
    ty=px*15; tx=-py*12; hovering=true;
    if(glare){ glare.setAttribute('cx',(0.5+px*1.15).toFixed(3)); glare.setAttribute('cy',(0.5+py*1.15).toFixed(3)); }
    kick();
  });
  ped.addEventListener('pointerleave',function(){ hovering=false; tx=0; ty=0; kick(); });
})();
</script>
@endsection
