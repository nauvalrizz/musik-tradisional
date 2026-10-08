@php
    use Illuminate\Support\Str;
    $url = '/alat/' . $item->id;
    if (request()->routeIs('pulau.*') || request()->segment(1) === 'pulau') {
        $url .= '?from=pulau&slug=' . request()->segment(2);
    }
    if (request()->has('q') && request()->segment(1) === 'search') {
        $url .= '?from=search&q=' . urlencode(request('q'));
    }
    $origin = optional($item->pulau)->nama;
@endphp
<a href="{{ url($url) }}" class="card reveal" aria-label="{{ $item->nama }}">
@if($item->gambar)
<img src="{{ gambar_alat($item->gambar) }}" alt="{{ $item->nama }}" loading="lazy" data-zoom>
@endif
<div class="card-body">
@if($origin)<p class="card-meta">{{ $origin }}</p>@endif
<h3>{{ $item->nama }}</h3>
<p>{{ Str::limit(strip_tags($item->deskripsi ?? ''), 90) }}</p>
</div>
</a>
