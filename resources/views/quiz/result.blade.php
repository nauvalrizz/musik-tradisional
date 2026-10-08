@extends('layouts.app')
@section('title', 'Hasil Kuis — MuSantara')
@section('content')
@if(session('explored_count', 0) < 3)
<script>window.location.href="/";</script>
@endif
<div class="wrap">
<div class="quiz-shell" style="text-align:center">
<div class="sect-label reveal" style="justify-content:center"><span class="n">§</span><span class="t">Hasil Penilaian</span></div>
<div class="quiz-card reveal" style="margin-top:1.5rem">
<div style="width:7rem;height:7rem;margin:0 auto 1.2rem;display:grid;place-items:center;border:1px solid var(--accent);border-radius:50%">
<div id="score" style="font-family:var(--display);font-size:2.4rem;color:var(--accent)"></div>
</div>
<p id="resultText" style="font-size:1.05rem"></p>
<p id="percentText" style="font-family:var(--mono);font-size:.72rem;color:var(--muted);margin-top:.4rem;letter-spacing:.1em"></p>
<div style="display:flex;gap:1.4rem;justify-content:center;margin:1.8rem 0;flex-wrap:wrap">
<a href="/quiz-global" class="btn-line magnet">Ulangi <span class="arw">→</span></a>
<a href="/" class="btn-line magnet">Beranda <span class="arw">→</span></a>
</div>
<button id="toggleReview" class="btn-line" style="margin:0 auto">Lihat Review</button>
<div id="reviewContainer" style="margin-top:1.4rem;text-align:left"></div>
</div>
</div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/quiz-result.js') }}"></script>
@endpush
