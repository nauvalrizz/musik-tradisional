@extends('layouts.app')
@section('title', $pulau->nama . ' — MuSantara')
@section('content')
<section class="section">
<div class="wrap">
<header class="section-head reveal">
<div class="sec-num">◈</div>
<div class="sec-title">
<p class="eyebrow">Koleksi Pulau</p>
<h2>{{ $pulau->nama }}</h2>
<p><strong>{{ $alat->count() }}</strong> alat musik terarsip dari wilayah ini.</p>
</div>
</header>
@if($alat->isEmpty())
<div class="search-card reveal" style="text-align:center">
<p>Belum ada data alat musik di pulau ini.</p>
<a class="btn" href="{{ url('/') }}" style="margin-top:1rem">Kembali ke Beranda</a>
</div>
@else
<div class="cards">
@foreach($alat as $item)
<x-card :item="$item" />
@endforeach
</div>
@endif
</div>
</section>
@endsection
