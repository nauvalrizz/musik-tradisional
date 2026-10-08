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

        // 🔁 fallback struktur lama
        if (file_exists(public_path('assets/img/alat-musik/' . $filename))) {
            return asset('assets/img/alat-musik/' . $filename);
        }

        // ❌ terakhir: default
        return asset('assets/img/default.png');
    }
}

if (!function_exists('audio_alat')) {
    function audio_alat($filename)
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
