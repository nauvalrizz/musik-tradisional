@extends('layouts.admin')

@section('content')
<div class="pagehead">
  <div class="kick">ANALITIK PENGGUNA</div>
  <h1>Statistik Kuis</h1>
  <p>Performa pengguna dalam mengenali bunyi Nusantara.</p>
</div>

<div class="stats">
  <div class="stat">
    <div class="ic">🎯</div>
    <div class="lb">TOTAL PERCOBAAN</div>
    <div class="vl">{{ $total }}</div>
  </div>
  <div class="stat">
    <div class="ic">✓</div>
    <div class="lb">JAWABAN BENAR</div>
    <div class="vl">{{ $benar }}</div>
  </div>
  <div class="stat">
    <div class="ic">✕</div>
    <div class="lb">JAWABAN SALAH</div>
    <div class="vl">{{ $salah }}</div>
  </div>
  <div class="stat">
    <div class="ic">%</div>
    <div class="lb">AKURASI GLOBAL</div>
    <div class="vl">{{ $akurasi }}<span style="font-size:1.1rem;color:var(--muted)">%</span></div>
  </div>
</div>

<div class="pagehead" style="margin-top:2.5rem">
  <h1 style="font-size:1.6rem">Per Kategori</h1>
</div>
<div class="tblwrap">
<table>
  <thead><tr><th>KATEGORI</th><th>TOTAL</th><th>BENAR</th><th>AKURASI</th></tr></thead>
  <tbody>
  @forelse($perKategori as $k)
    <tr>
      <td><span class="pill">{{ $k->kategori }}</span></td>
      <td>{{ $k->total }}</td>
      <td>{{ $k->benar }}</td>
      <td><strong style="color:var(--terra2)">{{ $k->total > 0 ? round(($k->benar / $k->total) * 100, 1) : 0 }}%</strong></td>
    </tr>
  @empty
    <tr><td colspan="4"><div class="empty">Belum ada data kuis.</div></td></tr>
  @endforelse
  </tbody>
</table>
</div>

<div class="pagehead" style="margin-top:2.5rem">
  <h1 style="font-size:1.6rem">Per Sumber Bunyi</h1>
</div>
<div class="tblwrap">
<table>
  <thead><tr><th>SUMBER BUNYI</th><th>TOTAL</th><th>BENAR</th><th>AKURASI</th></tr></thead>
  <tbody>
  @forelse($perSumber as $s)
    <tr>
      <td><span class="pill">{{ $s->sumber_bunyi }}</span></td>
      <td>{{ $s->total }}</td>
      <td>{{ $s->benar }}</td>
      <td><strong style="color:var(--terra2)">{{ $s->total > 0 ? round(($s->benar / $s->total) * 100, 1) : 0 }}%</strong></td>
    </tr>
  @empty
    <tr><td colspan="4"><div class="empty">Belum ada data kuis.</div></td></tr>
  @endforelse
  </tbody>
</table>
</div>
@endsection
