<?php

use App\Models\User;
use Livewire\Livewire;
use Carbon\CarbonPeriod;
use App\Livewire\Tokens;

beforeEach(function () {
    config([
        'wise.profile_id' => 'fake_profile_id',
        'supabase.url' => 'https://fake-url.supabase.co',
    ]);

    fakeRequest('https://api.transferwise.com/v1/profiles/fake_profile_id/activities', 'latest_transactions');
    fakeRequest('https://api.transferwise.com/v4/profiles/fake_profile_id/balances?types=STANDARD', 'balances');
    fakeRequest('https://fake-url.supabase.co/rest/v1/totals?select=price,price_eur,balance,created_at&limit=31&order=created_at.desc', 'totals');
    fakeRequest('https://fake-url.supabase.co/rest/v1/balances?select=pool,price,price_eur,balance,parent,created_at&limit=450&order=created_at.desc', 'tokens');
});

it('exists_on_the_page', function() {
    $this->actingAs(
        User::factory()->create()
    );

    $this->get('/')->assertSeeLivewire(Tokens::class);
});

it('refreshes_the_tokens_when_the_event_is_called', function () {
    $dates = collect(
        CarbonPeriod::create('Oct 09', 'Nov 08')
    )->map->format('M d')->toArray();

    config(['tokens.api_url' => 'https://fake-tokens-url.com']);

    fakeRequest('https://fake-tokens-url.com', 'new_tokens');

    Livewire::test(Tokens::class)
        ->assertSet('tokens',  fn ($tokens) => $tokens->first()->first() == (object) [
            'pool' => 'Token 4',
            'price' => 2000000,
            'price_eur' => 1800000,
            'balance' => 0,
            "parent" => null,
            "created_at" => "2023-11-08T00:00:00.000000+00:00"
        ])->assertSet('balances', fn ($balances) =>
            $balances['dates'] == $dates && end($balances['prices']) == 1884.82
        )->assertDispatched('tokens-loaded')
         ->dispatch('tokens-loaded')
         ->assertSet('tokens', fn ($tokens) => $tokens->first()->first() == (object) [
             'pool' => 'Token 4',
             'price' => 1000000,
             'price_eur' => 900000,
             'balance' => 0
         ] && count($tokens) == 30)->assertSet('balances', fn ($balances) =>
            end($balances['prices']) == 2074.88 &&
            end($balances['prices_eur']) == 1942.25 &&
            round(end($balances['totals']), 7) == 1150032.9456886 &&
            round(end($balances['totals_eur']), 7) == 1076552.8135102 &&
            round(end($balances['ethereum']), 7) == 554.2647988
         );
});
