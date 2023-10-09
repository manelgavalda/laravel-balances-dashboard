<?php

namespace App\Services;

use Carbon\Carbon;
use PHPSupabase\Service;

class DatabaseService
{
    const DAYS = 7;
    const NUMBER_OF_TOKENS = 15;
    const NUMBER_OF_BALANCES = 28;

    protected $service;

    function __construct($apiKey, $url)
    {
        $this->service = new Service($apiKey, $url);
    }

    public function getHistoricalBalances()
    {
        $balances = collect($this->executeHistoricalBalances())
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

    public function getTokens()
    {
        return collect($this->executeTokens())
            ->sortByDesc(fn ($token) => $token->total = $token->price * $token->balance)
            ->groupBy('created_at')->sortKeys();
    }

    protected function executeTokens()
    {
        return $this->execute('balances', [
            'order' => 'created_at.desc',
            'limit' => self::NUMBER_OF_TOKENS * self::DAYS,
            'select' => 'pool,price,price_eur,balance,parent,created_at'
        ]);
    }

    protected function executeHistoricalBalances()
    {
        return $this->execute('totals', [
            'order' => 'created_at.desc',
            'limit' => self::NUMBER_OF_BALANCES,
            'select' => 'price,price_eur,balance,created_at'
        ]);
    }

    protected function execute($table, $query) {
        return $this->service
            ->initializeDatabase($table)
            ->createCustomQuery($query)
            ->getResult();
    }
}
