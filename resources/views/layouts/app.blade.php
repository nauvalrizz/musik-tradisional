<!DOCTYPE html>
<html lang="id" class="loading">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>@yield('title','MuSantara — Arsip Bunyi Nusantara')</title>
<meta name="description" content="@yield('meta_desc','Arsip digital hidup alat musik tradisional Indonesia.')">
{{-- Open Graph / social preview --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="MuSantara">
<meta property="og:title" content="@yield('title','MuSantara — Arsip Bunyi Nusantara')">
<meta property="og:description" content="@yield('meta_desc','Arsip digital hidup alat musik tradisional Indonesia.')">
<meta property="og:url" content="{{ url()->current() }}">
<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:image" content="@yield('og_image', asset('assets/img/hero-gong.jpg'))">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title','MuSantara — Arsip Bunyi Nusantara')">
<meta name="twitter:description" content="@yield('meta_desc','Arsip digital hidup alat musik tradisional Indonesia.')">
<meta name="twitter:image" content="@yield('og_image', asset('assets/img/hero-gong.jpg'))">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
<meta name="theme-color" content="#141110">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Manrope:wght@400;500;600&display=swap" rel="stylesheet">
<style>
/* Critical: first frame = dark museum, no flash */
html{background:#141110}html.loading,html.loading body{overflow:hidden}
#ambBg{position:fixed;inset:-40px;z-index:0;pointer-events:none;overflow:hidden;
background:
radial-gradient(720px 480px at 22% 18%,rgba(169,110,70,.075),transparent 65%),
radial-gradient(640px 460px at 78% 55%,rgba(140,95,60,.06),transparent 65%),
radial-gradient(800px 400px at 50% 108%,rgba(110,80,55,.05),transparent 60%)}
/* Background ambient STATIS: layer fixed yang gerak terus bikin scroll repaint tiap frame */
#ambBg{animation:none}
.ambDust{display:none}
@keyframes dustFloat{0%{transform:translate3d(0,0,0);opacity:0}15%{opacity:.14}85%{opacity:.1}100%{transform:translate3d(24px,-46px,0);opacity:0}}
@media(max-width:860px){#ambBg .ambDust:nth-child(n+6){display:none}}
@media(prefers-reduced-motion:reduce){#ambBg{animation:none}.ambDust{display:none}}

#loader{position:fixed;inset:0;width:100vw;height:100vh;height:100dvh;z-index:500;background:#141110;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.9rem;overflow:hidden}
#loader.curtain-lift{pointer-events:none;transform:translate3d(0,-100%,0);transition:transform 1400ms cubic-bezier(0.76,0,0.24,1)}
#loader.gone{visibility:hidden}
#loader.done::after{content:'';position:absolute;left:0;right:0;bottom:0;height:12px;background:linear-gradient(180deg,#d4694a,#a95135);box-shadow:0 0 40px rgba(212,105,74,.9),0 4px 20px rgba(0,0,0,.6);pointer-events:none}
.mstage{position:relative;width:min(300px,72vw);height:210px}
.mped{position:absolute;left:50%;bottom:18px;width:120px;height:34px;margin-left:-60px;border-radius:48% 52% 50% 50%/70% 65% 60% 65%;background:linear-gradient(145deg,#4a382c,#2e231b);box-shadow:0 8px 18px rgba(0,0,0,.5),inset 0 2px 3px rgba(229,214,192,.12);transform:scale(0);opacity:0;will-change:transform,opacity}
#loader.go .mped{transform:scale(1);opacity:1;transition:transform .45s cubic-bezier(.34,1.56,.64,1),opacity .3s}
.mripple{position:absolute;left:50%;bottom:30px;width:140px;height:26px;margin-left:-70px;border:2px solid #a95135;border-radius:50%;opacity:0;transform:scale(.4);will-change:transform,opacity;pointer-events:none}
#loader.orc .mripple{animation:mrip .7s ease-out}
@keyframes mrip{0%{opacity:.65;transform:scale(.4)}100%{opacity:0;transform:scale(1.5)}}
.minst{position:absolute;bottom:52px;left:50%;width:64px;height:64px;margin-left:-32px;opacity:0;transform:translate3d(var(--mx),26px,0) scale(0);will-change:transform,opacity;filter:drop-shadow(0 8px 10px rgba(0,0,0,.5));backface-visibility:hidden}
.minst-inner{width:100%;height:100%;will-change:transform}
.minst-inner svg{width:100%;height:100%;display:block}
.m0{--mx:-104px}.m1{--mx:-52px}.m2{--mx:0px}.m3{--mx:52px}.m4{--mx:104px}
#loader.go .minst{opacity:1;transform:translate3d(var(--mx),var(--my),0) scale(1) rotate(var(--mr,0deg));transition:opacity .3s ease,transform .55s cubic-bezier(.34,1.45,.64,1)}
#loader.go .m0{--my:-8px;--mr:-5deg;transition-delay:.16s}
#loader.go .m1{--my:-16px;--mr:4deg;transition-delay:.28s}
#loader.go .m2{--my:-22px;--mr:0deg;transition-delay:.40s}
#loader.go .m3{--my:-16px;--mr:-4deg;transition-delay:.52s}
#loader.go .m4{--my:-8px;--mr:5deg;transition-delay:.64s}
#loader.orc .minst-inner{animation:morc .5s ease-in-out}
#loader.orc .m0 .minst-inner{animation-delay:0s}#loader.orc .m1 .minst-inner{animation-delay:.06s}#loader.orc .m2 .minst-inner{animation-delay:.12s}#loader.orc .m3 .minst-inner{animation-delay:.18s}#loader.orc .m4 .minst-inner{animation-delay:.24s}
@keyframes morc{0%,100%{transform:translateY(0)}40%{transform:translateY(-9px)}}
.mtitle{font-family:Fraunces,serif;font-size:1.5rem;letter-spacing:.22em;color:#e8dfd1;opacity:0;transform:translateY(10px);will-change:opacity,transform}
#loader.logo .mtitle{opacity:1;transform:translateY(0);transition:opacity .45s ease,transform .45s cubic-bezier(.22,1,.36,1)}
.msub{font-family:'DM Mono',monospace;font-size:.58rem;letter-spacing:.34em;color:#766d62;opacity:0;will-change:opacity}
#loader.logo .msub{opacity:1;transition:opacity .45s ease .12s}
/* bye di-skip, langsung tirai naik (v270) */
@media(prefers-reduced-motion:reduce){#loader.go .minst,#loader.go .mped{transition-duration:.01s}}
/* Lazy image fade-in (v268) */
img[loading="lazy"]{opacity:0;transition:opacity .5s ease}
img[loading="lazy"].ld,img[loading="lazy"][data-ld]{opacity:1}
/* Skeleton overlay saat pindah halaman (v268) */
#page-skeleton{position:fixed;inset:0;z-index:600;background:#141110;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:1.2rem;opacity:0;visibility:hidden;transition:opacity .3s ease,visibility .3s;pointer-events:none}
#page-skeleton.show{opacity:1;visibility:visible;pointer-events:all}
.sk-line{border-radius:8px;background:linear-gradient(90deg,#221c16 25%,#2e251d 50%,#221c16 75%);background-size:200% 100%;animation:skshim 1.2s ease-in-out infinite}
@keyframes skshim{0%{background-position:200% 0}100%{background-position:-200% 0}}
.sk-title{width:min(280px,60vw);height:28px}
.sk-sub{width:min(200px,44vw);height:14px}
.sk-row{display:flex;gap:1rem}
.sk-card{width:120px;height:150px;border-radius:14px}
#page-skeleton{align-items:stretch;justify-content:flex-start;padding:5.5rem 1.2rem 2rem;gap:0}
.sk-nav{position:absolute;top:1rem;left:50%;transform:translateX(-50%);width:min(420px,80vw);height:44px;border-radius:22px}
.sk-hero{width:min(640px,88vw);height:34px;margin:2.5rem auto 1rem}
.sk-sub2{width:min(420px,70vw);height:16px;margin:0 auto 2rem}
.sk-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:1rem;width:min(880px,92vw);margin:0 auto}
.sk-grid .sk-card{height:170px;width:auto}
.sk-foot{width:min(300px,60vw);height:12px;margin:2.5rem auto 0;opacity:.6}
@media(prefers-reduced-motion:reduce){.sk-line{animation:none}}
</style>
<link rel="stylesheet" href="{{ asset('assets/css/clay.css?v=155') }}">
</head>
<body class="{{ (request()->is('quiz-global') || request()->is('quiz-result')) ? 'quiz-mode' : '' }}">

{{-- LOADER: miniature museum orchestra --}}
<div id="ambBg" aria-hidden="true"><i class="ambDust" style="left:0%;top:0%;width:2px;height:2px;animation-duration:14.0s;animation-delay:-0.0s"></i><i class="ambDust" style="left:37%;top:53%;width:3px;height:3px;animation-duration:15.7s;animation-delay:-2.3s"></i><i class="ambDust" style="left:74%;top:18%;width:4px;height:4px;animation-duration:17.4s;animation-delay:-4.6s"></i><i class="ambDust" style="left:19%;top:71%;width:2px;height:2px;animation-duration:19.1s;animation-delay:-6.8999999999999995s"></i><i class="ambDust" style="left:56%;top:36%;width:3px;height:3px;animation-duration:20.8s;animation-delay:-9.2s"></i><i class="ambDust" style="left:1%;top:1%;width:4px;height:4px;animation-duration:22.5s;animation-delay:-11.5s"></i><i class="ambDust" style="left:38%;top:54%;width:2px;height:2px;animation-duration:24.2s;animation-delay:-13.799999999999999s"></i><i class="ambDust" style="left:75%;top:19%;width:3px;height:3px;animation-duration:25.9s;animation-delay:-16.099999999999998s"></i><i class="ambDust" style="left:20%;top:72%;width:4px;height:4px;animation-duration:27.6s;animation-delay:-18.4s"></i><i class="ambDust" style="left:57%;top:37%;width:2px;height:2px;animation-duration:29.299999999999997s;animation-delay:-20.7s"></i></div>
<div id="loader" aria-hidden="true">
<div class="mstage">
<div class="mped"></div>
<div class="mripple"></div>
@php
$loaderIconFn = function($nama){
  $n = strtolower($nama);
  $c1='#62483A'; $c2='#3A2E25'; $t='#A95135';
  if(str_contains($n,'kendang')||str_contains($n,'gendang')||str_contains($n,'tifa')||str_contains($n,'bedug'))
    return '<svg viewBox="0 0 100 100" width="64" height="64"><rect x="22" y="36" width="56" height="28" rx="14" fill="'.$c1.'"/><ellipse cx="22" cy="50" rx="9" ry="14" fill="#C9B59D"/><ellipse cx="78" cy="50" rx="9" ry="14" fill="#C9B59D"/><rect x="40" y="68" width="20" height="22" rx="4" fill="'.$c2.'"/></svg>';
  if(str_contains($n,'kenong')||str_contains($n,'saron')||str_contains($n,'bonang')||str_contains($n,'talempong'))
    return '<svg viewBox="0 0 100 100" width="64" height="64"><rect x="14" y="66" width="72" height="12" rx="5" fill="'.$c2.'"/><rect x="20" y="52" width="10" height="14" rx="2" fill="'.$t.'"/><rect x="34" y="48" width="10" height="18" rx="2" fill="'.$t.'"/><rect x="48" y="44" width="10" height="22" rx="2" fill="'.$t.'"/><rect x="62" y="48" width="10" height="18" rx="2" fill="'.$t.'"/><rect x="76" y="52" width="6" height="14" rx="2" fill="'.$t.'"/></svg>';
  if(str_contains($n,'angklung'))
    return '<svg viewBox="0 0 100 100" width="64" height="64"><line x1="18" y1="26" x2="82" y2="26" stroke="'.$c2.'" stroke-width="6" stroke-linecap="round"/><rect x="26" y="30" width="9" height="42" rx="4" fill="'.$c1.'"/><rect x="40" y="30" width="9" height="52" rx="4" fill="'.$c1.'"/><rect x="54" y="30" width="9" height="42" rx="4" fill="'.$c1.'"/><rect x="68" y="30" width="9" height="34" rx="4" fill="'.$c1.'"/></svg>';
  if(str_contains($n,'sasando'))
    return '<svg viewBox="0 0 100 100" width="64" height="64"><path d="M50 14 L78 82 L22 82 Z" fill="'.$c1.'"/><line x1="50" y1="14" x2="50" y2="82" stroke="#C9B59D" stroke-width="2"/><ellipse cx="50" cy="86" rx="20" ry="5" fill="'.$c2.'"/></svg>';
  return '<svg viewBox="0 0 100 100" width="64" height="64"><circle cx="50" cy="42" r="28" fill="'.$c1.'"/><circle cx="50" cy="42" r="10" fill="'.$t.'"/><circle cx="50" cy="42" r="28" fill="none" stroke="'.$c2.'" stroke-width="3"/><line x1="24" y1="66" x2="22" y2="90" stroke="'.$c2.'" stroke-width="5" stroke-linecap="round"/><line x1="76" y1="66" x2="78" y2="90" stroke="'.$c2.'" stroke-width="5" stroke-linecap="round"/></svg>';
};
$mInstruments = ['Gong','Kenong','Angklung','Kendang','Sasando'];
@endphp
@foreach($mInstruments as $mi => $mnm)
<div class="minst m{{$mi}}"><div class="minst-inner">{!! $loaderIconFn($mnm) !!}</div></div>
@endforeach
</div>
<div class="mtitle">MUSANTARA</div>
<div class="msub">ARSIP BUNYI NUSANTARA</div>
</div>

<div id="cursor" aria-hidden="true"><span class="cdot"></span><span class="clabel"></span></div>
<div id="miniPlayer" role="region" aria-label="Pemutar mini">
<button class="mp-dot" id="mpToggle" aria-label="Putar/Jeda">▶</button>
<div class="mp-info"><p class="mp-name" id="mpName">—</p><p class="mp-sub" id="mpSub">—</p><div class="mp-bar"><div class="mp-fill" id="mpFill"></div></div></div>
<button class="mp-x" id="mpClose" aria-label="Tutup">×</button>
</div>

<nav class="nav-console" id="siteHeader" aria-label="Navigasi utama">
<a href="{{ url('/') }}" class="nav-brand">MUSA<em>N</em>TARA</a>
<button class="nav-toggle" id="navToggle" aria-label="Buka menu" aria-expanded="false">☰</button>
<div class="nav-links" id="navLinks">
<span class="nav-marker" aria-hidden="true"></span>
<a href="{{ url('/') }}#jelajahi" class="nav-link" data-nav="jelajahi">JELAJAHI</a>
<a href="{{ url('/') }}#arsip" class="nav-link" data-nav="arsip">ARSIP</a>
<a href="{{ url('/') }}#pulau" class="nav-link" data-nav="pulau">PULAU</a>
<a href="{{ url('/') }}#koleksi" class="nav-link" data-nav="koleksi">KOLEKSI</a>
<a href="{{ url('/ruang-bunyi') }}" class="nav-link" data-nav="ruang-bunyi">RUANG BUNYI</a>
<a href="{{ url('/quiz-global') }}" class="nav-link" data-nav="kuis">KUIS</a>
</div>
<form class="nav-search" action="{{ route('search') }}" method="get" role="search">
<input type="search" name="q" placeholder="Cari dalam arsip…" aria-label="Cari dalam arsip">
<button type="submit" aria-label="Cari">⌕</button>
</form>
</nav>

<div id="scrollProg" aria-hidden="true"><i></i></div>
<div id="mapTip" aria-hidden="true"><p class="mt-n"></p><p class="mt-c"></p></div>
<main id="main">@yield('content')</main>

<footer>
<div class="wrap fgrid">
<div><p class="fl">MUSA<em>N</em>TARA</p><p class="fm" style="margin-top:.4rem">ARSIP BUNYI NUSANTARA · TANAH LIAT DIGITAL</p></div>
<div class="fm">© 2026 MuSantara · Dibentuk dari tanah, untuk bunyi</div>
</div>
</footer>

<div id="imgModal" style="display:none;position:fixed;inset:0;z-index:250;background:rgba(12,10,8,.94);align-items:center;justify-content:center;flex-direction:column;padding:1rem">
<span class="img-close" role="button" aria-label="Tutup" style="position:absolute;top:1rem;right:1.4rem;font-size:2rem;color:#E8DFD1;cursor:pointer">&times;</span>
<img id="imgZoom" alt="" style="max-width:min(92vw,860px);max-height:80vh">
<p id="imgCaption" style="color:#766D62;font-family:var(--mono);font-size:.68rem;margin-top:.7rem"></p>
</div>

<script src="{{ asset('assets/js/gamelan.js?v=161') }}" defer></script>
@stack('scripts')
<script>
(function(){
"use strict";
var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
var touch = window.matchMedia('(hover: none)').matches;

/* ——— LOADER: miniature orchestra (1.5-2.5s) ——— */
document.documentElement.classList.add('loading');
(function(){
  var loader=document.getElementById('loader');
  var winReady=false, animDone=false, lifted=false;
  /* ——— GATE ASET: tirai baru naik setelah gambar halaman + model 3D hero
     beneran ke-load, biar ga ada slot kosong pas pengunjung nyampe.
     Deadline 7 detik biar koneksi lambat ga kekunci selamanya. ——— */
  var assetsReady=false;
  var heroNeeded=!!document.getElementById('hero3d');
  var heroReady=!heroNeeded;
  window.__msnHeroPing=function(){ heroReady=true; tryEnter(); };
  // State: INTRO_VISIBLE -> INTRO_FINISHED -> HOME_READY -> CURTAIN_LIFTING -> REVEALED
  function liftCurtain(){
    if(lifted || !loader) return; lifted=true;
    // Phase B: hold 300ms (theatrical pause), home sudah render di balik
    setTimeout(function(){
      // Phase C: CURTAIN_LIFTING
      loader.classList.add('curtain-lift');
      document.documentElement.classList.remove('loading');
      var done=false;
      function finish(){
        if(done) return; done=true;
        // Phase D: REVEALED
        loader.classList.add('gone');
        loader.removeEventListener('transitionend', onEnd);
      }
      function onEnd(e){
        if(e.propertyName==='transform') finish();
      }
      loader.addEventListener('transitionend', onEnd);
      setTimeout(finish, 2000); // fallback aman
    }, 300);
  }
  function tryEnter(){
    if(winReady && animDone && assetsReady && heroReady) liftCurtain();
  }
  window.addEventListener('load', function(){ winReady=true; tryEnter(); });
  setTimeout(function(){ winReady=true; tryEnter(); }, 4000); // fallback paksa
  // Preload SEMUA gambar di halaman ini (termasuk yang lazy di section bawah):
  // cache anget = gambar langsung nongol pas section-nya kesampean.
  (function(){
    var seen={}, list=[];
    document.querySelectorAll('img').forEach(function(im){
      var s=im.currentSrc||im.src;
      if(s && !seen[s]){ seen[s]=1; list.push(s); }
    });
    if(!list.length){ assetsReady=true; return; }
    var left=list.length;
    function one(){ if(--left<=0){ assetsReady=true; tryEnter(); } }
    list.forEach(function(s){ var i=new Image(); i.onload=one; i.onerror=one; i.src=s; });
  })();
  setTimeout(function(){ assetsReady=true; heroReady=true; tryEnter(); }, 7000); // deadline aset
  var startDelay = reduced ? 0 : 80;
  setTimeout(function(){
    loader.classList.add('go'); // pedestal + instruments pop
    setTimeout(function(){ loader.classList.add('orc'); }, 880); // orchestra moment
    setTimeout(function(){ loader.classList.add('logo'); }, 1080); // title
    setTimeout(function(){ animDone=true; tryEnter(); }, 3000);
  }, startDelay);
  // Entry stagger homepage (di balik loader)
  var els = document.querySelectorAll('.hero-clay,.gamelan-sec,.sect,.map-sec');
  els.forEach(function(el,i){
    el.style.opacity='0'; el.style.transform='translateY(18px)';
    el.style.transition='opacity .7s ease '+(i*130)+'ms,transform .7s ease '+(i*130)+'ms';
  });
  var mo=new MutationObserver(function(){
    if(loader.classList.contains('curtain-lift')){
      requestAnimationFrame(function(){requestAnimationFrame(function(){
        els.forEach(function(el){ el.style.opacity='1'; el.style.transform='none'; });
      });});
      mo.disconnect();
    }
  });
  mo.observe(loader,{attributes:true,attributeFilter:['class']});
})();
// fallback: jangan kunci selamanya
setTimeout(function(){
  var l=document.getElementById('loader');
  if(l && !l.classList.contains('curtain-lift')){ l.classList.add('curtain-lift'); document.documentElement.classList.remove('loading'); setTimeout(function(){ l.classList.add('gone'); }, 2000); }
}, 8000);

/* ——— CUSTOM CURSOR ———
   Loop cuma jalan selama kursor gerak, berhenti sendiri pas udah nyampe.
   Posisi pakai transform (compositor), BUKAN left/top (layout tiap frame). */
var cur=document.getElementById('cursor'),clab=cur?cur.querySelector('.clabel'):null;
if(cur && !touch){
  var cx=innerWidth/2,cy=innerHeight/2,px=cx,py=cy,curRaf=0;
  function cl(){
    px+=(cx-px)*.35; py+=(cy-py)*.35;
    cur.style.transform='translate3d('+px.toFixed(1)+'px,'+py.toFixed(1)+'px,0)';
    if(Math.abs(cx-px)>.1||Math.abs(cy-py)>.1){ curRaf=requestAnimationFrame(cl); }
    else{ curRaf=0; }
  }
  document.addEventListener('mousemove',function(e){
    cx=e.clientX; cy=e.clientY;
    if(!curRaf) curRaf=requestAnimationFrame(cl);
  },{passive:true});
  document.addEventListener('mouseover',function(e){
    var t=e.target.closest('a,button,input,select,.gamelan-pad');
    cur.className='';
    if(!t) return;
    if(t.closest('.gamelan-pad,.play-btn,.pdot,.pad-row,.clay-disc,.hero-play')){cur.classList.add('snd');clab.textContent='BUNYI';}
    else if(t.closest('a')){cur.classList.add('opn');clab.textContent='BUKA';}
    else{cur.classList.add('hov');}
  });
}

/* ——— MINI PLAYER ——— */
var mp=document.getElementById('miniPlayer'),mpN=document.getElementById('mpName'),
    mpS=document.getElementById('mpSub'),mpF=document.getElementById('mpFill'),
    mpT=document.getElementById('mpToggle'),mpTimer=null;
var mpHideT=null;
function mpShow(name,sub,dur){
  mpN.textContent=name; mpS.textContent=sub||'MuSantara';
  mp.classList.add('show'); mpT.textContent='❚❚';
  clearInterval(mpTimer); clearTimeout(mpHideT);
  dur=dur||2000;
  var p=0, steps=25;
  mpTimer=setInterval(function(){
    p+=100/steps;
    if(p>=100){ p=100; clearInterval(mpTimer); }
    mpF.style.width=p+'%';
  }, dur/steps);
  mpHideT=setTimeout(function(){ mp.classList.remove('show'); clearInterval(mpTimer); }, dur+800);
}
document.getElementById('mpClose').addEventListener('click',function(){
  mp.classList.remove('show'); clearInterval(mpTimer); clearTimeout(mpHideT);
});
mpT.addEventListener('click',function(){
  var playing=mpT.textContent==='❚❚';
  mpT.textContent=playing?'▶':'❚❚';
  if(playing) clearInterval(mpTimer);
});

/* ——— AMBIENT CURSOR LIGHT ——— */
var amb = document.getElementById('ambient');
if(!touch && !reduced && amb){
  var ax=innerWidth/2, ay=innerHeight/3, tx=ax, ty=ay, shown=false;
  document.addEventListener('mousemove', function(e){
    tx=e.clientX; ty=e.clientY;
    if(!shown){ amb.style.opacity='1'; shown=true; }
  }, {passive:true});
  (function loop(){
    ax += (tx-ax)*0.06; ay += (ty-ay)*0.06;
    amb.style.left=ax+'px'; amb.style.top=ay+'px';
    requestAnimationFrame(loop);
  })();
}

/* ——— HERO 3D TILT ——— */
var heroObj = document.getElementById('heroObject');
if(heroObj && !touch && !reduced){
  var heroStage = document.getElementById('heroStage');
  heroStage.addEventListener('mousemove', function(e){
    var r = heroStage.getBoundingClientRect();
    var x = (e.clientX - r.left)/r.width - .5;
    var y = (e.clientY - r.top)/r.height - .5;
    heroObj.style.transform = 'rotateY('+(x*6)+'deg) rotateX('+(-y*5)+'deg)';
  });
  heroStage.addEventListener('mouseleave', function(){ heroObj.style.transform=''; });
}

/* ——— SCROLL PROGRESS ———
   scaleX (compositor) + rAF throttle. Dulu pakai width: layout ulang tiap event scroll. */
var spb=document.querySelector('#scrollProg i');
var spTick=false;
function spUpd(){
  spTick=false;
  var h=document.documentElement, max=h.scrollHeight-h.clientHeight;
  if(spb) spb.style.transform='scaleX('+(max>0?(h.scrollTop/max):0)+')';
}
window.addEventListener('scroll',function(){
  if(!spTick){ spTick=true; requestAnimationFrame(spUpd); }
},{passive:true});
spUpd();

/* ——— MAGNETIC (2-5px) ——— */
if(!touch && !reduced){
  document.querySelectorAll('.clay-btn.primary,.hero-play,.nav-search button').forEach(function(b){
    b.classList.add('magnet');
    b.addEventListener('mousemove',function(e){
      var r=b.getBoundingClientRect();
      var x=(e.clientX-r.left-r.width/2)/r.width, y=(e.clientY-r.top-r.height/2)/r.height;
      b.style.transform='translate('+(x*5).toFixed(1)+'px,'+(y*5).toFixed(1)+'px)';
    });
    b.addEventListener('mouseleave',function(){ b.style.transform=''; });
  });
}

/* ——— HERO: typing title (fallback aman: teks penuh di HTML) ——— */
(function(){
  var el=document.getElementById('heroTitle'); if(!el) return;
  if(matchMedia('(prefers-reduced-motion: reduce)').matches) return; // tampil penuh
  var full=el.getAttribute('data-full')||'MUSANTARA';
  el.setAttribute('aria-label',full);
  var wrap=document.createElement('span'); wrap.className='typed-wrap'; wrap.setAttribute('aria-hidden','true');
  var cursor=document.createElement('span'); cursor.className='typed-cursor';
  el.textContent=''; wrap.appendChild(cursor); el.appendChild(wrap);
  var paused=false;
  var hero3d=document.getElementById('hero3d');
  if(hero3d){
    hero3d.addEventListener('pointerdown',function(){paused=true;});
    addEventListener('pointerup',function(){setTimeout(function(){paused=false;},1500);});
  }
  var chars=[];
  function setCount(n){
    if(n>chars.length){
      var c=document.createElement('span');
      c.className='tchar enter'; c.textContent=full[chars.length];
      wrap.insertBefore(c,cursor); chars.push(c);
      (function(elm){setTimeout(function(){elm.classList.remove('enter');},280);})(c);
    }else if(n<chars.length){
      var last=chars.pop();
      last.classList.add('leave');
      (function(elm){setTimeout(function(){if(elm.parentNode)elm.parentNode.removeChild(elm);},200);})(last);
    }
  }
  var i=0, mode='type';
  function tick(){
    if(paused){ setTimeout(tick,600); return; }
    if(mode==='type'){
      i++; setCount(i); el.classList.add('typing');
      if(i>=full.length){ mode='hold'; el.classList.remove('typing'); setTimeout(tick,2000); return; }
      setTimeout(tick,180);
    }else if(mode==='hold'){
      mode='del'; setTimeout(tick,400);
    }else{
      i--; setCount(Math.max(i,0));
      if(i<=0){ mode='type'; setTimeout(tick,1000); return; }
      setTimeout(tick,140);
    }
  }
  tick();
})();
/* Hero 3D: parallax & sounding di-handle modul Three.js di home.blade.php */

/* ——— CARD IMAGE PARALLAX (subtle) ——— */
if(!touch && !reduced){
  document.querySelectorAll('.clay-card,.pod').forEach(function(c){
    var img=c.querySelector('.fig img,.pod-obj img'); if(!img) return;
    c.addEventListener('mousemove',function(e){
      var r=c.getBoundingClientRect();
      var x=(e.clientX-r.left)/r.width-.5, y=(e.clientY-r.top)/r.height-.5;
      img.style.translate=(x*-6).toFixed(1)+'px '+(y*-6).toFixed(1)+'px';
    });
    c.addEventListener('mouseleave',function(){ img.style.translate=''; });
  });
}

/* ——— IMAGE LOADING STATES ——— */
document.querySelectorAll('.clay-card .fig img,.pedestal .pobj img,.pod-obj img').forEach(function(img){
  function done(){ img.classList.add('ld'); }
  function err(){ var f=img.closest('.fig'); if(f) f.classList.add('img-err'); img.style.display='none'; }
  if(img.complete && img.naturalWidth>0) done();
  else{ img.addEventListener('load',done); img.addEventListener('error',err); }
});

/* ——— MAP TOOLTIP ——— */
var mtip=document.getElementById('mapTip');
if(mtip){
  document.querySelectorAll('.map-wrap path[data-slug]').forEach(function(p){
    p.addEventListener('mousemove',function(e){
      var s=p.dataset.slug, nm=(typeof NAMES!=='undefined'&&NAMES[s])?NAMES[s]:s;
      var c=(typeof COUNTS!=='undefined'&&COUNTS[s]!=null)?COUNTS[s]:'—';
      mtip.querySelector('.mt-n').textContent=nm.toUpperCase();
      mtip.querySelector('.mt-c').textContent=c+' INSTRUMEN · LIHAT →';
      mtip.style.left=e.clientX+'px'; mtip.style.top=e.clientY+'px';
      mtip.classList.add('show');
    });
    p.addEventListener('mouseleave',function(){ mtip.classList.remove('show'); });
  });
}

/* ——— NAV CONSOLE SHRINK + MOBILE MENU ——— */
var nc = document.getElementById('siteHeader');
window.addEventListener('scroll', function(){
  if(nc) nc.classList.toggle('shrink', window.scrollY>80);
}, {passive:true});
var nt=document.getElementById('navToggle'),nl=document.getElementById('navLinks');
if(nt&&nl){ nt.addEventListener('click',function(){
  var open=nl.classList.toggle('open'); nt.setAttribute('aria-expanded',open);
}); nl.addEventListener('click',function(e){ if(e.target.closest('a')) nl.classList.remove('open'); }); }

/* ——— NAVBAR ACTIVE STATE: museum navigation marker ——— */
(function(){
  var links=[].slice.call(document.querySelectorAll('.nav-link[data-nav]'));
  var marker=document.querySelector('.nav-marker');
  var path=window.location.pathname.replace(/\/$/,'');
  var isHome=(path===''||path==='/');
  var current=null;

  function setActive(key){
    if(current===key) return;
    current=key;
    links.forEach(function(a){
      var on=a.getAttribute('data-nav')===key;
      a.classList.toggle('active',on);
      if(on) a.setAttribute('aria-current','page'); else a.removeAttribute('aria-current');
    });
    // gerakkan marker ke link aktif
    if(marker){
      var act=document.querySelector('.nav-link.active');
      if(act){
        var lr=act.getBoundingClientRect(), nr=act.parentElement.getBoundingClientRect();
        var x=lr.left-nr.left+lr.width/2;
        marker.style.transform='translateX('+x+'px)';
        marker.style.opacity='1';
      } else { marker.style.opacity='0'; }
    }
  }

  // Route-based: EXACT matching (bukan substring)
  if(path==='/ruang-bunyi') setActive('ruang-bunyi');
  else if(path==='/quiz-global'||path==='/quiz-result') setActive('kuis');
  else if(isHome){
    // Section-based: hitung deterministik dari posisi scroll (anti-stuck)
    var secs=['jelajahi','arsip','pulau','koleksi'];
    var secTops={};
    function measureSecs(){
      secs.forEach(function(id){
        var el=document.getElementById(id);
        if(el) secTops[id]=el.getBoundingClientRect().top+window.scrollY;
      });
    }
    function activeFromScroll(){
      var y=window.scrollY+window.innerHeight*0.35, cand=secs[0];
      secs.forEach(function(id){
        if(secTops[id]!==undefined && secTops[id]<=y) cand=id;
      });
      return cand;
    }
    var ticking=false;
    function onScrollNav(){
      if(ticking) return; ticking=true;
      requestAnimationFrame(function(){
        ticking=false;
        var a=activeFromScroll();
        if(a) setActive(a);
      });
    }
    measureSecs();
    window.addEventListener('scroll',onScrollNav,{passive:true});
    window.addEventListener('resize',function(){ measureSecs(); onScrollNav(); });
    // initial + setelah load (gambar bisa geser layout)
    setActive(activeFromScroll());
    window.addEventListener('load',function(){ measureSecs(); onScrollNav(); });
    setTimeout(function(){ measureSecs(); onScrollNav(); },800);
  }

  // Smooth scroll dengan offset navbar
  document.querySelectorAll('a[href*="#"]').forEach(function(a){
    a.addEventListener('click',function(e){
      var href=a.getAttribute('href');
      var hash=href.indexOf('#')>=0?href.slice(href.indexOf('#')):null;
      if(!hash||hash.length<2) return;
      // hanya untuk anchor di homepage
      if(href.charAt(0)==='#'||(isHome&&href.indexOf(window.location.origin)===0)){
        var t=document.querySelector(hash);
        if(t){
          e.preventDefault();
          var nh=(nc?nc.offsetHeight:70)+14;
          var y=t.getBoundingClientRect().top+window.scrollY-nh;
          window.scrollTo({top:y,behavior:reduced?'auto':'smooth'});
          history.replaceState(null,'',hash);
        }
      }
    });
  });

  // Reposisi marker saat resize
  var rT; window.addEventListener('resize',function(){ clearTimeout(rT); rT=setTimeout(function(){ var c=current; current=null; setActive(c); },200); });
})();

/* ——— REVEAL: bidirectional scroll-reactive ——— */
var scrollDir='down',lastSY=window.scrollY;
window.addEventListener('scroll',function(){
  var y=window.scrollY;
  if(Math.abs(y-lastSY)>4){
    scrollDir=y>lastSY?'down':'up';
    document.body.dataset.sdir=scrollDir;
    lastSY=y;
  }
},{passive:true});
var io=new IntersectionObserver(function(es){
  es.forEach(function(en){
    var el=en.target;
    if(en.isIntersecting){
      el.classList.add('visible');
      el.classList.remove('exited');
      // stagger cards (hanya saat entrance)
      if(!el.dataset.staggered && (el.classList.contains('obj')||el.classList.contains('pod')||el.classList.contains('rv-card'))){
        var sibs=[].slice.call(el.parentNode.children).filter(function(c){return c.classList.contains('obj')||c.classList.contains('pod')||c.classList.contains('rv-card');});
        var idx=sibs.indexOf(el);
        el.style.transitionDelay=(idx*70)+'ms';
        el.dataset.staggered='1';
      }
      // kartu: reveal-once (hero tetap bidirectional untuk efek exited)
      if(el.classList.contains('pod')||el.classList.contains('obj')||el.classList.contains('rv-card')){ io.unobserve(el); }
    }else{
      // exit: hapus visible, tambah exited untuk arah keluar
      el.classList.remove('visible');
      el.classList.add('exited');
    }
  });
},{threshold:.12,rootMargin:'0px 0px -10% 0px'});
/* parallax drift untuk .ax-plx */
(function(){
  var plxEls=[];
  function collect(){ plxEls=[].slice.call(document.querySelectorAll('.ax-plx')); }
  collect();
  var ticking=false;
  function update(){
    ticking=false;
    var vh=window.innerHeight;
    plxEls.forEach(function(el){
      var r=el.getBoundingClientRect();
      if(r.bottom<0||r.top>vh) return;
      var prog=(r.top+r.height/2-vh/2)/vh; // -0.5..0.5
      var speed=parseFloat(el.dataset.plxSpeed||'14');
      el.style.setProperty('--px',(-prog*speed).toFixed(1)+'px');
    });
  }
  window.addEventListener('scroll',function(){
    if(!ticking){ ticking=true; requestAnimationFrame(update); }
  },{passive:true});
  window.addEventListener('resize',update);
  setTimeout(update,500);
})();
function observeReveals(){
  document.querySelectorAll('.reveal,.rv,.rv-rise,.rv-scale,.rv-fade,.rv-left,.rv-mask,.rv-img,.rv-card,.rv-right,.rv-tap,.obj,.pod,#heroTitleBlock,#heroArtifact,.ax-mask,.ax-slideL,.ax-slideR,.ax-clip,.ax-clipV,.ax-scale,.ax-cardL,.ax-cardC,.ax-cardR,.ax-tilt,.ax-blur,.ax-radial,.ax-spring').forEach(function(el){
    if(!el.dataset.observed){ el.dataset.observed='1'; io.observe(el); }
  });
}
// Tunggu loader selesai sebelum observe hero
if(document.documentElement.classList.contains('loading')){
  var loT=setInterval(function(){
    if(!document.documentElement.classList.contains('loading')){
      clearInterval(loT); observeReveals();
    }
  },200);
}else{ observeReveals(); }

/* ——— MAP ——— */
var NAMES={'sumatra':'Sumatera','jawa':'Jawa','kalimantan':'Kalimantan','sulawesi':'Sulawesi','bali-nusa-tenggara':'Bali & Nusa Tenggara','maluku':'Maluku','papua':'Papua'};
var dc=document.getElementById('island-data'),COUNTS={};
try{COUNTS=JSON.parse(dc?dc.dataset.counts:'{}');}catch(e){}
var rBar=document.getElementById('regionBar');
document.querySelectorAll('.map-wrap path[data-slug]').forEach(function(p){
  var s=p.dataset.slug;
  p.addEventListener('mouseenter',function(){
    document.querySelectorAll('.map-wrap path[data-slug]').forEach(function(o){ if(o!==p) o.classList.add('dim'); });
    if(rBar){
      var rn=rBar.querySelector('.rn'),rc=rBar.querySelector('.rc');
      if(rn) rn.textContent=NAMES[s]||s;
      if(rc) rc.textContent=(COUNTS[s]||0)+' instrumen';
      rBar.classList.add('has-sel');
    }
  });
  p.addEventListener('mouseleave',function(){
    document.querySelectorAll('.map-wrap path[data-slug]').forEach(function(o){ o.classList.remove('dim'); });
  });
  p.addEventListener('click',function(){
    p.classList.add('lift');
    setTimeout(function(){ window.location.href='/pulau/'+s; }, 320);
  });
});

/* ——— PADS: status + wave ——— */
var st=document.getElementById('soundStatus');
document.querySelectorAll('.gamelan-pad,.pad-row,.clay-disc').forEach(function(p){
  p.addEventListener('click',function(){
    var nm=p.dataset.instrument||'—';
    if(st) st.textContent='♪ '+nm.toUpperCase()+' · '+(p.dataset.freq||'')+' Hz';
    if(typeof mpShow==='function') mpShow(nm.charAt(0).toUpperCase()+nm.slice(1), 'Ruang Bunyi · '+(p.dataset.freq||'')+' Hz');
    if(p.hasAttribute('data-pod-audio')){
      document.querySelectorAll('.pod.playing').forEach(function(o){o.classList.remove('playing');});
      var pod=p.closest('.pod'); if(pod){ pod.classList.add('playing'); setTimeout(function(){pod.classList.remove('playing');},4000); }
    }
    document.querySelectorAll('.pad-row.playing').forEach(function(o){o.classList.remove('playing');});
    if(p.classList.contains('pad-row')) p.classList.add('playing');
    var w=p.closest('.stage-panel,.hero-meta,.dplayer,.player')?.querySelector('.hero-wave,.wave');
    if(w){ w.classList.add('on','playing'); setTimeout(function(){w.classList.remove('on','playing');},3800); }
  });
});

/* ——— MAGNETIC (subtle) ——— */
if(!touch && !reduced){
  document.querySelectorAll('.magnet,.btn-line,.play-btn').forEach(function(b){
    b.addEventListener('mousemove',function(e){
      var r=b.getBoundingClientRect();
      var x=e.clientX-(r.left+r.width/2), y=e.clientY-(r.top+r.height/2);
      b.style.transform='translate('+(x*.08)+'px,'+(y*.08)+'px)';
    });
    b.addEventListener('mouseleave',function(){ b.style.transform=''; });
  });
}

/* ——— CARD: tombol dengarkan jangan navigasi ——— */
document.addEventListener('click',function(e){
  var b=e.target.closest('.pod-sound');
  if(b){ e.preventDefault(); }
},true);
/* ——— LIGHTBOX ——— */
var m=document.getElementById('imgModal'),im=document.getElementById('imgZoom'),cp=document.getElementById('imgCaption');
function openM(s,a){ im.src=s; im.alt=a||''; cp.textContent=a||''; m.style.display='flex'; document.body.style.overflow='hidden'; }
function closeM(){ m.style.display='none'; document.body.style.overflow=''; }
document.addEventListener('click',function(e){
  var t=e.target.closest('img[data-zoom]');
  if(t){ openM(t.currentSrc||t.src,t.alt); return; }
  if(e.target.closest('.img-close')||e.target===m) closeM();
});
document.addEventListener('keydown',function(e){ if(e.key==='Escape') closeM(); });
})();
</script>
{{-- Skeleton overlay pindah halaman --}}
<div id="page-skeleton" aria-hidden="true">
<div class="sk-line sk-nav"></div>
<div class="sk-line sk-hero"></div>
<div class="sk-line sk-sub2"></div>
<div class="sk-grid"><div class="sk-line sk-card"></div><div class="sk-line sk-card"></div><div class="sk-line sk-card"></div><div class="sk-line sk-card"></div></div>
<div class="sk-line sk-foot"></div>
</div>
<script>
(function(){
  // Tandai gambar lazy yang sudah loaded (fade-in halus)
  function markImg(img){ img.classList.add('ld'); img.setAttribute('data-ld','1'); }
  document.querySelectorAll('img[loading="lazy"]').forEach(function(img){
    if(img.complete&&img.naturalWidth>0) markImg(img);
    else img.addEventListener('load',function(){markImg(img);},{once:true});
  });
  // Fallback IO untuk gambar di dalam pin galeri (programmatic scroll)
  if('IntersectionObserver' in window){
    var io=new IntersectionObserver(function(es){
      es.forEach(function(en){
        if(en.isIntersecting){ markImg(en.target); io.unobserve(en.target); }
      });
    },{rootMargin:'200px'});
    document.querySelectorAll('.hgal-pin img[loading="lazy"]').forEach(function(img){ io.observe(img); });
  }
})();
(function(){
  var sk=document.getElementById('page-skeleton');
  function skHide(){ if(sk) sk.classList.remove('show'); }
  // Halaman dipulihkan dari cache browser (tombol Back): skeleton jangan ikut tampil
  window.addEventListener('pageshow',skHide);
  document.addEventListener('click',function(e){
    var a=e.target.closest('a[href]'); if(!a||!sk) return;
    var href=a.getAttribute('href');
    if(!href||href.charAt(0)==='#'||a.target==='_blank'||a.hasAttribute('download')) return;
    if(/^https?:\/\//i.test(href)&&href.indexOf(location.host)<0) return;
    // Anchor di halaman yang sama (mis. /#koleksi dari home): bukan pindah halaman, jangan tampilkan
    try{ var u=new URL(a.href); if(u.pathname===location.pathname&&u.hash) return; }catch(_){}
    sk.classList.add('show');
    // Pengaman: navigasi gagal/nyangkut -> skeleton angkat sendiri
    setTimeout(skHide,5000);
  });
})();
</script>
</body>
</html>
