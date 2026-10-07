<?php

namespace App\Http\Controllers;

use App\Models\AlatMusik;
use Illuminate\Support\Arr;

class AlatController extends Controller
{
    public function show($id)
    {
        $alat = AlatMusik::with('pulau')->findOrFail($id);

        // ===== INIT SESSION =====
        if (!session()->has('explore_time')) {
            session()->put('explore_time', now());
        }

        // ===== RESET SETELAH 30 MENIT =====
        if (now()->diffInMinutes(session('explore_time')) > 30) {
            session()->forget('explored_items');
            session()->forget('explored_count');
            session()->forget('last_visit_all');
            session()->forget('total_time');

            session()->put('explore_time', now());
        }

        // ===== AMBIL DATA SESSION =====
        $explored = session()->get('explored_items', []);
        $lastVisitAll = session()->get('last_visit_all');

        // ===== TRACK TIME (SIMPLE GLOBAL) =====
        if ($lastVisitAll) {
            $diff = now()->diffInSeconds($lastVisitAll);

            // batasi max biar ga ngaco kalau tab ditinggal lama
            if ($diff < 300) { // max 5 menit
                $totalTime = session()->get('total_time', 0);
                session()->put('total_time', $totalTime + $diff);
            }
        }

        // update last visit global
        session()->put('last_visit_all', now());

        // ===== ANTI SPAM REFRESH PER ITEM =====
        $lastVisitKey = 'last_visit_' . $id;
        $lastVisit = session()->get($lastVisitKey);

        $allowTrack = false;

        if (!$lastVisit) {
            $allowTrack = true;
        } else {
            if (now()->diffInSeconds($lastVisit) > 3) {
                $allowTrack = true;
            }
        }

        // ===== TRACK EXPLORE =====
        if ($allowTrack) {

            if (!in_array($id, $explored)) {
                $explored[] = $id;
                session()->put('explored_items', $explored);
                session()->put('explored_count', count($explored));
            }

            session()->put($lastVisitKey, now());
        }

        // ===== LOGIKA KUIS =====
        $tipeSoalList = ['sumber_bunyi', 'kategori'];
        $tipeSoal = Arr::random($tipeSoalList);

        $pertanyaan = '';
        $jawabanBenar = '';
        $opsi = [];

        if ($tipeSoal === 'sumber_bunyi') {

            $pertanyaan = "Alat musik ini termasuk sumber bunyi apa?";
            $jawabanBenar = $alat->sumber_bunyi;

            $opsi = ['Idiofon', 'Aerofon', 'Kordofon', 'Membranofon'];
        } else {

            $pertanyaan = "Alat musik ini dimainkan dengan cara apa?";
            $jawabanBenar = $alat->kategori;

            $semuaKategori = ['Petik', 'Pukul', 'Tiup', 'Gesek', 'Goyang', 'Getar'];

            $opsi = array_diff($semuaKategori, [$jawabanBenar]);
            shuffle($opsi);

            $opsi = array_slice($opsi, 0, 3);
            $opsi[] = $jawabanBenar;
        }

        shuffle($opsi);

        // ===== ALAT TERKAIT: satu pulau atau satu sumber bunyi =====
        $terkait = AlatMusik::with('pulau')
            ->where('id', '!=', $alat->id)
            ->where(function ($q) use ($alat) {
                $q->where('pulau_id', $alat->pulau_id)
                    ->orWhere('sumber_bunyi', $alat->sumber_bunyi);
            })
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('pages.detail', compact(
            'alat',
            'pertanyaan',
            'jawabanBenar',
            'opsi',
            'tipeSoal',
            'terkait'
        ));
    }

    public function acak()
    {
        $alat = AlatMusik::inRandomOrder()->firstOrFail();
        return redirect('/alat/' . $alat->id);
    }
}
