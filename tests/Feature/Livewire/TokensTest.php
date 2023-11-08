<?php

use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use App\Livewire\Tokens;

it('tokens_are_correctly_set', function () {
    // Http::fake([
    //     'https://nwhfcbwwhkaeylyynngp.supabase.co'
    // ]);
    // Http::fake([
    //     '*'
    //     // 'https://nwhfcbwwhkaeylyynngp.supabase.co/rest/v1/balances?select=pool,price,price_eur,balance,parent,created_at&limit=450&order=created_at.desc'
    // ]);

    // Livewire::test(Tokens::class);
    //     ->assertSet('tokens', collect([
    //         '2023-11-08T04:07:13.621314+00:00' => collect([
    //             (object) [
    //                 'pool' => 'OP',
    //                 'balance' => 103.359,
    //                 'price' => 1.51,
    //                 'price_eur' => 1.41,
    //                 'parent' => null,
    //                 'created_at' => '2023-11-08T04:07:13.621314+00:00'
    //             ],
    //             (object) [
    //                 'pool' => 'fxETH/ETH',
    //                 'balance' => 17.3519,
    //                 'price' => 1884.82,
    //                 'price_eur' => 1763.24,
    //                 'parent' => null,
    //                 'created_at' => '2023-11-08T04:07:13.621314+00:00'
    //             ]
    //         ])
    //     ]));
});
