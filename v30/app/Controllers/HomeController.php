<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        // Mock data – později nahradí Model / databáze.
        // Hero míří na nejbližší ročník, proto bere termín i místo z config/zavod.php.
        $hero = [
            'nazev'    => 'Vodácká 30',
            'podtitul' => 'Tradiční běžkařský závod pro vodáky v Orlických horách',
            'datum'    => zavod()['rocnik']['datum'],
            'misto'    => zavod()['misto'],
        ];

        $trat = [
            'nazev' => 'Bílou stopou okolím Bedřichovky',
            'popis' => 'Okruh vede zasněženými lesy a loukami okolo chaty Bedřichovka v Orlických horách. '
                     . 'Na start se staví vodáci i nevodáci – stačí běžky, chuť do toho jít a zimní výbava. '
                     . 'Trať je upravená pro klasický styl i bruslení.',
            'fakta' => [
                ['label' => 'Délka trati', 'hodnota' => '30 km'],
                ['label' => 'Start a cíl',  'hodnota' => 'Chata Bedřichovka'],
                ['label' => 'Pohoří',       'hodnota' => 'Orlické hory'],
                ['label' => 'Styl',         'hodnota' => 'Klasicky i bruslením'],
            ],
        ];

        // Ročník 2026 v číslech (počet kategorií se bere z configu, aby seděl s propozicemi).
        $rocnik = [
            ['hodnota' => '30',    'jednotka' => 'km',  'label' => 'Hlavní trať'],
            ['hodnota' => '3 a 7', 'jednotka' => 'km',  'label' => 'Dětské tratě'],
            ['hodnota' => '26.',   'jednotka' => '',    'label' => 'Ročník'],
            ['hodnota' => (string) count(zavod()['kategorie']), 'jednotka' => '', 'label' => 'Vypsaných kategorií'],
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

        $title       = 'Vodácká 30 | Tradiční běžkařský závod pro vodáky v Orlických horách';
        $description = 'Vodácká 30 – tradiční běžkařský závod pro vodáky v Orlických horách. Termín, trať, výsledky, galerie a přihlášky na nadcházející ročník.';

        // Poslední zveřejněné příspěvky pro sekci "Novinky" (bez DB, čte se z JSON).
        $novinky = (new \App\Models\PostRepository())->published(3);

        $view = APP_ROOT . '/app/Views/home.php';

        require APP_ROOT . '/app/Views/layout.php';
    }
}
