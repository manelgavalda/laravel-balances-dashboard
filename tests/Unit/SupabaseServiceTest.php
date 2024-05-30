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

    $this->balances = $this->databaseService->getTotals();
});

it('retrieves_the_historical_balances', function () {
    $dates = $this->balances['dates'];

    expect($dates->last())->toBe('Nov 08 2024');
    expect($this->balances['debts']->last())->toBe(5000.0);
    expect($this->balances['totals']->last())->toBe(2000.0);
    expect($this->balances['totals_with_debt']->last())->toBe(-3000.0);

    expect(Carbon::parse($dates->first())->lt(Carbon::createFromFormat('M d Y', $dates->last())))->toBetrue();
});

it('retrieves_the_historical_prices', function () {
    createEntries('price');

    $this->prices = $this->databaseService->getPrices();

    $dates = $this->prices['dates'];

    expect($dates->last())->toBe('Nov 08 2024');
    expect($this->prices['eth']->last())->toBe(2000.0);
    expect($this->prices['eur']->last())->toBe(1900.0);
    expect($this->prices['btc']->last())->toBe(20000.0);

    expect(Carbon::parse($dates->first())->lt(Carbon::createFromFormat('M d Y', $dates->last())))->toBetrue();
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
        ->balance->toBe(10.0)
        ->name->toBe('Token 3')
        ->created_at->toString()->toBe('Fri Nov 08 2024 00:00:00 GMT+0000');

    expect(Carbon::parse($tokens[0]['Token 3']['created_at'])->isSameDay(Carbon::parse($this->balances['dates']->pop())))->toBeTrue();
    expect(Carbon::parse($tokens[1]['Token 3']['created_at'])->isSameDay(Carbon::parse($this->balances['dates']->last())))->toBeTrue();
});
