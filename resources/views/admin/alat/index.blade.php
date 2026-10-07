@extends('layouts.admin')

@section('content')
    <h1>Data Alat Musik</h1>
    <div class="card-form">

        <h2>Tambah Alat Musik</h2>

        <form action="/admin/alat" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Nama Alat</label>
                <input type="text" name="nama">
            </div>

            <div class="form-group">
                <label>Pulau</label>
                <select name="pulau_id">
                    @foreach ($pulau as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori">
                    @foreach ($kategori as $k)
                        <option value="{{ $k }}">{{ $k }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Sumber Bunyi</label>
                <select name="sumber_bunyi">
                    @foreach ($sumber as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Gambar</label>

                <label class="custom-file">
                    Pilih Gambar
                    <input type="file" name="gambar" onchange="previewImage(event)">
                </label>

                <img id="preview" class="img-preview">
            </div>

            <div class="form-group">
                <label>Audio</label>
                <input type="file" name="audio">
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi"></textarea>
            </div>

            <button class="btn-save">Tambah</button>

        </form>
    </div>

    <hr>

    <form method="GET" action="/admin/alat" onsubmit="saveScroll()" style="margin-bottom:20px;">
        <input type="text" name="search" placeholder="Cari alat musik..." value="{{ request('search') }}">

        <button class="btn-cari" type="submit">Cari</button>
    </form>

    <table class="table-modern">
        <tr>
            <th>Nama</th>
            <th>Pulau</th>
            <th>Kategori</th>
            <th>Sumber Bunyi</th>
            <th>Gambar</th>
            <th>Audio</th>
            <th>Aksi</th>
        </tr>

        @foreach ($alat as $item)
            <tr>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->pulau->nama ?? '-' }}</td>
                <td>{{ $item->kategori }}</td>
                <td>{{ $item->sumber_bunyi }}</td>

                <td>
                    @if ($item->gambar)
                        <img src="{{ gambar_alat($item->gambar) }}" class="img-preview" loading="lazy">
                    @else
                        -
                    @endif
                </td>

                <td>
                    @if ($item->audio)
                        <audio controls width="150">
                            <source src="{{ asset('assets/audio/alat-musik/' . $item->audio) }}" type="audio/mpeg">
                        </audio>
                    @endif
                </td>

                <td class="action-cell">

                    <a href="/admin/alat/{{ $item->id }}/edit" class="btn-edit">
                        Edit
                    </a>

                    <form action="/admin/alat/{{ $item->id }}" method="POST" onsubmit="return confirm('Yakin hapus?')"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">
                            Hapus
                        </button>
                    </form>

                </td>
            </tr>
        @endforeach
    </table>
    <div class="d-flex justify-content-center mt-4">
        {{ $alat->links('pagination::bootstrap-5') }}
    </div>
@section('scripts')
    <script>
        function previewImage(event) {
            const img = document.getElementById('preview');
            img.src = URL.createObjectURL(event.target.files[0]);
            img.style.display = 'block';
        }
    </script>
@endsection

@if(session('success'))
    <div class="toast show">
        ✔ {{ session('success') }}
    </div>
@endif

@section('scripts')
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast');
            if (toast) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(20px)';
            }
        }, 2500);
    </script>
@endsection


@endsection
