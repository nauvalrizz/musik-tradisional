@extends('layouts.admin')

@section('content')
<div class="pagehead">
  <div class="kick">PANEL KURATOR</div>
  <h1>Selamat datang kembali</h1>
  <p>Ringkasan arsip bunyi Nusantara hari ini.</p>
</div>

<div class="stats">
  <div class="stat">
    <div class="ic">🎵</div>
    <div class="lb">TOTAL ALAT MUSIK</div>
    <div class="vl">{{ $totalAlat }}</div>
  </div>
  <div class="stat">
    <div class="ic">🎯</div>
    <div class="lb">PERCOBAAN KUIS</div>
    <div class="vl">{{ $totalQuiz }}</div>
  </div>
  <div class="stat">
    <div class="ic">✓</div>
    <div class="lb">JAWABAN BENAR</div>
    <div class="vl">{{ $totalBenar }}</div>
  </div>
</div>

<div class="menus">
  <a href="/admin/alat" class="menu">
    <h3>Kelola Alat Musik</h3>
    <p>Tambah, sunting, dan hapus koleksi arsip instrumen tradisional.</p>
    <span class="go">BUKA ARSIP →</span>
  </a>
  <a href="/admin/statistik" class="menu">
    <h3>Statistik Kuis</h3>
    <p>Lihat performa dan partisipasi pengguna dalam kuis.</p>
    <span class="go">LIHAT STATISTIK →</span>
  </a>
</div>
@endsection
