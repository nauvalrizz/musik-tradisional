<?php

namespace App\Http\Controllers;

use App\Models\AlatMusik;
use App\Models\QuizResult;

class AdminController extends Controller
{
    public function index()
    {
        $totalAlat = AlatMusik::count();
        $totalQuiz = QuizResult::count();
        $totalBenar = QuizResult::where('is_correct', 1)->count();
        $recent = AlatMusik::with('pulau')->orderBy('id', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalAlat',
            'totalQuiz',
            'totalBenar',
            'recent'
        ));
    }
}
