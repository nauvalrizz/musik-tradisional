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

        <article class="detail">
            {{-- HERO arsip: foto terkontain + blok judul --}}
            <header class="detail-hero" data-reveal>
                <div class="detail-hero-media">
                    @if ($alat->gambar)
                        <img id="previewImg" data-zoom src="{{ gambar_alat($alat->gambar) }}"
                            alt="{{ $alat->nama }}" loading="lazy">
                    @endif
                    <div class="detail-hero-shade"></div>
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
                <div class="detail-hero-text">
                    <p class="specimen-no">Arsip № {{ str_pad($alat->id, 3, '0', STR_PAD_LEFT) }}</p>
                    <h1>{{ $alat->nama }}</h1>
                    <p class="detail-origin">{{ optional($alat->pulau)->nama ?? 'Nusantara' }}</p>
                </div>
            </header>

            <div class="detail-grid">
                <div class="detail-main">
                    <div class="meta" data-reveal>
                        <div class="meta-row"><span>Pulau</span><strong>{{ optional($alat->pulau)->nama ?? '-' }}</strong></div>
                        <div class="meta-row"><span>Sumber bunyi</span><strong>{{ $alat->sumber_bunyi ?? '-' }}</strong></div>
                        <div class="meta-row"><span>Kategori</span><strong>{{ $alat->kategori ?? '-' }}</strong></div>
                    </div>

                    <p class="description" data-reveal>
                        {!! nl2br(e($alat->deskripsi)) !!}
                    </p>

                    @if ($alat->audio)
                        <div class="audio-box" data-reveal>
                            <h3>Dengarkan Suara</h3>
                            <audio controls>
                                <source src="{{ audio_alat($alat->audio) }}" type="audio/mpeg">
                            </audio>
                        </div>
                    @endif
                </div>

                <aside class="detail-side" data-reveal>
                    <p class="detail-side-eyebrow">Uji Telinga</p>
                    <p class="detail-side-note">Seberapa kenal kamu dengan {{ $alat->nama }}?</p>
                    <button id="btnQuiz" class="btn-primary quiz-trigger">
                        Coba Kuis
                    </button>
                </aside>
            </div>
        </article>

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
                    <button class="quiz-retry" type="button">Coba Lagi</button>
                    <button class="quiz-close" type="button">Tutup</button>
                </div>

            </div>
        </div>

    </div>
@endsection
