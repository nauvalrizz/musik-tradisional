@php
    use Illuminate\Support\Str;

    $url = '/alat/' . $item->id;

    if (request()->routeIs('pulau.*') || request()->segment(1) === 'pulau') {
        $url .= '?from=pulau&slug=' . request()->segment(2);
    }

    if (request()->has('q') && request()->segment(1) === 'search') {
        $query = request('q');
        $url .= '?from=search&q=' . urlencode($query);
    }

    $no = str_pad($item->id, 3, '0', STR_PAD_LEFT);
    $origin = optional($item->pulau)->nama;
@endphp

{{-- Kartu flip arsip v8: depan foto + nomor katalog, belakang kartu indeks arsip --}}
<a href="{{ url($url) }}" class="flip-card" data-reveal aria-label="{{ $item->nama }}">
    <span class="flip-inner">
        <span class="flip-front">
            <img src="{{ $item->gambar ? gambar_alat($item->gambar) : asset('assets/img/default.png') }}"
                alt="{{ $item->nama }}" loading="lazy">
            <span class="flip-shade"></span>
            <span class="flip-no">{{ $no }}</span>
            <span class="flip-front-text">
                @if ($origin)
                    <span class="flip-origin">{{ $origin }}</span>
                @endif
                <span class="flip-title">{{ $item->nama }}</span>
            </span>
        </span>
        <span class="flip-back">
            <span class="flip-back-head">
                <span class="flip-back-no">№ {{ $no }}</span>
                <span class="flip-back-stamp">Arsip</span>
            </span>
            <span class="flip-back-title">{{ $item->nama }}</span>
            <span class="flip-back-desc">{{ Str::limit($item->deskripsi, 140) }}</span>
            <span class="flip-back-meta">
                @if ($origin)
                    <span><b>Pulau</b>{{ $origin }}</span>
                @endif
                @if ($item->sumber_bunyi)
                    <span><b>Bunyi</b>{{ $item->sumber_bunyi }}</span>
                @endif
            </span>
            <span class="flip-back-link">Buka arsip <i>&rarr;</i></span>
        </span>
    </span>
</a>
