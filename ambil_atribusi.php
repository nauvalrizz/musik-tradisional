<?php

// koneksi DB
$conn = mysqli_connect("localhost", "root", "", "musik_tradisional");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// ambil data
$query = mysqli_query($conn, "SELECT id, sumber_url FROM alat_musik");

while ($row = mysqli_fetch_assoc($query)) {
    $id = $row['id'];
    $url = $row['sumber_url'];

    if (!$url) continue;

    // ambil nama file dari URL (File:xxxx.jpg)
    if (preg_match('/File:(.+)$/', $url, $match)) {
        $fileName = $match[1];

        // encode biar aman
        $fileName = str_replace(' ', '_', $fileName);

        $api = "https://commons.wikimedia.org/w/api.php?action=query&titles=File:$fileName&prop=imageinfo&iiprop=extmetadata&format=json";

        // 🔥 FIX 403 → wajib pakai User-Agent
        $options = [
            "http" => [
                "header" => "User-Agent: MusikTradisionalApp/1.0\r\n"
            ]
        ];

        $context = stream_context_create($options);
        $json = @file_get_contents($api, false, $context);

        if (!$json) {
            echo "Gagal ambil data ID $id<br>";
            continue;
        }

        $data = json_decode($json, true);

        if (!isset($data['query']['pages'])) {
            echo "Data kosong ID $id<br>";
            continue;
        }

        $pages = $data['query']['pages'];
        $page = array_values($pages)[0];

        if (!isset($page['imageinfo'][0]['extmetadata'])) {
            echo "Metadata kosong ID $id<br>";
            continue;
        }

        $meta = $page['imageinfo'][0]['extmetadata'];

        // ambil author & license
        $author = isset($meta['Artist']['value'])
            ? strip_tags($meta['Artist']['value'])
            : 'Unknown';

        $license = isset($meta['LicenseShortName']['value'])
            ? $meta['LicenseShortName']['value']
            : 'Unknown';

        // escape biar aman
        $author = mysqli_real_escape_string($conn, $author);
        $license = mysqli_real_escape_string($conn, $license);

        // update DB
        mysqli_query($conn, "
            UPDATE alat_musik 
            SET author = '$author', license = '$license' 
            WHERE id = $id
        ");

        echo "✔️ Updated ID $id<br>";

        // delay biar ga kena limit API
        sleep(1);
    } else {
        echo "❌ URL tidak valid ID $id<br>";
    }
}

echo "<br>✅ SELESAI";
