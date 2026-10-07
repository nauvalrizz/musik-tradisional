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

    $origin = optional($item->pulau)->nama;
@endphp

{{-- Kartu katalog v12: foto kompak + grain, tanpa nomor besar --}}
<a href="{{ url($url) }}" class="flip-card" data-reveal aria-label="{{ $item->nama }}">
    <span class="flip-inner">
        <span class="flip-front">
            <span class="flip-photo">
                <img src="{{ $item->gambar ? gambar_alat($item->gambar) : asset('assets/img/default.png') }}"
                    alt="{{ $item->nama }}" loading="lazy">
                <span class="flip-grain" aria-hidden="true"></span>
            </span>
            <span class="flip-front-text">
                @if ($origin)
                    <span class="flip-origin">{{ $origin }}</span>
                @endif
                <span class="flip-title">{{ $item->nama }}</span>
            </span>
        </span>
        <span class="flip-back">
            <span class="flip-back-head">
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
