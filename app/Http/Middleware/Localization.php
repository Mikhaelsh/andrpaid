<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class Localization
{
    public function handle(Request $request, Closure $next)
    {
        if (Cookie::get('user_locale')) {
            App::setLocale(Cookie::get('user_locale'));
        } else {
            App::setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
