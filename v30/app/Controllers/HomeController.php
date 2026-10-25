<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        // Termín, místo a počet kategorií se berou z config/zavod.php, aby homepage
        // netvrdila jiný ročník ani jiná čísla než zbytek webu.
        $zavod     = zavod();
        $nejblizsi = $zavod['rocnik'];
        $archiv    = $zavod['archiv'];

        $hero = [
            'nazev'    => 'Vodácká 30',
            'podtitul' => 'Tradiční vodácký běžkařský závod v Orlických horách',
            'datum'    => $nejblizsi['datum'],
            'misto'    => $zavod['misto'],
            'rocnik'   => $nejblizsi['cislo'] . '. ročník',
        ];

        $trat = [
            'nazev' => 'Bílou stopou okolím Bedřichovky',
            'popis' => 'Třicet kilometrů po hřebenech Orlických hor, se startem i cílem u chaty Bedřichovka. '
                     . 'Stopa vede zasněženými lesy a loukami Orlického Záhoří a je upravená pro klasický styl i bruslení.',
            'fakta' => [
                ['label' => 'Délka trati', 'hodnota' => '30 km'],
                ['label' => 'Start a cíl',  'hodnota' => 'Chata Bedřichovka'],
                ['label' => 'Pohoří',       'hodnota' => 'Orlické hory'],
                ['label' => 'Styl',         'hodnota' => 'Klasicky i volně'],
            ],
        ];

        // Poslední odjetý ročník v číslech (archiv). Počet kategorií se počítá
        // z configu, aby se nemohl rozejít s propozicemi ani s přihláškou.
        $rocnik = [
            ['hodnota' => '30',    'jednotka' => 'km',  'label' => 'Hlavní trať'],
            ['hodnota' => '3 a 7', 'jednotka' => 'km',  'label' => 'Dětské tratě'],
            ['hodnota' => $archiv['cislo'] . '.', 'jednotka' => '', 'label' => 'Ročník'],
            ['hodnota' => (string) count($zavod['kategorie']), 'jednotka' => '', 'label' => 'Vypsaných kategorií'],
        ];

        // Klíčové body závodního dne (úplný program je v propozicích).
        $program = [
            ['cas' => '9:00',  'polozka' => 'Registrace dětských i dospělých kategorií'],
            ['cas' => '10:00', 'polozka' => 'Start dětských kategorií'],
            ['cas' => '12:00', 'polozka' => 'Start hlavního závodu na 30 km'],
            ['cas' => '15:00', 'polozka' => 'Vyhlášení kategorií PRO'],
            ['cas' => '17:00', 'polozka' => 'Vyhlášení PRO RADOST a konec závodu'],
        ];

        // Podium nejrychlejší kategorie posledního odjetého ročníku.
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
