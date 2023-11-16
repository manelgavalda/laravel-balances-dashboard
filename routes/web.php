<?php

use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
if (!function_exists('vercel_asset'))   {
    function vercel_asset($path)
    {
        return config(
            app()->environment('production') ? 'assets.url' : 'url'
        ) . $path;
    }
}

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/get-tokens', fn ()  => Http::get(config('tokens.api_url'))->object());
});

require __DIR__.'/auth.php';
