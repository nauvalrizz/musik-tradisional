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
<div class="detail-top">
<a href="{{ $backUrl }}" class="btn-line magnet reveal">{{ $backText }}</a>
</div>
<div class="detail-grid">
<div class="detail-media reveal">
<figure class="obj-fig">
@if($alat->gambar)
<img src="{{ gambar_alat($alat->gambar) }}" alt="{{ $alat->nama }}" data-zoom>
@endif
</figure>
@if($alat->sumber_gambar)
<p class="attr">Sumber: <a href="{{ $alat->sumber_gambar }}" target="_blank" rel="noopener">{{ $alat->author ?? 'Wikimedia Commons' }}</a> · {{ $alat->license ?? 'Lihat di sumber' }}</p>
@endif
</div>
<div class="detail-info reveal">
<p class="obj-num">Arsip № {{ str_pad($alat->id,3,'0',STR_PAD_LEFT) }}</p>
<h1>{{ $alat->nama }}</h1>
<p class="origin">{{ optional($alat->pulau)->nama ?? 'Nusantara' }} · {{ $alat->sumber_bunyi ?? '—' }}</p>

@if($alat->audio)
<div class="dplayer">
<div class="prow">
<button class="play-btn magnet" id="btnAudio" aria-label="Putar rekaman">▶</button>
<div><p style="font-weight:600">Rekaman Asli</p><p class="time">{{ $alat->nama }}</p></div>
</div>
<audio id="audioEl" src="{{ audio_alat($alat->audio) }}" preload="none"></audio>
<div class="hero-wave" id="audioWave" aria-hidden="true" style="justify-content:flex-start;margin-top:1rem">@for($i=0;$i<24;$i++)<i style="height:{{ 18+($i*47%82) }}%"></i>@endfor</div>
</div>
@push('scripts')
<script>
document.getElementById('btnAudio')?.addEventListener('click',function(){
var a=document.getElementById('audioEl'),w=document.getElementById('audioWave');
if(a.paused){a.play();w.classList.add('on');this.textContent='❚❚';}
else{a.pause();w.classList.remove('on');this.textContent='▶';}
a.onended=function(){w.classList.remove('on');document.getElementById('btnAudio').textContent='▶';};
});
</script>
@endpush
@else
<div class="dplayer">
<div class="prow">
<button class="play-btn magnet" id="btnSynth" data-sumber="{{ $alat->sumber_bunyi ?? 'Idiofon' }}" aria-label="Putar sintesis">▶</button>
<div><p style="font-weight:600">Karakter Bunyi</p><p class="time">Sintesis · {{ $alat->sumber_bunyi ?? 'Idiofon' }}</p></div>
</div>
<div class="hero-wave" id="synthWave" aria-hidden="true" style="justify-content:flex-start;margin-top:1rem">@for($i=0;$i<24;$i++)<i style="height:{{ 18+($i*47%82) }}%"></i>@endfor</div>
<p class="attr">Rekaman asli belum tersedia — sintesis karakter bunyinya.</p>
</div>
@endif

<dl class="spec">
<div><dt class="k">Pulau</dt><dd>{{ optional($alat->pulau)->nama ?? '—' }}</dd></div>
<div><dt class="k">Sumber Bunyi</dt><dd>{{ $alat->sumber_bunyi ?? '—' }}</dd></div>
<div><dt class="k">Kategori</dt><dd>{{ $alat->kategori ?? '—' }}</dd></div>
</dl>
<div class="prose">{!! nl2br(e($alat->deskripsi)) !!}</div>
<div style="margin-top:2rem">
<button class="btn-line magnet quiz-trigger" type="button">Kuis: {{ $alat->nama }} <span class="arw">→</span></button>
</div>
</div>
</div>

@if($terkait->count())
<div class="sect-label reveal" style="margin-top:1rem"><span class="n">→</span><span class="t">Lanjutkan Menjelajah</span></div>
<div class="objects">
@foreach($terkait->take(4) as $idx => $item)
<article class="obj">
<a href="{{ url('/alat/'.$item->id) }}" aria-label="{{ $item->nama }}">
<figure class="obj-fig">
@if($item->gambar)<img src="{{ gambar_alat($item->gambar) }}" alt="{{ $item->nama }}" loading="lazy" data-zoom>@endif
</figure>
<div class="obj-meta">
<span class="n">{{ str_pad($idx+1,2,'0',STR_PAD_LEFT) }}</span>
<span class="t">{{ $item->nama }}<small>{{ optional($item->pulau)->nama ?? '—' }}</small></span>
<span class="a">→</span>
</div>
<div class="obj-accent" aria-hidden="true"></div>
</a>
</article>
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
<button class="btn-line quiz-retry" type="button">Coba Lagi</button>
<button class="btn-line quiz-close" type="button">Tutup</button>
</div>
</div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/detail-synth.js') }}"></script>
<script src="{{ asset('assets/js/quiz.js') }}"></script>
<script>
document.getElementById('btnSynth')?.addEventListener('click',function(){
var w=document.getElementById('synthWave');
if(w){w.classList.add('on');setTimeout(function(){w.classList.remove('on');},3500);}
});
</script>
@endpush
