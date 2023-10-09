<?php

use Carbon\Carbon;
use App\Services\DatabaseService;

expect()->extend('toBeParsed', function () {
   expect(reset($this->value))->toBe($this->value[0]);
   $this->toHaveCount(DatabaseService::NUMBER_OF_BALANCES);
});

beforeEach(function () {
    $config = config('supabase');
    $this->databaseService = new DatabaseService($config['api_key'], $config['url']);

    $this->balances = $this->databaseService->getHistoricalBalances();
});

test('you_can_get_the_historical_balances_from_supabase', function () {
    $prices = $this->balances['prices'];

    expect($prices)->toBeParsed();

    $pricesEur = $this->balances['prices_eur'];

    expect($pricesEur)->toBeParsed();

    $ethereum = $this->balances['ethereum'];

    expect($ethereum)->toBeParsed();

    $totals = $this->balances['totals'];

    expect($totals)->toBeParsed();

    expect(end($totals))->toBe(end($ethereum) * end($prices));

    $totalsEur = $this->balances['totals_eur'];

    expect($totalsEur)->toBeParsed();

    expect(end($totalsEur))->toBe(end($ethereum) * end($pricesEur));

    $dates = $this->balances['dates'];

    expect($dates)->toBeParsed();

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
    expect(Carbon::parse($tokens->last()->first()->created_at)->isSameDay(Carbon::parse(end($this->balances['dates']))))->toBeTrue();
    expect(Carbon::parse($tokens->reverse()->values()->get(1)->last()->created_at)->isSameDay(Carbon::parse(prev($this->balances['dates']))))->toBeTrue();

    $totals = $tokens->first()->map(fn($token) => $token->price * $token->balance);

    expect($tokens->first()->last()->price * $tokens->first()->last()->balance)->toBe($totals->min());
    expect($tokens->first()->first()->price * $tokens->first()->first()->balance)->toBe($totals->max());
})->group('supabase');
