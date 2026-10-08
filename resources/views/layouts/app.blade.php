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
#loader{position:fixed;inset:0;z-index:500;background:#141110;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:1.2rem;overflow:hidden}
#loader .load-cam{transition:transform 2.2s cubic-bezier(.22,1,.36,1);transform:scale(1.18) translateY(26px)}
#loader.wide .load-cam{transform:scale(1) translateY(0)}
#loader.enter .load-cam{transform:scale(1.12) translateY(-14px)}
#loader.done{opacity:0;visibility:hidden;transition:opacity .5s,visibility .5s}
</style>
<link rel="stylesheet" href="{{ asset('assets/css/clay.css?v=121') }}">
</head>
<body class="{{ (request()->is('quiz-global') || request()->is('quiz-result')) ? 'quiz-mode' : '' }}">

{{-- LOADER --}}
<div id="loader" aria-hidden="true">
<div class="load-cam" style="display:flex;flex-direction:column;align-items:center;gap:1.2rem">
<div class="load-sign"><span>NUSANTARA ORCHESTRA</span></div>
<div class="load-scene">
@php
$entrances=['e-rise','e-slide','e-rot','e-roll'];
$litems = ($loaderItems ?? collect())->take(4);
// Ikon SVG clay per tipe instrumen (ganti foto asli di loader saja)
function loaderIcon($nama){
  $n = strtolower($nama);
  $c1='#62483A'; $c2='#3A2E25'; $t='#A95135';
  if(str_contains($n,'kendang')||str_contains($n,'gendang')||str_contains($n,'tifa')||str_contains($n,'bedug')||str_contains($n,'babun'))
    return '<svg viewBox="0 0 100 100" width="72" height="72"><rect x="22" y="36" width="56" height="28" rx="14" fill="'.$c1.'"/><ellipse cx="22" cy="50" rx="9" ry="14" fill="#C9B59D"/><ellipse cx="78" cy="50" rx="9" ry="14" fill="#C9B59D"/><rect x="40" y="68" width="20" height="22" rx="4" fill="'.$c2.'"/></svg>';
  if(str_contains($n,'saron')||str_contains($n,'bonang')||str_contains($n,'kenong')||str_contains($n,'kolintang')||str_contains($n,'gambang')||str_contains($n,'talempong'))
    return '<svg viewBox="0 0 100 100" width="72" height="72"><rect x="14" y="66" width="72" height="12" rx="5" fill="'.$c2.'"/><rect x="20" y="52" width="10" height="14" rx="2" fill="'.$t.'"/><rect x="34" y="48" width="10" height="18" rx="2" fill="'.$t.'"/><rect x="48" y="44" width="10" height="22" rx="2" fill="'.$t.'"/><rect x="62" y="48" width="10" height="18" rx="2" fill="'.$t.'"/><rect x="76" y="52" width="6" height="14" rx="2" fill="'.$t.'"/></svg>';
  if(str_contains($n,'sasando'))
    return '<svg viewBox="0 0 100 100" width="72" height="72"><path d="M50 14 L78 82 L22 82 Z" fill="'.$c1.'"/><line x1="50" y1="14" x2="50" y2="82" stroke="#C9B59D" stroke-width="2"/><line x1="40" y1="30" x2="40" y2="82" stroke="#C9B59D" stroke-width="1.4"/><line x1="60" y1="30" x2="60" y2="82" stroke="#C9B59D" stroke-width="1.4"/><ellipse cx="50" cy="86" rx="20" ry="5" fill="'.$c2.'"/></svg>';
  if(str_contains($n,'angklung'))
    return '<svg viewBox="0 0 100 100" width="72" height="72"><line x1="18" y1="26" x2="82" y2="26" stroke="'.$c2.'" stroke-width="6" stroke-linecap="round"/><rect x="26" y="30" width="9" height="42" rx="4" fill="'.$c1.'"/><rect x="40" y="30" width="9" height="52" rx="4" fill="'.$c1.'"/><rect x="54" y="30" width="9" height="42" rx="4" fill="'.$c1.'"/><rect x="68" y="30" width="9" height="34" rx="4" fill="'.$c1.'"/></svg>';
  if(str_contains($n,'sape')||str_contains($n,'saluang')||str_contains($n,'kecapi'))
    return '<svg viewBox="0 0 100 100" width="72" height="72"><ellipse cx="50" cy="62" rx="18" ry="22" fill="'.$c1.'"/><rect x="46" y="8" width="8" height="42" rx="3" fill="'.$c2.'"/><line x1="50" y1="12" x2="50" y2="78" stroke="#C9B59D" stroke-width="1.6"/><line x1="44" y1="14" x2="44" y2="72" stroke="#C9B59D" stroke-width="1.2"/><line x1="56" y1="14" x2="56" y2="72" stroke="#C9B59D" stroke-width="1.2"/></svg>';
  // default: gong
  return '<svg viewBox="0 0 100 100" width="72" height="72"><circle cx="50" cy="42" r="28" fill="'.$c1.'"/><circle cx="50" cy="42" r="10" fill="'.$t.'"/><circle cx="50" cy="42" r="28" fill="none" stroke="'.$c2.'" stroke-width="3"/><line x1="24" y1="66" x2="22" y2="90" stroke="'.$c2.'" stroke-width="5" stroke-linecap="round"/><line x1="76" y1="66" x2="78" y2="90" stroke="'.$c2.'" stroke-width="5" stroke-linecap="round"/></svg>';
}
if($litems->isEmpty()){ $litems = collect([1,2,3,4]); $lfallback=true; } else { $lfallback=false; }
@endphp
@foreach($litems as $li => $it)
<div class="lped {{ $entrances[$li % 4] }}"><div class="llight"></div>
<div class="lobj">{!! $lfallback ? '<div class="lclay"></div>' : loaderIcon($it->nama) !!}</div>
<div class="lbase"></div>
<div class="lrip"></div>
</div>
@endforeach
</div>
<div class="lfloor"><i></i><i></i><i></i><i></i></div>
</div>
<p class="lphase" id="lphase">MEMBANGUN RUANG MUSIK</p>
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

