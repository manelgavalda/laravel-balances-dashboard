<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\Http;

class Tokens extends Component
{
    public $tokens;
    public $balances;

    public function mount()
    {
        $supabaseService = (new SupabaseService(config('supabase.api_key'), config('supabase.url')));

        $this->tokens = $supabaseService->getTokens();
        $this->balances = $supabaseService->getHistoricalBalances();

        $this->dispatch('tokens-loaded');
    }

    #[On('tokens-loaded')]
    public function reloadTokens()
    {
        $result = Http::get("https://nuxt-test-tau-eosin.vercel.app/api/balances?token=M9yrAdZIxvwQM2Lq9OxCixzh!QSpL=sqYF!ocTnw9OS6VRITwooEiCrSuc!-CVVd")->object();

        $tokens = $result->balances;

        $this->tokens->prepend(
            collect($tokens)->sortBy(fn ($token) => $token->price * $token->balance)
        );

        $this->balances['prices'][] = $result->ethereumPrice->usd;
        $this->balances['prices_eur'][] = $result->ethereumPrice->eur;
        $this->balances['totals'][] = collect($tokens)->sum(fn ($token) => $token->price * $token->balance);
        $this->balances['totals_eur'][] = collect($tokens)->sum(fn ($token) => $token->price_eur * $token->balance);
        $this->balances['ethereum'][] = collect($tokens)->sum(fn ($token) => $token->price * $token->balance / $result->ethereumPrice->usd);
    }
}
