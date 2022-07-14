<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Route;
use App\Models\Curroute;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *use Illuminate\Support\Facades\Session;
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Inertia::share([
            'name' => function() {
                return config('app.name');
            },
            'url' => function() {
                return config('app.url');
            },
            'locale' => function() {
                return app()->getlocale();
            },
            'languages' => function() {
                return config('languages');
            },
            'language' => function () {
                // system('notify-send "' . Route::current()->getName() . '"');
                $routes = Curroute::with('langs')
                    ->whereName(Route::current()->getName())
                    ->get()
                    ->all();
                if ($routes) {
                    return $routes[0]->langs
                        ->pluck(app()->getlocale(), 'key')
                        ->all();
                }
                return [];
            }
        ]);
    }
}
