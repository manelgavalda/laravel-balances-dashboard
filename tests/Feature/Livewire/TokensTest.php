<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Tokens;
use App\Services\SupabaseService;

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

it('dispatches_an_event_when_tokens_are_loaded', function () {
    $supabaseService = new SupabaseService('fake-api-key', 'https://fake-url.supabase.co');

    Livewire::test(Tokens::class)
        ->assertDispatched('tokens-loaded')
        ->assertSet('tokens', $supabaseService->getTokens())
        ->assertSet('balances', $supabaseService->getHistoricalBalances());
});

it('tokens_are_refreshed_when_the_event_is_called', function () {
    config(['tokens.api_url' => 'https://fake-tokens-url.com']);

    fakeRequest('https://fake-tokens-url.com', 'new_tokens');

    Livewire::test(Tokens::class)
        ->assertViewHas('tokens', function ($tokens) {
            expect(count($tokens))->toBe(30);

            return true;
        })
        ->assertViewHas('balances', function ($balances) {
            expect(count($balances['prices']))->toBe(31);
            expect(count($balances['totals']))->toBe(31);
            expect(count($balances['ethereum']))->toBe(31);
            expect(count($balances['prices_eur']))->toBe(31);
            expect(count($balances['totals_eur']))->toBe(31);

            return true;
        })
        ->dispatch('tokens-loaded')
        ->assertViewHas('tokens', function ($tokens) {
            expect(count($tokens))->toBe(31);

            return true;
        })
        ->assertViewHas('balances', function ($balances) {
            expect(count($balances['prices']))->toBe(32);
            expect(count($balances['totals']))->toBe(32);
            expect(count($balances['ethereum']))->toBe(32);
            expect(count($balances['prices_eur']))->toBe(32);
            expect(count($balances['totals_eur']))->toBe(32);

            return true;
        });
});
