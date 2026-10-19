<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404 — MuSantara</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Manrope:wght@400;500&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#141110;color:#ede6d6;font-family:'Manrope',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:2rem}
.wrap{max-width:520px}
.num{font-family:'Fraunces',serif;font-size:clamp(5rem,20vw,9rem);font-weight:600;color:#b5502f;line-height:1}
h1{font-family:'Fraunces',serif;font-size:1.6rem;font-weight:500;margin:1rem 0 .6rem;color:#f6f1e6}
p{color:#a89a83;font-size:.95rem;line-height:1.7;margin-bottom:2rem}
a{display:inline-block;padding:.85rem 2rem;border:1px solid #b5502f;border-radius:99px;color:#ede6d6;text-decoration:none;font-size:.9rem;letter-spacing:.06em;transition:.25s}
a:hover{background:#b5502f;color:#fff}
.gong{width:72px;height:72px;margin:0 auto 1.2rem;border-radius:50%;background:radial-gradient(circle at 35% 35%,#d98a5f,#b5502f 55%,#7c3a22);box-shadow:0 0 60px rgba(181,80,47,.35);position:relative}
.gong::after{content:'';position:absolute;inset:28px;border-radius:50%;background:#141110;opacity:.55}
</style>
</head>
<body>
<div class="wrap">
<div class="gong"></div>
<div class="num">404</div>
<h1>Nada ini tidak ditemukan</h1>
<p>Halaman yang kamu cari sudah pindah, terhapus, atau tidak pernah ada — seperti bunyi gong yang menghilang ditelan hutan.</p>
<a href="{{ url('/') }}">KEMBALI KE BERANDA</a>
</div>
</body>
</html>
