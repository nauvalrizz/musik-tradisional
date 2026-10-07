@extends('layouts.admin')

@section('content')
    <div class="admin-container">

        <h1 class="admin-title">Dashboard Admin</h1>

        <!-- CARDS -->
        <div class="admin-grid">

            <div class="admin-card">
                <div class="card-icon blue">🎵</div>
                <div>
                    <p class="card-label">Total Alat Musik</p>
                    <h2>{{ $totalAlat }}</h2>
                </div>
            </div>

            <div class="admin-card">
                <div class="card-icon green">🎯</div>
                <div>
                    <p class="card-label">Percobaan Kuis</p>
                    <h2>{{ $totalQuiz }}</h2>
                </div>
            </div>

            <div class="admin-card">
                <div class="card-icon yellow">✅</div>
                <div>
                    <p class="card-label">Jawaban Benar</p>
                    <h2>{{ $totalBenar }}</h2>
                </div>
            </div>

        </div>

        <!-- MENU -->
        <div class="admin-menu">

            <a href="/admin/alat" class="menu-card blue">
                <h3>Kelola Alat Musik</h3>
                <p>Tambah, edit, dan hapus data</p>
            </a>

            <a href="/admin/statistik" class="menu-card green">
                <h3>Statistik Kuis</h3>
                <p>Lihat performa pengguna</p>
            </a>

        </div>

    </div>
@endsection
