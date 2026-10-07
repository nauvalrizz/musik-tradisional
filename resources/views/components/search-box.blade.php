<form action="{{ route('search') }}" method="GET" id="searchForm">

    <div class="search-box-wrapper">

        <div class="search-box">

            {{-- 🔍 INPUT --}}
            <input type="text" name="q" id="searchInput"
                placeholder="Cari alat musik..."
                value="{{ request('q') }}"
                autocomplete="off">

            {{-- 🎯 FILTER INLINE --}}
            <select    ect name="kategori" id="kategoriInput" class="kategori-select">
                <option value="">Semua</option>
                <option value="Petik">Petik</option>
                <option value="Pukul">Pukul</option>
                <option value="Tiup">Tiup</option>
                <option value="Gesek">Gesek</option>
                <option value="Goyang">Goyang</option>
                <option value="Getar">Getar</option>
            </select>

            <button type="submit">Cari</button>

        </div>

        {{-- 🔥 DROPDOWN --}}
        <div id="liveSearchResult" class="live-search-dropdown"></div>

    </div>

</form>