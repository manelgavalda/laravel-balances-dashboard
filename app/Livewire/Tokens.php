<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\SupabaseService;

class Tokens extends Component
{
    public $tokens;
    public $balances;

    public function mount()
    {
        $this->tokens = (new SupabaseService(config('supabase.api_key'), config('supabase.url')))->getTokens();
        $this->balances = (new SupabaseService(config('supabase.api_key'), config('supabase.url')))->getHistoricalBalances();
    }
}
