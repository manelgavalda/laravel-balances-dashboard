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
            ->reverse()
            ->values();

        return [
            'prices' => $balances->pluck('price')->all(),
            'ethereum' => $balances->pluck('balance')->all(),
            'totals' => $balances->map(fn ($balance) => $balance->price * $balance->balance)->all(),
            'dates' => $balances->map(fn ($balance) => Carbon::parse($balance->created_at)->format('M d'))->all()
        ];
    }

    public function getTokens()
    {
        return collect($this->executeTokens())
            ->each(fn ($token) => $token->rewards = []);
    }

    protected function executeTokens()
    {
        return $this->execute('balances', [
            'select' => 'pool,price,balance,parent,created_at',
            'limit' => 15,
            'order' => 'created_at.desc'
        ]);
    }

    protected function executeHistoricalBalances()
    {
        return $this->execute('totals', [
            'select' => 'price,balance,created_at',
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
