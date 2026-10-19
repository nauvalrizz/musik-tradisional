<?php

namespace App\Http\Controllers;

use App\Models\AlatMusik;
use App\Models\Pulau;
use App\Models\QuizResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

    // Backup database: dump 3 tabel utama sebagai file .sql (tanpa shell/mysqldump)
    public function backup()
    {
        $tables = ['alat_musik', 'pulau', 'quiz_results'];
        $sql = "-- MuSantara DB backup " . now()->toDateTimeString() . "\n\n";
        foreach ($tables as $t) {
            if (!Schema::hasTable($t)) continue;
            $create = DB::selectOne("SHOW CREATE TABLE `$t`");
            $row = (array) $create;
            $sql .= ($row['Create Table'] ?? '') . ";\n\n";
            foreach (DB::table($t)->orderBy('id')->cursor() as $r) {
                $vals = [];
                foreach ((array) $r as $v) {
                    $vals[] = is_null($v) ? 'NULL' : "'" . str_replace("'", "''", $v) . "'";
                }
                $sql .= "INSERT INTO `$t` VALUES (" . implode(',', $vals) . ");\n";
            }
            $sql .= "\n";
        }
        $name = 'musantara-backup-' . now()->format('Ymd-His') . '.sql';
        return response($sql, 200, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => "attachment; filename=\"$name\"",
        ]);
    }
}
