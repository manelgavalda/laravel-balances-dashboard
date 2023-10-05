<?php

use Carbon\Carbon;
use App\Services\DatabaseService;

test('you_can_get_the_historical_balances_from_supabase', function () {
    $config = config('supabase');

    $databaseService = new DatabaseService($config['api_key'], $config['url']);

    $historicalBalances = $databaseService->getHistoricalBalances();

    expect($historicalBalances)
        ->toBeIterable()
        ->toHaveCount(28);

    $firstBalance = $historicalBalances->first();

    expect($firstBalance)->toHaveProperties([
        'created_at', 'balance', 'price'
    ]);

    $lastBalanceDate = Carbon::parse($historicalBalances->last()->created_at);

    expect(Carbon::parse($firstBalance->created_at)->lt($lastBalanceDate))->toBetrue();
})->group('supabase');
