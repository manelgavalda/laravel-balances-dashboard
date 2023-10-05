<?php

use Carbon\Carbon;
use App\Services\DatabaseService;

test('you_can_get_the_historical_balances_from_supabase', function () {
    $config = config('supabase');

    $balances = (new DatabaseService($config['api_key'], $config['url']))
        ->getHistoricalBalances();

    expect($balances['dates'])->toHaveCount(28);
    expect($balances['ethereum'])->toHaveCount(28);
    expect($balances['prices'])->toHaveCount(28);
    expect($balances['totals'])->toHaveCount(28);

    $firstBalanceDate = Carbon::parse($balances['dates'][0]);
    $lastBalanceDate = Carbon::parse(end($balances['dates']));

    expect($firstBalanceDate->lt($lastBalanceDate))->toBetrue();
})->group('supabase');
