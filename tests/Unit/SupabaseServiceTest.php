<?php

use Carbon\Carbon;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\File;

function createEntries($type) {
    $file = str()->plural($type);
    $class = app()->make('App\Models\\' . str()->ucfirst($type));

    collect(json_decode(File::get(base_path() . "/tests/responses/{$file}.json")))
        ->each(fn($entry) => $class::create(json_decode(json_encode($entry), true)));
}

beforeEach(function () {
    createEntries('total');

    $this->databaseService = new SupabaseService;

    $this->balances = $this->databaseService->getHistoricalBalances();
});

it('retrieves_the_historical_balances', function () {
    $dates = $this->balances['dates'];

    expect(end($dates))->toBe('Nov 08 2024');
    expect(end($this->balances['prices']))->toBe(2000.0);
    expect(end($this->balances['bitcoin']))->toBe(20000.0);
    expect(end($this->balances['ethereum']))->toBe(2000.0);
    expect(end($this->balances['totals']))->toBe(4000000.0);
    expect(end($this->balances['prices_eur']))->toBe(1900.0);
    expect(end($this->balances['totals_eur']))->toBe(3800000.0);

    expect(Carbon::parse($dates[0])->lt(Carbon::createFromFormat('M d Y', end($dates))))->toBetrue();
});

it('retrieves_the_tokens', function () {
    createEntries('token');

    $tokens = $this->databaseService->getTokens();

    expect($tokens)->toHaveCount(30);
    expect($tokens[0])->toHaveCount(3);
    expect($tokens[6])->toHaveCount(15);

    expect($tokens[0]['Token 3'])
        ->id->toBe(1)
        ->price->toBe(5.0)
        ->parent->toBeNull()
        ->balance->toBe(10.0)
        ->price_eur->toBe(4.0)
        ->pool->toBe('Token 3')
        ->created_at->toBe('2024-11-08T00:00:00.000000Z');

    expect(Carbon::parse($tokens[0]['Token 3']['created_at'])->isSameDay(Carbon::parse(end($this->balances['dates']))))->toBeTrue();
    expect(Carbon::parse($tokens[1]['Token 3']['created_at'])->isSameDay(Carbon::parse(prev($this->balances['dates']))))->toBeTrue();
});
