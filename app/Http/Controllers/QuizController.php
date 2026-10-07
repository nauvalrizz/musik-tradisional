<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AlatMusik; // 🔥 WAJIB

class QuizController extends Controller
{
    /**
     * ================= QUIZ PER ALAT =================
     */

    /**
     * ================= QUIZ GLOBAL PAGE =================
     */
    public function global()
    {
        if (session('explored_count', 0) < 3) {
            return redirect('/')
                ->with('error', 'Eksplor minimal 3 alat musik dulu!');
        }

        $data = AlatMusik::inRandomOrder()->take(5)->get();

        $allKategori = ['Petik', 'Pukul', 'Tiup', 'Gesek', 'Goyang', 'Getar'];

        $questions = $data->map(function ($item) use ($allKategori) {

            $correct = $item->kategori;

            // ambil semua selain jawaban benar
            $wrong = array_values(array_diff($allKategori, [$correct]));

            // random 3 jawaban salah
            shuffle($wrong);
            $wrong = array_slice($wrong, 0, 3);

            // gabung + shuffle
            $options = array_merge([$correct], $wrong);
            shuffle($options);

            return [
                'question' => "Alat musik {$item->nama} dimainkan dengan cara apa?",
                'options' => $options,
                'answer' => $correct
            ];
        })->toArray();

        session()->put('quiz_questions', $questions);

        return view('quiz.global', compact('questions'));
    }

    /**
     * ================= QUIZ RESULT PAGE =================
     */
    public function result()
    {
        if (session('explored_count', 0) < 3) {
            return redirect('/')
                ->with('error', 'Eksplor minimal 3 alat musik dulu!');
        }

        return view('quiz.result');
    }

    /**
     * ================= DATA UNTUK QUIZ GLOBAL (API) =================
     */
    public function globalData()
    {
        return response()->json(
            AlatMusik::with('pulau')->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'kategori' => $item->kategori,
                    'sumber_bunyi' => $item->sumber_bunyi,
                    'asal_daerah' => $item->pulau->nama ?? 'Tidak diketahui',
                    'gambar' => $item->gambar ? gambar_alat($item->gambar) : null,
                ];
            })
        );
    }
    public function submitGlobal(Request $request)
    {
        $answers = $request->answers;

        foreach ($answers as $ans) {
            \App\Models\QuizResult::create([
                'session_id' => session()->getId(),
                'alat_musik_id' => $ans['alat_id'],
                'tipe_soal' => $ans['tipe'],
                'is_correct' => $ans['is_correct']
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function getQuestions()
    {
        $data = AlatMusik::inRandomOrder()->take(10)->get();

        $allKategori = ['Petik', 'Pukul', 'Tiup', 'Gesek', 'Goyang', 'Getar'];
        $allSumber   = ['Kordofon', 'Aerofon', 'Membranofon', 'Idiofon'];
        $allNama     = AlatMusik::pluck('nama')->toArray();

        $questions = $data->map(function ($item) use ($allKategori, $allSumber, $allNama) {

            $type = collect(['kategori', 'nama', 'sumber'])->random();

            // === tentukan correct & pool wrong ===
            if ($type === 'kategori') {
                $correct = $item->kategori;
                $pool = array_values(array_diff($allKategori, [$correct]));
                $question = "Alat musik {$item->nama} dimainkan dengan cara apa?";
            } elseif ($type === 'sumber') {
                $correct = $item->sumber_bunyi;
                $pool = array_values(array_diff($allSumber, [$correct]));
                $question = "{$item->nama} termasuk sumber bunyi apa?";
            } else {
                $correct = $item->nama;
                $pool = array_values(array_diff($allNama, [$correct]));
                $question = "Nama alat musik pada gambar ini adalah?";
            }

            // === ambil 3 salah → total 4 opsi ===
            shuffle($pool);
            $wrong = array_slice($pool, 0, 3);

            $options = array_merge([$correct], $wrong);
            shuffle($options);

            return [
                'question' => $question,
                'options'  => $options,
                'correct'  => $correct,
                'tipe'     => $type,
                'alat_id'  => $item->id,
                'image'    => $item->gambar ? gambar_alat($item->gambar) : null,
            ];
        });

        return response()->json($questions->values());
    }
}
