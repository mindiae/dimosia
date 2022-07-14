<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/literature', function () {
    return Inertia::render('Literature');
})->name('literature');

Route::get('/bible', function () {
    return Inertia::render('Bible', [
        'connections' => fn ()  => config('bibles')
    ]);
})->name('bible');

Route::get('lang/{locale}',
    [App\Http\Controllers\LanguageController::class, 'switchLang']
)->name('lang.switch');
