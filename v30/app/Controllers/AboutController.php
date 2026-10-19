<?php

namespace App\Controllers;

class AboutController
{
    public function index()
    {
        // Mock data – údaje z posledního zveřejněného ročníku 2026.
        $fakta = [
            ['label' => 'Datum ročníku 2026',  'hodnota' => '17. ledna 2026'],
            ['label' => 'Pořadatel',           'hodnota' => 'TJ KVT Pardubice'],
            ['label' => 'Ředitel závodu',      'hodnota' => 'Aleš Křivka'],
            ['label' => 'Hlavní rozhodčí',     'hodnota' => 'Jirka Roleček'],
            ['label' => 'Místo startu a cíle', 'hodnota' => 'Bedřichovka, Orlické Záhoří'],
            ['label' => 'Hlavní trať',         'hodnota' => '30 km'],
            ['label' => 'Dětské tratě',        'hodnota' => '3 km klasicky · 7 km volně'],
            ['label' => 'Kontrolní bod',       'hodnota' => 'Pěticestí'],
        ];

        $title = 'O závodu | Vodácká 30';
        $view  = APP_ROOT . '/app/Views/about.php';

        require APP_ROOT . '/app/Views/layout.php';
    }
}
