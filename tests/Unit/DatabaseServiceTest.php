<?php

use Carbon\Carbon;
use App\Services\DatabaseService;

beforeEach(function () {
    $config = config('supabase');

    $this->databaseService = new DatabaseService($config['api_key'], $config['url']);

    $this->balances = $this->databaseService->getHistoricalBalances();
});

test('you_can_get_the_historical_balances_from_supabase', function () {
    $this->balances = $this->databaseService->getHistoricalBalances();

    expect($this->balances['prices'])->toHaveCount(28);
    expect(end($this->balances['prices']))->toBeNumeric();

    expect($this->balances['ethereum'])->toHaveCount(28);
    expect(end($this->balances['ethereum']))->toBeNumeric();

    expect($this->balances['totals'])->toHaveCount(28);
    expect(end($this->balances['totals']))->toBeNumeric()
        ->toBe(end($this->balances['ethereum']) * end($this->balances['prices']));

    $lastDate = Carbon::createFromFormat('M d', end($this->balances['dates']));

    expect($this->balances['dates'])->toHaveCount(28);
    expect(end($this->balances['dates']))
        ->toStartWith($lastDate->shortMonthName)
        ->toEndWith($lastDate->day);
    expect(Carbon::parse($this->balances['dates'][0])->lt($lastDate))->toBetrue();
})->group('supabase');

test('you_can_get_the_tokens_from_supabase', function () {
    $tokens = $this->databaseService->getTokens();

    expect($tokens)->toHaveCount(15);

    expect($tokens[0])->toHaveProperties([
        'pool', 'price', 'balance', 'parent', 'created_at'
    ]);

    expect(Carbon::parse($tokens[0]->created_at)->isSameDay(Carbon::parse(end($this->balances['dates']))))->toBeTrue();
})->group('supabase');
