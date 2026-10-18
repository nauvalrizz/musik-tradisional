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

<div class="charts">
  <div class="chart-box">
    <h3>Benar vs Salah</h3>
    <div class="chart-wrap"><canvas id="chDonut"></canvas></div>
  </div>
  <div class="chart-box">
    <h3>Akurasi per Kategori</h3>
    <div class="chart-wrap"><canvas id="chKategori"></canvas></div>
  </div>
  <div class="chart-box">
    <h3>Akurasi per Sumber Bunyi</h3>
    <div class="chart-wrap"><canvas id="chSumber"></canvas></div>
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

<style>
.charts{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1rem;margin-bottom:2rem}
.chart-box{background:var(--card);border:1px solid var(--line);border-radius:18px;padding:1.35rem 1.45rem}
.chart-box h3{font-family:var(--display);font-size:1.1rem;font-weight:600;margin-bottom:1rem}
.chart-wrap{position:relative;height:240px}
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
(function(){
  const TERRA='#a84a2a', TERRA_L='rgba(168,74,42,.75)', SAND='rgba(90,68,42,.18)', INK='#4d4132';
  Chart.defaults.font.family="'Inter',sans-serif";
  Chart.defaults.color='#857664';
  // Donut: benar vs salah
  new Chart(document.getElementById('chDonut'),{type:'doughnut',
    data:{labels:['Benar','Salah'],datasets:[{data:[{{ $benar }},{{ $salah }}],backgroundColor:[TERRA,'#d8cdb8'],borderWidth:0,hoverOffset:6}]},
    options:{maintainAspectRatio:false,cutout:'68%',plugins:{legend:{position:'bottom',labels:{boxWidth:12,padding:16}}}}});
  // Bar: per kategori
  const katLabels=@json($perKategori->pluck('kategori'));
  const katAcc=@json($perKategori->map(fn($k)=>$k->total>0?round($k->benar/$k->total*100,1):0));
  new Chart(document.getElementById('chKategori'),{type:'bar',
    data:{labels:katLabels,datasets:[{data:katAcc,backgroundColor:TERRA_L,borderRadius:8,barThickness:26}]},
    options:{maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>' '+c.parsed.y+'%'}}},
      scales:{y:{beginAtZero:true,max:100,ticks:{callback:v=>v+'%'},grid:{color:SAND}},x:{grid:{display:false},ticks:{color:INK}}}}});
  // Bar: per sumber bunyi
  const sumLabels=@json($perSumber->pluck('sumber_bunyi'));
  const sumAcc=@json($perSumber->map(fn($s)=>$s->total>0?round($s->benar/$s->total*100,1):0));
  new Chart(document.getElementById('chSumber'),{type:'bar',
    data:{labels:sumLabels,datasets:[{data:sumAcc,backgroundColor:TERRA_L,borderRadius:8,barThickness:26}]},
    options:{maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>' '+c.parsed.y+'%'}}},
      scales:{y:{beginAtZero:true,max:100,ticks:{callback:v=>v+'%'},grid:{color:SAND}},x:{grid:{display:false},ticks:{color:INK}}}}});
})();
</script>
@endsection
