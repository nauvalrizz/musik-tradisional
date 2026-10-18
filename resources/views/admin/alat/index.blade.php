@extends('layouts.admin')
@section('crumb','ALAT MUSIK')
@section('content')
@if(session('success'))
  <div class="alert">{{ session('success') }}</div>
@endif

<div class="pagehead">
  <div class="kick">ARSIP KOLEKSI</div>
  <h1>Alat Musik</h1>
  <p>Kelola seluruh instrumen tradisional dalam arsip.</p>
</div>

<details class="collapse" @if($errors->any()) open @endif>
  <summary>Tambah Alat Musik <span class="plus">+</span></summary>
  <div class="cbody">
  <form action="/admin/alat" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="frow">
      <div class="fg">
        <label>NAMA ALAT</label>
        <input type="text" name="nama" required placeholder="cth: Gamelan Jawa" value="{{ old('nama') }}">
      </div>
      <div class="fg">
        <label>PULAU</label>
        <select name="pulau_id">
          @foreach ($pulau as $p)
            <option value="{{ $p->id }}" {{ old('pulau_id')==$p->id?'selected':'' }}>{{ $p->nama }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="frow">
      <div class="fg">
        <label>KATEGORI</label>
        <select name="kategori">
          @foreach ($kategori as $k)
            <option value="{{ $k }}" {{ old('kategori')==$k?'selected':'' }}>{{ $k }}</option>
          @endforeach
        </select>
      </div>
      <div class="fg">
        <label>SUMBER BUNYI</label>
        <select name="sumber_bunyi">
          @foreach ($sumber as $s)
            <option value="{{ $s }}" {{ old('sumber_bunyi')==$s?'selected':'' }}>{{ $s }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="frow">
      <div class="fg">
        <label>GAMBAR</label>
        <input type="file" name="gambar" accept="image/*">
      </div>
      <div class="fg">
        <label>AUDIO</label>
        <input type="file" name="audio" accept="audio/*">
      </div>
    </div>
    <div class="fg">
      <label>DESKRIPSI</label>
      <textarea name="deskripsi" placeholder="Ceritakan asal-usul dan cara memainkannya…">{{ old('deskripsi') }}</textarea>
    </div>
    @if($errors->any())
      <div class="alert" style="background:rgba(168,50,50,.08);border-color:rgba(168,50,50,.3);color:#a83232">{{ $errors->first() }}</div>
    @endif
    <button class="btn btn-t" type="submit">Tambah ke Arsip</button>
  </form>
  </div>
</details>

<div class="panel">
  <div class="panel-h">
    <h2>Daftar Instrumen <span class="count">{{ $alat->total() }} DATA</span></h2>
    <form class="toolbar" method="GET" action="/admin/alat">
      <input type="text" name="search" placeholder="Cari alat musik…" value="{{ request('search') }}">
      <button class="btn btn-g btn-s" type="submit">Cari</button>
    </form>
  </div>
  <div class="tblwrap">
  <table>
    <thead><tr><th>ALAT</th><th>PULAU</th><th>KATEGORI</th><th>MEDIA</th><th style="text-align:right">AKSI</th></tr></thead>
    <tbody>
    @forelse ($alat as $item)
      <tr>
        <td><strong>{{ $item->nama }}</strong></td>
        <td><span class="pill">{{ $item->pulau->nama ?? '-' }}</span></td>
        <td>{{ $item->kategori }}<br><span style="color:var(--muted);font-size:.82rem">{{ $item->sumber_bunyi }}</span></td>
        <td>
          <div style="display:flex;align-items:center;gap:.6rem">
          @if ($item->gambar)
            <img src="{{ gambar_alat($item->gambar) }}" class="thumb" loading="lazy" alt="{{ $item->nama }}">
          @endif
          @if ($item->audio)
            <audio controls preload="none"><source src="{{ asset('assets/audio/alat-musik/' . $item->audio) }}" type="audio/mpeg"></audio>
          @endif
          @if(!$item->gambar && !$item->audio)
            <span style="color:var(--muted);font-size:.82rem">—</span>
          @endif
          </div>
        </td>
        <td style="text-align:right;white-space:nowrap">
          <a href="/admin/alat/{{ $item->id }}/edit" class="btn btn-g btn-s">Sunting</a>
          <form action="/admin/alat/{{ $item->id }}" method="POST" onsubmit="return confirm('Hapus {{ addslashes($item->nama) }}?')" style="display:inline">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-d btn-s">Hapus</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="5"><div class="empty">Belum ada data. Buka "Tambah Alat Musik" di atas.</div></td></tr>
    @endforelse
    </tbody>
  </table>
  </div>
</div>

<div class="pagi">{{ $alat->appends(request()->query())->links('vendor.pagination.musantara') }}</div>
@endsection
