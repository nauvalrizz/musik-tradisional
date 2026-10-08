@extends('layouts.app')
@section('title', $pulau->nama . ' — MuSantara')
@section('content')
<div class="wrap" style="padding:3rem 0">
<a href="{{ url('/') }}#pulau" class="back-link reveal">← Kepulauan</a>
<div class="chapter-head reveal" style="margin-bottom:2rem">
<span class="ch-index">Wilayah</span>
<div class="ch-title">
<h2>{{ $pulau->nama }}</h2>
<p>{{ $alat->count() }} instrumen terarsip dari wilayah ini.</p>
</div>
</div>
@if($alat->isEmpty())
<div class="player reveal" style="text-align:center"><p>Belum ada data di wilayah ini.</p></div>
@else
<div class="objects">
@foreach($alat as $idx => $item)
<article class="object reveal">
<a href="{{ url('/alat/'.$item->id.'?from=pulau&slug='.$pulau->slug) }}">
<figure class="object-fig">
@if($item->gambar)<img src="{{ gambar_alat($item->gambar) }}" alt="{{ $item->nama }}" loading="lazy" data-zoom>@endif
</figure>
<div class="object-label">
<span class="obj-num">{{ str_pad($idx+1,2,'0',STR_PAD_LEFT) }}</span>
<p class="obj-name">{{ $item->nama }}</p>
<p class="obj-meta">{{ $item->sumber_bunyi ?? '—' }}</p>
</div>
</a>
</article>
@endforeach
</div>
@endif
</div>
@endsection
