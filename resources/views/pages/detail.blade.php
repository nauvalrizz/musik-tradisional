@extends('layouts.app')
@section('title', $alat->nama . ' — MuSantara')
@section('content')
@php
    $from = request('from'); $slug = request('slug'); $q = request('q');
    $backUrl = url('/');
    $backText = '← Kembali ke beranda';
    if ($from === 'search') { $backUrl = url('/search?q='.urlencode($q)); $backText = '← Kembali ke hasil pencarian'; }
    elseif ($from === 'pulau') { $backUrl = url('/pulau/'.$slug); $backText = '← Kembali ke '.ucfirst($slug); }
@endphp

<section class="section">
<div class="wrap">
<a href="{{ $backUrl }}" class="btn reveal" style="margin-bottom:1.5rem">{{ $backText }}</a>

<div class="detail-hero reveal">
@if($alat->gambar)
<div class="detail-photo">
<img src="{{ gambar_alat($alat->gambar) }}" alt="{{ $alat->nama }}" data-zoom loading="lazy">
</div>
@endif
<div class="detail-head">
<p class="eyebrow">Arsip № {{ str_pad($alat->id, 3, '0', STR_PAD_LEFT) }}</p>
<h1>{{ $alat->nama }}</h1>
<p class="card-meta" style="font-size:.8rem">{{ optional($alat->pulau)->nama ?? 'Nusantara' }}</p>
@if($alat->sumber_gambar)
<p class="attr">Sumber: <a href="{{ $alat->sumber_gambar }}" target="_blank" rel="noopener">{{ $alat->author ?? 'Wikimedia Commons' }}</a> · {{ $alat->license ?? 'Lihat di sumber' }}</p>
@endif
</div>
</div>

<div class="detail-grid">
<div class="detail-main">
<div class="info-card reveal">
<div class="info-row"><span>Pulau</span><strong>{{ optional($alat->pulau)->nama ?? '-' }}</strong></div>
<div class="info-row"><span>Sumber Bunyi</span><strong>{{ $alat->sumber_bunyi ?? '-' }}</strong></div>
<div class="info-row"><span>Kategori</span><strong>{{ $alat->kategori ?? '-' }}</strong></div>
</div>

<div class="info-card reveal">
<h3>Tentang</h3>
<p>{!! nl2br(e($alat->deskripsi)) !!}</p>
</div>

<div class="info-card reveal">
<h3>Dengarkan</h3>
@if($alat->audio)
<audio controls style="width:100%" src="{{ audio_alat($alat->audio) }}"></audio>
@else
<button type="button" id="btnSynth" class="btn btn-primary" data-sumber="{{ $alat->sumber_bunyi ?? 'Idiofon' }}">▶ Putar Karakter Bunyi</button>
<p class="attr" style="margin-top:.8rem">Rekaman asli belum tersedia — ini sintesis karakter bunyinya.</p>
@endif
</div>
</div>

<aside class="detail-side">
<div class="info-card reveal" style="text-align:center">
<p class="eyebrow">Uji Telinga</p>
<p style="margin:.6rem 0 1.2rem;color:var(--muted)">Seberapa kenal kamu dengan {{ $alat->nama }}?</p>
<button class="btn btn-primary quiz-trigger" type="button">Coba Kuis</button>
</div>
</aside>
</div>

@if($terkait->count())
<div style="margin-top:3rem">
<header class="section-head reveal">
<div class="sec-num">✦</div>
<div class="sec-title">
<p class="eyebrow">Jelajah Lebih Jauh</p>
<h2>Arsip terkait.</h2>
</div>
</header>
<div class="cards">
@foreach($terkait as $item)
<x-card :item="$item" />
@endforeach
</div>
</div>
@endif
</div>
</section>

{{-- Quiz modal --}}
<div id="quizModal" class="img-modal" aria-hidden="true">
<div class="quiz-box">
<span class="img-close quiz-close" role="button" aria-label="Tutup">&times;</span>
<h3>Kuis Cepat</h3>
<p><strong>{{ $alat->nama }}</strong><br>{{ $pertanyaan }}</p>
<div class="quiz-options" data-correct="{{ $jawabanBenar }}" data-id="{{ $alat->id }}" data-tipe="{{ $tipeSoal }}">
@foreach($opsi as $o)
<button class="quiz-choice" type="button" data-value="{{ $o }}">{{ $o }}</button>
@endforeach
</div>
<p id="quiz-feedback" aria-live="polite"></p>
<div class="cta-row" style="justify-content:center;margin-top:1rem">
<button class="btn quiz-retry" type="button">Coba Lagi</button>
<button class="btn quiz-close" type="button">Tutup</button>
</div>
</div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/detail-synth.js') }}"></script>
<script src="{{ asset('assets/js/quiz.js') }}"></script>
@endpush
