@extends('layouts.app')

@section('content')
    <div class="container search-page d-flex flex-column">

        <h2 class="mb-3">Hasil Pencarian</h2>

        <div class="page-head" data-reveal>
            <p class="page-eyebrow">Pencarian Arsip</p>
            <h1 class="page-title">Hasil Pencarian</h1>
            <div class="page-rule"></div>
            <p class="page-sub">
                <strong>{{ $data->total() }}</strong> hasil ditemukan
                @if ($q)
                    untuk &ldquo;{{ $q }}&rdquo;
                @endif
            </p>
        </div>

        <form method="GET" action="{{ url('/search') }}" class="filter-bar" data-reveal id="filterForm">
            <input type="hidden" name="q" value="{{ $q }}">
            <div class="filter-field">
                <span>Pulau</span>
                <div class="cdd" data-cdd>
                    <button type="button" class="cdd-btn" aria-haspopup="listbox">
                        <em>{{ $pulaus->firstWhere('id', (int) $pulau)->nama ?? 'Semua' }}</em><i>&#9662;</i>
                    </button>
                    <div class="cdd-list" role="listbox">
                        <button type="button" data-value="" class="{{ !$pulau ? 'sel' : '' }}">Semua</button>
                        @foreach ($pulaus as $pl)
                            <button type="button" data-value="{{ $pl->id }}"
                                class="{{ (string) $pulau === (string) $pl->id ? 'sel' : '' }}">{{ $pl->nama }}</button>
                        @endforeach
                    </div>
                    <input type="hidden" name="pulau" value="{{ $pulau }}">
                </div>
            </div>
            <div class="filter-field">
                <span>Sumber Bunyi</span>
                <div class="cdd" data-cdd>
                    <button type="button" class="cdd-btn" aria-haspopup="listbox">
                        <em>{{ $sumber ?: 'Semua' }}</em><i>&#9662;</i>
                    </button>
                    <div class="cdd-list" role="listbox">
                        <button type="button" data-value="" class="{{ !$sumber ? 'sel' : '' }}">Semua</button>
                        @foreach ($sumbers as $sb)
                            <button type="button" data-value="{{ $sb }}"
                                class="{{ $sumber === $sb ? 'sel' : '' }}">{{ $sb }}</button>
                        @endforeach
                    </div>
                    <input type="hidden" name="sumber" value="{{ $sumber }}">
                </div>
            </div>
            @if ($pulau || $sumber)
                <a href="{{ url('/search?q=' . urlencode($q ?? '')) }}" class="filter-reset">Atur ulang</a>
            @endif
        </form>

        <div class="row g-4 mt-4">

            @forelse($data as $item)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <x-card :item="$item" />
                </div>
            @empty
                <div class="empty-wrapper">
                    <p class="text-muted empty-state">
                        Data tidak ditemukan
                    </p>
                </div>
            @endforelse

        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/search-page.js') }}?v=13"></script>
@endpush
