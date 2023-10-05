<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Services\DatabaseService;

class ShowDashboard extends Controller
{
    public function __invoke() {
        $balanceHistory = $this->getBalanceHistory();

        $balances = $balanceHistory->pluck('balance')->all();

        $dates = $balanceHistory->map(fn ($balance) =>
            Carbon::parse($balance->created_at)->format('Y-m-d')
        )->all();

        return view('dashboard', compact('balances', 'dates'));
    }

    protected function getBalanceHistory()
    {
        $supabaseConfig = config('supabase');

        return (new DatabaseService(
            $supabaseConfig['api_key'], $supabaseConfig['url']
        ))->getHistoricalBalances();
    }
}
