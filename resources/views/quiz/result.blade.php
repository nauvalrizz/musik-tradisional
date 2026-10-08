@extends('layouts.app')
@section('title', 'Hasil Kuis — MuSantara')
@section('content')
@if(session('explored_count', 0) < 3)
<script>window.location.href="/";</script>
@endif
<section class="section">
<div class="wrap">
<div class="quiz-shell">
<div class="quiz-card reveal" style="text-align:center">
<p class="eyebrow" style="color:var(--on-accent);opacity:.8">Hasil Penilaian</p>
<h2>Hasil Ujianmu</h2>
<div class="score-seal"><div id="score"></div></div>
<p id="resultText"></p>
<p id="percentText" style="font-family:var(--mono)"></p>
<div class="cta-row" style="justify-content:center;margin:1.5rem 0">
<a href="/quiz-global" class="btn">Ulangi Kuis</a>
<a href="/" class="btn">Beranda</a>
</div>
<button id="toggleReview" class="btn">Lihat Review Jawaban</button>
<div id="reviewContainer" style="margin-top:1.2rem;text-align:left"></div>
</div>
</div>
</div>
</section>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/quiz-result.js') }}"></script>
@endpush
