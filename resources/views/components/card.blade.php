@php
    $idx = $idx ?? 0;
    $url = '/alat/' . $item->id;
    if (request()->routeIs('pulau.*') || request()->segment(1) === 'pulau') {
        $url .= '?from=pulau&slug=' . request()->segment(2);
    }
    if (request()->has('q') && request()->segment(1) === 'search') {
        $url .= '?from=search&q=' . urlencode(request('q'));
    }
    $idle = ['a','b','c'][$idx % 3];
    $seal = strtoupper(substr(optional($item->pulau)->nama ?? 'NUS', 0, 3));
    $freq = 196 + ($item->id % 8) * 49;
@endphp
<article class="pod idle-{{ $idle }}" data-pod>
<a href="{{ url($url) }}" class="pod-link" aria-label="{{ $item->nama }}">
<div class="pod-stage">
<div class="pod-obj">
<div class="pod-shadow" aria-hidden="true"></div>
@if($item->gambar)<img src="{{ gambar_alat($item->gambar) }}" alt="{{ $item->nama }}" loading="lazy">@else<div class="pod-noimg" aria-hidden="true"></div>@endif
</div>
<div class="pod-base">
<span class="pod-seal" aria-hidden="true">{{ $seal }}</span>
<div class="pod-plaque">
<h3>{{ $item->nama }}</h3>
<p>{{ optional($item->pulau)->nama ?? 'Nusantara' }}{{ $item->sumber_bunyi ? ' · '.$item->sumber_bunyi : '' }}</p>
</div>
</div>
<div class="pod-wave" aria-hidden="true">@for($i=0;$i<12;$i++)<i style="height:{{ 25+($i*41%75) }}%;animation-delay:{{ $i*60 }}ms"></i>@endfor</div>
</div>
</a>
<div class="pod-actions">
<button type="button" class="pod-sound gamelan-pad" data-instrument="{{ strtolower(str_replace(' ','-',$item->nama)) }}" data-freq="{{ $freq }}" data-pod-audio aria-label="Dengarkan {{ $item->nama }}">▶ Dengarkan</button>
</div>
</article>
