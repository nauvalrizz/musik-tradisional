@extends('layouts.admin')
@section('crumb','ALAT MUSIK / SUNTING')
@section('content')
<div class="pagehead">
  <div class="kick">SUNTING ARSIP</div>
  <h1>{{ $alat->nama }}</h1>
  <p>Perbarui data instrumen dalam koleksi.</p>
</div>

<div class="panel">
  <div style="padding:1.6rem">
  <form action="/admin/alat/{{ $alat->id }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="frow">
      <div class="fg">
        <label>NAMA ALAT</label>
        <input type="text" name="nama" value="{{ old('nama',$alat->nama) }}" required>
      </div>
      <div class="fg">
        <label>PULAU</label>
        <select name="pulau_id">
          @foreach ($pulau as $p)
            <option value="{{ $p->id }}" {{ old('pulau_id',$alat->pulau_id)==$p->id?'selected':'' }}>{{ $p->nama }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="frow">
      <div class="fg">
        <label>KATEGORI</label>
        <select name="kategori">
          @foreach ($kategori as $k)
            <option value="{{ $k }}" {{ old('kategori',$alat->kategori)==$k?'selected':'' }}>{{ $k }}</option>
          @endforeach
        </select>
      </div>
      <div class="fg">
        <label>SUMBER BUNYI</label>
        <select name="sumber_bunyi">
          @foreach ($sumber as $s)
            <option value="{{ $s }}" {{ old('sumber_bunyi',$alat->sumber_bunyi)==$s?'selected':'' }}>{{ $s }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="frow">
      <div class="fg">
        <label>GAMBAR</label>
        @if($alat->gambar)
          <div style="margin-bottom:.6rem"><img src="{{ gambar_alat($alat->gambar) }}" class="thumb" style="width:88px;height:88px" alt="{{ $alat->nama }}"></div>
        @endif
        <input type="file" name="gambar" accept="image/*" hidden id="fgambar">
        <label class="upl" for="fgambar">
          <span class="upl-btn">Pilih Gambar</span>
          <span class="upl-name">Belum ada file</span>
        </label>
        <div style="font-size:.78rem;color:var(--muted);margin-top:.4rem">Kosongkan jika tidak diganti</div>
      </div>
      <div class="fg">
        <label>AUDIO</label>
        @if($alat->audio)
          <div style="margin-bottom:.6rem"><audio controls preload="none"><source src="{{ audio_alat($alat->audio) }}" type="audio/mpeg"></audio></div>
        @endif
        <input type="file" name="audio" accept="audio/*" hidden id="faudio">
        <label class="upl" for="faudio">
          <span class="upl-btn">Pilih Audio</span>
          <span class="upl-name">Belum ada file</span>
        </label>
        <div style="font-size:.78rem;color:var(--muted);margin-top:.4rem">Kosongkan jika tidak diganti</div>
      </div>
    </div>
    <div class="fg">
      <label>DESKRIPSI</label>
      <textarea name="deskripsi">{{ old('deskripsi',$alat->deskripsi) }}</textarea>
    </div>
    @if($errors->any())
      <div class="alert" style="background:rgba(168,50,50,.08);border-color:rgba(168,50,50,.3);color:#a83232">{{ $errors->first() }}</div>
    @endif
    <div style="display:flex;gap:.7rem;flex-wrap:wrap">
      <button class="btn btn-t" type="submit">Simpan Perubahan</button>
      <a href="/admin/alat" class="btn btn-g">Batal</a>
    </div>
  </form>
  </div>
</div>
@endsection
