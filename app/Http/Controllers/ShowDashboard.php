<?php

namespace App\Http\Controllers;

use App\Services\DatabaseService;

class ShowDashboard extends Controller
{
    public function __invoke() {
        $supabaseConfig = config('supabase');

        $balances = (new DatabaseService(
            $supabaseConfig['api_key'], $supabaseConfig['url']
        ))->getHistoricalBalances();

        return view('dashboard', compact('balances'));
    }
}
