<!DOCTYPE html>
<html lang="id" class="loading">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>@yield('title','MuSantara — Arsip Bunyi Nusantara')</title>
<meta name="description" content="Arsip digital hidup alat musik tradisional Indonesia.">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Manrope:wght@400;500;600&display=swap" rel="stylesheet">
<style>
/* Critical: first frame = dark museum, no flash */
html{background:#141110}html.loading,html.loading body{overflow:hidden}
#ambient{position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden}
#wayangBg{position:absolute;right:0;top:50%;height:min(88vh,840px);width:auto;transform:translateY(-50%);opacity:.42}
@media(max-width:860px){#wayangBg{height:58vh;opacity:.28;right:-10%}}
#loader{position:fixed;inset:0;z-index:500;background:#141110;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.9rem;overflow:hidden}
#loader.done{opacity:0;visibility:hidden;transition:opacity .45s ease,visibility .45s}
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
#loader.bye .mstage{transform:scale(.93);opacity:0;transition:transform .38s ease,opacity .38s ease}
#loader.bye .mtitle,#loader.bye .msub{opacity:0;transition:opacity .3s ease}
@media(prefers-reduced-motion:reduce){#loader.go .minst,#loader.go .mped{transition-duration:.01s}}
</style>
<link rel="stylesheet" href="{{ asset('assets/css/clay.css?v=153') }}">
</head>
<body class="{{ (request()->is('quiz-global') || request()->is('quiz-result')) ? 'quiz-mode' : '' }}">

{{-- LOADER: miniature museum orchestra --}}
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
<div id="ambient" aria-hidden="true">
<svg id="wayangBg" viewBox="0 0 420 920" preserveAspectRatio="xMidYMax slice">
<g fill="#3d2f24">
<!-- gelung mahkota -->
<path d="M168 96 Q150 60 172 34 Q196 10 218 32 Q230 44 224 62 Q214 52 204 58 Q192 66 196 84 Z"/>
<path d="M186 108 L200 58 L212 104 L226 62 L238 108 L250 72 L258 112 L186 118 Z"/>
<!-- kepala profil -->
<path d="M184 118 Q206 116 218 128 L224 142 L216 148 L220 162 Q218 182 200 188 L186 184 L180 150 Z"/>
<path d="M184 148 L172 158 L184 162 Z"/>
<!-- leher + bahu -->
<path d="M192 188 L206 188 L204 214 L190 214 Z"/>
<path d="M178 214 Q204 206 230 216 L236 236 L172 236 Z"/>
<!-- badan -->
<path d="M176 236 Q204 230 232 238 L238 320 L242 420 L170 420 L174 320 Z"/>
<path d="M172 380 L240 380 L238 402 L174 402 Z"/>
<!-- lengan kanan -->
<path d="M178 244 Q150 260 128 250 L112 238 L104 248 L122 264 Q148 280 182 272 Z"/>
<path d="M104 248 Q92 244 88 252 L96 262 Q104 260 110 254 Z"/>
<!-- lengan kiri -->
<path d="M232 244 Q258 260 264 290 L268 320 L254 324 L248 294 Q244 272 230 264 Z"/>
<path d="M254 324 Q258 340 250 348 L238 344 L242 322 Z"/>
<!-- kain -->
<path d="M170 402 L242 402 L252 560 L246 580 L236 566 L228 584 L218 568 L208 586 L198 570 L188 588 L178 572 L168 590 L158 574 L152 560 Z"/>
<!-- kaki -->
<path d="M184 580 L200 580 L194 700 L188 760 L172 760 L178 700 Z"/>
<path d="M214 580 L230 580 L244 660 L252 720 L262 760 L246 764 L234 722 L222 660 Z"/>
<ellipse cx="180" cy="768" rx="26" ry="7"/>
<ellipse cx="254" cy="768" rx="26" ry="7"/>
<!-- selendang -->
<path d="M232 250 Q280 280 300 340 L290 348 Q272 296 230 272 Z"/>
<!-- keris -->
<path d="M228 420 L252 468 L244 474 L222 432 Z"/>
</g>
</svg>
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
  var winReady=false, animDone=false;
  function tryEnter(){
    if(winReady && animDone && !loader.classList.contains('done')){
      loader.classList.add('bye');
      setTimeout(function(){
        loader.classList.add('done');
        document.documentElement.classList.remove('loading');
      }, 420);
    }
  }
  window.addEventListener('load', function(){ winReady=true; tryEnter(); });
  var startDelay = reduced ? 0 : 80;
  setTimeout(function(){
    loader.classList.add('go'); // pedestal + instruments pop
    setTimeout(function(){ loader.classList.add('orc'); }, 880); // orchestra moment
    setTimeout(function(){ loader.classList.add('logo'); }, 1080); // title
    setTimeout(function(){ animDone=true; tryEnter(); }, 1480);
  }, startDelay);
  // Entry stagger homepage (di balik loader)
  var els = document.querySelectorAll('.hero-clay,.gamelan-sec,.sect,.map-sec');
  els.forEach(function(el,i){
    el.style.opacity='0'; el.style.transform='translateY(18px)';
    el.style.transition='opacity .7s ease '+(i*130)+'ms,transform .7s ease '+(i*130)+'ms';
  });
  var mo=new MutationObserver(function(){
    if(loader.classList.contains('done')){
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
  if(l && !l.classList.contains('done')){ l.classList.add('done'); document.documentElement.classList.remove('loading'); }
}, 8000);

/* ——— CUSTOM CURSOR ——— */
var cur=document.getElementById('cursor'),clab=cur?cur.querySelector('.clabel'):null;
if(cur && !touch){
  var cx=0,cy=0,px=0,py=0;
  document.addEventListener('mousemove',function(e){cx=e.clientX;cy=e.clientY;},{passive:true});
  (function cl(){
    px+=(cx-px)*.35; py+=(cy-py)*.35;
    cur.style.left=px+'px'; cur.style.top=py+'px';
    requestAnimationFrame(cl);
  })();
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

/* ——— SCROLL PROGRESS ——— */
var spb=document.querySelector('#scrollProg i');
window.addEventListener('scroll',function(){
  var h=document.documentElement, max=h.scrollHeight-h.clientHeight;
  if(spb) spb.style.width=(max>0?(h.scrollTop/max*100):0)+'%';
},{passive:true});

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

/* ——— REVEAL ——— */
var io=new IntersectionObserver(function(es){
  es.forEach(function(en){
    if(en.isIntersecting){
      en.target.classList.add('visible');
      // stagger cards
      if(en.target.classList.contains('obj')||en.target.classList.contains('pod')){
        var sibs=[].slice.call(en.target.parentNode.children);
        var idx=sibs.indexOf(en.target);
        en.target.style.transitionDelay=(idx*80)+'ms';
      }
      io.unobserve(en.target);
    }
  });
},{threshold:.08,rootMargin:'0px 0px -6% 0px'});
document.querySelectorAll('.reveal,.obj,.pod').forEach(function(el){ io.observe(el); });

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
      rBar.querySelector('.rn').textContent=NAMES[s]||s;
      rBar.querySelector('.rc').textContent=(COUNTS[s]||0)+' instrumen';
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
</body>
</html>
