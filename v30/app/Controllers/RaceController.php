<?php

namespace App\Controllers;

class RaceController
{
    public function propositions()
    {
        // Mock data – později nahradí Model / databáze.
        // Údaje níže popisují poslední zveřejněný ročník 2026 (17. ledna 2026).
        $poradatele = [
            ['role' => 'Pořadatel',       'jmeno' => 'TJ KVT Pardubice'],
            ['role' => 'Ředitel závodu',  'jmeno' => 'Aleš Křivka'],
            ['role' => 'Hlavní rozhodčí', 'jmeno' => 'Jirka Roleček'],
        ];

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

        $kategorie = [
            ['kod' => 'children-under-6',  'nazev' => 'Chlapci a dívky do 6 let',          'trat' => '3 km',  'styl' => 'klasicky', 'cena' => '200 Kč'],
            ['kod' => 'children-7-10',     'nazev' => 'Chlapci a dívky od 7 do 10 let',     'trat' => '7 km',  'styl' => 'volně',    'cena' => '200 Kč'],
            ['kod' => 'children-11-14',    'nazev' => 'Chlapci a dívky od 11 do 14 let',    'trat' => '7 km',  'styl' => 'volně',    'cena' => '200 Kč'],
            ['kod' => 'children-pro-7-14', 'nazev' => 'Chlapci a dívky PRO od 7 do 14 let', 'trat' => '7 km',  'styl' => 'volně',    'cena' => '200 Kč'],
            ['kod' => 'juniors',           'nazev' => 'Junioři',                            'trat' => '30 km', 'styl' => 'volně',    'cena' => '300 / 400 Kč'],
            ['kod' => 'men-pro',           'nazev' => 'Muži PRO',                           'trat' => '30 km', 'styl' => 'volně',    'cena' => '300 / 400 Kč'],
            ['kod' => 'women-pro',         'nazev' => 'Ženy PRO',                           'trat' => '30 km', 'styl' => 'volně',    'cena' => '300 / 400 Kč'],
            ['kod' => 'men-classic',       'nazev' => 'Muži KLASICI',                       'trat' => '30 km', 'styl' => 'klasicky', 'cena' => '300 / 400 Kč'],
            ['kod' => 'women-classic',     'nazev' => 'Ženy KLASIČKY',                      'trat' => '30 km', 'styl' => 'klasicky', 'cena' => '300 / 400 Kč'],
            ['kod' => 'pro-for-fun',       'nazev' => 'Muži a ženy PRO RADOST',             'trat' => '30 km', 'styl' => 'volně',    'cena' => '300 / 400 Kč'],
        ];

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

        $startovne = [
            'Dětské kategorie: 200 Kč.',
            'Dospělé kategorie při včasné bezhotovostní platbě (do 15. ledna 2026): 300 Kč.',
            'Platba na místě: 400 Kč.',
            'Závod končí v 17:00 – časový limit trati.',
        ];

        $title = 'Propozice a trať | Vodácká 30';
        $view  = APP_ROOT . '/app/Views/propositions.php';

        require APP_ROOT . '/app/Views/layout.php';
    }
}
