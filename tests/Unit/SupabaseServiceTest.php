<?php

use Carbon\Carbon;
use App\Models\Total;
use App\Models\Balance;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

uses(DatabaseMigrations::class)->group('supabase');

expect()->extend('toBeParsed', function () {
   expect(reset($this->value))->toBe($this->value[0]);

   $this->toHaveCount(31);
});

beforeEach(function () {
    $this->databaseService = new SupabaseService;

    $totals = collect(json_decode(File::get(base_path() . "/tests/responses/totals.json")));

    $totals->each(fn($total) => Total::create(json_decode(json_encode($total), true)));

    $this->balances = $this->databaseService->getHistoricalBalances();
});

it('retrieves_the_historical_balances', function () {
    expect($dates = $this->balances['dates'])->toBeParsed();
    expect($totals = $this->balances['totals'])->toBeParsed();
    expect($prices = $this->balances['prices'])->toBeParsed();
    expect($ethereum = $this->balances['ethereum'])->toBeParsed();
    expect($pricesEur = $this->balances['prices_eur'])->toBeParsed();
    expect($totalsEur = $this->balances['totals_eur'])->toBeParsed();

    expect(end($totals))->toBe(end($ethereum) * end($prices));
    expect(end($totalsEur))->toBe(end($ethereum) * end($pricesEur));

    expect(end($dates))->toBe('Nov 08 2023');
    expect(Carbon::parse($dates[0])->lt(Carbon::createFromFormat('M d Y', end($dates))))->toBetrue();
});

it('retrieves_the_tokens', function () {
    $tokens = collect(json_decode(File::get(base_path() . "/tests/responses/tokens.json")));

    $tokens->each(fn($token) => Balance::create(json_decode(json_encode($token), true)));

    $tokens = $this->databaseService->getTokens();

    $weeklyTokens = $tokens->take(7);

    expect($tokens)->toHaveCount(30);
    expect($weeklyTokens->first())->toHaveCount(15);
    expect($weeklyTokens->last())->toHaveCount(15);

    expect($tokens->first()->first()->toArray())->toHaveKeys(['id', 'pool', 'price', 'price_eur', 'balance', 'parent', 'created_at', 'updated_at']);

    expect($tokens->first()->first()->created_at->isSameDay(Carbon::parse(end($this->balances['dates']))))->toBeTrue();
    expect($tokens->values()->get(1)->last()->created_at->isSameDay(Carbon::parse(prev($this->balances['dates']))))->toBeTrue();
});
