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
.musantara-pagi .pg{min-width:2.6rem;height:2.6rem;display:inline-grid;place-items:center;border-radius:12px;border:1px solid rgba(90,68,42,.18);font-family:'JetBrains Mono',monospace;font-size:.85rem;color:#857664;background:#f6f1e6;transition:.22s;padding:0 .7rem}
.musantara-pagi a.pg:hover{border-color:#a84a2a;color:#221a12;transform:translateY(-2px)}
.musantara-pagi .pg-on{background:#221a12;color:#f6f1e6;border-color:transparent;font-weight:600}
.musantara-pagi .pg-dis{opacity:.35;cursor:default}
</style>
@endif
