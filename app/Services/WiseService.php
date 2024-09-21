<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class WiseService
{
    function __construct(
        protected $apiToken,
        protected $profileId
    ) {}

    public function getBalance()
    {
        return $this->getResult(
            "balances?types=STANDARD", 4
        )[0]->amount->value;
    }

    public function getLatestTransactions()
    {
        return collect(
            $this->getResult('activities?size=100', 1)->activities
        )->map(function ($activity) {
            $activity->createdOn = Carbon::parse($activity->createdOn)->diffForHumans();

            return $activity;
        });
    }

    protected function getResult($uri, $version)
    {
        return Http::withToken($this->apiToken)->get("https://api.transferwise.com/v{$version}/profiles/{$this->profileId}/{$uri}")->object();
    }
}
