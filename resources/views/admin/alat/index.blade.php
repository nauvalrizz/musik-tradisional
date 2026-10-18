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
          <div style="display:flex;align-items:center;gap:.7rem">
          @if ($item->gambar)
            <img src="{{ gambar_alat($item->gambar) }}" class="thumb" loading="lazy" alt="{{ $item->nama }}">
          @endif
          @if ($item->audio)
            <button type="button" class="aplay" data-src="{{ asset('assets/audio/alat-musik/' . $item->audio) }}" aria-label="Putar audio {{ $item->nama }}">
              <svg class="ic-play" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
              <svg class="ic-pause" viewBox="0 0 24 24" fill="currentColor" style="display:none"><path d="M6 5h4v14H6zM14 5h4v14h-4z"/></svg>
            </button>
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

<style>
.aplay{width:42px;height:42px;border-radius:50%;border:none;background:var(--terra);color:#fff;display:grid;place-items:center;cursor:pointer;transition:.2s;flex-shrink:0}
.aplay:hover{filter:brightness(1.12);transform:scale(1.06)}
.aplay svg{width:20px;height:20px}
.aplay.playing{background:var(--ink)}
</style>
<script>
(function(){
  let cur=null, audio=new Audio();
  function setBtn(btn,playing){
    btn.classList.toggle('playing',playing);
    btn.querySelector('.ic-play').style.display=playing?'none':'';
    btn.querySelector('.ic-pause').style.display=playing?'':'none';
  }
  audio.addEventListener('ended',function(){ if(cur){setBtn(cur,false);cur=null;} });
  document.querySelectorAll('.aplay').forEach(function(btn){
    btn.addEventListener('click',function(){
      if(cur===btn && !audio.paused){ audio.pause(); setBtn(btn,false); cur=null; return; }
      if(cur) setBtn(cur,false);
      cur=btn; audio.src=btn.dataset.src; audio.play(); setBtn(btn,true);
    });
  });
})();
</script>
@endsection
