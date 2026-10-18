@extends('layouts.admin')

@section('content')
@if(session('success'))
  <div class="alert">✔ {{ session('success') }}</div>
@endif

<div class="pagehead">
  <div class="kick">ARSIP KOLEKSI</div>
  <h1>Alat Musik</h1>
  <p>Kelola seluruh instrumen tradisional dalam arsip.</p>
</div>

<div class="form">
  <form action="/admin/alat" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="frow">
      <div class="fg">
        <label>NAMA ALAT</label>
        <input type="text" name="nama" required placeholder="cth: Gamelan Jawa">
      </div>
      <div class="fg">
        <label>PULAU</label>
        <select name="pulau_id">
          @foreach ($pulau as $p)
            <option value="{{ $p->id }}">{{ $p->nama }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="frow">
      <div class="fg">
        <label>KATEGORI</label>
        <select name="kategori">
          @foreach ($kategori as $k)
            <option value="{{ $k }}">{{ $k }}</option>
          @endforeach
        </select>
      </div>
      <div class="fg">
        <label>SUMBER BUNYI</label>
        <select name="sumber_bunyi">
          @foreach ($sumber as $s)
            <option value="{{ $s }}">{{ $s }}</option>
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
      <textarea name="deskripsi" placeholder="Ceritakan asal-usul dan cara memainkannya…"></textarea>
    </div>
    <button class="btn btn-p" type="submit">＋ Tambah ke Arsip</button>
  </form>
</div>

<div class="toolbar">
  <form class="search" method="GET" action="/admin/alat">
    <input type="text" name="search" placeholder="Cari alat musik…" value="{{ request('search') }}">
    <button class="btn btn-g btn-s" type="submit">CARI</button>
  </form>
</div>

<div class="tblwrap">
<table>
  <thead>
    <tr>
      <th>ALAT</th><th>PULAU</th><th>KATEGORI</th><th>MEDIA</th><th>AKSI</th>
    </tr>
  </thead>
  <tbody>
  @forelse ($alat as $item)
    <tr>
      <td><strong>{{ $item->nama }}</strong></td>
      <td><span class="pill">{{ $item->pulau->nama ?? '-' }}</span></td>
      <td>{{ $item->kategori }}<br><small style="color:var(--muted)">{{ $item->sumber_bunyi }}</small></td>
      <td>
        @if ($item->gambar)
          <img src="{{ gambar_alat($item->gambar) }}" class="thumb" loading="lazy">
        @endif
        @if ($item->audio)
          <audio controls preload="none"><source src="{{ asset('assets/audio/alat-musik/' . $item->audio) }}" type="audio/mpeg"></audio>
        @endif
      </td>
      <td style="white-space:nowrap">
        <a href="/admin/alat/{{ $item->id }}/edit" class="btn btn-g btn-s">Sunting</a>
        <form action="/admin/alat/{{ $item->id }}" method="POST" onsubmit="return confirm('Yakin hapus {{ $item->nama }}?')" style="display:inline">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-d btn-s">Hapus</button>
        </form>
      </td>
    </tr>
  @empty
    <tr><td colspan="5"><div class="empty">Belum ada data. Tambahkan alat musik pertama di atas.</div></td></tr>
  @endforelse
  </tbody>
</table>
</div>

@if(method_exists($alat,'links'))
<div class="pagi">{{ $alat->appends(request()->query())->links('vendor.pagination.musantara') }}</div>
@endif
@endsection
