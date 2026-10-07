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

        <form method="GET" action="{{ url('/search') }}" class="filter-bar" data-reveal>
            <input type="hidden" name="q" value="{{ $q }}">
            <label class="filter-field">
                <span>Pulau</span>
                <select name="pulau" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    @foreach ($pulaus as $pl)
                        <option value="{{ $pl->id }}" {{ (string) $pulau === (string) $pl->id ? 'selected' : '' }}>
                            {{ $pl->nama }}
                        </option>
                    @endforeach
                </select>
            </label>
            <label class="filter-field">
                <span>Sumber Bunyi</span>
                <select name="sumber" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    @foreach ($sumbers as $sb)
                        <option value="{{ $sb }}" {{ $sumber === $sb ? 'selected' : '' }}>
                            {{ $sb }}
                        </option>
                    @endforeach
                </select>
            </label>
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
