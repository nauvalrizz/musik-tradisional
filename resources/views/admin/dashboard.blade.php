@extends('layouts.admin')
@section('crumb','DASHBOARD')
@section('content')
<div class="pagehead">
  <div class="kick">PANEL KURATOR</div>
  <h1>Ringkasan</h1>
  <p>Data koleksi dan aktivitas pengguna MuSantara.</p>
</div>

<div class="kpis">
  <div class="kpi">
    <div class="n">{{ $totalAlat }}</div>
    <div class="t">ALAT MUSIK</div>
    <div class="d">Instrumen terarsip di koleksi</div>
  </div>
  <div class="kpi">
    <div class="n">{{ $totalQuiz }}</div>
    <div class="t">PERCOBAAN KUIS</div>
    <div class="d">Total kuis yang dimainkan</div>
  </div>
  <div class="kpi">
    <div class="n">{{ $totalBenar }}</div>
    <div class="t">JAWABAN BENAR</div>
    <div class="d">{{ $totalQuiz > 0 ? round($totalBenar / $totalQuiz * 100) : 0 }}% tingkat akurasi</div>
    @if($totalQuiz > 0)
    <div class="bar"><i style="width:{{ round($totalBenar / $totalQuiz * 100) }}%"></i></div>
    @endif
  </div>
</div>

<div class="qa">
  <a href="/admin/alat">
    <div><h3>Kelola Alat Musik</h3><p>Tambah, sunting, dan hapus koleksi arsip.</p></div>
    <span class="arr">→</span>
  </a>
  <a href="/admin/statistik">
    <div><h3>Statistik Kuis</h3><p>Analisis performa dan partisipasi pengguna.</p></div>
    <span class="arr">→</span>
  </a>
</div>
@endsection
