<?php

use Carbon\Carbon;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

uses()->group('supabase');

expect()->extend('toBeParsed', function () {
   expect(reset($this->value))->toBe($this->value[0]);

   $this->toHaveCount(31);
});

beforeEach(function () {
    $config = config('supabase');

    $this->databaseService = new SupabaseService($config['api_key'], $config['url']);

    Http::fake(["https://nwhfcbwwhkaeylyynngp.supabase.co/rest/v1/totals?select=price,price_eur,balance,created_at&limit=31&order=created_at.desc" => Http::response(
        File::get(base_path() . "/tests/Unit/responses/totals.json")
    )]);

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
    Http::fake(["https://nwhfcbwwhkaeylyynngp.supabase.co/rest/v1/balances?select=pool,price,price_eur,balance,parent,created_at&limit=450&order=created_at.desc" => Http::response(
        File::get(base_path() . "/tests/Unit/responses/tokens.json")
    )]);

    $tokens = $this->databaseService->getTokens();

    $weeklyTokens = $tokens->take(7);

    expect($tokens)->toHaveCount(30);
    expect($weeklyTokens->first())->toHaveCount(15);
    expect($weeklyTokens->last())->toHaveCount(15);
    expect($tokens->first()->first())->toHaveProperties(['pool', 'price', 'price_eur', 'balance', 'parent', 'created_at']);
    expect(Carbon::parse($tokens->first()->first()->created_at)->isSameDay(Carbon::parse(end($this->balances['dates']))))->toBeTrue();
    expect(Carbon::parse($tokens->values()->get(1)->last()->created_at)->isSameDay(Carbon::parse(prev($this->balances['dates']))))->toBeTrue();
});
