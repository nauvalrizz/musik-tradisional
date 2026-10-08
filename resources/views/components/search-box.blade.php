<form action="{{ route('search') }}" method="GET" id="searchForm">
    <div class="search-box">
        <label for="searchInput">Cari koleksi</label>
        <input type="text" name="q" id="searchInput"
            placeholder="Mis. logam, Jawa, sasando"
            value="{{ request('q') }}" autocomplete="off" aria-label="Cari koleksi">
        <span aria-hidden="true">⌕</span>
    </div>
    <div id="liveSearchResult" class="results" aria-live="polite"></div>
</form>
