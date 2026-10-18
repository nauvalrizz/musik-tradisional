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
  --bg:#141110; --bg2:#1a1512; --card:#1e1815; --card2:#241c17;
  --terra:#b5502f; --terra2:#d4714a; --cream:#ede6d6; --muted:#9a8878; --line:rgba(181,80,47,.14);
  --display:'Fraunces',serif; --body:'Inter',sans-serif; --mono:'JetBrains Mono',monospace;
}
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:var(--body);background:radial-gradient(900px 500px at 15% 0%,rgba(181,80,47,.06),transparent 60%),var(--bg);color:var(--cream);min-height:100vh;font-size:15px;line-height:1.6}
a{color:inherit;text-decoration:none}
.topbar{position:sticky;top:0;z-index:50;display:flex;align-items:center;justify-content:space-between;padding:.9rem 2rem;background:rgba(20,17,16,.88);backdrop-filter:blur(12px);border-bottom:1px solid var(--line)}
.brand{display:flex;align-items:center;gap:.7rem}
.brand .mk{width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,var(--terra),#8a3a20);display:grid;place-items:center;font-family:var(--display);font-weight:600;font-size:1.1rem;color:#fff}
.brand b{font-family:var(--display);font-size:1.15rem;letter-spacing:.04em}
.brand small{display:block;font-family:var(--mono);font-size:.6rem;letter-spacing:.22em;color:var(--terra2)}
.topnav{display:flex;gap:.4rem;align-items:center}
.topnav a{font-family:var(--mono);font-size:.72rem;letter-spacing:.14em;padding:.55rem 1rem;border-radius:99px;color:var(--muted);transition:.25s}
.topnav a:hover{color:var(--cream);background:rgba(181,80,47,.1)}
.topnav a.on{color:#fff;background:var(--terra)}
.topnav a.site{border:1px solid var(--line)}
.wrap{max-width:1080px;margin:0 auto;padding:2.5rem 1.5rem 4rem}
.pagehead{margin-bottom:2rem}
.pagehead .kick{font-family:var(--mono);font-size:.65rem;letter-spacing:.24em;color:var(--terra2);margin-bottom:.5rem}
.pagehead h1{font-family:var(--display);font-size:clamp(1.8rem,4vw,2.6rem);font-weight:600;letter-spacing:-.01em}
.pagehead p{color:var(--muted);margin-top:.4rem}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.1rem;margin-bottom:2rem}
.stat{background:linear-gradient(160deg,var(--card),var(--card2));border:1px solid var(--line);border-radius:18px;padding:1.4rem 1.5rem;position:relative;overflow:hidden;transition:transform .3s,box-shadow .3s}
.stat:hover{transform:translateY(-3px);box-shadow:0 12px 32px rgba(0,0,0,.35)}
.stat::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--terra),transparent)}
.stat .ic{width:46px;height:46px;border-radius:14px;display:grid;place-items:center;font-size:1.35rem;margin-bottom:1rem;background:rgba(181,80,47,.14);border:1px solid var(--line)}
.stat .lb{font-family:var(--mono);font-size:.65rem;letter-spacing:.18em;color:var(--muted);margin-bottom:.25rem}
.stat .vl{font-family:var(--display);font-size:2.2rem;font-weight:600;line-height:1}
.menus{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.1rem}
.menu{background:linear-gradient(150deg,#2a1e16,#1e1512);border:1px solid var(--line);border-radius:20px;padding:1.8rem;transition:.3s;position:relative;overflow:hidden;display:block}
.menu:hover{transform:translateY(-4px);border-color:rgba(181,80,47,.4);box-shadow:0 16px 40px rgba(0,0,0,.4)}
.menu h3{font-family:var(--display);font-size:1.35rem;margin-bottom:.4rem}
.menu p{color:var(--muted);font-size:.92rem}
.menu .go{margin-top:1.2rem;display:inline-flex;align-items:center;gap:.5rem;font-family:var(--mono);font-size:.72rem;letter-spacing:.14em;color:var(--terra2)}
.tblwrap{background:var(--card);border:1px solid var(--line);border-radius:18px;overflow:hidden;margin-bottom:2rem;overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:.92rem;min-width:640px}
th{font-family:var(--mono);font-size:.65rem;letter-spacing:.16em;color:var(--muted);text-align:left;padding:1rem 1.2rem;border-bottom:1px solid var(--line);background:rgba(0,0,0,.2)}
td{padding:.95rem 1.2rem;border-bottom:1px solid rgba(181,80,47,.07);vertical-align:middle}
tr:last-child td{border-bottom:none}
tbody tr{transition:background .2s}
tbody tr:hover{background:rgba(181,80,47,.05)}
.thumb{width:52px;height:52px;border-radius:12px;object-fit:cover;background:#2a211b;border:1px solid var(--line)}
.pill{display:inline-block;font-family:var(--mono);font-size:.65rem;letter-spacing:.1em;padding:.3rem .7rem;border-radius:99px;background:rgba(181,80,47,.14);color:var(--terra2);border:1px solid var(--line)}
.btn{display:inline-flex;align-items:center;gap:.5rem;padding:.65rem 1.3rem;border-radius:12px;border:none;cursor:pointer;font-family:var(--body);font-weight:600;font-size:.88rem;transition:.25s}
.btn-p{background:linear-gradient(135deg,var(--terra2),var(--terra));color:#fff;box-shadow:0 4px 16px rgba(181,80,47,.3)}
.btn-p:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(181,80,47,.4)}
.btn-g{background:rgba(181,80,47,.1);color:var(--cream);border:1px solid var(--line)}
.btn-g:hover{background:rgba(181,80,47,.2)}
.btn-d{background:rgba(180,50,50,.12);color:#e08a8a;border:1px solid rgba(180,50,50,.25)}
.btn-d:hover{background:rgba(180,50,50,.22)}
.btn-s{padding:.45rem .9rem;font-size:.8rem;border-radius:9px}
.form{background:var(--card);border:1px solid var(--line);border-radius:18px;padding:2rem;margin-bottom:2rem}
.fg{margin-bottom:1.2rem}
.fg label{display:block;font-family:var(--mono);font-size:.68rem;letter-spacing:.14em;color:var(--muted);margin-bottom:.5rem}
.fg input,.fg select,.fg textarea{width:100%;padding:.8rem 1rem;border-radius:12px;border:1px solid var(--line);background:#151110;color:var(--cream);font-family:var(--body);font-size:.95rem;transition:border .2s}
.fg input:focus,.fg select:focus,.fg textarea:focus{outline:none;border-color:var(--terra)}
.fg textarea{min-height:110px;resize:vertical}
.frow{display:grid;grid-template-columns:1fr 1fr;gap:1.2rem}
@media(max-width:640px){.frow{grid-template-columns:1fr}.topbar{padding:.8rem 1rem;flex-wrap:wrap;gap:.6rem}.wrap{padding:1.5rem 1rem 3rem}}
.alert{background:rgba(80,180,100,.1);border:1px solid rgba(80,180,100,.3);color:#8fd49a;padding:.9rem 1.2rem;border-radius:12px;margin-bottom:1.5rem;font-size:.9rem}
.toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.4rem;flex-wrap:wrap;gap:1rem}
.search{display:flex;gap:.6rem}
.search input{padding:.65rem 1rem;border-radius:12px;border:1px solid var(--line);background:#151110;color:var(--cream);font-size:.9rem;min-width:200px}
.search input:focus{outline:none;border-color:var(--terra)}
.pagi{display:flex;gap:.4rem;justify-content:center;margin-top:1.5rem;flex-wrap:wrap}
.pagi a,.pagi span{padding:.55rem 1rem;border-radius:10px;border:1px solid var(--line);font-size:.85rem;color:var(--muted)}
.pagi a:hover{border-color:var(--terra);color:var(--cream)}
.pagi span.on{background:var(--terra);color:#fff;border-color:var(--terra)}
audio{width:100%;max-width:200px;height:32px}
.empty{text-align:center;padding:3rem;color:var(--muted)}
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
