<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\Models\AlatMusik;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        // Loader "Nusantara Orchestra": 4 instrumen asli untuk scene loading
        View::composer('layouts.app', function ($view) {
            try {
                $items = AlatMusik::whereNotNull('gambar')->where('gambar','!=','')->inRandomOrder()->take(4)->get();
            } catch (\Throwable $e) { $items = collect(); }
            $view->with('loaderItems', $items);
        });
    }
}
