<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_homepage_introduces_vodacka_30_in_czech(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Vodácká 30')
            ->assertSee('Po hřebenech Orlických hor')
            ->assertSee('Termín čeká na potvrzení pořadatelem.')
            ->assertSee('Závodní výsledky');
    }
}
