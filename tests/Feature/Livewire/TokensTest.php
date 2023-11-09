<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Tokens;

beforeEach(function () {
    config([
        'wise.profile_id' => 'fake_profile_id',
        'supabase.url' => 'https://fake-url.supabase.co'
    ]);

    fakeRequest('https://api.transferwise.com/v1/profiles/fake_profile_id/activities', 'latest_transactions');
    fakeRequest('https://api.transferwise.com/v4/profiles/fake_profile_id/balances?types=STANDARD', 'balances');
    fakeRequest('https://fake-url.supabase.co/rest/v1/totals?select=price,price_eur,balance,created_at&limit=31&order=created_at.desc', 'totals');
    fakeRequest('https://fake-url.supabase.co/rest/v1/balances?select=pool,price,price_eur,balance,parent,created_at&limit=450&order=created_at.desc', 'tokens');
});

it('exists_on_the_page', function() {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/')
         ->assertSeeLivewire(Tokens::class);
});

it('tokens_are_correctly_set', function () {
    Livewire::test(Tokens::class)
        ->assertViewHas('tokens');
});
