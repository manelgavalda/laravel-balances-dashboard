<?php

use Carbon\Carbon;
use App\Models\Total;
use App\Models\Balance;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\File;

expect()->extend('toBeParsed', function () {
   expect(reset($this->value))->toBe($this->value[0]);

   $this->toHaveCount(31);
});

beforeEach(function () {
    $this->databaseService = new SupabaseService;

    collect(json_decode(File::get(base_path() . "/tests/responses/totals.json")))
        ->each(fn($total) => Total::create(json_decode(json_encode($total), true)));

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

    expect(end($dates))->toBe('Nov 08 2024');
    expect(Carbon::parse($dates[0])->lt(Carbon::createFromFormat('M d Y', end($dates))))->toBetrue();
});

it('retrieves_the_tokens', function () {
    collect(json_decode(File::get(base_path() . "/tests/responses/tokens.json")))
        ->each(fn($token) => Balance::create(json_decode(json_encode($token), true)));

    $tokens = $this->databaseService->getTokens();

    expect($tokens)->toHaveCount(30);
    expect($tokens[0])->toHaveCount(3);
    expect($tokens[6])->toHaveCount(15);

    expect($tokens[0]['Token 3'])
        ->id->toBe(1)
        ->pool->toBe('Token 3')
        ->price->toBe(5.0)
        ->parent->toBeNull()
        ->balance->toBe(10.0)
        ->price_eur->toBe(4.0)
        ->created_at->toBe('2024-11-08T00:00:00.000000Z');

    expect(Carbon::parse($tokens[0]['Token 3']['created_at'])->isSameDay(Carbon::parse(end($this->balances['dates']))))->toBeTrue();
    expect(Carbon::parse($tokens[1]['Token 3']['created_at'])->isSameDay(Carbon::parse(prev($this->balances['dates']))))->toBeTrue();
});
