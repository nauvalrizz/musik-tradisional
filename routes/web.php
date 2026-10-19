<?php

use Illuminate\Support\Facades\Route;
use App\Models\AlatMusik;
use App\Http\Controllers\Admin\AlatMusikController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PulauController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\RuangBunyiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\StatistikController;


Route::get('/test', function () {
    return AlatMusik::all();
});

Route::get('/', [HomeController::class, 'index']);

Route::middleware('admin.auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/admin/alat', [AlatMusikController::class, 'index']);
    Route::post('/admin/alat', [AlatMusikController::class, 'store']);
    Route::get('/admin/alat/{id}/edit', [AlatMusikController::class, 'edit']);
    Route::put('/admin/alat/{id}', [AlatMusikController::class, 'update']);
    Route::delete('/admin/alat/{id}', [AlatMusikController::class, 'destroy']);
    Route::get('/admin/statistik', [StatistikController::class, 'index']);
    Route::get('/admin/backup', [AdminController::class, 'backup']);
});

Route::get('/pulau/{slug}', [PulauController::class, 'show']);

Route::get('/acak', [AlatController::class, 'acak']);
Route::get('/alat/{id}', [AlatController::class, 'show']);

Route::post('/quiz/submit', [QuizController::class, 'submit'])->middleware('throttle:20,1');

Route::get('/quiz/questions', [QuizController::class, 'getQuestions']);

Route::get('/api/search', [HomeController::class, 'ajaxSearch']);

Route::get('/ruang-bunyi', [RuangBunyiController::class, 'index']);

Route::get('/quiz-global', [QuizController::class, 'global'])
    ->middleware('check.explore');

Route::get('/quiz-result', [QuizController::class, 'result'])
    ->middleware('check.explore');

Route::get('/api/quiz-data', [QuizController::class, 'globalData']);


Route::get('/ambil-atribusi', function () {
    require base_path('ambil_atribusi.php');
});

Route::post('/quiz-global/submit', [QuizController::class, 'submitGlobal'])->middleware('throttle:20,1');
Route::post('/api/quiz-global/submit', [QuizController::class, 'submitGlobal'])->middleware('throttle:20,1');
Route::post('/quiz/submit-global', [QuizController::class, 'submitGlobal'])->middleware('throttle:20,1');

// sitemap.xml untuk SEO
Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => url('/'), 'priority' => '1.0'],
        ['loc' => url('/ruang-bunyi'), 'priority' => '0.8'],
        ['loc' => url('/quiz-global'), 'priority' => '0.7'],
    ];
    foreach (\App\Models\Pulau::all() as $p) {
        $urls[] = ['loc' => url('/pulau/' . $p->slug), 'priority' => '0.8'];
    }
    foreach (\App\Models\AlatMusik::select('id')->get() as $a) {
        $urls[] = ['loc' => url('/alat/' . $a->id), 'priority' => '0.9'];
    }
    return response()->view('sitemap', compact('urls'))->header('Content-Type', 'text/xml');
});

// halaman search
Route::get('/search', [HomeController::class, 'search'])->name('search');

// live search (dropdown)
Route::get('/live-search', [HomeController::class, 'liveSearch']);
