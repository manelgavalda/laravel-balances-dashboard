<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Services\WiseService;
use App\Services\SupabaseService;

class Dashboard extends Controller
{
    public function __invoke() {
        $supabaseService = new SupabaseService;
        $wiseService = new WiseService(config('wise.api_token'), config('wise.profile_id'));

        return Inertia::render('Dashboard', [
            'balance' => $wiseService->getBalance(),
            'balances' => $supabaseService->getHistoricalBalances(),
            'transactions' => $wiseService->getLatestTransactions(),
            'tokens' => $supabaseService->getTokens()->values()->toArray()
        ]);
    }
}
