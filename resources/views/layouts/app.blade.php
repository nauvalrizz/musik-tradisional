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
<link rel="stylesheet" href="{{ asset('assets/css/clay.css?v=121') }}">
</head>
<body>
<a class="skip" href="#main">Lewati ke konten</a>

{{-- LOADER --}}
<div id="loader" aria-hidden="true">
<div class="load-scene">
@php $entrances=['e-rise','e-slide','e-rot','e-roll']; @endphp
@foreach(($loaderItems ?? collect())->take(4) as $li => $it)
<div class="lped {{ $entrances[$li % 4] }}">
<div class="lobj"><img src="{{ gambar_alat($it->gambar) }}" alt=""></div>
<div class="lbase"></div>
<div class="lrip"></div>
</div>
@endforeach
</div>
<div class="lwave" aria-hidden="true">@for($i=0;$i<18;$i++)<i style="height:{{ 30+($i*37%70) }}%;animation-delay:{{ $i*70 }}ms"></i>@endfor</div>
<div class="lt">MUSANTARA</div>
<div class="ls">ARSIP BUNYI NUSANTARA</div>
</div>
<div id="ambient" aria-hidden="true"></div>
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
<a href="{{ url('/') }}#arsip" class="nav-link">ARSIP</a>
<a href="{{ url('/') }}#jelajahi" class="nav-link">JELAJAHI</a>
<a href="{{ url('/') }}#pulau" class="nav-link">PULAU</a>
<a href="{{ url('/') }}#koleksi" class="nav-link">KOLEKSI</a>
<a href="{{ url('/ruang-bunyi') }}" class="nav-link">RUANG BUNYI</a>
<a href="{{ url('/quiz-global') }}" class="nav-link">KUIS</a>
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

<script src="{{ asset('assets/js/gamelan.js') }}" defer></script>
@stack('scripts')
<script>
(function(){
"use strict";
var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
var touch = window.matchMedia('(hover: none)').matches;

/* ——— LOADER 2.2s ——— */
document.documentElement.classList.add('loading');
window.addEventListener('load', function(){
  setTimeout(function(){
    document.getElementById('loader').classList.add('done');
    document.documentElement.classList.remove('loading');
    // entry stagger
    // Orchestra: pedestal naik bertahap, lalu instrumen + ripple
    var peds=document.querySelectorAll('.lped');
    peds.forEach(function(pd,i){
      setTimeout(function(){ pd.classList.add('up'); }, 250+i*300);
      setTimeout(function(){ pd.classList.add('show'); }, 800+i*300);
    });
    setTimeout(function(){ document.getElementById('loader').classList.add('logo'); }, 1900);
    var els = document.querySelectorAll('.hero-clay,.gamelan-sec,.sect,.map-sec');
    els.forEach(function(el,i){
      el.style.opacity='0'; el.style.transform='translateY(18px)';
      el.style.transition='opacity .7s ease '+(i*130)+'ms,transform .7s ease '+(i*130)+'ms';
      requestAnimationFrame(function(){requestAnimationFrame(function(){
        el.style.opacity='1'; el.style.transform='none';
      });});
    });
  }, reduced ? 100 : 2500);
});
// fallback: jangan kunci selamanya
setTimeout(function(){
  var l=document.getElementById('loader');
  if(l && !l.classList.contains('done')){ l.classList.add('done'); document.documentElement.classList.remove('loading'); }
}, 4500);

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
function mpShow(name,sub){
  mpN.textContent=name; mpS.textContent=sub||'MuSantara';
  mp.classList.add('show'); mpT.textContent='❚❚';
  clearInterval(mpTimer); var p=0;
  mpTimer=setInterval(function(){ p+=2; if(p>=100){p=0;} mpF.style.width=p+'%'; },120);
}
document.getElementById('mpClose').addEventListener('click',function(){
  mp.classList.remove('show'); clearInterval(mpTimer);
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

/* ——— HERO TILT (max 4deg) ——— */
var pobj=document.querySelector('.pedestal .pobj');
if(pobj && !touch && !reduced){
  var ped=pobj.closest('.pedestal');
  ped.addEventListener('mousemove',function(e){
    var r=ped.getBoundingClientRect();
    var x=(e.clientX-r.left)/r.width-.5, y=(e.clientY-r.top)/r.height-.5;
    pobj.style.transform='rotateY('+(x*8)+'deg) rotateX('+(-y*7)+'deg) translateY(-4px)';
  });
  ped.addEventListener('mouseleave',function(){ pobj.style.transform=''; });
}

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
