<?php

namespace App\Http\Controllers;

use App\Models\Pulau;
use App\Models\AlatMusik;

class PulauController extends Controller
{
    public function show($slug)
    {
        $pulau = Pulau::where('slug', $slug)->firstOrFail();

        $alat = AlatMusik::where('pulau_id', $pulau->id)->get();
        $featured = AlatMusik::where('pulau_id', $pulau->id)->whereNotNull('gambar')->where('gambar','!=','')->inRandomOrder()->first();

        return view('pages.pulau', compact('pulau', 'alat', 'featured'));
    }
}