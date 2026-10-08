@extends('layouts.app')
@section('title', 'Pencarian — MuSantara')
@section('content')
<div class="wrap">
<section class="search-hero">
<p class="hero-kicker reveal">Pencarian Arsip</p>
<h1 class="reveal" style="font-family:var(--display);font-size:clamp(2rem,4vw,3rem);font-weight:500;margin-bottom:.6rem">
@if($q) &ldquo;{{ $q }}&rdquo; @else Jelajahi @endif
</h1>
<p class="reveal" style="color:var(--ink2)"><strong>{{ $data->total() }}</strong> hasil ditemukan.</p>
<form method="GET" action="{{ url('/search') }}" class="reveal" style="margin-top:1.6rem">
<div class="search-bar">
<input type="text" name="q" value="{{ $q }}" placeholder="Cari instrumen…" aria-label="Cari instrumen">
<button type="submit">Cari</button>
</div>
<div class="filters">
<select name="pulau" onchange="this.form.submit()" aria-label="Filter pulau">
<option value="">Semua Pulau</option>
@foreach($pulaus as $pl)
<option value="{{ $pl->id }}" {{ (string)$pulau===(string)$pl->id ? 'selected' : '' }}>{{ $pl->nama }}</option>
@endforeach
</select>
<select name="sumber" onchange="this.form.submit()" aria-label="Filter sumber bunyi">
<option value="">Semua Sumber</option>
@foreach($sumbers as $sb)
<option value="{{ $sb }}" {{ $sumber===$sb ? 'selected' : '' }}>{{ $sb }}</option>
@endforeach
</select>
@if($pulau || $sumber || $q)
<a href="{{ url('/search') }}" class="back-link" style="margin:0;align-self:center">Atur ulang</a>
@endif
</div>
</form>
</section>
<div style="padding:2.5rem 0">
@if($data->isEmpty())
<div class="player reveal" style="text-align:center"><p>Tidak ditemukan. Coba kata kunci lain.</p></div>
@else
<div class="objects">
@foreach($data as $idx => $item)
<article class="object reveal">
<a href="{{ url('/alat/'.$item->id.'?from=search&q='.urlencode($q ?? '')) }}">
<figure class="object-fig">
@if($item->gambar)<img src="{{ gambar_alat($item->gambar) }}" alt="{{ $item->nama }}" loading="lazy" data-zoom>@endif
</figure>
<div class="object-label">
<span class="obj-num">{{ str_pad($idx+1,2,'0',STR_PAD_LEFT) }}</span>
<p class="obj-name">{{ $item->nama }}</p>
<p class="obj-meta">{{ optional($item->pulau)->nama ?? '—' }} · {{ $item->sumber_bunyi ?? '—' }}</p>
</div>
</a>
</article>
@endforeach
</div>
@if($data->hasPages())
<div class="pager">{{ $data->appends(request()->query())->links() }}</div>
@endif
@endif
</div>
</div>
@endsection
