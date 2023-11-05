<?php

use App\Services\WiseService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

uses()->group('wise');

test('you_can_get_your_balance', function () {
    Http::fake([
        "https://api.transferwise.com/v4/profiles/fake_profile_id/balances?types=STANDARD" => Http::response(
            '[{"id":1,"currency":"EUR","amount":{"value":1000.00,"currency":"EUR"},"reservedAmount":{"value":0.00,"currency":"EUR"},"cashAmount":{"value":1000.00,"currency":"EUR"},"totalWorth":{"value":1000.00,"currency":"EUR"},"type":"STANDARD","name":null,"icon":null,"investmentState":"NOT_INVESTED","creationTime":"1999-07-28T10:51:11.222788Z","modificationTime":"1999-11-05T05:19:00.535398Z","visible":true,"primary":true,"groupId":null}]',
        )
    ]);

    $wiseService = new WiseService('fake_api_token', 'fake_profile_id');

    expect($wiseService->getBalance())->toBe(1000.0);

    Http::assertSent(fn (Request $request) =>
        $request->hasHeader('Authorization', 'Bearer fake_api_token') && $request['types'] == 'STANDARD'
    );
});

