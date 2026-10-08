<?php

namespace App\Http\Controllers;

use App\Models\AlatMusik;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featured = AlatMusik::inRandomOrder()
            ->limit(6)
            ->get();

        // Jumlah alat per pulau (untuk tooltip peta)
        $islandCounts = AlatMusik::join('pulau', 'alat_musik.pulau_id', '=', 'pulau.id')
            ->selectRaw('pulau.slug, COUNT(*) as jml')
            ->groupBy('pulau.slug')
            ->pluck('jml', 'slug')
            ->toArray();

        return view('pages.home', compact('featured', 'islandCounts'));
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
