<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use LazilyRefreshDatabase;

    #[DataProvider('publicPageCases')]
    public function test_public_information_page_is_available(string $path, string $heading): void
    {
        $this->get($path)
            ->assertOk()
            ->assertSee($heading);
    }

    public function test_homepage_links_to_public_information_pages(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Termín se připravuje')
            ->assertSee(route('about'))
            ->assertSee(route('results'))
            ->assertSee(route('registration'));
    }

    public static function publicPageCases(): array
    {
        return [
            'race information' => ['/zavod', 'O závodu'],
            'regulations' => ['/propozice', 'Propozice'],
            'results' => ['/vysledky', 'Výsledky'],
            'registration' => ['/prihlasky', 'Přihlášky'],
            'contact' => ['/kontakt', 'Kontakt'],
        ];
    }
}
