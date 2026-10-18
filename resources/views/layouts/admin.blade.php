<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kurator — MuSantara</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#e9e2d2; --card:#f6f1e6; --card2:#efe8d8;
  --ink:#221a12; --ink2:#4d4132; --muted:#857664;
  --terra:#a84a2a; --terra-soft:rgba(168,74,42,.09);
  --line:rgba(90,68,42,.16);
  --sb:#20180f; --sb2:#2a2013; --sb-tx:#d8cdb8; --sb-mut:#8a7a63;
  --display:'Fraunces',serif; --body:'Inter',sans-serif; --mono:'JetBrains Mono',monospace;
}
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:var(--body);background:var(--bg);color:var(--ink);min-height:100vh;font-size:15px;line-height:1.6;-webkit-font-smoothing:antialiased}
a{color:inherit;text-decoration:none}
::selection{background:rgba(168,74,42,.2)}
/* ===== Sidebar ===== */
.sidebar{position:fixed;top:0;left:0;bottom:0;width:248px;background:var(--sb);color:var(--sb-tx);display:flex;flex-direction:column;z-index:60;transition:transform .3s cubic-bezier(.32,.72,.28,1)}
.sb-brand{display:flex;align-items:center;gap:.7rem;padding:1.4rem 1.4rem 1.2rem}
.sb-brand .mk{width:36px;height:36px;border-radius:11px;background:var(--terra);display:grid;place-items:center;font-family:var(--display);font-weight:600;font-size:1.15rem;color:#f6f1e6;flex-shrink:0}
.sb-brand b{font-family:var(--display);font-size:1.12rem;letter-spacing:.02em;color:#f2ecdd;display:block;line-height:1.2}
.sb-brand small{font-family:var(--mono);font-size:.56rem;letter-spacing:.24em;color:var(--terra);font-weight:500}
.sb-nav{padding:.6rem .9rem;display:flex;flex-direction:column;gap:.25rem;flex:1}
.sb-label{font-family:var(--mono);font-size:.58rem;letter-spacing:.22em;color:var(--sb-mut);padding:.9rem .8rem .4rem}
.sb-link{display:flex;align-items:center;gap:.8rem;padding:.72rem .8rem;border-radius:12px;color:var(--sb-mut);font-size:.92rem;font-weight:500;transition:.2s}
.sb-link svg{width:19px;height:19px;flex-shrink:0;opacity:.85}
.sb-link:hover{color:#f2ecdd;background:rgba(255,255,255,.05)}
.sb-link.on{color:#fff;background:var(--terra)}
.sb-link.on svg{opacity:1}
.sb-foot{padding:1rem .9rem;border-top:1px solid rgba(255,255,255,.07)}
.sb-foot a{display:flex;align-items:center;gap:.8rem;padding:.72rem .8rem;border-radius:12px;color:var(--sb-mut);font-size:.88rem;font-weight:500;transition:.2s}
.sb-foot a:hover{color:#f2ecdd;background:rgba(255,255,255,.05)}
.sb-foot svg{width:19px;height:19px;opacity:.85}
/* ===== Main ===== */
.main{margin-left:248px;min-height:100vh;display:flex;flex-direction:column}
.topbar{position:sticky;top:0;z-index:40;display:flex;align-items:center;gap:1rem;padding:.95rem 2rem;background:rgba(233,226,210,.88);backdrop-filter:blur(12px);border-bottom:1px solid var(--line)}
.burger{display:none;width:40px;height:40px;border:1px solid var(--line);border-radius:11px;background:var(--card);cursor:pointer;align-items:center;justify-content:center;flex-direction:column;gap:5px}
.burger span{display:block;width:18px;height:2px;background:var(--ink);border-radius:2px}
.crumb{font-family:var(--mono);font-size:.68rem;letter-spacing:.18em;color:var(--muted)}
.crumb b{color:var(--terra);font-weight:500}
.content{padding:2.25rem 2rem 4rem;max-width:1060px;width:100%;margin:0 auto;flex:1}
.overlay{display:none;position:fixed;inset:0;background:rgba(20,14,8,.5);z-index:55;opacity:0;transition:opacity .3s}
/* ===== Page head ===== */
.pagehead{margin-bottom:1.8rem}
.pagehead .kick{font-family:var(--mono);font-size:.62rem;letter-spacing:.24em;color:var(--terra);margin-bottom:.55rem;font-weight:500}
.pagehead h1{font-family:var(--display);font-size:clamp(1.7rem,3.5vw,2.4rem);font-weight:600;letter-spacing:-.015em;line-height:1.15}
.pagehead p{color:var(--muted);margin-top:.45rem;font-size:.93rem}
/* ===== KPI (shadcn pattern: number + one-line explanation) ===== */
.kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:1rem;margin-bottom:2rem}
.kpi{background:var(--card);border:1px solid var(--line);border-radius:18px;padding:1.35rem 1.45rem;transition:transform .25s,box-shadow .25s}
.kpi:hover{transform:translateY(-2px);box-shadow:0 10px 26px rgba(90,68,42,.1)}
.kpi .n{font-family:var(--display);font-size:2.3rem;font-weight:600;letter-spacing:-.02em;line-height:1}
.kpi .n small{font-size:1.1rem;color:var(--muted);font-weight:500}
.kpi .t{font-family:var(--mono);font-size:.62rem;letter-spacing:.18em;color:var(--muted);margin:.55rem 0 .3rem;font-weight:500}
.kpi .d{font-size:.83rem;color:var(--ink2)}
.kpi .bar{margin-top:.9rem;height:4px;border-radius:99px;background:var(--card2);overflow:hidden}
.kpi .bar i{display:block;height:100%;border-radius:99px;background:var(--terra)}
/* ===== Table ===== */
.panel{background:var(--card);border:1px solid var(--line);border-radius:18px;overflow:hidden;margin-bottom:1.75rem}
.panel-h{display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:1.15rem 1.4rem;border-bottom:1px solid var(--line);flex-wrap:wrap}
.panel-h h2{font-family:var(--display);font-size:1.25rem;font-weight:600}
.panel-h .count{font-family:var(--mono);font-size:.68rem;color:var(--muted);letter-spacing:.1em}
.tblwrap{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:.9rem;min-width:620px}
th{font-family:var(--mono);font-size:.6rem;letter-spacing:.16em;color:var(--muted);text-align:left;padding:.9rem 1.3rem;border-bottom:1px solid var(--line);background:var(--card2);font-weight:500;white-space:nowrap}
td{padding:.95rem 1.3rem;border-bottom:1px solid rgba(90,68,42,.08);vertical-align:middle;color:var(--ink2)}
td strong{color:var(--ink);font-weight:600}
tr:last-child td{border-bottom:none}
tbody tr{transition:background .15s}
tbody tr:hover{background:var(--terra-soft)}
.thumb{width:50px;height:50px;border-radius:11px;object-fit:cover;background:var(--card2);border:1px solid var(--line)}
.pill{display:inline-block;font-family:var(--mono);font-size:.62rem;letter-spacing:.06em;padding:.3rem .7rem;border-radius:99px;background:var(--terra-soft);color:var(--terra);font-weight:500;white-space:nowrap}
/* ===== Buttons ===== */
.btn{display:inline-flex;align-items:center;gap:.5rem;padding:.66rem 1.3rem;border-radius:12px;border:none;cursor:pointer;font-family:var(--body);font-weight:600;font-size:.86rem;transition:.2s;letter-spacing:.01em;white-space:nowrap}
.btn-t{background:var(--terra);color:#fff}
.btn-t:hover{filter:brightness(1.1);transform:translateY(-1px)}
.btn-ink{background:var(--ink);color:#f6f1e6}
.btn-ink:hover{background:var(--terra);transform:translateY(-1px)}
.btn-g{background:transparent;color:var(--ink2);border:1px solid var(--line)}
.btn-g:hover{border-color:var(--terra);color:var(--terra)}
.btn-d{background:transparent;color:#a83232;border:1px solid rgba(168,50,50,.28)}
.btn-d:hover{background:rgba(168,50,50,.08)}
.btn-s{padding:.44rem .9rem;font-size:.78rem;border-radius:9px}
/* ===== Forms ===== */
.fg{margin-bottom:1.2rem}
.fg label{display:block;font-family:var(--mono);font-size:.64rem;letter-spacing:.15em;color:var(--muted);margin-bottom:.5rem;font-weight:500}
.fg input,.fg select,.fg textarea{width:100%;padding:.8rem 1rem;border-radius:12px;border:1px solid var(--line);background:#fff;color:var(--ink);font-family:var(--body);font-size:.93rem;transition:border .2s,box-shadow .2s}
.fg input:focus,.fg select:focus,.fg textarea:focus{outline:none;border-color:var(--terra);box-shadow:0 0 0 3px rgba(168,74,42,.12)}
.fg textarea{min-height:105px;resize:vertical}
.frow{display:grid;grid-template-columns:1fr 1fr;gap:1.2rem}
.collapse{border:1px solid var(--line);border-radius:18px;background:var(--card);margin-bottom:1.75rem;overflow:hidden}
.collapse>summary{list-style:none;cursor:pointer;padding:1.2rem 1.4rem;display:flex;align-items:center;justify-content:space-between;font-weight:600;font-size:.95rem}
.collapse>summary::-webkit-details-marker{display:none}
.collapse>summary .plus{font-size:1.3rem;color:var(--terra);transition:transform .25s;line-height:1}
.collapse[open]>summary .plus{transform:rotate(45deg)}
.collapse .cbody{padding:0 1.4rem 1.4rem;border-top:1px solid var(--line);padding-top:1.4rem}
/* ===== Misc ===== */
.alert{background:rgba(60,130,70,.1);border:1px solid rgba(60,130,70,.3);color:#2e6b38;padding:.9rem 1.2rem;border-radius:13px;margin-bottom:1.5rem;font-size:.89rem;font-weight:500}
.toolbar{display:flex;gap:.7rem;align-items:center;flex-wrap:wrap}
.toolbar input{padding:.66rem 1rem;border-radius:12px;border:1px solid var(--line);background:#fff;color:var(--ink);font-size:.88rem;min-width:190px}
.toolbar input:focus{outline:none;border-color:var(--terra);box-shadow:0 0 0 3px rgba(168,74,42,.12)}
.empty{text-align:center;padding:2.75rem;color:var(--muted);font-size:.9rem}
audio{width:100%;max-width:190px;height:30px}
/* ===== Responsive ===== */
@media(max-width:900px){
  .sidebar{transform:translateX(-100%)}
  body.nav-open .sidebar{transform:none}
  body.nav-open .overlay{display:block;opacity:1}
  .main{margin-left:0}
  .burger{display:inline-flex}
  .content{padding:1.6rem 1.1rem 3.5rem}
  .topbar{padding:.85rem 1.1rem}
  .frow{grid-template-columns:1fr}
}
</style>
</head>
<body>
<aside class="sidebar" id="sidebar">
  <a class="sb-brand" href="/admin">
    <span class="mk">M</span>
    <span><b>MuSantara</b><small>KURATOR PANEL</small></span>
  </a>
  <nav class="sb-nav">
    <div class="sb-label">MENU</div>
    <a href="/admin" class="sb-link {{ request()->is('admin') ? 'on' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
      Dashboard
    </a>
    <a href="/admin/alat" class="sb-link {{ request()->is('admin/alat*') ? 'on' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
      Alat Musik
    </a>
    <a href="/admin/statistik" class="sb-link {{ request()->is('admin/statistik') ? 'on' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 3v18h18"/><path d="M7 15l4-5 3 3 5-7"/></svg>
      Statistik
    </a>
  </nav>
  <div class="sb-foot">
    <a href="/" target="_blank">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><path d="M15 3h6v6"/><path d="M10 14L21 3"/></svg>
      Lihat Situs
    </a>
  </div>
</aside>
<div class="overlay" id="overlay"></div>
<div class="main">
  <header class="topbar">
    <button class="burger" id="burger" aria-label="Buka menu"><span></span><span></span><span></span></button>
    <div class="crumb">KURATOR <b>/</b> @yield('crumb','DASHBOARD')</div>
  </header>
  <main class="content">
    @yield('content')
  </main>
</div>
<script>
document.getElementById('burger').addEventListener('click',function(){document.body.classList.toggle('nav-open')});
document.getElementById('overlay').addEventListener('click',function(){document.body.classList.remove('nav-open')});
document.querySelectorAll('.sb-link').forEach(function(a){a.addEventListener('click',function(){document.body.classList.remove('nav-open')})});
</script>
</body>
</html>
