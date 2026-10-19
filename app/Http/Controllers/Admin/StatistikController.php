<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizResult;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    public function index()
    {
        // Cache 1 jam — data agregat berat, tidak perlu real-time per detik
        $data = Cache::remember('statistik_admin', 3600, function () {
            // GLOBAL
            $total = QuizResult::count();
            $benar = QuizResult::where('is_correct', 1)->count();
            $salah = QuizResult::where('is_correct', 0)->count();
            $akurasi = $total > 0 ? round(($benar / $total) * 100, 2) : 0;

            // PER KATEGORI
            $perKategori = QuizResult::select(
                    'alat_musik.kategori',
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(is_correct) as benar')
                )
                ->join('alat_musik', 'quiz_results.alat_musik_id', '=', 'alat_musik.id')
                ->where('quiz_results.tipe_soal', 'kategori')
                ->groupBy('alat_musik.kategori')
                ->get();

            // PER SUMBER BUNYI
            $perSumber = QuizResult::select(
                    'alat_musik.sumber_bunyi',
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(is_correct) as benar')
                )
                ->join('alat_musik', 'quiz_results.alat_musik_id', '=', 'alat_musik.id')
                ->whereIn('quiz_results.tipe_soal', ['sumber_bunyi', 'sumber'])
                ->groupBy('alat_musik.sumber_bunyi')
                ->get();

            return compact('total', 'benar', 'salah', 'akurasi', 'perKategori', 'perSumber');
        });

        return view('admin.statistik', $data);
    }
}
