@extends('layouts.app')
@section('title', $alat->nama . ' — MuSantara')
@section('content')
@php
    $from = request('from'); $slug = request('slug'); $q = request('q');
    $backUrl = url('/'); $backText = '← Beranda';
    if ($from === 'search') { $backUrl = url('/search?q='.urlencode($q)); $backText = '← Hasil pencarian'; }
    elseif ($from === 'pulau') { $backUrl = url('/pulau/'.$slug); $backText = '← '.ucfirst($slug); }
@endphp
<div class="wrap">
<div style="padding:7rem 0 1rem">
<a href="{{ $backUrl }}" class="clay-btn reveal">{{ $backText }}</a>
</div>
<div class="hero-grid" style="padding:1rem 0 2rem">
<div class="pedestal reveal">
<div class="pring" aria-hidden="true"></div>
<div class="pbase" aria-hidden="true"></div>
<div class="pobj">
@if($alat->gambar)
<img src="{{ gambar_alat($alat->gambar) }}" alt="{{ $alat->nama }}" data-zoom>
@endif
</div>
@if($alat->sumber_gambar)
<p class="attr" style="position:absolute;bottom:-1.6rem;font-size:.72rem;color:var(--muted)">Sumber: <a href="{{ $alat->sumber_gambar }}" target="_blank" rel="noopener" style="color:var(--light)">{{ $alat->author ?? 'Wikimedia Commons' }}</a></p>
@endif
</div>
<div class="reveal">
<p class="hero-kicker">ARSIP № {{ str_pad($alat->id,3,'0',STR_PAD_LEFT) }}</p>
<h1 class="hero-title" style="font-size:clamp(2rem,4.5vw,3rem)">{{ $alat->nama }}</h1>
<p class="hero-sub">{{ optional($alat->pulau)->nama ?? 'Nusantara' }} · {{ $alat->sumber_bunyi ?? '—' }}</p>

@if($alat->audio)
<div class="audio-console" style="margin:1.6rem 0">
<div style="display:flex;align-items:center;gap:1.1rem">
<button class="hero-play" id="btnAudio" aria-label="Putar rekaman" style="position:static">▶</button>
<div><p class="ttl">Rekaman Asli</p><p class="sub">{{ $alat->nama }}</p></div>
</div>
<audio id="audioEl" src="{{ audio_alat($alat->audio) }}" preload="none"></audio>
<div class="waveform" id="audioWave" aria-hidden="true">@for($i=0;$i<28;$i++)<i style="height:{{ 18+($i*47%82) }}%"></i>@endfor</div>
<div class="audio-time"><span id="tCur">00:00</span><span id="tDur">--:--</span></div>
</div>
@push('scripts')
<script>
(function(){
var b=document.getElementById('btnAudio'),a=document.getElementById('audioEl'),w=document.getElementById('audioWave');
var tC=document.getElementById('tCur'),tD=document.getElementById('tDur');
if(!b||!a||!w) return;
var bars=w.querySelectorAll('i'), raf=null;
function fmt(s){ if(!isFinite(s)||s<0) return '--:--'; s=Math.floor(s);
  return String(Math.floor(s/60)).padStart(2,'0')+':'+String(s%60).padStart(2,'0'); }
function draw(){
  var d=a.duration||0, c=a.currentTime||0, p=d>0?c/d:0;
  if(tC) tC.textContent=fmt(c);
  if(tD) tD.textContent=fmt(d);
  var lit=Math.round(p*bars.length);
  bars.forEach(function(br,i){ br.classList.toggle('lit', i<lit); });
  if(!a.paused&&!a.ended) raf=requestAnimationFrame(draw); else raf=null;
}
function sync(){
  var playing=!a.paused&&!a.ended;
  w.classList.toggle('playing',playing);
  b.classList.toggle('playing',playing);
  b.textContent=playing?'\u275A\u275A':'\u25B6';
  if(playing&&!raf) draw(); else if(!playing&&raf){cancelAnimationFrame(raf);raf=null;}
  if(a.ended) draw(); // pastikan 100% di akhir
}
b.addEventListener('click',function(){ if(a.paused){a.play();}else{a.pause();} });
a.addEventListener('play',sync);a.addEventListener('pause',sync);a.addEventListener('ended',sync);
a.addEventListener('loadedmetadata',function(){ if(tD) tD.textContent=fmt(a.duration); draw(); });
// klik waveform = seek (kalau durasi ada)
w.addEventListener('click',function(e){
  var d=a.duration; if(!d||!isFinite(d)) return;
  var r=w.getBoundingClientRect();
  a.currentTime=((e.clientX-r.left)/r.width)*d;
  draw();
});
w.style.cursor='pointer';
})();
</script>
@endpush
@else
<div class="audio-console" style="margin:1.6rem 0">
<div style="display:flex;align-items:center;gap:1.1rem">
<button class="hero-play" id="btnSynth" data-sumber="{{ $alat->sumber_bunyi ?? 'Idiofon' }}" aria-label="Putar sintesis" style="position:static">▶</button>
<div><p class="ttl">Karakter Bunyi</p><p class="sub">Sintesis · {{ $alat->sumber_bunyi ?? 'Idiofon' }}</p></div>
</div>
<div class="waveform" id="synthWave" aria-hidden="true">@for($i=0;$i<28;$i++)<i style="height:{{ 18+($i*47%82) }}%"></i>@endfor</div>
<p class="attr" style="font-size:.78rem;color:var(--muted)">Rekaman asli belum tersedia — sintesis karakter bunyinya.</p>
</div>
@endif

