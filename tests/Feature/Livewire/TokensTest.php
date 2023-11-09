<?php

use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use App\Livewire\Tokens;

it('tokens_are_correctly_set', function () {
    Http::fake(["https://nwhfcbwwhkaeylyynngp.supabase.co/rest/v1/totals?select=price,price_eur,balance,created_at&limit=31&order=created_at.desc" => Http::response(
        File::get(base_path() . "/tests/Unit/responses/totals.json")
    )]);

    Http::fake(["https://nwhfcbwwhkaeylyynngp.supabase.co/rest/v1/balances?select=pool,price,price_eur,balance,parent,created_at&limit=450&order=created_at.desc" => Http::response(
        File::get(base_path() . "/tests/Unit/responses/tokens.json")
    )]);

    Livewire::test(Tokens::class);
});
