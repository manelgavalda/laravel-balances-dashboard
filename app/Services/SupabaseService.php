<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class SupabaseService
{
    function __construct(
        protected $apiKey,
        protected $url
    ) {}

    public function getTokens()
    {
        return $this->getResult('balances?select=pool,price,price_eur,balance,parent,created_at&limit=450&order=created_at.desc')
            ->groupBy('created_at')->take(30);
    }

    public function getHistoricalBalances()
    {
        $balances = $this->getResult('totals?select=price,price_eur,balance,created_at&limit=31&order=created_at.desc')
            ->reverse()->values();

        return [
            'prices' => $balances->pluck('price')->toArray(),
            'ethereum' => $balances->pluck('balance')->toArray(),
            'prices_eur' => $balances->pluck('price_eur')->toArray(),
            'totals' => $balances->map(fn ($balance) => $balance->price * $balance->balance)->toArray(),
            'totals_eur' => $balances->map(fn ($balance) => $balance->price_eur * $balance->balance)->toArray(),
            'dates' => $balances->map(fn ($balance) => Carbon::parse($balance->created_at)->format('M d'))->toArray()
        ];
    }

    protected function getResult($uri)
    {
        return collect(Http::withHeaders(['apikey' => $this->apiKey])->get("{$this->url}/rest/v1/{$uri}")->object());
    }
}
