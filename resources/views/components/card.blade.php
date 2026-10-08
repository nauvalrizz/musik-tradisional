@php $idx = $idx ?? 0; @endphp
@php
    $url = '/alat/' . $item->id;
    if (request()->routeIs('pulau.*') || request()->segment(1) === 'pulau') {
        $url .= '?from=pulau&slug=' . request()->segment(2);
    }
@endphp
<article class="obj idle-{{ ['a','b','c'][$idx % 3] }}">
<div class="clay-card">
<a href="{{ url($url) }}" aria-label="{{ $item->nama }}">
<div class="fig">
<span class="idx">{{ str_pad($idx+1,2,'0',STR_PAD_LEFT) }}</span>
@if($item->gambar)<img src="{{ gambar_alat($item->gambar) }}" alt="{{ $item->nama }}" loading="lazy" data-zoom>@endif
</div>
<div class="meta">
<h3>{{ $item->nama }}</h3>
<p class="rg">{{ optional($item->pulau)->nama ?? '' }}{{ $item->sumber_bunyi ? ' · '.$item->sumber_bunyi : '' }}</p>
<div class="row">
<span class="cat">{{ strtoupper($item->sumber_bunyi ?? 'TRADISIONAL') }}</span>
<button type="button" class="sound-tag gamelan-pad" data-instrument="{{ strtolower(str_replace(' ','-',$item->nama)) }}" data-freq="{{ 196 + ($item->id % 8) * 49 }}" aria-label="Dengarkan {{ $item->nama }}">▶ DENGARKAN</button>
</div>
</div>
</a>
</div>
</article>
