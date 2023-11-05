<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WiseService
{
    function __construct(
        protected $apiToken,
        protected $profileId
    ) {}

    public function getBalance()
    {
        return Http::withToken($this->apiToken)->get(
            "https://api.transferwise.com/v4/profiles/{$this->profileId}/balances",
            ['types' => 'STANDARD']
        )->object()[0]->amount->value;
    }
}
