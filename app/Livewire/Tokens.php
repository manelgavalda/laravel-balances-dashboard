<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\DatabaseService;

class Tokens extends Component
{
    public $tokens;
    public $balances;

    public function mount()
    {
        $this->tokens = (new DatabaseService(config('supabase.api_key'), config('supabase.url')))->getTokens();
        $this->balances = (new DatabaseService(config('supabase.api_key'), config('supabase.url')))->getHistoricalBalances();
    }

    public function refreshTokens()
    {
        $this->tokens->first()->get(1)->price = 100;
        $this->tokens->first()->get(1)->price_eur = 90;
        $this->tokens->first()->get(1)->balance = 1000;
    }

    public function render()
    {
        return view('livewire.tokens');
    }
}