<div class="plaque-grid">
<div class="plaque"><p class="k">PULAU</p><p class="v">{{ optional($alat->pulau)->nama ?? '—' }}</p></div>
<div class="plaque"><p class="k">SUMBER BUNYI</p><p class="v">{{ $alat->sumber_bunyi ?? '—' }}</p></div>
<div class="plaque"><p class="k">KATEGORI</p><p class="v">{{ $alat->kategori ?? '—' }}</p></div>
</div>
<div style="color:var(--muted);max-width:60ch;line-height:1.8">{!! nl2br(e($alat->deskripsi)) !!}</div>
<div style="margin-top:2rem">
<button class="clay-btn quiz-trigger" type="button">Kuis: {{ $alat->nama }} <span class="arw">→</span></button>
</div>
</div>
</div>
</div>

@if($terkait->count())
<div class="sect-label reveal" style="margin-top:1rem"><span class="n">→</span><span class="t">Lanjutkan Menjelajah</span></div>
<div class="pods">
@foreach($terkait->take(4) as $idx => $item)
@include('components.card',['item'=>$item,'idx'=>$idx])
@endforeach
</div>
@endif
</div>

{{-- Quiz modal --}}
<div id="quizModal" aria-hidden="true">
<div class="quiz-card">
<button class="quiz-close" aria-label="Tutup" style="position:absolute;top:1rem;right:1.2rem;font-size:1.5rem;color:var(--text2)">×</button>
<p class="obj-num" style="font-family:var(--mono);font-size:.62rem;letter-spacing:.2em;color:var(--muted)">KUIS CEPAT</p>
<p style="margin:.7rem 0"><strong>{{ $alat->nama }}</strong><br><span style="color:var(--text2)">{{ $pertanyaan }}</span></p>
<div class="quiz-options" data-correct="{{ $jawabanBenar }}" data-id="{{ $alat->id }}" data-tipe="{{ $tipeSoal }}">
@foreach($opsi as $o)
<button class="quiz-choice" type="button" data-value="{{ $o }}">{{ $o }}</button>
@endforeach
</div>
<p id="quiz-feedback" aria-live="polite"></p>
<div style="display:flex;gap:.6rem;justify-content:center;margin-top:1rem">
<button class="clay-btn quiz-retry" type="button">Coba Lagi</button>
<button class="clay-btn quiz-close" type="button">Tutup</button>
</div>
</div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/detail-synth.js') }}"></script>
<script src="{{ asset('assets/js/quiz.js') }}"></script>
<script>
(function(){
var b=document.getElementById('btnSynth'),w=document.getElementById('synthWave');
if(!b||!w) return;
new MutationObserver(function(){
  w.classList.toggle('playing',b.classList.contains('playing'));
}).observe(b,{attributes:true,attributeFilter:['class']});
})();
</script>
@endpush
