<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>@yield('title','MuSantara — Arsip Bunyi Nusantara')</title>
<meta name="description" content="Arsip bunyi alat musik tradisional Indonesia.">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Manrope:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/clay.css') }}">
</head>
<body>
<a class="skip" href="#main">Lewati ke konten</a>
@include('partials.navbar')
<main id="main">@yield('content')</main>
@include('partials.footer')
<div id="imgModal" class="img-modal" aria-hidden="true">
<span class="img-close" role="button" aria-label="Tutup">&times;</span>
<img id="imgZoom" alt=""><p class="img-caption" id="imgCaption"></p>
</div>
<script src="{{ asset('assets/js/gamelan.js') }}" defer></script>
@stack('scripts')
<script>
(function(){
var m=document.getElementById('imgModal'),im=document.getElementById('imgZoom'),cp=document.getElementById('imgCaption');
function openM(s,a){im.src=s;im.alt=a||'';cp.textContent=a||'';m.classList.add('open');document.body.style.overflow='hidden';}
function closeM(){m.classList.remove('open');document.body.style.overflow='';}
document.addEventListener('click',function(e){var t=e.target.closest('img[data-zoom]');if(t){openM(t.currentSrc||t.src,t.alt);return;}if(e.target.closest('.img-close')||e.target===m)closeM();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeM();});
var io=new IntersectionObserver(function(es){es.forEach(function(en){if(en.isIntersecting){en.target.classList.add('visible');io.unobserve(en.target);}});},{threshold:.1});
document.querySelectorAll('.reveal').forEach(function(el){io.observe(el);});
/* Peta: klik pulau -> /pulau/{slug} */
var NAMES={'sumatra':'Sumatra','jawa':'Jawa','kalimantan':'Kalimantan','sulawesi':'Sulawesi','bali-nusa-tenggara':'Bali & NTT','maluku':'Maluku','papua':'Papua'};
var dc=document.getElementById('island-data'),COUNTS={};
try{COUNTS=JSON.parse(dc?dc.dataset.counts:'{}');}catch(e){}
document.querySelectorAll('.map-card path[data-slug]').forEach(function(p){
var s=p.dataset.slug;
p.style.cursor='pointer';
p.addEventListener('click',function(){window.location.href='/pulau/'+s;});
p.addEventListener('mouseenter',function(e){var t=document.getElementById('mapTip');if(!t){t=document.createElement('div');t.id='mapTip';t.style.cssText='position:fixed;z-index:99;background:#4a4038;color:#ede6d6;padding:.5rem .9rem;border-radius:14px;font:500 .7rem "DM Mono",monospace;pointer-events:none;box-shadow:0 8px 18px rgba(0,0,0,.3)';document.body.appendChild(t);}t.textContent=NAMES[s]+' · '+(COUNTS[s]||0)+' alat';t.style.display='block';});
p.addEventListener('mousemove',function(e){var t=document.getElementById('mapTip');if(t){t.style.left=(e.clientX+14)+'px';t.style.top=(e.clientY+14)+'px';}});
p.addEventListener('mouseleave',function(){var t=document.getElementById('mapTip');if(t)t.style.display='none';});
});
/* Gamelan: update status text */
var st=document.getElementById('soundStatus');
document.querySelectorAll('.gamelan-pad').forEach(function(p){
p.addEventListener('click',function(){if(st)st.textContent='♪ '+p.dataset.instrument.toUpperCase()+' · '+p.dataset.freq+' Hz';p.classList.add('hit');setTimeout(function(){p.classList.remove('hit');},180);});
});
})();
</script>
</body>
</html>
