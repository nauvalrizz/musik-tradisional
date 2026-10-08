<?php

namespace App\Http\Controllers;

use App\Models\AlatMusik;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Hero: satu instrumen berfoto
        $hero = AlatMusik::whereNotNull('gambar')->where('gambar', '!=', '')
            ->inRandomOrder()->first();

        // Koleksi per region (maks 4 per pulau, yg berfoto dulu)
        $regions = [];
        foreach (\App\Models\Pulau::orderBy('id')->get() as $pl) {
            $items = AlatMusik::where('pulau_id', $pl->id)
                ->whereNotNull('gambar')->where('gambar', '!=', '')
                ->inRandomOrder()->limit(4)->get();
            if ($items->count()) $regions[] = ['pulau' => $pl, 'items' => $items];
        }

        // Jumlah alat per pulau (untuk peta)
        $islandCounts = AlatMusik::join('pulau', 'alat_musik.pulau_id', '=', 'pulau.id')
            ->selectRaw('pulau.slug, COUNT(*) as jml')
            ->groupBy('pulau.slug')
            ->pluck('jml', 'slug')
            ->toArray();

        // Hero: 3 instrumen permanen dari DB (single source of truth untuk audio)
        $heroInstruments = [];
        foreach (['gong' => 'gong', 'kenong' => 'kenong', 'angklung' => 'angklung'] as $id => $kw) {
            $rec = AlatMusik::where('nama', 'LIKE', "%{$kw}%")->first();
            if ($rec) {
                $heroInstruments[$id] = [
                    'id' => $id,
                    'db_id' => $rec->id,
                    'nama' => $rec->nama,
                    'region' => optional($rec->pulau)->nama ?? 'Nusantara',
                    'sumber' => $rec->sumber_bunyi,
                    'audio' => $rec->audio ? audio_alat($rec->audio) : null,
                ];
            }
        }

        // Ruang Bunyi: audio DB asli per instrumen (single source of truth)
        $ruangBunyi = [];
        foreach (['gong','kempul','kenong','saron','bonang','gambang','demung','peking'] as $kw) {
            $rec = AlatMusik::where('nama', 'LIKE', "%{$kw}%")->first();
            $ruangBunyi[$kw] = [
                'nama' => $rec ? $rec->nama : ucfirst($kw),
                'audio' => ($rec && $rec->audio) ? audio_alat($rec->audio) : null,
            ];
        }

        return view('pages.home', compact('hero', 'regions', 'islandCounts', 'heroInstruments', 'ruangBunyi'));
    }
    public function search(Request $request)
    {
        $q = $request->q;
        $kategori = $request->kategori;
        $pulau = $request->pulau;
        $sumber = $request->sumber;

        $query = AlatMusik::with('pulau');

        if ($q) {
            $query->where('nama', 'like', "%$q%");
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if ($pulau) {
            $query->where('pulau_id', $pulau);
        }

        if ($sumber) {
            $query->where('sumber_bunyi', $sumber);
        }

        $data = $query->paginate(12)->withQueryString();

        $pulaus = \App\Models\Pulau::orderBy('nama')->get();
        $sumbers = AlatMusik::distinct()->orderBy('sumber_bunyi')->pluck('sumber_bunyi');

        return view('pages.search', compact('data', 'q', 'kategori', 'pulau', 'sumber', 'pulaus', 'sumbers'));
    }
    public function liveSearch(Request $request)
{
    $q = trim($request->q);
    $kategori = $request->kategori;

    // tetap validasi lu
    if (!$q || strlen($q) < 2) {
        return response()->json([]);
    }

    $query = AlatMusik::with('pulau');

    // 🔥 SEARCH NAMA (WAJIB)
    $query->where('nama', 'like', '%' . $q . '%');

    // 🔥 FILTER KATEGORI (AMAN)
    if (!empty($kategori)) {
        $query->where('kategori', $kategori);
    }

    $data = $query->limit(3)->get();

    return response()->json(
        $data->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'asal_daerah' => $item->pulau->nama ?? '-',
                'gambar' => gambar_alat($item->gambar)
            ];
        })
    );
}
}