/* ——— LOADER: orchestra mulai LANGSUNG, hide nunggu ready ——— */
document.documentElement.classList.add('loading');
(function(){
  var loader=document.getElementById('loader');
  var peds=document.querySelectorAll('.lped');
  var lp=document.getElementById('lphase');
  var fl=document.querySelectorAll('.lfloor i');
  var phases=['MEMBANGUN RUANG MUSIK','MENYUSUN KOLEKSI','MENYALAKAN RUANG BUNYI'];
  var winReady=false, animDone=false;
  function tryEnter(){
    if(winReady && animDone && !loader.classList.contains('done')){
      // ENTERING_MUSEUM: kamera maju masuk
      loader.classList.add('enter');
      setTimeout(function(){
        loader.classList.add('done');
        document.documentElement.classList.remove('loading');
      }, 650);
    }
  }
  window.addEventListener('load', function(){ winReady=true; tryEnter(); });
  // Orchestra mulai segera (script di akhir body, DOM siap)
  var startDelay = reduced ? 0 : 150;
  setTimeout(function(){
    loader.classList.add('wide'); // camera pull back
    peds.forEach(function(pd,i){
      setTimeout(function(){ pd.classList.add('up'); }, i*280);
      setTimeout(function(){
        pd.classList.add('show');
        setTimeout(function(){ pd.classList.add('lit'); if(fl[i]) fl[i].classList.add('lit'); }, 450);
        if(lp&&phases[Math.min(i,2)]) lp.textContent=phases[Math.min(i,2)];
      }, 500+i*280);
    });
    var lastMs = 500+(peds.length-1)*280+900;
    setTimeout(function(){ loader.classList.add('logo'); }, lastMs); // LOADING_COMPLETE
    setTimeout(function(){ animDone=true; tryEnter(); }, lastMs+400);
  }, startDelay);
  // Entry stagger homepage (di balik loader)
  var els = document.querySelectorAll('.hero-clay,.gamelan-sec,.sect,.map-sec');
  els.forEach(function(el,i){
    el.style.opacity='0'; el.style.transform='translateY(18px)';
    el.style.transition='opacity .7s ease '+(i*130)+'ms,transform .7s ease '+(i*130)+'ms';
  });
  // Reveal homepage tepat saat loader done
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
