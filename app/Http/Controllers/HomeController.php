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

        return view('pages.home', compact('featured'));
    }
    public function search(Request $request)
    {
        $q = $request->q;
        $kategori = $request->kategori;

        $query = AlatMusik::query();

        if ($q) {
            $query->where('nama', 'like', "%$q%");
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $data = $query->paginate(12);

        return view('pages.search', compact('data', 'q', 'kategori'));
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
