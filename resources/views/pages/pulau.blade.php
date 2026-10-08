@extends('layouts.app')
@section('title', $pulau->nama . ' — MuSantara')
@section('content')
<div class="wrap" style="padding:3rem 0 4rem">
<a href="{{ url('/') }}#pulau" class="btn-line magnet reveal">← Kepulauan</a>
<div class="search-head reveal">
<p class="hero-kicker">Wilayah</p>
<h1>{{ $pulau->nama }}</h1>
<p class="cnt">{{ $alat->count() }} instrumen terarsip</p>
</div>
@if($alat->isEmpty())
<div class="quiz-card reveal" style="text-align:center"><p style="color:var(--text2)">Belum ada data di wilayah ini.</p></div>
@else
<div class="objects">
@foreach($alat as $idx => $item)
<article class="obj">
<a href="{{ url('/alat/'.$item->id.'?from=pulau&slug='.$pulau->slug) }}" aria-label="{{ $item->nama }}">
<figure class="obj-fig">
@if($item->gambar)<img src="{{ gambar_alat($item->gambar) }}" alt="{{ $item->nama }}" loading="lazy" data-zoom>@endif
</figure>
<div class="obj-meta">
<span class="n">{{ str_pad($idx+1,2,'0',STR_PAD_LEFT) }}</span>
<span class="t">{{ $item->nama }}<small>{{ $item->sumber_bunyi ?? '—' }}</small></span>
<span class="a">Dengarkan →</span>
</div>
<div class="obj-accent" aria-hidden="true"></div>
</a>
</article>
@endforeach
</div>
@endif
</div>
@endsection
