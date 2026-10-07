@extends('layouts.app')

@section('content')
    <!-- LOADER -->
    <div id="pageLoader">
        <div class="container mt-4">
            <div class="row g-4">
                @for ($i = 0; $i < 3; $i++)
                    <div class="col-12">
                        <x-skeleton-card />
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- REAL CONTENT -->
    <div id="realContent" style="display:none;">

        @php
            $from = request('from');
            $slug = request('slug');
            $q = request('q');
        @endphp

        @if ($from === 'search')
            <a href="{{ url('/search?q=' . urlencode($q)) }}" class="back-btn" data-no-transition>
                ← Kembali ke hasil pencarian
            </a>
        @elseif($from === 'pulau')
            <a href="{{ url('/pulau/' . $slug) }}" class="back-btn" data-no-transition>
                ← Kembali ke {{ ucfirst($slug) }}
            </a>
        @else
            <a href="{{ url('/') }}" class="back-btn" data-no-transition>
                ← Kembali ke beranda
            </a>
        @endif

        <div class="specimen-head" data-reveal>
            <p class="specimen-no">Arsip № {{ str_pad($alat->id, 3, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="detail-container">

            <!-- IMAGE -->
            <div class="detail-image fade-up" style="--bg: url('{{ gambar_alat($alat->gambar ?? null) }}')">

                @if ($alat->gambar)
                    <img id="previewImg" src="{{ gambar_alat($alat->gambar) }}"
                        alt="{{ $alat->nama }}" loading="lazy">
                @endif

                @if ($alat->sumber_gambar)
                    <div class="atribusi-overlay">
                        Sumber:
                        <a href="{{ $alat->sumber_gambar }}" target="_blank">
                            {{ $alat->author ?? 'Wikimedia Commons' }}
                        </a><br>
                        Lisensi: {{ $alat->license ?? 'Lihat di sumber' }}
                    </div>
                @endif

            </div>

            <!-- CONTENT -->
            <div class="detail-content fade-up delay-1">

                <h1>{{ $alat->nama }}</h1>

                <div class="meta">
                    <div class="meta-row"><span>Pulau</span><strong>{{ optional($alat->pulau)->nama ?? '-' }}</strong></div>
                    <div class="meta-row"><span>Sumber bunyi</span><strong>{{ $alat->sumber_bunyi ?? '-' }}</strong></div>
                    <div class="meta-row"><span>Kategori</span><strong>{{ $alat->kategori ?? '-' }}</strong></div>
                </div>

                <p class="description">
                    {!! nl2br(e($alat->deskripsi)) !!}
                </p>

                @if ($alat->audio)
                    <div class="audio-box">
                        <h3>Dengarkan Suara</h3>
                        <audio controls>
                            <source src="{{ audio_alat($alat->audio) }}" type="audio/mpeg">
                        </audio>
                    </div>
                @endif

                <button id="btnQuiz" class="btn-primary quiz-trigger">
                    Coba Kuis
                </button>

            </div>

        </div>

        <!-- QUIZ -->
        <div id="quizModal" class="quiz-modal">
            <div class="quiz-card">

                <h3>Kuis Cepat</h3>

                <p>
                    <strong>{{ $alat->nama }}</strong><br>
                    {{ $pertanyaan }}
                </p>

                <div class="quiz-options" data-correct="{{ $jawabanBenar }}" data-id="{{ $alat->id }}"
                    data-tipe="{{ $tipeSoal }}">

                    @foreach ($opsi as $o)
                        <button class="quiz-choice" type="button" data-value="{{ $o }}">
                            {{ $o }}
                        </button>
                    @endforeach

                </div>

                <p id="quiz-feedback"></p>

                <div class="quiz-actions">
                    <button class="quiz-retry" type="button">Coba Lagi 🔁</button>
                    <button class="quiz-close" type="button">Tutup</button>
                </div>

            </div>
        </div>

        <!-- IMAGE MODAL -->
        <div id="imgModal" class="img-modal">
            <span class="img-close">&times;</span>
            <img class="img-modal-content" id="imgZoom">
        </div>

    </div>
@endsection
