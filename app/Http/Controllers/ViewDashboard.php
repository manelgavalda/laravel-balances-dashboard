<?php

namespace App\Http\Controllers;

use App\Services\WiseService;
use App\Services\DatabaseService;

class ViewDashboard extends Controller
{
    public function __invoke() {
        $wiseService = new WiseService(config('wise.api_token'), config('wise.profile_id'));
        $databaseService = new DatabaseService(config('supabase.api_key'), config('supabase.url'));

        return view('dashboard', [
            'balance' => $wiseService->getBalance(),
            'tokens' => $databaseService->getTokens(),
            'balances' => $databaseService->getHistoricalBalances(),
            'transactions' => $wiseService->getLatestTransactions()
        ]);
    }
}
