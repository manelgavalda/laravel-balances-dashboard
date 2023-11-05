<?php

use App\Services\WiseService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

uses()->group('wise');

beforeEach(function () {
    $this->wiseService = new WiseService('fake_api_token', 'fake_profile_id');
});

afterEach(function () {
    Http::assertSent(fn (Request $request) =>
        $request->hasHeader('Authorization', 'Bearer fake_api_token')
    );
});

function fakeRequest($uri, $version, $file) {
    Http::fake(["https://api.transferwise.com/v{$version}/profiles/fake_profile_id/{$uri}" => Http::response(
        File::get(base_path() . "/tests/Unit/responses/{$file}.json")
    )]);
}

test('you_can_get_your_balance', function () {
    fakeRequest('balances?types=STANDARD', 4, 'balances');

    expect($this->wiseService->getBalance())->toBe(1000.0);
});

test('you_can_get_your_latest_transactions', function () {
    fakeRequest('activities', 1, 'latest_transactions');

    expect($transactions = $this->wiseService->getLatestTransactions())
        ->toBeCollection()->toHaveCount(10);

    expect($transaction = $transactions->first())->toBeObject();

    expect($transaction->status)->toBe('COMPLETED');
    expect($transaction->type)->toBe('CARD_PAYMENT');
    expect($transaction->primaryAmount)->toBe('12 USD');
    expect($transaction->secondaryAmount)->toBe('10 EUR');
    expect($transaction->createdOn)->toBe('2023-01-01T00:00:00.000Z');
    expect($transaction->title)->toBe('<strong>Test Transaction</strong>');
});
