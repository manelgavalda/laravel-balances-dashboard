<?php

namespace App\Services;

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
        return $this->execute('totals', [
            'select' => 'price,balance,created_at',
            'limit' => 28,
            'order' => 'created_at.desc'
        ]);
    }

    protected function execute($table, $query) {
        return collect($this->service
            ->initializeDatabase($table)
            ->createCustomQuery($query)
            ->getResult())->reverse()->values();
    }
}
