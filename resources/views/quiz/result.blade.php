@extends('layouts.app')
@section('title', 'Hasil Kuis — MuSantara')
@section('content')
@if(session('explored_count', 0) < 3)
<script>window.location.href="/";</script>
@endif
<div class="wrap">
<div class="quiz-shell" style="text-align:center">
<p class="hero-kicker reveal">Hasil Penilaian</p>
<div class="quiz-card reveal" style="margin-top:1rem">
<div class="score-seal" style="width:8rem;height:8rem;margin:0 auto 1.2rem;display:grid;place-items:center;border:2px solid var(--ink);border-radius:50%">
<div id="score" style="font-family:var(--display);font-size:2.6rem"></div>
</div>
<p id="resultText" style="font-size:1.1rem"></p>
<p id="percentText" style="font-family:var(--mono);color:var(--ink2);margin-top:.4rem"></p>
<div style="display:flex;gap:.7rem;justify-content:center;margin:1.6rem 0;flex-wrap:wrap">
<a href="/quiz-global" class="quiz-opt" style="margin:0;width:auto;text-decoration:none;display:inline-block">Ulangi</a>
<a href="/" class="quiz-opt" style="margin:0;width:auto;text-decoration:none;display:inline-block">Beranda</a>
</div>
<button id="toggleReview" class="quiz-opt" style="margin:0 auto">Lihat Review</button>
<div id="reviewContainer" style="margin-top:1.2rem;text-align:left"></div>
</div>
</div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/quiz-result.js') }}"></script>
@endpush
