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

{{-- Kartu arsip v3: seluruh kartu adalah tautan --}}
<a href="{{ url($url) }}" class="arsip-card" data-reveal>
    <div class="arsip-media">
        <img src="{{ $item->gambar ? gambar_alat($item->gambar) : asset('assets/img/default.png') }}"
            alt="{{ $item->nama }}" loading="lazy">
        <span class="arsip-no">№ {{ $no }}</span>
        <span class="arsip-frame" aria-hidden="true"></span>
    </div>
    <div class="arsip-body">
        @if ($origin)
            <p class="arsip-origin">{{ $origin }}</p>
        @endif
        <h3 class="arsip-title">{{ $item->nama }}</h3>
        <p class="arsip-desc">{{ Str::limit($item->deskripsi, 90) }}</p>
    </div>
</a>
