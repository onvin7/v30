<?php

namespace Database\Seeders;

use App\Enums\RaceEditionStatus;
use App\Models\Category;
use App\Models\Event;
use App\Models\RaceEdition;
use Illuminate\Database\Seeder;

class Vodacka30Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $event = Event::query()->updateOrCreate(
            ['slug' => 'vodacka-30'],
            [
                'name' => 'Vodácká 30',
                'summary' => 'Běžecký lyžařský závod po hřebenech Orlických hor pořádaný TJ KVT Pardubice.',
                'status' => 'active',
            ],
        );

        $archiveEdition = RaceEdition::query()->updateOrCreate(
            ['event_id' => $event->id, 'year' => 2026],
            [
                'event_date' => '2026-01-17',
                'date_confirmed' => true,
                'is_current' => false,
                'status' => RaceEditionStatus::Archived,
                'venue' => 'Chata Bedřichovka, Orlické Záhoří',
                'course_summary' => 'Hlavní trať 30 km; dětské tratě 3 km klasicky a 7 km volně. Kontrolní bod a občerstvení na Pěticestí.',
                'fee_notes' => 'Historické startovné 2026: 200 Kč dětské kategorie; 300 Kč při včasné bezhotovostní platbě; 400 Kč na místě. Nepoužívat pro další ročník.',
            ],
        );

        RaceEdition::query()->updateOrCreate(
            ['event_id' => $event->id, 'year' => 2027],
            [
                'event_date' => '2027-01-16',
                'date_confirmed' => false,
                'is_current' => true,
                'status' => RaceEditionStatus::Planned,
                'venue' => null,
                'course_summary' => null,
                'fee_notes' => null,
            ],
        );

        $categories = [
            ['code' => 'children-under-6', 'name' => 'Chlapci a dívky do 6 let', 'distance_km' => 3, 'technique' => 'classic', 'entry_fee_cents' => 20000],
            ['code' => 'children-7-10', 'name' => 'Chlapci a dívky od 7 do 10 let', 'distance_km' => 7, 'technique' => 'free', 'entry_fee_cents' => 20000],
            ['code' => 'children-11-14', 'name' => 'Chlapci a dívky od 11 do 14 let', 'distance_km' => 7, 'technique' => 'free', 'entry_fee_cents' => 20000],
            ['code' => 'children-pro-7-14', 'name' => 'Chlapci a dívky PRO od 7 do 14 let', 'distance_km' => 7, 'technique' => 'free', 'entry_fee_cents' => 20000],
            ['code' => 'juniors', 'name' => 'Junioři', 'distance_km' => 30, 'technique' => null, 'entry_fee_cents' => null],
            ['code' => 'men-pro', 'name' => 'Muži PRO', 'distance_km' => 30, 'technique' => 'free', 'entry_fee_cents' => null],
            ['code' => 'women-pro', 'name' => 'Ženy PRO', 'distance_km' => 30, 'technique' => 'free', 'entry_fee_cents' => null],
            ['code' => 'men-classic', 'name' => 'Muži KLASICI', 'distance_km' => 30, 'technique' => 'classic', 'entry_fee_cents' => null],
            ['code' => 'women-classic', 'name' => 'Ženy KLASIČKY', 'distance_km' => 30, 'technique' => 'classic', 'entry_fee_cents' => null],
            ['code' => 'pro-for-fun', 'name' => 'Muži a ženy PRO RADOST', 'distance_km' => 30, 'technique' => null, 'entry_fee_cents' => null],
            ['code' => 'veterans', 'name' => 'Veteráni/veteránky', 'distance_km' => 30, 'technique' => null, 'entry_fee_cents' => null],
        ];

        foreach ($categories as $index => $attributes) {
            Category::query()->updateOrCreate(
                ['race_edition_id' => $archiveEdition->id, 'code' => $attributes['code']],
                [...$attributes, 'sort_order' => $index + 1],
            );
        }
    }
}
