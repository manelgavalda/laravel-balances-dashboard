<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Total;
use App\Models\Token;

class SupabaseService
{
    public function getTokens()
    {
        return Token::orderByDesc('created_at')->limit(450)->get()
            ->groupBy('created_at')->take(30)->map->keyBy('pool')->values()->toArray();
    }

    public function getHistoricalBalances()
    {
        $balances = Total::orderByDesc('created_at')->limit(31)->get()
           ->reverse()->values();

        return [
            'prices' => $balances->pluck('price')->toArray(),
            'ethereum' => $balances->pluck('balance')->toArray(),
            'btc_prices' => $balances->pluck('btc_price')->toArray(),
            'prices_eur' => $balances->pluck('price_eur')->toArray(),
            'btc_prices_eur' => $balances->pluck('btc_price_eur')->toArray(),
            'totals' => $balances->map(fn ($balance) => $balance->price * $balance->balance)->toArray(),
            'totals_eur' => $balances->map(fn ($balance) => $balance->price_eur * $balance->balance)->toArray(),
            'dates' => $balances->map(fn ($balance) => Carbon::parse($balance->created_at)->format('M d Y'))->toArray(),
            'bitcoin' => $balances->map(fn ($balance) => $balance->btc_price ? (($balance->price * $balance->balance) / $balance->btc_price) : null)->toArray()
        ];
    }
}
