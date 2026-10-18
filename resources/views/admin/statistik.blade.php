@extends('layouts.admin')
@section('crumb','STATISTIK')
@section('content')
<div class="pagehead">
  <div class="kick">ANALITIK PENGGUNA</div>
  <h1>Statistik Kuis</h1>
  <p>Performa pengguna dalam mengenali bunyi Nusantara.</p>
</div>

<div class="kpis">
  <div class="kpi">
    <div class="n">{{ $total }}</div>
    <div class="t">PERCOBAAN</div>
    <div class="d">Total kuis dimainkan</div>
  </div>
  <div class="kpi">
    <div class="n">{{ $benar }}</div>
    <div class="t">JAWABAN BENAR</div>
    <div class="d">{{ $total > 0 ? round($benar / $total * 100) : 0 }}% dari total</div>
    @if($total > 0)
    <div class="bar"><i style="width:{{ round($benar / $total * 100) }}%"></i></div>
    @endif
  </div>
  <div class="kpi">
    <div class="n">{{ $salah }}</div>
    <div class="t">JAWABAN SALAH</div>
    <div class="d">Perlu edukasi lebih lanjut</div>
  </div>
  <div class="kpi">
    <div class="n">{{ $akurasi }}<small>%</small></div>
    <div class="t">AKURASI GLOBAL</div>
    <div class="d">Rata-rata keseluruhan</div>
  </div>
</div>

<div class="panel">
  <div class="panel-h"><h2>Per Kategori</h2></div>
  <div class="tblwrap">
  <table>
    <thead><tr><th>KATEGORI</th><th>TOTAL</th><th>BENAR</th><th style="text-align:right">AKURASI</th></tr></thead>
    <tbody>
    @forelse($perKategori as $k)
      <tr>
        <td><span class="pill">{{ $k->kategori }}</span></td>
        <td>{{ $k->total }}</td>
        <td>{{ $k->benar }}</td>
        <td style="text-align:right"><strong style="color:var(--terra)">{{ $k->total > 0 ? round(($k->benar / $k->total) * 100, 1) : 0 }}%</strong></td>
      </tr>
    @empty
      <tr><td colspan="4"><div class="empty">Belum ada data kuis.</div></td></tr>
    @endforelse
    </tbody>
  </table>
  </div>
</div>

<div class="panel">
  <div class="panel-h"><h2>Per Sumber Bunyi</h2></div>
  <div class="tblwrap">
  <table>
    <thead><tr><th>SUMBER BUNYI</th><th>TOTAL</th><th>BENAR</th><th style="text-align:right">AKURASI</th></tr></thead>
    <tbody>
    @forelse($perSumber as $s)
      <tr>
        <td><span class="pill">{{ $s->sumber_bunyi }}</span></td>
        <td>{{ $s->total }}</td>
        <td>{{ $s->benar }}</td>
        <td style="text-align:right"><strong style="color:var(--terra)">{{ $s->total > 0 ? round(($s->benar / $s->total) * 100, 1) : 0 }}%</strong></td>
      </tr>
    @empty
      <tr><td colspan="4"><div class="empty">Belum ada data kuis.</div></td></tr>
    @endforelse
    </tbody>
  </table>
  </div>
</div>
@endsection
