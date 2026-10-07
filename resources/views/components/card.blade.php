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

{{-- Spesimen v4: nomor katalog besar ala arsip museum --}}
<a href="{{ url($url) }}" class="spesimen" data-reveal>
    <div class="spesimen-media">
        <img src="{{ $item->gambar ? gambar_alat($item->gambar) : asset('assets/img/default.png') }}"
            alt="{{ $item->nama }}" loading="lazy">
        <span class="spesimen-no">{{ $no }}</span>
    </div>
    <div class="spesimen-body">
        @if ($origin)
            <p class="spesimen-origin">{{ $origin }}</p>
        @endif
        <h3 class="spesimen-title">{{ $item->nama }}</h3>
        <p class="spesimen-desc">{{ Str::limit($item->deskripsi, 80) }}</p>
        <span class="spesimen-link">Buka arsip <i>&rarr;</i></span>
    </div>
</a>
