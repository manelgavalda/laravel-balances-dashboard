<?php

use Carbon\Carbon;
use App\Services\DatabaseService;

uses()->group('supabase');

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
    expect($dates = $this->balances['dates'])->toBeParsed();
    expect($totals = $this->balances['totals'])->toBeParsed();
    expect($prices = $this->balances['prices'])->toBeParsed();
    expect($ethereum = $this->balances['ethereum'])->toBeParsed();
    expect($pricesEur = $this->balances['prices_eur'])->toBeParsed();
    expect($totalsEur = $this->balances['totals_eur'])->toBeParsed();

    expect(end($totals))->toBe(end($ethereum) * end($prices));
    expect(end($totalsEur))->toBe(end($ethereum) * end($pricesEur));

    $lastDate = Carbon::createFromFormat('M d', end($dates));

    expect(Carbon::parse($dates[0])->lt($lastDate))->toBetrue();
    expect(end($dates))->toStartWith($lastDate->shortMonthName)->toEndWith($lastDate->day);
});

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
});
