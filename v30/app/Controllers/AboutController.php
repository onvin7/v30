<?php

namespace App\Controllers;

class AboutController
{
    public function index()
    {
        // Mock data – základní údaje o závodě; termín a pořadatelé z config/zavod.php.
        $rocnik     = zavod()['rocnik'];
        $archiv     = zavod()['archiv'];
        $poradatele = zavod()['poradatele'];

        $fakta = [
            ['label' => 'Termín dalšího ročníku', 'hodnota' => $rocnik['datum']],
            ['label' => 'Datum ročníku 2026',     'hodnota' => $archiv['datum']],
            ['label' => 'Pořadatel',              'hodnota' => $poradatele['poradatel']['jmeno']],
            ['label' => 'Ředitel závodu',         'hodnota' => $poradatele['reditel']['jmeno']],
            ['label' => 'Hlavní rozhodčí',        'hodnota' => $poradatele['rozhodci']['jmeno']],
            ['label' => 'Místo startu a cíle',    'hodnota' => zavod()['start_cil']],
            ['label' => 'Hlavní trať',            'hodnota' => '30 km'],
            ['label' => 'Dětské tratě',           'hodnota' => '3 km klasicky · 7 km volně'],
            ['label' => 'Kontrolní bod',          'hodnota' => zavod()['kontrolni_bod']],
        ];

        $title       = 'O závodu | Vodácká 30';
        $description = 'Základní informace o Vodácké 30 – termín, pořadatel, trať a místo konání v Orlických horách.';
        $view        = APP_ROOT . '/app/Views/about.php';

        require APP_ROOT . '/app/Views/layout.php';
    }
}
