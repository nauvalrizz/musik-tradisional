<?php
// Fix Aramba image: update DB gambar field untuk alat id=6
// Jalankan sekali via browser, lalu HAPUS file ini.
header('Content-Type: text/plain; charset=utf-8');

$envFile = file_exists(__DIR__ . '/laravel/.env')
    ? __DIR__ . '/laravel/.env'   // struktur server InfinityFree
    : __DIR__ . '/../.env';        // struktur lokal
$env = [];
foreach (file($envFile) as $line) {
    $line = trim($line);
    if ($line === '' || $line[0] === '#') continue;
    [$k, $v] = array_pad(explode('=', $line, 2), 2, '');
    $env[trim($k)] = trim($v, " \t\n\r\0\x0B\"'");
}

try {
    $pdo = new PDO(
        "mysql:host={$env['DB_HOST']};port={$env['DB_PORT']};dbname={$env['DB_DATABASE']};charset=utf8mb4",
        $env['DB_USERNAME'],
        $env['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $stmt = $pdo->prepare("UPDATE alat_musik SET gambar = ? WHERE id = 6");
    $stmt->execute(['aramba.jpg']);
    $rows = $stmt->rowCount();

    $check = $pdo->query("SELECT id, nama, gambar FROM alat_musik WHERE id = 6")->fetch(PDO::FETCH_ASSOC);
    echo "OK - rows updated: $rows\n";
    echo "id={$check['id']} nama={$check['nama']} gambar={$check['gambar']}\n";
    echo "\nHAPUS file fix-aramba.php sekarang.\n";
} catch (Exception $e) {
    http_response_code(500);
    echo "ERROR: " . $e->getMessage() . "\n";
}
