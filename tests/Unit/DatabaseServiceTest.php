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

    $pricesEur = $this->balances['prices_eur'];

    expect($pricesEur)->toHaveCount(28);
    expect(end($pricesEur))->toBeNumeric();

    $ethereum = $this->balances['ethereum'];

    expect($ethereum)->toHaveCount(28);
    expect(end($ethereum))->toBeNumeric();

    $totals = $this->balances['totals'];

    expect($totals)->toHaveCount(28);
    expect(reset($totals))->toBe($totals[0]);
    expect(end($totals))->toBeNumeric()->toBe(end($ethereum) * end($prices));

    $totalsEur = $this->balances['totals_eur'];

    expect($totalsEur)->toHaveCount(28);
    expect(reset($totalsEur))->toBe($totalsEur[0]);
    expect(end($totalsEur))->toBeNumeric()->toBe(end($ethereum) * end($pricesEur));

    $dates = $this->balances['dates'];

    expect(reset($dates))->toBe($dates[0]);
    expect($dates)->toHaveCount(DatabaseService::NUMBER_OF_BALANCES);

    $lastDate = Carbon::createFromFormat('M d', end($dates));

    expect(Carbon::parse($dates[0])->lt($lastDate))->toBetrue();
    expect(end($dates))->toStartWith($lastDate->shortMonthName)->toEndWith($lastDate->day);
})->group('supabase');

test('you_can_get_the_tokens_from_supabase', function () {
    $tokens = $this->databaseService->getTokens();

    expect($tokens)->toHaveCount(DatabaseService::DAYS);
    expect($tokens->last())->toHaveCount(DatabaseService::NUMBER_OF_TOKENS);
    expect($tokens->first())->toHaveCount(DatabaseService::NUMBER_OF_TOKENS);
    expect($tokens->first()->first())->toHaveProperties(['pool', 'price', 'price_eur', 'balance', 'parent', 'created_at']);
    expect(Carbon::parse($tokens->first()->first()->created_at)->isSameDay(Carbon::parse(end($this->balances['dates']))))->toBeTrue();
    expect(Carbon::parse($tokens->last()->last()->created_at)->isSameDay(Carbon::parse(prev($this->balances['dates']))))->toBeTrue();

    $totals = collect($tokens->first())->map(fn($token) => $token->price * $token->balance);

    expect($tokens->first()->last()->price * $tokens->first()->last()->balance)->toBe($totals->min());
    expect($tokens->first()->first()->price * $tokens->first()->first()->balance)->toBe($totals->max());
})->group('supabase');
