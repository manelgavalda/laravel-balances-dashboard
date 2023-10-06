<?php

namespace App\Http\Controllers;

use App\Services\DatabaseService;

class ViewDashboard extends Controller
{
    public function __invoke() {
        $supabaseConfig = config('supabase');

        $databaseService = new DatabaseService(
            $supabaseConfig['api_key'], $supabaseConfig['url']
        );

        $tokens = $databaseService->getTokens();
        $balances = $databaseService->getHistoricalBalances();

        $ethereumPrice = end($balances['prices']);

        return view('dashboard', compact('tokens', 'balances', 'ethereumPrice'));
    }
}
