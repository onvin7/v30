<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        // Mock data – později nahradí Model / databáze.
        $hero = [
            'nazev'    => 'Vodácká 30',
            'podtitul' => 'Tradiční vodácký běžkařský závod v Orlických horách',
            'datum'    => '17. ledna 2026',
            'misto'    => 'Chata Bedřichovka, Orlické hory',
        ];

        $trat = [
            'nazev' => 'Bílou stopou okolím Bedřichovky',
            'popis' => 'Třicet kilometrů po hřebenech Orlických hor, se startem i cílem u chaty Bedřichovka. '
                     . 'Stopa vede zasněženými lesy a loukami Orlického Záhoří a je upravená pro klasický styl i bruslení.',
            'fakta' => [
                ['label' => 'Délka trati', 'hodnota' => '30 km'],
                ['label' => 'Start a cíl',  'hodnota' => 'Chata Bedřichovka'],
                ['label' => 'Pohoří',       'hodnota' => 'Orlické hory'],
                ['label' => 'Styl',         'hodnota' => 'Klasicky i bruslením'],
            ],
        ];

        // Ročník 2026 v číslech.
        $rocnik = [
            ['hodnota' => '30',    'jednotka' => 'km',  'label' => 'Hlavní trať'],
            ['hodnota' => '3 a 7', 'jednotka' => 'km',  'label' => 'Dětské tratě'],
            ['hodnota' => '26.',   'jednotka' => '',    'label' => 'Ročník'],
            ['hodnota' => '10',    'jednotka' => '',    'label' => 'Vypsaných kategorií'],
        ];

        // Klíčové body závodního dne (úplný program je v propozicích).
        $program = [
            ['cas' => '9:00',  'polozka' => 'Registrace dětských i dospělých kategorií'],
            ['cas' => '10:00', 'polozka' => 'Start dětských kategorií'],
            ['cas' => '12:00', 'polozka' => 'Start hlavního závodu na 30 km'],
            ['cas' => '15:00', 'polozka' => 'Vyhlášení kategorií PRO'],
            ['cas' => '17:00', 'polozka' => 'Vyhlášení PRO RADOST a konec závodu'],
        ];

        // Podium nejrychlejší kategorie 26. ročníku.
        $podiumKategorie = 'Muži PRO – volně';
        $podium = [
            ['poradi' => 1, 'jmeno' => 'Skotálek Jan',   'oddil' => 'FORT SMC',              'cas' => '1:43:08'],
            ['poradi' => 2, 'jmeno' => 'Suchý Jaroslav', 'oddil' => 'Dračí legie Pardubice', 'cas' => '1:44:11'],
            ['poradi' => 3, 'jmeno' => 'Strnad Tomáš',   'oddil' => '3D Fitness Race Team',  'cas' => '1:44:43'],
        ];

        $title = 'Vodácká 30 | Tradiční vodácký běžkařský závod v Orlických horách';
        $view  = APP_ROOT . '/app/Views/home.php';

        require APP_ROOT . '/app/Views/layout.php';
    }
}
