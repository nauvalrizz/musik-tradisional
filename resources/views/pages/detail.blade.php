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
<a href="{{ $backUrl }}" class="back-link reveal">{{ $backText }}</a>
<div class="detail-grid">
<div class="detail-media reveal">
<figure class="object-fig">
@if($alat->gambar)
<img src="{{ gambar_alat($alat->gambar) }}" alt="{{ $alat->nama }}" data-zoom>
@endif
</figure>
@if($alat->sumber_gambar)
<p class="obj-meta" style="margin-top:.8rem">Sumber: <a href="{{ $alat->sumber_gambar }}" target="_blank" rel="noopener" style="text-decoration:underline">{{ $alat->author ?? 'Wikimedia Commons' }}</a> · {{ $alat->license ?? 'Lihat di sumber' }}</p>
@endif
</div>
<div class="detail-info reveal">
<p class="obj-num">Arsip № {{ str_pad($alat->id,3,'0',STR_PAD_LEFT) }}</p>
<h1>{{ $alat->nama }}</h1>
<p class="obj-meta">{{ optional($alat->pulau)->nama ?? 'Nusantara' }} · {{ $alat->sumber_bunyi ?? '—' }} · {{ $alat->kategori ?? '—' }}</p>
<div class="obj-rule" style="margin:1.2rem 0"></div>
@if($alat->audio)
<div class="player" style="margin-bottom:1.6rem">
<div class="player-row">
<button class="play-btn" id="btnAudio" aria-label="Putar rekaman">▶</button>
<div><p style="font-weight:600">Rekaman Asli</p><p class="time">{{ $alat->nama }}</p></div>
</div>
<audio id="audioEl" src="{{ audio_alat($alat->audio) }}" preload="none"></audio>
<div class="wave" id="audioWave" aria-hidden="true">@for($i=0;$i<24;$i++)<i style="height:{{ 20+($i*41%80) }}%"></i>@endfor</div>
</div>
@push('scripts')
<script>
document.getElementById('btnAudio')?.addEventListener('click',function(){
var a=document.getElementById('audioEl'),w=document.getElementById('audioWave');
if(a.paused){a.play();w.classList.add('playing');this.textContent='❚❚';}
else{a.pause();w.classList.remove('playing');this.textContent='▶';}
a.onended=function(){w.classList.remove('playing');document.getElementById('btnAudio').textContent='▶';};
});
</script>
@endpush
@else
<div class="player" style="margin-bottom:1.6rem">
<div class="player-row">
<button class="play-btn" id="btnSynth" data-sumber="{{ $alat->sumber_bunyi ?? 'Idiofon' }}" aria-label="Putar sintesis">▶</button>
<div><p style="font-weight:600">Karakter Bunyi</p><p class="time">Sintesis · {{ $alat->sumber_bunyi ?? 'Idiofon' }}</p></div>
</div>
<div class="wave" id="synthWave" aria-hidden="true">@for($i=0;$i<24;$i++)<i style="height:{{ 20+($i*41%80) }}%"></i>@endfor</div>
<p class="obj-meta" style="margin-top:.8rem">Rekaman asli belum tersedia.</p>
</div>
@endif
<dl class="spec-table">
<div><dt class="k">Pulau</dt><dd>{{ optional($alat->pulau)->nama ?? '—' }}</dd></div>
<div><dt class="k">Sumber Bunyi</dt><dd>{{ $alat->sumber_bunyi ?? '—' }}</dd></div>
<div><dt class="k">Kategori</dt><dd>{{ $alat->kategori ?? '—' }}</dd></div>
</dl>
<div class="prose">{!! nl2br(e($alat->deskripsi)) !!}</div>
<div style="margin-top:1.8rem">
<button class="quiz-opt quiz-trigger" type="button" style="text-align:center;font-weight:600">Coba Kuis: {{ $alat->nama }} →</button>
</div>
</div>
</div>

@if($terkait->count())
<div class="region-block reveal" style="margin-top:1rem">
<div class="region-head">
<span class="region-num">→</span>
<span class="region-name">Arsip Terkait</span>
<a class="region-count" href="{{ url('/acak') }}">Acak →</a>
</div>
<div class="objects">
@foreach($terkait as $idx => $item)
<article class="object">
<a href="{{ url('/alat/'.$item->id) }}">
<figure class="object-fig">
@if($item->gambar)<img src="{{ gambar_alat($item->gambar) }}" alt="{{ $item->nama }}" loading="lazy" data-zoom>@endif
</figure>
<div class="object-label">
<span class="obj-num">{{ str_pad($idx+1,2,'0',STR_PAD_LEFT) }}</span>
<p class="obj-name">{{ $item->nama }}</p>
<p class="obj-meta">{{ optional($item->pulau)->nama ?? '—' }}</p>
</div>
</a>
</article>
@endforeach
</div>
</div>
@endif
</div>

{{-- Quiz modal --}}
<div id="quizModal" style="display:none;position:fixed;inset:0;z-index:100;background:rgba(38,35,31,.9);align-items:center;justify-content:center;padding:1rem">
<div class="quiz-card reveal" style="max-width:480px;width:100%;position:relative;max-height:90vh;overflow-y:auto">
<button class="quiz-close" aria-label="Tutup" style="position:absolute;top:1rem;right:1.2rem;font-size:1.6rem">×</button>
<p class="obj-num">Kuis Cepat</p>
<p style="margin:.6rem 0"><strong>{{ $alat->nama }}</strong><br>{{ $pertanyaan }}</p>
<div class="quiz-options" data-correct="{{ $jawabanBenar }}" data-id="{{ $alat->id }}" data-tipe="{{ $tipeSoal }}">
@foreach($opsi as $o)
<button class="quiz-opt quiz-choice" type="button" data-value="{{ $o }}">{{ $o }}</button>
@endforeach
</div>
<p id="quiz-feedback" aria-live="polite" style="min-height:1.5rem;font-weight:600"></p>
<div style="display:flex;gap:.6rem;justify-content:center;margin-top:.8rem">
<button class="quiz-opt quiz-retry" type="button" style="margin:0;width:auto">Coba Lagi</button>
<button class="quiz-opt quiz-close" type="button" style="margin:0;width:auto">Tutup</button>
</div>
</div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/detail-synth.js') }}"></script>
<script src="{{ asset('assets/js/quiz.js') }}"></script>
<script>
/* synth wave anim */
document.getElementById('btnSynth')?.addEventListener('click',function(){
var w=document.getElementById('synthWave');
w.classList.add('playing');setTimeout(function(){w.classList.remove('playing');},3500);
});
</script>
@endpush
