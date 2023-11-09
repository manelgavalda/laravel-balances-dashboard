<?php

use Carbon\Carbon;
use App\Services\WiseService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

uses()->group('wise');

beforeEach(fn () =>
    $this->wiseService = new WiseService('fake_api_token', 'fake_profile_id')
);

afterEach(fn () => Http::assertSent(fn (Request $request) =>
    $request->hasHeader('Authorization', 'Bearer fake_api_token')
));

test('you_can_get_your_balance', function () {
    fakeRequest('https://api.transferwise.com/v4/profiles/fake_profile_id/balances?types=STANDARD', 'balances');

    expect($this->wiseService->getBalance())->toBe(1000.0);
});

test('you_can_get_your_latest_transactions', function () {
    Carbon::setTestNow('2023-01-02');

    fakeRequest('https://api.transferwise.com/v1/profiles/fake_profile_id/activities', 'latest_transactions');

    expect($transactions = $this->wiseService->getLatestTransactions())
        ->toBeCollection()->toHaveCount(10);

    expect($transaction = $transactions->first())->toBeObject();

    expect($transaction->status)->toBe('COMPLETED');
    expect($transaction->type)->toBe('CARD_PAYMENT');
    expect($transaction->createdOn)->toBe('1 day ago');
    expect($transaction->primaryAmount)->toBe('12 USD');
    expect($transaction->secondaryAmount)->toBe('10 EUR');
    expect($transaction->title)->toBe('<strong>Test Transaction</strong>');
});
