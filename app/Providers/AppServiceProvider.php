<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('partials.pagination');

        // Share settings to all views
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $view->with('appSettings', \App\Models\PengaturanAplikasi::getSettings());
            $view->with('activeAkademik', \App\Models\PengaturanAkademik::active());
        });


    }
}
