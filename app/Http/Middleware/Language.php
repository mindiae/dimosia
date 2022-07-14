<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        app()->setLocale(config('app.locale'));
        
        if (session()->has('applocale') AND array_key_exists(session('applocale'), config('languages'))) {
            
            app()->setlocale(session('applocale'));
        }
        else { // This is optional as Laravel will automatically set the fallback language if there is none specified
            app()->setLocale(config('app.fallback_locale'));
        }
        return $next($request);
    }
}
