@extends('layouts.app')

@section('content')
    @if (session('explored_count', 0) < 3)
        <script>
            window.location.href = "/";
        </script>
    @endif
    <div class="quiz-page quiz-result">
        <div class="quiz-card">

            <p class="result-eyebrow">Arsip Penilaian</p>
            <h2 class="result-title">Hasil Ujian</h2>

            <div class="score-seal">
                <div id="score" class="score"></div>
            </div>

            <p id="resultText" class="result-text"></p>

            <p id="percentText" class="percent-text"></p>

            <div class="result-divider"></div>

            <div class="quiz-actions">
                <a href="/quiz-global" class="btn-primary">Ulangi Quiz</a>
                <a href="/" class="back-home">Kembali ke Home</a>
            </div>

            <button id="toggleReview" class="btn-secondary">
                Lihat Review Jawaban
            </button>

            <div id="reviewContainer"></div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/quiz-result.js') }}?v=8"></script>
@endpush
