@extends('layouts.admin')
@section('crumb','DASHBOARD')
@section('content')
<div class="pagehead">
  <div class="kick">PANEL KURATOR</div>
  <h1>Ringkasan</h1>
  <p>Data koleksi dan aktivitas pengguna MuSantara.</p>
  <div style="margin-top:1rem"><a href="/admin/backup" class="btn btn-g btn-s">Unduh Backup Database</a></div>
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

<div class="panel">
  <div class="panel-h">
    <h2>Baru Ditambahkan</h2>
    <a href="/admin/alat" class="btn btn-g btn-s">Kelola Semua →</a>
  </div>
  <div class="tblwrap">
  <table>
    <thead><tr><th>ALAT</th><th>PULAU</th><th>KATEGORI</th></tr></thead>
    <tbody>
    @forelse($recent as $item)
      <tr>
        <td><strong>{{ $item->nama }}</strong></td>
        <td><span class="pill">{{ $item->pulau->nama ?? '-' }}</span></td>
        <td>{{ $item->kategori }}</td>
      </tr>
    @empty
      <tr><td colspan="3"><div class="empty">Belum ada data.</div></td></tr>
    @endforelse
    </tbody>
  </table>
  </div>
</div>
@endsection
