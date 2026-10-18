@extends('layouts.admin')

@section('content')
<div class="pagehead">
  <div class="kick">PANEL KURATOR</div>
  <h1>Ringkasan Arsip</h1>
  <p>Data koleksi dan aktivitas pengguna MuSantara.</p>
</div>

<div class="stats">
  <div class="stat">
    <div class="lb">ALAT MUSIK</div>
    <div class="vl">{{ $totalAlat }}</div>
  </div>
  <div class="stat">
    <div class="lb">PERCOBAAN KUIS</div>
    <div class="vl">{{ $totalQuiz }}</div>
  </div>
  <div class="stat">
    <div class="lb">JAWABAN BENAR</div>
    <div class="vl">{{ $totalBenar }}</div>
    @if($totalQuiz > 0)
    <div class="bar"><i style="width:{{ round($totalBenar / $totalQuiz * 100) }}%"></i></div>
    @endif
  </div>
</div>

<div class="menus">
  <a href="/admin/alat" class="menu">
    <div>
      <h3>Kelola Alat Musik</h3>
      <p>Tambah, sunting, dan hapus koleksi arsip instrumen.</p>
    </div>
    <span class="arr">→</span>
  </a>
  <a href="/admin/statistik" class="menu">
    <div>
      <h3>Statistik Kuis</h3>
      <p>Analisis performa dan partisipasi pengguna.</p>
    </div>
    <span class="arr">→</span>
  </a>
</div>
@endsection
