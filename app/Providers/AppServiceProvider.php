<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
        // The whole dashboard is Bootstrap 5; Laravel's pagination links
        // default to Tailwind markup, which would look broken here.
        Paginator::useBootstrapFive();
    }
}
