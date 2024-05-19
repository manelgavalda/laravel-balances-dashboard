<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Price;
use App\Models\Total;
use App\Models\Token;

class SupabaseService
{
    public function getTokens()
    {
        return Token::orderByDesc('created_at')->limit(450)->get()
            ->groupBy('created_at')->take(30)->map->keyBy('name')->values();
    }

    public function getTotals()
    {
        $balances = Total::orderByDesc('created_at')->limit(31)->get()
           ->reverse()->values();

        return [
            'debts' => $balances->pluck('debt'),
            'totals' => $balances->pluck('total'),
            'dates' => $balances->map(fn ($balance) => Carbon::parse($balance->created_at)->format('M d Y')),
        ];
    }

    public function getPrices()
    {
        $prices = Price::orderByDesc('created_at')->limit(31)->get()
           ->reverse()->values();

        return [
            'eth' => $prices->pluck('eth'),
            'btc' => $prices->pluck('btc'),
            'eur' => $prices->pluck('eur'),
            'dates' => $prices->map(fn ($price) => Carbon::parse($price->created_at)->format('M d Y')),
        ];
    }
}
