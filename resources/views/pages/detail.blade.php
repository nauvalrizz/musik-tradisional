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

                    <div class="audio-box" data-reveal>
                        <h3>Dengarkan Suara</h3>
                        @if ($alat->audio)
                            <audio controls>
                                <source src="{{ audio_alat($alat->audio) }}" type="audio/mpeg">
                            </audio>
                        @else
                            <button type="button" id="btnSynth" class="synth-btn"
                                data-sumber="{{ $alat->sumber_bunyi ?? 'Idiofon' }}">
                                <span class="synth-wave" aria-hidden="true"><i></i><i></i><i></i><i></i></span>
                                Putar karakter bunyi
                                <small>{{ $alat->sumber_bunyi ?? 'Idiofon' }} &middot; sintetis</small>
                            </button>
                            <p class="synth-note">Rekaman asli belum tersedia — ini sintesis karakter bunyinya.</p>
                        @endif
                    </div>
                </div>

                <aside class="detail-side" data-reveal>
                    <p class="detail-side-eyebrow">Uji Telinga</p>
                    <p class="detail-side-note">Seberapa kenal kamu dengan {{ $alat->nama }}?</p>
                    <button id="btnQuiz" class="btn-primary quiz-trigger">
                        Coba Kuis
                    </button>
                </aside>
            </div>

            @if ($terkait->count())
                <section class="terkait" data-reveal>
                    <div class="terkait-head">
                        <div>
                            <p class="section-eyebrow">Jelajah Lebih Jauh</p>
                            <h2 class="section-head">Arsip <em>terkait.</em></h2>
                        </div>
                        <a href="{{ url('/acak') }}" class="acak-btn" data-no-transition>
                            <span class="dice" aria-hidden="true">&#9860;</span>
                            Jelajah Acak
                        </a>
                    </div>
                    <div class="koleksi-grid">
                        @foreach ($terkait as $item)
                            @include('components.card', ['item' => $item])
                        @endforeach
                    </div>
                </section>
            @endif
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
@push('scripts')
    <script src="{{ asset('assets/js/detail-synth.js') }}?v=11"></script>
@endpush

@endsection
