@extends('layouts.app')
@section('title', 'Kuis — MuSantara')
@section('content')
<div class="wrap">
<div class="quiz-shell">
<p class="hero-kicker reveal">Kuis Nusantara</p>
<div class="reveal" style="display:flex;justify-content:space-between;font-family:var(--mono);font-size:.7rem;letter-spacing:.1em;margin:.8rem 0">
<span>Soal <span id="currentStep">1</span> / <span id="totalStep">10</span></span>
<span><span id="quizTimer">10</span> detik</span>
</div>
<div class="timer-bar reveal" style="height:6px;background:var(--paper2);border:1px solid var(--line2);margin-bottom:1.5rem"><div id="timerProgress" style="height:100%;background:var(--terra);width:100%;transition:width 1s linear"></div></div>
<div class="quiz-card reveal">
<div id="quizImage" style="margin-bottom:1rem"></div>
<p class="quiz-q" id="question">Memuat…</p>
<div id="options"></div>
<div id="quizLoading" class="quiz-loading" style="display:none;text-align:center;color:var(--ink2);font-size:.85rem;padding:1rem">Menyiapkan soal…</div>
</div>
</div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/quiz-global.js') }}"></script>
@endpush
