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
        return $this->getResult("balances?types=STANDARD", 4)[0]->amount->value;
    }

    public function getLatestTransactions()
    {
        return collect(
            $this->getResult('activities?size=100', 1)->activities
        )->map(fn($activity) => $this->parseActivity($activity));
    }

    protected function parseActivity($activity)
    {
        $date = Carbon::parse($activity->createdOn);

        $activity->month = $date->format('M');

        $activity->createdOn = $date->diffForHumans();

        $activity->primaryAmount = (float) $activity->primaryAmount;

        return $activity;
    }

    public function getMonthlySpending()
    {
        $spendings = [];

        $this->getLatestTransactions()->each(function ($activity) use (&$spendings) {
            if (!isset($spendings[$activity->month])) {
                $spendings[$activity->month] = 0;
            }

            $spendings[$activity->month] += $activity->primaryAmount;
        });

        array_pop($spendings);

        return $spendings;
    }

    protected function getResult($uri, $version)
    {
        return Http::withToken($this->apiToken)->get("https://api.transferwise.com/v{$version}/profiles/{$this->profileId}/{$uri}")->object();
    }
}
