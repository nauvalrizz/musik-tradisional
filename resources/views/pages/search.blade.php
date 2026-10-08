@extends('layouts.app')
@section('title', 'Pencarian — MuSantara')
@section('content')
<section class="section">
<div class="wrap">
<header class="section-head reveal">
<div class="sec-num">⌕</div>
<div class="sec-title">
<p class="eyebrow">Pencarian Arsip</p>
<h2>Hasil Pencarian</h2>
<p><strong>{{ $data->total() }}</strong> hasil ditemukan @if($q) untuk &ldquo;{{ $q }}&rdquo;@endif</p>
</div>
</header>

<form method="GET" action="{{ url('/search') }}" class="filter-card reveal" id="filterForm">
<input type="hidden" name="q" value="{{ $q }}">
<div class="filter-row">
<div class="filter-field">
<span>Pulau</span>
<div class="cdd" data-cdd>
<button type="button" class="cdd-btn"><em>{{ $pulaus->firstWhere('id',(int)$pulau)->nama ?? 'Semua' }}</em><i>▾</i></button>
<div class="cdd-list">
<button type="button" data-value="" class="{{ !$pulau ? 'sel' : '' }}">Semua</button>
@foreach($pulaus as $pl)
<button type="button" data-value="{{ $pl->id }}" class="{{ (string)$pulau===(string)$pl->id ? 'sel' : '' }}">{{ $pl->nama }}</button>
@endforeach
</div>
<input type="hidden" name="pulau" value="{{ $pulau }}">
</div>
</div>
<div class="filter-field">
<span>Sumber Bunyi</span>
<div class="cdd" data-cdd>
<button type="button" class="cdd-btn"><em>{{ $sumber ?: 'Semua' }}</em><i>▾</i></button>
<div class="cdd-list">
<button type="button" data-value="" class="{{ !$sumber ? 'sel' : '' }}">Semua</button>
@foreach($sumbers as $sb)
<button type="button" data-value="{{ $sb }}" class="{{ $sumber===$sb ? 'sel' : '' }}">{{ $sb }}</button>
@endforeach
</div>
<input type="hidden" name="sumber" value="{{ $sumber }}">
</div>
</div>
@if($pulau || $sumber)
<a href="{{ url('/search?q='.urlencode($q ?? '')) }}" class="btn" style="min-height:44px">Atur Ulang</a>
@endif
</div>
</form>

<div class="cards" style="margin-top:1.6rem">
@forelse($data as $item)
<x-card :item="$item" />
@empty
<div class="search-card reveal" style="text-align:center;grid-column:1/-1">
<p>Tidak ditemukan. Coba kata kunci lain.</p>
</div>
@endforelse
</div>

@if($data->hasPages())
<div class="pager reveal">
{{ $data->appends(request()->query())->links() }}
</div>
@endif
</div>
</section>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/search-page.js') }}"></script>
@endpush
