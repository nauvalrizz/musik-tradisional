<?php

namespace App\Http\Controllers;

use App\Models\AlatMusik;

class RuangBunyiController extends Controller
{
    public function index()
    {
        // 3 instrumen untuk ruang 3D: cari berdasarkan nama, fallback ke yang ada gambar
        $gong = AlatMusik::where('nama','like','%gong%')->whereNotNull('gambar')->first()
             ?? AlatMusik::whereNotNull('gambar')->first();
        $kendang = AlatMusik::where('nama','like','%kendang%')->whereNotNull('gambar')->first()
             ?? AlatMusik::whereNotNull('gambar')->skip(1)->first();
        $saron = AlatMusik::where('nama','like','%saron%')->whereNotNull('gambar')->first()
             ?? AlatMusik::whereNotNull('gambar')->skip(2)->first();

        $items = collect([$gong, $kendang, $saron])->filter()->values()->map(function($a, $i){
            $nama = strtolower($a->nama);
            $tipe = 'gong';
            if (str_contains($nama,'kendang') || str_contains($nama,'gendang') || str_contains($nama,'tifa') || str_contains($nama,'bedug') || str_contains($nama,'babun')) $tipe = 'kendang';
            elseif (str_contains($nama,'saron') || str_contains($nama,'gambang') || str_contains($nama,'kolintang') || str_contains($nama,'bonang') || str_contains($nama,'kenong')) $tipe = 'saron';
            return [
                'id' => $a->id,
                'nama' => $a->nama,
                'pulau' => optional($a->pulau)->nama ?? 'Nusantara',
                'sumber' => $a->sumber_bunyi ?? '—',
                'gambar' => $a->gambar ? gambar_alat($a->gambar) : null,
                'tipe_3d' => $tipe,
            ];
        });

        return view('pages.ruang-bunyi', compact('items'));
    }
}
