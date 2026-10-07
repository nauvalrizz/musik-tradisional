{{-- Kartu arsip v3: seluruh kartu adalah tautan --}}
<a href="/alat/{{ $alat->id }}" class="arsip-card" data-reveal>
    <div class="arsip-media">
        <img src="{{ gambar_alat($alat) }}" alt="{{ $alat->nama_alat }}" loading="lazy">
        <span class="arsip-no">№ {{ str_pad($alat->id, 3, '0', STR_PAD_LEFT) }}</span>
        <span class="arsip-frame" aria-hidden="true"></span>
    </div>
    <div class="arsip-body">
        <p class="arsip-origin">{{ $alat->pulau->nama_pulau ?? 'Nusantara' }}</p>
        <h3 class="arsip-title">{{ $alat->nama_alat }}</h3>
        <p class="arsip-desc">{{ \Illuminate\Support\Str::limit(strip_tags($alat->deskripsi), 90) }}</p>
    </div>
</a>
