@extends('layouts.admin')

@section('content')
    <div class="container mt-5">

        <h1>Statistik Kuis</h1>

        <div class="row text-center mb-4">
            <div class="col-md-3">
                <div class="card p-3">
                    <h4>{{ $total }}</h4>
                    <small>Total Percobaan</small>
                </div>
            </div>
        </div>

        <p>Jawaban Benar: {{ $benar }}</p>
        <p>Jawaban Salah: {{ $salah }}</p>
        <p>Akurasi Global: {{ $akurasi }}%</p>

        <hr>

        <h2>Statistik Soal: Kategori</h2>

        <table border="1" cellpadding="10">
            <tr>
                <th>Kategori</th>
                <th>Total</th>
                <th>Benar</th>
                <th>Akurasi (%)</th>
            </tr>

            @foreach ($perKategori as $k)
                <tr>
                    <td>{{ $k->kategori }}</td>
                    <td>{{ $k->total }}</td>
                    <td>{{ $k->benar }}</td>
                    <td>
                        {{ $k->total > 0 ? round(($k->benar / $k->total) * 100, 2) : 0 }}
                    </td>
                </tr>
            @endforeach
        </table>

        <canvas id="kategoriChart" height="100"></canvas>

        <hr>

        <h2>Statistik Soal: Sumber Bunyi</h2>

        <table border="1" cellpadding="10">
            <tr>
                <th>Sumber Bunyi</th>
                <th>Total</th>
                <th>Benar</th>
                <th>Akurasi (%)</th>
            </tr>

            @foreach ($perSumber as $s)
                <tr>
                    <td>{{ $s->sumber_bunyi }}</td>
                    <td>{{ $s->total }}</td>
                    <td>{{ $s->benar }}</td>
                    <td>
                        {{ $s->total > 0 ? round(($s->benar / $s->total) * 100, 2) : 0 }}
                    </td>
                </tr>
            @endforeach
        </table>

        <canvas id="sumberChart" height="100"></canvas>
        <div class="admin-grid mb-4">

            <div class="admin-card">
                <p>Total Percobaan</p>
                <h2>{{ $total }}</h2>
            </div>

            <div class="admin-card">
                <p>Akurasi</p>
                <h2>{{ $akurasi }}%</h2>
            </div>

            <div class="admin-card">
                <p>Salah</p>
                <h2>{{ $salah }}</h2>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const kategoriData = @json($perKategori);
            const kategoriLabels = kategoriData.map(k => k.kategori);
            const kategoriAccuracy = kategoriData.map(k =>
                k.total > 0 ? ((k.benar / k.total) * 100).toFixed(2) : 0
            );

            new Chart(document.getElementById('kategoriChart'), {
                type: 'bar',
                data: {
                    labels: kategoriLabels,
                    datasets: [{
                        label: 'Akurasi (%)',
                        data: kategoriAccuracy
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });

            const sumberData = @json($perSumber);
            const sumberLabels = sumberData.map(s => s.sumber_bunyi);
            const sumberAccuracy = sumberData.map(s =>
                s.total > 0 ? ((s.benar / s.total) * 100).toFixed(2) : 0
            );

            new Chart(document.getElementById('sumberChart'), {
                type: 'bar',
                data: {
                    labels: sumberLabels,
                    datasets: [{
                        label: 'Akurasi (%)',
                        data: sumberAccuracy
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });

        });
    </script>
@endsection
