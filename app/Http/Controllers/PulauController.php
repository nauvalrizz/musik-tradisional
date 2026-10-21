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

        // Siluet pulau: ambil path SVG pulau ini dari partial peta (sumber tunggal)
        $pathD = null;
        $mapFile = resource_path('views/partials/map.blade.php');
        if (is_file($mapFile)) {
            $svg = file_get_contents($mapFile);
            $slugQ = preg_quote($pulau->slug, '/');
            if (preg_match('/<path[^>]*\bdata-slug="' . $slugQ . '"[^>]*>/s', $svg, $tag)
                && preg_match('/\bd="([^"]+)"/', $tag[0], $m)) {
                $pathD = $m[1];
            }
        }

        // Info singkat tiap pulau (label penjelasan di hero halaman pulau)
        $tentangMap = [
            'sumatra' => "Rumah gondang Batak, talempong Minangkabau, dan serunai Melayu — tradisi bunyi yang hidup dari pesisir sampai dataran tinggi Sumatra.",
            'jawa' => "Tanah gamelan — orkestra perunggu pusaka keraton Jawa dan Sunda, tempat gong, kenong, dan saron berlapis dalam satu irama yang berputar.",
            'kalimantan' => "Pulau sungai-sungai besar. Di sini sape' Dayak dan gambus Kutai mengalir dari pedalaman hutan sampai ke pesisir.",
            'sulawesi' => "Dari kolintang Minahasa sampai gendang Makassar — bunyi kayu, bambu, dan kulit dari semenanjung-semenanjung Sulawesi.",
            'bali-nusa-tenggara' => "Gamelan Bali yang berdentang rapat, gendang beleq Lombok yang mengarak pesta, dan sasando Rote yang dipetik dari daun lontar.",
            'maluku' => "Kepulauan rempah-rempah. Tahuri — terompet kerang raksasa — dan tifa mengantar nyanyian laut masyarakat Maluku.",
            'papua' => "Ujung timur Nusantara; tifa, suling tambur, dan pikon mengiringi upacara dari pesisir sampai lembah pegunungan Papua.",
        ];
        $tentang = $tentangMap[$pulau->slug] ?? "Salah satu kepulauan Nusantara dengan tradisi bunyi yang khas dan hidup sampai hari ini.";

        return view('pages.pulau', compact('pulau', 'alat', 'featured', 'pathD', 'tentang'));
    }
}