<?php

namespace App\Http\Controllers;

use App\Services\WiseService;
use App\Services\DatabaseService;

class ViewDashboard extends Controller
{
    public function __invoke() {
        $supabaseConfig = config('supabase');
        $databaseService = new DatabaseService($supabaseConfig['api_key'], $supabaseConfig['url']);

        $wiseConfig = config('wise');
        $wiseService = new WiseService($wiseConfig['api_token'], $wiseConfig['profile_id']);

        return view('dashboard', [
            'balance' => $wiseService->getBalance(),
            'tokens' => $databaseService->getTokens(),
            'balances' => $databaseService->getHistoricalBalances(),
            'transactions' => $wiseService->getLatestTransactions()
        ]);
    }
}
