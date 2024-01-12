<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Total;
use App\Models\Balance;

class SupabaseService
{
    public function getTokens()
    {
        return Balance::orderByDesc('created_at')->limit(450)->get()->map(function ($balance) {
            $balance->price = floatval($balance->price);
            $balance->balance = floatval($balance->balance);
            $balance->price_eur = floatval($balance->price_eur);

            return $balance;
        })->groupBy('created_at')->take(30)->map->keyBy('pool')->values()->toArray();
    }

    public function getHistoricalBalances()
    {
        $balances = Total::orderByDesc('created_at')->limit(31)->get()->map(function ($total) {
            $total->price = floatval($total->price);
            $total->balance = floatval($total->balance);
            $total->price_eur = floatval($total->price_eur);

            return $total;
        })->reverse()->values();

        return [
            'prices' => $balances->pluck('price')->toArray(),
            'ethereum' => $balances->pluck('balance')->toArray(),
            'prices_eur' => $balances->pluck('price_eur')->toArray(),
            'totals' => $balances->map(fn ($balance) => $balance->price * $balance->balance)->toArray(),
            'totals_eur' => $balances->map(fn ($balance) => $balance->price_eur * $balance->balance)->toArray(),
            'dates' => $balances->map(fn ($balance) => Carbon::parse($balance->created_at)->format('M d Y'))->toArray()
        ];
    }
}
