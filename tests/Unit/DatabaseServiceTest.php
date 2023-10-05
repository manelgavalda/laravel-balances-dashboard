<?php

use Carbon\Carbon;
use App\Services\DatabaseService;

beforeEach(function () {
    $config = config('supabase');

    $this->databaseService = new DatabaseService($config['api_key'], $config['url']);
});

test('you_can_get_the_historical_balances_from_supabase', function () {
    $balances = $this->databaseService->getHistoricalBalances();

    $date = Carbon::createFromFormat('M d', end($balances['dates']));

    expect($balances['dates'])->toHaveCount(28);
    expect(end($balances['dates']))
        ->toStartWith($date->shortMonthName)
        ->toEndWith($date->day);

    expect($balances['ethereum'])->toHaveCount(28);
    expect(end($balances['ethereum']))->toBeNumeric();

    expect($balances['prices'])->toHaveCount(28);
    expect(end($balances['prices']))->toBeNumeric();

    expect($balances['totals'])->toHaveCount(28);
    expect(end($balances['totals']))->toBeNumeric()
        ->toBe(end($balances['ethereum']) * end($balances['prices']));

    $firstBalanceDate = Carbon::parse($balances['dates'][0]);
    $lastBalanceDate = Carbon::parse(end($balances['dates']));

    expect($firstBalanceDate->lt($lastBalanceDate))->toBetrue();
})->group('supabase');

test('you_can_get_the_tokens_from_supabase', function () {
    // $config = config('supabase');

    // $tokens = (new DatabaseService($config['api_key'], $config['url']))
    //     ->getTokens();

    // expect($tokens)->toHaveCount(13);

    // expect($firstBalanceDate->lt($lastBalanceDate))->toBetrue();
})->group('supabase');
