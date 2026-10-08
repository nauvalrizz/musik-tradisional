@php
    $url = '/alat/' . $item->id;
    if (request()->routeIs('pulau.*') || request()->segment(1) === 'pulau') {
        $url .= '?from=pulau&slug=' . request()->segment(2);
    }
    if (request()->has('q') && request()->segment(1) === 'search') {
        $url .= '?from=search&q=' . urlencode(request('q'));
    }
@endphp
<article class="obj">
<a href="{{ url($url) }}" aria-label="{{ $item->nama }}">
<figure class="obj-fig">
@if($item->gambar)<img src="{{ gambar_alat($item->gambar) }}" alt="{{ $item->nama }}" loading="lazy" data-zoom>@endif
</figure>
<div class="obj-meta">
<span class="n">→</span>
<span class="t">{{ $item->nama }}<small>{{ optional($item->pulau)->nama ?? '—' }}</small></span>
<span class="a">→</span>
</div>
<div class="obj-accent" aria-hidden="true"></div>
</a>
</article>
