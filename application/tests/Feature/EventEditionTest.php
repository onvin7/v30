<?php

namespace Tests\Feature;

use App\Enums\RaceEditionStatus;
use App\Models\Category;
use App\Models\Event;
use App\Models\RaceEdition;
use Database\Seeders\Vodacka30Seeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class EventEditionTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_categories_belong_to_their_race_edition_and_event(): void
    {
        $event = Event::factory()->create();
        $edition = RaceEdition::factory()->for($event)->create();
        $category = Category::factory()->for($edition)->create();

        $this->assertTrue($edition->event->is($event));
        $this->assertTrue($category->raceEdition->is($edition));
    }

    public function test_an_event_cannot_have_two_editions_for_the_same_year(): void
    {
        $event = Event::factory()->create();
        RaceEdition::factory()->for($event)->create(['year' => 2026]);

        $this->expectException(QueryException::class);

        RaceEdition::factory()->for($event)->create(['year' => 2026]);
    }

    public function test_homepage_reads_edition_and_category_values_from_database(): void
    {
        $event = Event::factory()->create([
            'name' => 'Testovací Vodácká 30',
            'slug' => 'vodacka-30',
            'summary' => 'Syntetický popis závodu z testovací databáze.',
        ]);

        $archiveEdition = RaceEdition::factory()->for($event)->create([
            'year' => 2026,
            'is_current' => false,
            'status' => RaceEditionStatus::Archived,
            'venue' => 'Testovací Bedřichovka',
        ]);
        Category::factory()->for($archiveEdition)->create([
            'code' => 'test-main',
            'name' => 'Testovací hlavní trať',
            'distance_km' => 30,
        ]);
        Category::factory()->for($archiveEdition)->create([
            'code' => 'test-kids-3',
            'name' => 'Testovací dětská trať 3 km',
            'distance_km' => 3,
        ]);
        Category::factory()->for($archiveEdition)->create([
            'code' => 'test-kids-7',
            'name' => 'Testovací dětská trať 7 km',
            'distance_km' => 7,
        ]);
        RaceEdition::factory()->for($event)->create([
            'year' => 2027,
            'event_date' => '2027-01-16',
            'date_confirmed' => false,
            'is_current' => true,
            'status' => RaceEditionStatus::Planned,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Testovací Vodácká 30')
            ->assertSee('Syntetický popis závodu z testovací databáze.')
            ->assertSee('16. ledna')
            ->assertSee('Termín čeká na potvrzení pořadatelem.')
            ->assertSee('30')
            ->assertSee('3 / 7')
            ->assertSee('Testovací Bedřichovka');
    }

    public function test_public_seed_is_repeatable_and_leaves_2027_categories_unconfirmed(): void
    {
        $this->seed(Vodacka30Seeder::class);
        $this->seed(Vodacka30Seeder::class);

        $event = Event::query()->where('slug', 'vodacka-30')->firstOrFail();
        $archiveEdition = RaceEdition::query()->whereBelongsTo($event)->where('year', 2026)->firstOrFail();
        $plannedEdition = RaceEdition::query()->whereBelongsTo($event)->where('year', 2027)->firstOrFail();

        $this->assertSame(1, Event::query()->count());
        $this->assertSame(11, $archiveEdition->categories()->count());
        $this->assertFalse($plannedEdition->date_confirmed);
        $this->assertSame(0, $plannedEdition->categories()->count());
    }
}
