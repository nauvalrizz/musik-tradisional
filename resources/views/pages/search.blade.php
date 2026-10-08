@extends('layouts.app')
@section('title', 'Pencarian — MuSantara')
@section('content')
<div class="wrap">
<div class="search-panel reveal">
<p class="hero-kicker">PENCARIAN ARSIP</p>
<h1 style="font-family:var(--display);font-size:clamp(1.8rem,4vw,2.6rem);font-weight:600;margin:.5rem 0">@if($q)&ldquo;{{ $q }}&rdquo;@else Jelajahi @endif</h1>
<p style="font-family:var(--mono);font-size:.62rem;letter-spacing:.2em;color:var(--muted)">{{ $data->total() }} HASIL DITEMUKAN</p>
<form method="GET" action="{{ url('/search') }}" style="margin-top:1.4rem">
<input type="text" name="q" value="{{ $q }}" placeholder="Cari instrumen, pulau, kategori…" aria-label="Cari" class="search-input">
<div class="sfilters" style="display:flex;gap:.6rem;flex-wrap:wrap;margin-top:1rem">
<select name="pulau" onchange="this.form.submit()" aria-label="Filter pulau" class="chip" style="appearance:none">
<option value="">Semua Pulau</option>
@foreach($pulaus as $pl)
<option value="{{ $pl->id }}" {{ (string)$pulau===(string)$pl->id ? 'selected' : '' }}>{{ $pl->nama }}</option>
@endforeach
</select>
<select name="sumber" onchange="this.form.submit()" aria-label="Filter sumber" class="chip" style="appearance:none">
<option value="">Semua Sumber</option>
@foreach($sumbers as $sb)
<option value="{{ $sb }}" {{ $sumber===$sb ? 'selected' : '' }}>{{ $sb }}</option>
@endforeach
</select>
@if($pulau || $sumber || $q)
<a href="{{ url('/search') }}" class="chip" style="display:inline-flex;align-items:center">Atur ulang</a>
@endif
</div>
</form>
</div>
<div style="padding-bottom:4rem">
@if($data->isEmpty())
<div class="quiz-card reveal" style="text-align:center;margin-top:2rem">
<p style="font-family:var(--mono);font-size:.62rem;letter-spacing:.2em;color:var(--muted)">BELUM ADA KOLEKSI</p>
<p style="color:var(--muted);margin:.8rem 0">Tidak ditemukan. Coba kata kunci lain.</p>
<a href="{{ url('/search') }}" class="clay-btn">Atur ulang</a>
</div>
@else
<div class="pods" style="margin-top:2rem">
@foreach($data as $idx => $item)
@include('components.card',['item'=>$item,'idx'=>$idx])
@endforeach
</div>
@if($data->hasPages())
<div class="pager" style="margin-top:2.5rem;text-align:center">{{ $data->appends(request()->query())->links() }}</div>
@endif
@endif
</div>
</div>
@endsection
