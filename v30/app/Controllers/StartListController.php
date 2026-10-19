<?php

namespace App\Controllers;

class StartListController
{
    public function index()
    {
        // Startovní listina 26. ročníku (17. 1. 2026) sestavená z oficiální výsledkové
        // listiny na původním webu vodacka30.cz. Zveřejňují se pouze schválená veřejná
        // pole (startovní číslo, jméno, oddíl, kategorie).
        $startovniListina = [
            [
                'kategorie' => 'Mládežníci 3 km – dívky',
                'zavodnici' => [
                    ['cislo' => 16, 'jmeno' => 'Suchá Karla',           'oddil' => 'TJ Sokol Troja'],
                    ['cislo' => 23, 'jmeno' => 'Andrejsová Rozmarinka', 'oddil' => 'TJ KVT Pardubice'],
                    ['cislo' => 2,  'jmeno' => 'Andrejsová Mariánka',   'oddil' => 'TJ KVT Pardubice'],
                ],
            ],
            [
                'kategorie' => 'Mládežníci 3 km – chlapci',
                'zavodnici' => [
                    ['cislo' => 25, 'jmeno' => 'Bílý Ferdinand', 'oddil' => 'TJ Sokol Troja'],
                ],
            ],
            [
                'kategorie' => 'Mládežníci 7 km – dívky',
                'zavodnici' => [
                    ['cislo' => 28, 'jmeno' => 'Čapská Valerie',    'oddil' => 'TJ Synthesia Pardubice'],
                    ['cislo' => 4,  'jmeno' => 'Klekarová Martina', 'oddil' => ''],
                    ['cislo' => 1,  'jmeno' => 'Roušavá Nela',      'oddil' => 'Spartak Letohrad'],
                    ['cislo' => 17, 'jmeno' => 'Doskočilová Ela',   'oddil' => 'Vodáci Choceň'],
                    ['cislo' => 22, 'jmeno' => 'Lokvencová Anežka', 'oddil' => 'SKK Vysoké Mýto'],
                    ['cislo' => 29, 'jmeno' => 'Kutlvašrová Marie', 'oddil' => 'Vodáci Choceň'],
                    ['cislo' => 31, 'jmeno' => 'Sedláková Ema',     'oddil' => 'SKK Vysoké Mýto'],
                ],
            ],
            [
                'kategorie' => 'Mládežníci 7 km – chlapci',
                'zavodnici' => [
                    ['cislo' => 27, 'jmeno' => 'Sloupenký Jakub', 'oddil' => 'Kačeři Chrudim'],
                    ['cislo' => 24, 'jmeno' => 'Kutlvašr Luděk',  'oddil' => 'Vodáci Choceň'],
                    ['cislo' => 5,  'jmeno' => 'Jiroušek Milan',  'oddil' => 'SKK Vysoké Mýto'],
                ],
            ],
            [
                'kategorie' => 'Junioři',
                'zavodnici' => [
                    ['cislo' => 123, 'jmeno' => 'Klekar Theodor',   'oddil' => ''],
                    ['cislo' => 109, 'jmeno' => 'Karlík Tobiáš',    'oddil' => 'Vodáci Choceň'],
                    ['cislo' => 122, 'jmeno' => 'Klekar Viktor',    'oddil' => ''],
                    ['cislo' => 139, 'jmeno' => 'Karlík Lukáš',     'oddil' => 'Vodáci Choceň'],
                    ['cislo' => 111, 'jmeno' => 'Voženílek Štěpán', 'oddil' => 'Vodáci Choceň'],
                    ['cislo' => 151, 'jmeno' => 'Jetmar Ondřej',    'oddil' => 'SKK Vysoké Mýto'],
                    ['cislo' => 102, 'jmeno' => 'Horký Jakub',      'oddil' => 'Vodáci Choceň'],
                    ['cislo' => 101, 'jmeno' => 'Voženílek Martin', 'oddil' => 'Vodáci Choceň'],
                ],
            ],
            [
                'kategorie' => 'Ženy PRO – volně',
                'zavodnici' => [
                    ['cislo' => 129, 'jmeno' => 'Smítalová Martina', 'oddil' => ''],
                    ['cislo' => 150, 'jmeno' => 'Dočkalová Radka',   'oddil' => 'Dračí legie Pardubice'],
                    ['cislo' => 144, 'jmeno' => 'Suchá Tereza',      'oddil' => 'TJ Sokol Troja'],
                ],
            ],
            [
                'kategorie' => 'Muži PRO – volně',
                'zavodnici' => [
                    ['cislo' => 135, 'jmeno' => 'Skotálek Jan',    'oddil' => 'FORT SMC'],
                    ['cislo' => 148, 'jmeno' => 'Suchý Jaroslav',  'oddil' => 'Dračí legie Pardubice'],
                    ['cislo' => 134, 'jmeno' => 'Strnad Tomáš',    'oddil' => '3D Fitness Race Team'],
                    ['cislo' => 133, 'jmeno' => 'Korcina Michal',  'oddil' => 'Střelka Brno'],
                    ['cislo' => 142, 'jmeno' => 'Němec Jiří',      'oddil' => 'XCSPORT.CZ Nordic racing team'],
                    ['cislo' => 138, 'jmeno' => 'Horák Lukáš',     'oddil' => 'Sokol Hradec Králové'],
                    ['cislo' => 141, 'jmeno' => 'Bek Ondřej',      'oddil' => 'Žižkovský tygři'],
                    ['cislo' => 137, 'jmeno' => 'Dašek Oldřich',   'oddil' => 'Sokol Hradec Králové'],
                    ['cislo' => 124, 'jmeno' => 'Husarčík Teodor', 'oddil' => 'TJ ČZU Praha Rolníci'],
                    ['cislo' => 119, 'jmeno' => 'Šilar Jakub',     'oddil' => 'SKK Vysoké Mýto'],
                    ['cislo' => 120, 'jmeno' => 'Krejza Filip',    'oddil' => 'SKK Vysoké Mýto'],
                    ['cislo' => 149, 'jmeno' => 'Suchý Matěj',     'oddil' => 'Prosport Sezemice'],
                    ['cislo' => 131, 'jmeno' => 'Kos Michal',      'oddil' => 'OK Hradec Králové'],
                    ['cislo' => 108, 'jmeno' => 'Vincenc Ondřej',  'oddil' => 'Vodáci Choceň'],
                    ['cislo' => 115, 'jmeno' => 'Sloupenský Jan',  'oddil' => 'Kačeři Chrudim'],
                    ['cislo' => 140, 'jmeno' => 'Dvořáček Jan',    'oddil' => 'TJ KVT Pardubice'],
                    ['cislo' => 146, 'jmeno' => 'Keindl Petr',     'oddil' => 'KVS Hradec Králové'],
                    ['cislo' => 52,  'jmeno' => 'Kunát David',     'oddil' => 'Běháme Ředice!'],
                    ['cislo' => 121, 'jmeno' => 'Sedlák Václav',   'oddil' => 'SKK Vysoké Mýto'],
                ],
            ],
            [
                'kategorie' => 'Muži KLASICI',
                'zavodnici' => [
                    ['cislo' => 147, 'jmeno' => 'Ehl Jaroslav',   'oddil' => 'EHL Team'],
                    ['cislo' => 107, 'jmeno' => 'Roušavý Michal', 'oddil' => 'Wasserman'],
                    ['cislo' => 136, 'jmeno' => 'Krejsa Martin',  'oddil' => 'KUS Sopotnice'],
                    ['cislo' => 143, 'jmeno' => 'Toman Radek',    'oddil' => 'Elo-Toman'],
                    ['cislo' => 106, 'jmeno' => 'Kutlvašr Pavel', 'oddil' => 'Vodáci Choceň'],
                    ['cislo' => 105, 'jmeno' => 'Kutlvašr Petr',  'oddil' => 'Vodáci Choceň'],
                    ['cislo' => 132, 'jmeno' => 'Suk Vratislav',  'oddil' => 'Čiko'],
                    ['cislo' => '',  'jmeno' => 'Novotný Luděk',  'oddil' => '3D Fitness Race Team'],
                ],
            ],
            [
                'kategorie' => 'Ženy PRO RADOST',
                'zavodnici' => [
                    ['cislo' => 118, 'jmeno' => 'Bílá Kateřina',        'oddil' => 'TJ Sokol Troja'],
                    ['cislo' => 145, 'jmeno' => 'Miláčková Andrea',     'oddil' => ''],
                    ['cislo' => 103, 'jmeno' => 'Holečková Lenka',      'oddil' => 'Vodáci Choceň'],
                    ['cislo' => 128, 'jmeno' => 'Nováková Veronika',    'oddil' => 'KV Čochtan Plzeň'],
                    ['cislo' => 126, 'jmeno' => 'Suchomelová Marcela',  'oddil' => 'KV DELI Lovosice – Lovosičandy'],
                    ['cislo' => 125, 'jmeno' => 'Kociánová Monika',     'oddil' => 'KV DELI Lovosice – Lovosičandy'],
                    ['cislo' => 127, 'jmeno' => 'Foralová Lucie',       'oddil' => 'KV Čochtan Plzeň'],
                    ['cislo' => 104, 'jmeno' => 'Voženílková Bohumila', 'oddil' => 'Vodáci Choceň'],
                    ['cislo' => 113, 'jmeno' => 'Němcová Eliška',       'oddil' => 'NeKell'],
                ],
            ],
            [
                'kategorie' => 'Muži PRO RADOST',
                'zavodnici' => [
                    ['cislo' => 116, 'jmeno' => 'Janda Vojtěch',   'oddil' => 'Kačeři Chrudim'],
                    ['cislo' => 110, 'jmeno' => 'Doskočil Eda',    'oddil' => 'Vodáci Choceň'],
                    ['cislo' => 130, 'jmeno' => 'Hlaváček Zdeněk', 'oddil' => 'SKP Rychnov nad Kněžnou'],
                    ['cislo' => 112, 'jmeno' => 'Němec René',      'oddil' => 'NeKell'],
                    ['cislo' => 117, 'jmeno' => 'Dašek Michal',    'oddil' => 'TJ KVT Pardubice'],
                    ['cislo' => 12,  'jmeno' => 'Pánek Dan',       'oddil' => 'KV Starý Kolín – Baštečtí lyžníci'],
                    ['cislo' => 11,  'jmeno' => 'Kahoun Václav',   'oddil' => 'KV Starý Kolín – Baštečtí lyžníci'],
                    ['cislo' => 13,  'jmeno' => 'Melichar Lukáš',  'oddil' => 'KV Starý Kolín – Baštečtí lyžníci'],
                    ['cislo' => 114, 'jmeno' => 'Keller Jakub',    'oddil' => 'NeKell'],
                ],
            ],
        ];

        $title = 'Startovní listina | Vodácká 30';
        $view  = APP_ROOT . '/app/Views/start_list.php';

        require APP_ROOT . '/app/Views/layout.php';
    }
}
