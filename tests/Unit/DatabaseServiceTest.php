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

    $prices = $this->balances['prices'];

    expect($prices)->toHaveCount(28);
    expect($prices->last())->toBeNumeric();

    $ethereum = $this->balances['ethereum'];

    expect($ethereum)->toHaveCount(28);
    expect($ethereum->last())->toBeNumeric();

    $totals = $this->balances['totals'];

    expect($totals)->toHaveCount(28);
    expect($totals->last())->toBeNumeric()
        ->toBe($ethereum->last() * $prices->last());

    $dates = $this->balances['dates'];

    $lastDate = Carbon::createFromFormat('M d', $dates->last());

    expect($dates)->toHaveCount(28);
    expect($dates->last())
        ->toStartWith($lastDate->shortMonthName)
        ->toEndWith($lastDate->day);
    expect(Carbon::parse($dates[0])->lt($lastDate))->toBetrue();
})->group('supabase');

test('you_can_get_the_tokens_from_supabase', function () {
    $tokens = $this->databaseService->getTokens();

    expect($tokens)->toHaveCount(15);

    $firstToken = $tokens->first();

    $totals = collect($tokens)->map(fn($token) => $token->price * $token->balance);

    expect($firstToken->price * $firstToken->balance)->toBe($totals->max());
    expect($tokens->last()->price * $tokens->last()->balance)->toBe($totals->min());

    expect($firstToken)->toHaveProperties([
        'pool', 'price', 'balance', 'parent', 'created_at', 'rewards'
    ]);
    expect($firstToken->rewards)->toBeArray();

    expect(Carbon::parse($firstToken->created_at)->isSameDay(Carbon::parse($this->balances['dates']->last())))->toBeTrue();
})->group('supabase');
