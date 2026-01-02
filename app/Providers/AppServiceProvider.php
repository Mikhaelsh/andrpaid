<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
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
        Blade::if('lecturer', function () {
            return Auth::check() && Auth::user()->isLecturer();
        });

        Blade::if('university', function () {
            return Auth::check() && Auth::user()->isUniversity();
        });

        Blade::if('admin', function () {
            return Auth::check() && Auth::user()->id === 1;
        });
    }
}
