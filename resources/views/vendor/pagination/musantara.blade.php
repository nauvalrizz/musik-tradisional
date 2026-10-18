@if ($paginator->hasPages())
<nav class="musantara-pagi" role="navigation" aria-label="Navigasi halaman">
  @if ($paginator->onFirstPage())
    <span class="pg pg-dis" aria-disabled="true">‹</span>
  @else
    <a class="pg" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya">‹</a>
  @endif

  @foreach ($elements as $element)
    @if (is_string($element))
      <span class="pg pg-dis">{{ $element }}</span>
    @endif
    @if (is_array($element))
      @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
          <span class="pg pg-on" aria-current="page">{{ $page }}</span>
        @else
          <a class="pg" href="{{ $url }}">{{ $page }}</a>
        @endif
      @endforeach
    @endif
  @endforeach

  @if ($paginator->hasMorePages())
    <a class="pg" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya">›</a>
  @else
    <span class="pg pg-dis" aria-disabled="true">›</span>
  @endif
</nav>
<style>
.musantara-pagi{display:flex;gap:.45rem;justify-content:center;align-items:center;flex-wrap:wrap}
.musantara-pagi .pg{min-width:2.6rem;height:2.6rem;display:inline-grid;place-items:center;border-radius:12px;border:1px solid rgba(181,80,47,.2);font-family:'JetBrains Mono',monospace;font-size:.85rem;color:#9a8878;background:rgba(30,24,21,.6);transition:.22s;padding:0 .7rem}
.musantara-pagi a.pg:hover{border-color:#b5502f;color:#ede6d6;transform:translateY(-2px)}
.musantara-pagi .pg-on{background:linear-gradient(135deg,#d4714a,#b5502f);color:#fff;border-color:transparent;font-weight:600;box-shadow:0 4px 14px rgba(181,80,47,.35)}
.musantara-pagi .pg-dis{opacity:.35;cursor:default}
</style>
@endif
