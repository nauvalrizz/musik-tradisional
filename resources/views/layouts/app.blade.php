<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>@yield('title','MuSantara — Arsip Musik Tradisional Indonesia')</title>
<meta name="description" content="Arsip digital interaktif alat musik tradisional Indonesia.">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Manrope:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/museum.css') }}">
</head>
<body>
<a class="skip" href="#main">Lewati ke konten</a>
<header class="site-head">
<div class="wrap head-inner">
<a class="brand" href="{{ url('/') }}">MuSantara<small>ARSIP MUSIK TRADISIONAL</small></a>
<nav class="head-nav" aria-label="Navigasi utama">
<a href="{{ url('/') }}#bunyi">Arsip</a>
<a href="{{ url('/') }}#telusuri">Jelajahi</a>
<a href="{{ url('/') }}#pulau">Pulau</a>
<a href="{{ url('/') }}#koleksi">Koleksi</a>
</nav>
<form class="head-search" action="{{ route('search') }}" method="GET" role="search">
<input type="text" name="q" placeholder="Cari instrumen…" aria-label="Cari instrumen" value="{{ request('q') }}">
<button type="submit" aria-label="Cari">→</button>
</form>
</div>
</header>
<main id="main">@yield('content')</main>
<footer class="site-foot">
<div class="wrap foot-grid">
<div class="foot-brand">
<span class="brand">MuSantara</span>
<p>Arsip digital alat musik tradisional Indonesia — suara, tempat, dan cerita.</p>
</div>
<nav class="foot-nav" aria-label="Navigasi footer">
<a href="{{ url('/') }}#bunyi">Arsip</a>
<a href="{{ url('/') }}#pulau">Pulau</a>
<a href="{{ url('/') }}#koleksi">Koleksi</a>
<a href="/quiz-global">Kuis</a>
</nav>
<p class="foot-note">© {{ date('Y') }} MuSantara<br>Edukasi budaya Indonesia</p>
</div>
</footer>
<div id="imgModal" class="img-modal" aria-hidden="true" style="display:none;position:fixed;inset:0;z-index:100;background:rgba(38,35,31,.92);align-items:center;justify-content:center;flex-direction:column;padding:1rem">
<span class="img-close" role="button" aria-label="Tutup" style="position:absolute;top:1rem;right:1.4rem;font-size:2rem;color:#F3EEE4;cursor:pointer">&times;</span>
<img id="imgZoom" alt="" style="max-width:min(92vw,860px);max-height:80vh">
<p id="imgCaption" style="color:#8A8177;font-family:var(--mono);font-size:.7rem;margin-top:.7rem"></p>
</div>
<script src="{{ asset('assets/js/gamelan.js') }}" defer></script>
@stack('scripts')
<script>
(function(){
var m=document.getElementById('imgModal'),im=document.getElementById('imgZoom'),cp=document.getElementById('imgCaption');
function openM(s,a){im.src=s;im.alt=a||'';cp.textContent=a||'';m.style.display='flex';document.body.style.overflow='hidden';}
function closeM(){m.style.display='none';document.body.style.overflow='';}
document.addEventListener('click',function(e){var t=e.target.closest('img[data-zoom]');if(t){openM(t.currentSrc||t.src,t.alt);return;}if(e.target.closest('.img-close')||e.target===m)closeM();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeM();});
var io=new IntersectionObserver(function(es){es.forEach(function(en){if(en.isIntersecting){en.target.classList.add('visible');io.unobserve(en.target);}});},{threshold:.08});
document.querySelectorAll('.reveal').forEach(function(el){io.observe(el);});
/* Peta */
var NAMES={'sumatra':'Sumatera','jawa':'Jawa','kalimantan':'Kalimantan','sulawesi':'Sulawesi','bali-nusa-tenggara':'Bali & Nusa Tenggara','maluku':'Maluku','papua':'Papua'};
var dc=document.getElementById('island-data'),COUNTS={};
try{COUNTS=JSON.parse(dc?dc.dataset.counts:'{}');}catch(e){}
var info=document.getElementById('regionInfo');
document.querySelectorAll('.map-frame path[data-slug]').forEach(function(p){
var s=p.dataset.slug;
p.addEventListener('click',function(){window.location.href='/pulau/'+s;});
p.addEventListener('mouseenter',function(){if(info)info.textContent=NAMES[s]+' — '+(COUNTS[s]||0)+' instrumen. Klik untuk menjelajah →';});
});
/* Pads -> status */
var st=document.getElementById('soundStatus');
document.querySelectorAll('.gamelan-pad,.clay-disc').forEach(function(p){
p.addEventListener('click',function(){
var nm=p.dataset.instrument||'gong';
if(st)st.textContent='♪ '+(NAMES[nm]||nm).toUpperCase();
p.classList.add('hit');setTimeout(function(){p.classList.remove('hit');},300);
});
});
/* Waveform anim */
document.querySelectorAll('.play-btn,.clay-disc,.obj-play').forEach(function(b){
b.addEventListener('click',function(){
var w=b.closest('.player,.sound-panel,.obj-label,.sound-stage')?.querySelector('.wave');
if(w){w.classList.add('playing');setTimeout(function(){w.classList.remove('playing');},4000);}
});
});
})();
</script>
</body>
</html>
