<?php

use Carbon\Carbon;
use App\Services\DatabaseService;

beforeEach(function () {
    $config = config('supabase');

    $this->databaseService = new DatabaseService($config['api_key'], $config['url']);

    $this->balances = $this->databaseService->getHistoricalBalances();
});

test('you_can_get_the_historical_balances_from_supabase', function () {
    $prices = $this->balances['prices'];

    expect($prices)->toHaveCount(28);
    expect(end($prices))->toBeNumeric();

    $ethereum = $this->balances['ethereum'];

    expect($ethereum)->toHaveCount(28);
    expect(end($ethereum))->toBeNumeric();

    $totals = $this->balances['totals'];

    expect($totals)->toHaveCount(28);
    expect(reset($totals))->toBe($totals[0]);
    expect(end($totals))->toBeNumeric()->toBe(end($ethereum) * end($prices));

    $dates = $this->balances['dates'];

    expect($dates)->toHaveCount(28);
    expect(reset($dates))->toBe($dates[0]);

    $lastDate = Carbon::createFromFormat('M d', end($dates));

    expect(Carbon::parse($dates[0])->lt($lastDate))->toBetrue();
    expect(end($dates))->toStartWith($lastDate->shortMonthName)->toEndWith($lastDate->day);
})->group('supabase');

test('you_can_get_the_tokens_from_supabase', function () {
    $tokens = $this->databaseService->getTokens();

    expect($tokens)->toHaveCount(15);
    expect($tokens[0]->rewards)->toBeArray();
    expect($tokens[0])->toHaveProperties(['pool', 'price', 'balance', 'parent', 'created_at', 'rewards']);
    expect(Carbon::parse($tokens[0]->created_at)->isSameDay(Carbon::parse(end($this->balances['dates']))))->toBeTrue();

    $totals = collect($tokens)->map(fn($token) => $token->price * $token->balance);

    expect($tokens[0]->price * $tokens[0]->balance)->toBe($totals->max());
    expect(end($tokens)->price * end($tokens)->balance)->toBe($totals->min());
})->group('supabase');
