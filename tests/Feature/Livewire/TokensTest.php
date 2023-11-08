<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Tokens;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class TokensTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(Tokens::class)
            ->assertStatus(200);
    }
}
