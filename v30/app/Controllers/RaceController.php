<?php

namespace App\Controllers;

class RaceController
{
    public function propositions()
    {
        // Mock data – později nahradí Model / databáze.
        // Údaje níže popisují poslední zveřejněný ročník 2026 (17. ledna 2026);
        // termín dalšího ročníku je potvrzený na 16. ledna 2027 (viz config/zavod.php).
        $poradatele = zavod()['poradatele'];

        $program = [
            ['cas' => '9:00',  'polozka' => 'Registrace dětských kategorií'],
            ['cas' => '10:00', 'polozka' => 'Start dětí do 6 let (3 km klasicky)'],
            ['cas' => '10:00', 'polozka' => 'Registrace dospělých kategorií'],
            ['cas' => '10:30', 'polozka' => 'Start dětí 7–10, 11–14 a PRO (7 km volně)'],
            ['cas' => '11:30', 'polozka' => 'Vyhlášení dětských kategorií'],
            ['cas' => '11:45', 'polozka' => 'Výklad trati pro dospělé'],
            ['cas' => '12:00', 'polozka' => 'Start hlavního závodu na 30 km'],
            ['cas' => '15:00', 'polozka' => 'Vyhlášení kategorií PRO'],
            ['cas' => '17:00', 'polozka' => 'Vyhlášení kategorií PRO RADOST a konec závodu'],
            ['cas' => '18:00', 'polozka' => 'Večeře'],
            ['cas' => '19:00', 'polozka' => 'Promítání'],
            ['cas' => '20:00', 'polozka' => 'Pohoda'],
        ];

        // Kategorie se berou z config/zavod.php – propozice i přihláška tak nabízejí
        // přesně stejnou sadu a stejné ceny.
        $kategorie = [];
        foreach (zavod()['kategorie'] as $kod => $kat) {
            $kategorie[] = [
                'kod'           => $kod,
                'nazev'         => $kat['nazev'],
                'trat'          => $kat['trat'],
                'styl'          => $kat['styl'],
                'cena'          => $kat['cena'] . ' Kč',
                'cena_na_miste' => $kat['cena_na_miste'] . ' Kč',
            ];
        }

        // Trasy závodu a mapové podklady. Dětské tratě zatím používají stejný podklad jako hlavní trať.
        $trasy = [
            [
                'nazev' => 'Hlavní trať',
                'trat'  => '30 km',
                'styl'  => 'volně nebo klasicky',
                'popis' => 'Okruh pro juniory a dospělé kategorie. Start a cíl u chaty Bedřichovka v Orlickém Záhoří, kontrolní bod na Pěticestí u Horské služby.',
                'mapa'  => 'https://mapy.com/s/calefedoho',
            ],
            [
                'nazev' => 'Dětská trať',
                'trat'  => '7 km',
                'styl'  => 'volně',
                'popis' => 'Trať pro děti 7–10 a 11–14 let a kategorii dětí PRO.',
                'mapa'  => 'https://mapy.com/s/calefedoho',
            ],
            [
                'nazev' => 'Dětská trať',
                'trat'  => '3 km',
                'styl'  => 'klasicky',
                'popis' => 'Nejlehčí trať pro nejmenší závodníky do 6 let.',
                'mapa'  => 'https://mapy.com/s/calefedoho',
            ],
        ];

        // Startovné se skládá z cen v configu, aby se čísla neopisovala na třech místech.
        $detske = zavod()['kategorie']['children-under-6']['cena'];
        $vcasna = zavod()['kategorie']['men-pro']['cena'];
        $naMiste = zavod()['kategorie']['men-pro']['cena_na_miste'];

        $startovne = [
            sprintf('Dětské kategorie na 3 a 7 km: %d Kč.', $detske),
            sprintf('Dospělé kategorie při včasné bezhotovostní platbě (přihláška a platba do %s): %d Kč.', zavod()['rocnik']['uzavierka'], $vcasna),
            sprintf('Platba na místě startu: %d Kč.', $naMiste),
            'Závod končí v 17:00 – časový limit trati.',
        ];

        $title       = 'Propozice a trať | Vodácká 30';
        $description = 'Propozice Vodácké 30: program závodního dne, vypsané kategorie, tratě 30, 7 a 3 km, startovné a mapové podklady.';
        $view        = APP_ROOT . '/app/Views/propositions.php';

        require APP_ROOT . '/app/Views/layout.php';
    }
}
