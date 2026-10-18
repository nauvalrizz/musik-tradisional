@extends('layouts.admin')

@section('content')
<div class="pagehead">
  <div class="kick">SUNTING ARSIP</div>
  <h1>{{ $alat->nama }}</h1>
  <p>Perbarui data instrumen dalam koleksi.</p>
</div>

<div class="form">
  <form action="/admin/alat/{{ $alat->id }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="frow">
      <div class="fg">
        <label>NAMA ALAT</label>
        <input type="text" name="nama" value="{{ $alat->nama }}" required>
      </div>
      <div class="fg">
        <label>PULAU</label>
        <select name="pulau_id">
          @foreach ($pulau as $p)
            <option value="{{ $p->id }}" {{ $alat->pulau_id == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="frow">
      <div class="fg">
        <label>KATEGORI</label>
        <select name="kategori">
          @foreach ($kategori as $k)
            <option value="{{ $k }}" {{ $alat->kategori == $k ? 'selected' : '' }}>{{ $k }}</option>
          @endforeach
        </select>
      </div>
      <div class="fg">
        <label>SUMBER BUNYI</label>
        <select name="sumber_bunyi">
          @foreach ($sumber as $s)
            <option value="{{ $s }}" {{ $alat->sumber_bunyi == $s ? 'selected' : '' }}>{{ $s }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="frow">
      <div class="fg">
        <label>GAMBAR SAAT INI</label>
        @if($alat->gambar)
          <div><img src="{{ gambar_alat($alat->gambar) }}" class="thumb" style="width:96px;height:96px;border-radius:16px"></div>
        @else
          <div style="color:var(--muted)">— belum ada —</div>
        @endif
        <div style="margin-top:.6rem"><input type="file" name="gambar" accept="image/*"></div>
      </div>
      <div class="fg">
        <label>AUDIO</label>
        @if($alat->audio)
          <div><audio controls preload="none"><source src="{{ asset('assets/audio/alat-musik/' . $alat->audio) }}" type="audio/mpeg"></audio></div>
        @else
          <div style="color:var(--muted)">— belum ada —</div>
        @endif
        <div style="margin-top:.6rem"><input type="file" name="audio" accept="audio/*"></div>
      </div>
    </div>
    <div class="fg">
      <label>DESKRIPSI</label>
      <textarea name="deskripsi">{{ $alat->deskripsi }}</textarea>
    </div>
    <div style="display:flex;gap:.8rem">
      <button class="btn btn-p" type="submit">Simpan Perubahan</button>
      <a href="/admin/alat" class="btn btn-g">Batal</a>
    </div>
  </form>
</div>
@endsection
