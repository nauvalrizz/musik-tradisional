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
  --display:'Fraunces',serif; --body:'Inter',sans-serif; --mono:'JetBrains Mono',monospace;
}
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:var(--body);background:var(--bg);color:var(--ink);min-height:100vh;font-size:15px;line-height:1.6;-webkit-font-smoothing:antialiased}
a{color:inherit;text-decoration:none}
::selection{background:rgba(168,74,42,.2)}
/* Topbar */
.topbar{position:sticky;top:0;z-index:50;display:flex;align-items:center;justify-content:space-between;padding:.85rem 2rem;background:rgba(246,241,230,.9);backdrop-filter:blur(14px);border-bottom:1px solid var(--line)}
.brand{display:flex;align-items:center;gap:.75rem}
.brand .mk{width:36px;height:36px;border-radius:11px;background:var(--terra);display:grid;place-items:center;font-family:var(--display);font-weight:600;font-size:1.15rem;color:#f6f1e6}
.brand b{font-family:var(--display);font-size:1.2rem;letter-spacing:.02em}
.brand small{display:block;font-family:var(--mono);font-size:.58rem;letter-spacing:.26em;color:var(--terra);font-weight:500}
.topnav{display:flex;gap:.3rem;align-items:center}
.topnav a{font-family:var(--mono);font-size:.7rem;letter-spacing:.12em;padding:.6rem 1.05rem;border-radius:99px;color:var(--muted);transition:.22s;font-weight:500}
.topnav a:hover{color:var(--ink);background:var(--terra-soft)}
.topnav a.on{color:#f6f1e6;background:var(--ink)}
.topnav a.site{border:1px solid var(--line);color:var(--ink2)}
/* Layout */
.wrap{max-width:1060px;margin:0 auto;padding:2.75rem 1.5rem 4.5rem}
.pagehead{margin-bottom:2rem}
.pagehead .kick{font-family:var(--mono);font-size:.64rem;letter-spacing:.26em;color:var(--terra);margin-bottom:.6rem;font-weight:500}
.pagehead h1{font-family:var(--display);font-size:clamp(1.9rem,4vw,2.7rem);font-weight:600;letter-spacing:-.015em;line-height:1.15}
.pagehead p{color:var(--muted);margin-top:.5rem;font-size:.95rem}
/* Stats — clean typographic, no boxes-with-emoji */
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));background:var(--card);border:1px solid var(--line);border-radius:20px;overflow:hidden;margin-bottom:2.25rem}
.stat{padding:1.6rem 1.7rem;position:relative}
.stat+.stat{border-left:1px solid var(--line)}
@media(max-width:640px){.stat+.stat{border-left:none;border-top:1px solid var(--line)}}
.stat .lb{font-family:var(--mono);font-size:.64rem;letter-spacing:.2em;color:var(--muted);margin-bottom:.6rem;font-weight:500}
.stat .vl{font-family:var(--display);font-size:2.6rem;font-weight:600;line-height:1;letter-spacing:-.02em}
.stat .vl small{font-size:1.2rem;color:var(--muted);font-weight:500}
.stat .bar{margin-top:1rem;height:4px;border-radius:99px;background:var(--card2);overflow:hidden}
.stat .bar i{display:block;height:100%;border-radius:99px;background:var(--terra)}
/* Menu links — editorial list */
.menus{background:var(--card);border:1px solid var(--line);border-radius:20px;overflow:hidden}
.menu{display:flex;align-items:center;justify-content:space-between;padding:1.7rem 1.8rem;transition:background .22s}
.menu+.menu{border-top:1px solid var(--line)}
.menu:hover{background:var(--terra-soft)}
.menu h3{font-family:var(--display);font-size:1.3rem;font-weight:600;margin-bottom:.25rem}
.menu p{color:var(--muted);font-size:.9rem}
.menu .arr{font-size:1.3rem;color:var(--terra);transition:transform .22s;flex-shrink:0;margin-left:1rem}
.menu:hover .arr{transform:translateX(5px)}
/* Table */
.tblwrap{background:var(--card);border:1px solid var(--line);border-radius:20px;overflow:hidden;margin-bottom:2rem;overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:.92rem;min-width:640px}
th{font-family:var(--mono);font-size:.62rem;letter-spacing:.18em;color:var(--muted);text-align:left;padding:1.05rem 1.3rem;border-bottom:1px solid var(--line);background:var(--card2);font-weight:500}
td{padding:1rem 1.3rem;border-bottom:1px solid rgba(90,68,42,.08);vertical-align:middle;color:var(--ink2)}
td strong{color:var(--ink);font-weight:600}
tr:last-child td{border-bottom:none}
tbody tr{transition:background .18s}
tbody tr:hover{background:var(--terra-soft)}
.thumb{width:52px;height:52px;border-radius:12px;object-fit:cover;background:var(--card2);border:1px solid var(--line)}
.pill{display:inline-block;font-family:var(--mono);font-size:.64rem;letter-spacing:.08em;padding:.32rem .75rem;border-radius:99px;background:var(--terra-soft);color:var(--terra);font-weight:500}
/* Buttons */
.btn{display:inline-flex;align-items:center;gap:.5rem;padding:.68rem 1.35rem;border-radius:12px;border:none;cursor:pointer;font-family:var(--body);font-weight:600;font-size:.87rem;transition:.22s;letter-spacing:.01em}
.btn-p{background:var(--ink);color:#f6f1e6}
.btn-p:hover{background:var(--terra);transform:translateY(-1px)}
.btn-t{background:var(--terra);color:#fff}
.btn-t:hover{filter:brightness(1.08);transform:translateY(-1px)}
.btn-g{background:transparent;color:var(--ink2);border:1px solid var(--line)}
.btn-g:hover{border-color:var(--terra);color:var(--terra)}
.btn-d{background:transparent;color:#a83232;border:1px solid rgba(168,50,50,.3)}
.btn-d:hover{background:rgba(168,50,50,.08)}
.btn-s{padding:.45rem .95rem;font-size:.79rem;border-radius:9px}
/* Forms */
.form{background:var(--card);border:1px solid var(--line);border-radius:20px;padding:2.1rem;margin-bottom:2rem}
.form h2{font-family:var(--display);font-size:1.4rem;margin-bottom:1.4rem;font-weight:600}
.fg{margin-bottom:1.25rem}
.fg label{display:block;font-family:var(--mono);font-size:.66rem;letter-spacing:.16em;color:var(--muted);margin-bottom:.55rem;font-weight:500}
.fg input,.fg select,.fg textarea{width:100%;padding:.82rem 1.05rem;border-radius:12px;border:1px solid var(--line);background:#fff;color:var(--ink);font-family:var(--body);font-size:.94rem;transition:border .2s,box-shadow .2s}
.fg input:focus,.fg select:focus,.fg textarea:focus{outline:none;border-color:var(--terra);box-shadow:0 0 0 3px rgba(168,74,42,.12)}
.fg textarea{min-height:110px;resize:vertical}
.frow{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem}
@media(max-width:640px){.frow{grid-template-columns:1fr}.topbar{padding:.8rem 1rem;flex-wrap:wrap;gap:.6rem}.wrap{padding:1.75rem 1rem 3.5rem}.form{padding:1.5rem}}
.alert{background:rgba(60,130,70,.1);border:1px solid rgba(60,130,70,.3);color:#2e6b38;padding:.95rem 1.25rem;border-radius:14px;margin-bottom:1.6rem;font-size:.9rem;font-weight:500}
.toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.4rem;flex-wrap:wrap;gap:1rem}
.search{display:flex;gap:.6rem}
.search input{padding:.68rem 1.05rem;border-radius:12px;border:1px solid var(--line);background:#fff;color:var(--ink);font-size:.9rem;min-width:210px}
.search input:focus{outline:none;border-color:var(--terra);box-shadow:0 0 0 3px rgba(168,74,42,.12)}
.pagi{display:flex;justify-content:center;margin-top:1.5rem}
audio{width:100%;max-width:200px;height:32px}
.empty{text-align:center;padding:3rem;color:var(--muted)}
.divider{height:1px;background:var(--line);margin:2rem 0;border:none}
</style>
</head>
<body>
<header class="topbar">
  <a class="brand" href="/admin">
    <span class="mk">M</span>
    <span><b>MuSantara</b><small>KURATOR PANEL</small></span>
  </a>
  <nav class="topnav">
    <a href="/admin" class="{{ request()->is('admin') ? 'on' : '' }}">DASHBOARD</a>
    <a href="/admin/alat" class="{{ request()->is('admin/alat*') ? 'on' : '' }}">ALAT MUSIK</a>
    <a href="/admin/statistik" class="{{ request()->is('admin/statistik') ? 'on' : '' }}">STATISTIK</a>
    <a href="/" class="site" target="_blank">LIHAT SITUS ↗</a>
  </nav>
</header>
<main class="wrap">
  @yield('content')
</main>
</body>
</html>
