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
                @if (!empty($kategori))
                    &middot; Kategori: <strong>{{ $kategori }}</strong>
                @endif
            </p>
        </div>

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
