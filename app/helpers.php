<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('gambar_alat')) {
    function gambar_alat($filename)
    {
        // ❌ kalau kosong → default image
        if (empty($filename)) {
            return asset('assets/img/default.png');
        }

        // 🔥 kalau sudah full URL (CDN / external)
        if (str_starts_with($filename, 'http')) {
            return $filename;
        }

        // 🔥 kalau format lama (udah include path)
        if (str_contains($filename, 'assets/')) {
            return asset($filename);
        }

        // ✅ PRIORITAS: file fisik di docroot (htdocs/storage/img) - layout server InfinityFree
        if (file_exists(public_path('storage/img/' . $filename))) {
            return asset('storage/img/' . $filename);
        }

        // 🔁 Storage disk standar Laravel
        if (Storage::disk('public')->exists('img/' . $filename)) {
            return asset('storage/img/' . $filename);
        }

        // 🔁 fallback struktur lama (basename di assets/img/alat-musik/)
        if (file_exists(public_path('assets/img/alat-musik/' . basename($filename)))) {
            return asset('assets/img/alat-musik/' . basename($filename));
        }

        // ❌ terakhir: default
        return asset('assets/img/default.png');
    }
}

if (!function_exists('kompres_gambar')) {
    // Resize gambar max 1200px (lebar) + kompres JPEG 80% / PNG, hemat bandwidth.
    // Return true jika berhasil dikompres, false jika GD tidak tersedia (file dibiarkan apa adanya).
    function kompres_gambar($path, $maxWidth = 1200, $quality = 80)
    {
        if (!extension_loaded('gd') || !file_exists($path)) return false;
        $info = @getimagesize($path);
        if (!$info) return false;
        [$w, $h, $type] = $info;
        if ($w <= $maxWidth) {
            // sudah kecil — kompres ulang saja tanpa resize
            $nw = $w; $nh = $h;
        } else {
            $nw = $maxWidth;
            $nh = (int) round($h * $maxWidth / $w);
        }
        $src = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG => @imagecreatefrompng($path),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };
        if (!$src) return false;
        $dst = imagecreatetruecolor($nw, $nh);
        if ($type === IMAGETYPE_PNG) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
        $ok = match ($type) {
            IMAGETYPE_JPEG => imagejpeg($dst, $path, $quality),
            IMAGETYPE_PNG => imagepng($dst, $path, (int) round(9 * (100 - $quality) / 100)),
            IMAGETYPE_WEBP => imagewebp($dst, $path, $quality),
            default => false,
        };
        imagedestroy($src);
        imagedestroy($dst);
        return (bool) $ok;
    }
}

if (!function_exists('audio_alat')) {    function audio_alat($filename)
    {
        if (empty($filename)) {
            return null;
        }
        $filename = trim(str_replace('\\', '/', (string)$filename));
        if ($filename === '' || str_contains($filename, '..')) {
            return null;
        }
        if (str_starts_with($filename, 'http')) {
            return $filename;
        }
        // Langsung ke file audio terverifikasi. $filename dari DB (mis. saluang.mp3).
        $filename = basename($filename);
        return asset('assets/js/audio/' . $filename);
    }
}
