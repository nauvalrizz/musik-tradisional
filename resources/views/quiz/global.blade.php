@extends('layouts.app')

@section('content')
    <div class="quiz-page quiz-main">

        {{-- 🔥 CARD UTAMA --}}
        <div class="quiz-card">

            {{-- 🔥 TOP (TIMER + STEP) --}}
            <div class="quiz-top">

                <div class="quiz-timer">
                    ⏱ <span id="quizTimer">10</span> detik
                </div>

                <div class="timer-bar">
                    <div id="timerProgress"></div>
                </div>

                <div class="quiz-meta">
                    <span>Soal <span id="currentStep">1</span> / <span id="totalStep">10</span></span>
                    <span class="quiz-sub">Gas terus</span>
                </div>

            </div>

            {{-- 🔥 CONTENT UTAMA --}}
            <div class="quiz-content split-layout">

                {{-- KIRI --}}
                <div class="quiz-left">
                    <div id="quizImage"></div>
                </div>

                {{-- KANAN --}}
                <div class="quiz-right">

                    <h2 id="question">Loading...</h2>

                    <div id="options"></div>

                    {{-- 🔥 LOADING --}}
                    <div id="quizLoading" class="quiz-loading">
                        <div class="loading-bar"></div>
                        <span>Menyiapkan soal berikutnya...</span>
                    </div>

                </div>

            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/quiz-global.js') }}"></script>
@endpush
