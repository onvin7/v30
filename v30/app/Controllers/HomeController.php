<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        // Mock data – později nahradí Model / databáze.
        $hero = [
            'nazev'    => 'Vodácká 30',
            'podtitul' => 'Tradiční běžkařský závod pro vodáky v Orlických horách',
            'datum'    => '17. ledna 2026',
            'misto'    => 'Chata Bedřichovka, Orlické hory',
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

        $title = 'Vodácká 30 | Tradiční běžkařský závod pro vodáky v Orlických horách';
        $view  = APP_ROOT . '/app/Views/home.php';

        require APP_ROOT . '/app/Views/layout.php';
    }
}
