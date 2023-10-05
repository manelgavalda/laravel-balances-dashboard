<?php

use Carbon\Carbon;
use App\Services\DatabaseService;

beforeEach(function () {
    $config = config('supabase');

    $this->databaseService = new DatabaseService($config['api_key'], $config['url']);
});

test('you_can_get_the_historical_balances_from_supabase', function () {
    $balances = $this->databaseService->getHistoricalBalances();

    expect($balances['prices'])->toHaveCount(28);
    expect(end($balances['prices']))->toBeNumeric();

    expect($balances['ethereum'])->toHaveCount(28);
    expect(end($balances['ethereum']))->toBeNumeric();

    expect($balances['totals'])->toHaveCount(28);
    expect(end($balances['totals']))->toBeNumeric()
        ->toBe(end($balances['ethereum']) * end($balances['prices']));

    $lastDate = Carbon::createFromFormat('M d', end($balances['dates']));

    expect($balances['dates'])->toHaveCount(28);
    expect(end($balances['dates']))
        ->toStartWith($lastDate->shortMonthName)
        ->toEndWith($lastDate->day);
    expect(Carbon::parse($balances['dates'][0])->lt($lastDate))->toBetrue();
})->group('supabase');

test('you_can_get_the_tokens_from_supabase', function () {
    // $config = config('supabase');

    // $tokens = (new DatabaseService($config['api_key'], $config['url']))
    //     ->getTokens();

    // expect($tokens)->toHaveCount(13);

    // expect($firstBalanceDate->lt($lastBalanceDate))->toBetrue();
})->group('supabase');
