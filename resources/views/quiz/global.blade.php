@extends('layouts.app')
@section('title', 'Kuis — MuSantara')
@section('content')
<div class="wrap">
<div class="quiz-shell">
<div class="sect-label reveal"><span class="n">§</span><span class="t">Kuis Nusantara</span></div>
<div class="reveal" style="display:flex;justify-content:space-between;font-family:var(--mono);font-size:.66rem;letter-spacing:.14em;color:var(--muted);margin-bottom:.8rem">
<span>SOAL <span id="currentStep">1</span> / <span id="totalStep">10</span></span>
<span><span id="quizTimer">10</span> DETIK</span>
</div>
<div class="reveal" style="height:3px;background:var(--surface);margin-bottom:1.6rem"><div id="timerProgress" style="height:100%;background:var(--accent);width:100%;transition:width 1s linear"></div></div>
<div class="quiz-card reveal">
<div id="quizImage" style="margin-bottom:1rem"></div>
<p class="quiz-q" id="question">Memuat…</p>
<div id="options"></div>
<div id="quizLoading" style="display:none;text-align:center;color:var(--muted);font-size:.85rem;padding:1rem">Menyiapkan soal…</div>
</div>
</div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/quiz-global.js') }}"></script>
@endpush
