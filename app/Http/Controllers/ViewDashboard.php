<?php

namespace App\Http\Controllers;

use App\Services\DatabaseService;

class ViewDashboard extends Controller
{
    public function __invoke() {
        $supabaseConfig = config('supabase');
        $databaseService = new DatabaseService($supabaseConfig['api_key'], $supabaseConfig['url']);

        return view('dashboard', [
            'days' => DatabaseService::DAYS,
            'tokens' => $databaseService->getTokens(),
            'balances' => $databaseService->getHistoricalBalances()
        ]);
    }
}
