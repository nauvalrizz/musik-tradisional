@extends('layouts.admin')

@section('content')
    <h2>Edit Alat Musik</h2>

    <form action="/admin/alat/{{ $alat->id }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="text" name="nama" value="{{ $alat->nama }}"><br><br>

        <!-- PULAU -->
        <select name="pulau_id">
            @foreach ($pulau as $p)
                <option value="{{ $p->id }}" {{ $alat->pulau_id == $p->id ? 'selected' : '' }}>
                    {{ $p->nama }}
                </option>
            @endforeach
        </select><br><br>

        <!-- KATEGORI -->
        <select name="kategori">
            @foreach ($kategori as $k)
                <option value="{{ $k }}" {{ $alat->kategori == $k ? 'selected' : '' }}>
                    {{ $k }}
                </option>
            @endforeach
        </select><br><br>

        <!-- SUMBER BUNYI -->
        <select name="sumber_bunyi">
            @foreach ($sumber as $s)
                <option value="{{ $s }}" {{ $alat->sumber_bunyi == $s ? 'selected' : '' }}>
                    {{ $s }}
                </option>
            @endforeach
        </select><br><br>

        <input type="file" name="gambar"><br><br>
        <input type="file" name="audio"><br><br>

        <textarea name="deskripsi">{{ $alat->deskripsi }}</textarea><br><br>

        <button class="btn btn-primary" type="submit">Update</button>
    </form>
@endsection
