<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewDashboard;

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

Route::get('/', ViewDashboard::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/test', function() {
    $wiseConfig = config('wise');

    $response = Http::withToken($wiseConfig['api_token'])->get(
        "https://api.transferwise.com/v4/profiles/{$wiseConfig['profile_id']}/balances",
        ['types' => 'STANDARD']
    )->object();

    dd($response[0]->amount->value);
})->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
