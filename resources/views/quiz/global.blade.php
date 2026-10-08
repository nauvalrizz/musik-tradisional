@extends('layouts.app')
@section('title', 'Kuis — MuSantara')
@section('content')
<section class="section">
<div class="wrap">
<div class="quiz-shell">
<header class="section-head reveal">
<div class="sec-num">?</div>
<div class="sec-title">
<p class="eyebrow">Kuis Nusantara</p>
<h2>Uji telingamu.</h2>
<p>10 soal, 10 detik per soal. Semangat!</p>
</div>
</header>
<div class="reveal">
<div class="quiz-top">
<div class="quiz-timer"><span>Waktu</span><span id="quizTimer">10</span><span>detik</span></div>
<div class="quiz-meta">Soal <span id="currentStep">1</span> / <span id="totalStep">10</span></div>
</div>
<div class="timer-bar"><div id="timerProgress"></div></div>
<div class="quiz-q">
<div id="quizImage"></div>
<h2 id="question">Memuat…</h2>
<div id="options" class="quiz-options"></div>
<div id="quizLoading" class="quiz-loading">Menyiapkan soal berikutnya…</div>
</div>
</div>
</div>
</div>
</section>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/quiz-global.js') }}"></script>
@endpush
