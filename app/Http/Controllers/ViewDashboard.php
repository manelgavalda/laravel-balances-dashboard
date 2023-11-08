<?php

namespace App\Http\Controllers;

use App\Services\WiseService;

class ViewDashboard extends Controller
{
    public function __invoke() {
        $wiseService = new WiseService(config('wise.api_token'), config('wise.profile_id'));

        return view('dashboard', [
            'balance' => $wiseService->getBalance(),
            'transactions' => $wiseService->getLatestTransactions()
        ]);
    }
}
