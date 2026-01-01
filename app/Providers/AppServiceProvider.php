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
            // Return true if user is logged in AND is a lecturer
            return Auth::check() && Auth::user()->isLecturer();
        });

        Blade::if('university', function () {
            // Return true if user is logged in AND is a lecturer
            return Auth::check() && Auth::user()->isUniversity();
        });
    }
}
