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
            'debt' => $balances->pluck('debt')->toArray(),
            'prices' => $balances->pluck('price')->toArray(), // eth_price
            'ethereum' => $balances->pluck('balance')->toArray(), // total_usd / eth_price
            'btc_prices' => $balances->pluck('btc_price')->toArray(), // btc_price
            'prices_eur' => $balances->pluck('price_eur')->toArray(), // eth_price * eur_usd
            'btc_prices_eur' => $balances->pluck('btc_price_eur')->toArray(), // btc_price * eur_usd
            'totals' => $balances->map(fn ($balance) => $balance->price * $balance->balance)->toArray(), // total_usd
            'totals_eur' => $balances->map(fn ($balance) => $balance->price_eur * $balance->balance)->toArray(), // total_usd * eur_usd
            'dates' => $balances->map(fn ($balance) => Carbon::parse($balance->created_at)->format('M d Y'))->toArray(), // created_at
            'bitcoin' => $balances->map(fn ($balance) => $balance->btc_price ? (($balance->price * $balance->balance) / $balance->btc_price) : null)->toArray() // total_usd / btc_price
        ];
    }
}
