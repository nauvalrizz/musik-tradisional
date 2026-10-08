@extends('layouts.app')
@section('title', 'Pencarian — MuSantara')
@section('content')
<div class="wrap">
<div class="search-head reveal">
<p class="hero-kicker">Pencarian Arsip</p>
<h1>@if($q)&ldquo;{{ $q }}&rdquo;@else Jelajahi @endif</h1>
<p class="cnt">{{ $data->total() }} hasil ditemukan</p>
<form method="GET" action="{{ url('/search') }}" class="reveal" style="margin-top:1.4rem">
<div class="search-bar" style="display:flex;gap:.7rem;max-width:34rem">
<input type="text" name="q" value="{{ $q }}" placeholder="Cari dalam arsip…" aria-label="Cari" style="flex:1;background:var(--surface);border:1px solid var(--line);padding:.75rem 1rem;color:var(--text);border-radius:2px">
<button type="submit" class="btn-line magnet" style="border:1px solid var(--line);padding:.75rem 1.4rem;border-radius:2px">Cari</button>
</div>
<div class="sfilters">
<select name="pulau" onchange="this.form.submit()" aria-label="Filter pulau">
<option value="">Semua Pulau</option>
@foreach($pulaus as $pl)
<option value="{{ $pl->id }}" {{ (string)$pulau===(string)$pl->id ? 'selected' : '' }}>{{ $pl->nama }}</option>
@endforeach
</select>
<select name="sumber" onchange="this.form.submit()" aria-label="Filter sumber">
<option value="">Semua Sumber</option>
@foreach($sumbers as $sb)
<option value="{{ $sb }}" {{ $sumber===$sb ? 'selected' : '' }}>{{ $sb }}</option>
@endforeach
</select>
@if($pulau || $sumber || $q)
<a href="{{ url('/search') }}" class="btn-line" style="align-self:center">Atur ulang</a>
@endif
</div>
</form>
</div>
<div style="padding-bottom:4rem">
@if($data->isEmpty())
<div class="quiz-card reveal" style="text-align:center"><p style="color:var(--text2)">Tidak ditemukan. Coba kata kunci lain.</p></div>
@else
<div class="objects">
@foreach($data as $idx => $item)
<article class="obj">
<a href="{{ url('/alat/'.$item->id.'?from=search&q='.urlencode($q ?? '')) }}" aria-label="{{ $item->nama }}">
<figure class="obj-fig">
@if($item->gambar)<img src="{{ gambar_alat($item->gambar) }}" alt="{{ $item->nama }}" loading="lazy" data-zoom>@endif
</figure>
<div class="obj-meta">
<span class="n">{{ str_pad($idx+1,2,'0',STR_PAD_LEFT) }}</span>
<span class="t">{{ $item->nama }}<small>{{ optional($item->pulau)->nama ?? '—' }} · {{ $item->sumber_bunyi ?? '—' }}</small></span>
<span class="a">→</span>
</div>
<div class="obj-accent" aria-hidden="true"></div>
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
