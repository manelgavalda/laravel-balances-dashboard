<?php

namespace App\Services;

use Carbon\Carbon;
use PHPSupabase\Service;

class DatabaseService
{
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
            ->sortByDesc(fn ($token) => $token->price * $token->balance)
            ->groupBy('created_at');
    }

    protected function executeTokens()
    {
        return $this->execute('balances', [
            'select' => 'pool,price,price_eur,balance,parent,created_at',
            'limit' => 15 * 2,
            'order' => 'created_at.desc'
        ]);
    }

    protected function executeHistoricalBalances()
    {
        return $this->execute('totals', [
            'select' => 'price,price_eur,balance,created_at',
            'limit' => 28,
            'order' => 'created_at.desc'
        ]);
    }

    protected function execute($table, $query) {
        return $this->service
            ->initializeDatabase($table)
            ->createCustomQuery($query)
            ->getResult();
    }
}
