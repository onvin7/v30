<?php

namespace App\Models;

class PublicRaceRepository
{
    public static function results(): array
    {
        return [
            [
                'klic' => 'mladeznici-3km-divky',
                'nazev' => 'Mládežníci 3 km – dívky',
                'vysledky' => [
                    ['poradi' => 1, 'cislo' => 16, 'jmeno' => 'Suchá Karla',           'oddil' => 'TJ Sokol Troja',     'cas' => '0:32:02', 'ztrata' => '—'],
                    ['poradi' => 2, 'cislo' => 23, 'jmeno' => 'Andrejsová Rozmarinka', 'oddil' => 'TJ KVT Pardubice',   'cas' => '0:48:46', 'ztrata' => '0:16:44'],
                    ['poradi' => 3, 'cislo' => 2,  'jmeno' => 'Andrejsová Mariánka',   'oddil' => 'TJ KVT Pardubice',   'cas' => '1:12:26', 'ztrata' => '0:40:24'],
                ],
            ],
            [
                'klic' => 'mladeznici-3km-chlapci',
                'nazev' => 'Mládežníci 3 km – chlapci',
                'vysledky' => [
                    ['poradi' => 1, 'cislo' => 25, 'jmeno' => 'Bílý Ferdinand', 'oddil' => 'TJ Sokol Troja', 'cas' => '0:49:46', 'ztrata' => '—'],
                ],
            ],
            [
                'klic' => 'mladeznici-7km-divky',
                'nazev' => 'Mládežníci 7 km – dívky',
                'vysledky' => [
                    ['poradi' => 1, 'cislo' => 28, 'jmeno' => 'Čapská Valerie',    'oddil' => 'TJ Synthesia Pardubice', 'cas' => '0:36:52', 'ztrata' => '—'],
                    ['poradi' => 2, 'cislo' => 4,  'jmeno' => 'Klekarová Martina', 'oddil' => '',                       'cas' => '0:39:48', 'ztrata' => '0:02:56'],
                    ['poradi' => 3, 'cislo' => 1,  'jmeno' => 'Roušavá Nela',      'oddil' => 'Spartak Letohrad',       'cas' => '0:46:47', 'ztrata' => '0:09:55'],
                    ['poradi' => 4, 'cislo' => 17, 'jmeno' => 'Doskočilová Ela',   'oddil' => 'Vodáci Choceň',          'cas' => '0:52:54', 'ztrata' => '0:16:02'],
                    ['poradi' => 5, 'cislo' => 22, 'jmeno' => 'Lokvencová Anežka', 'oddil' => 'SKK Vysoké Mýto',        'cas' => '0:55:30', 'ztrata' => '0:18:38'],
                    ['poradi' => 6, 'cislo' => 29, 'jmeno' => 'Kutlvašrová Marie', 'oddil' => 'Vodáci Choceň',          'cas' => '1:05:04', 'ztrata' => '0:28:12'],
                    ['poradi' => 7, 'cislo' => 31, 'jmeno' => 'Sedláková Ema',     'oddil' => 'SKK Vysoké Mýto',        'cas' => '1:14:19', 'ztrata' => '0:37:27'],
                ],
            ],
            [
                'klic' => 'mladeznici-7km-chlapci',
                'nazev' => 'Mládežníci 7 km – chlapci',
                'vysledky' => [
                    ['poradi' => 1, 'cislo' => 27, 'jmeno' => 'Sloupenký Jakub', 'oddil' => 'Kačeři Chrudim',  'cas' => '0:58:03', 'ztrata' => '—'],
                    ['poradi' => 2, 'cislo' => 24, 'jmeno' => 'Kutlvašr Luděk',  'oddil' => 'Vodáci Choceň',   'cas' => '1:02:58', 'ztrata' => '0:04:55'],
                    ['poradi' => 3, 'cislo' => 5,  'jmeno' => 'Jiroušek Milan',  'oddil' => 'SKK Vysoké Mýto', 'cas' => '1:35:34', 'ztrata' => '0:37:31'],
                ],
            ],
            [
                'klic' => 'juniori',
                'nazev' => 'Junioři',
                'vysledky' => [
                    ['poradi' => 1, 'cislo' => 123, 'jmeno' => 'Klekar Theodor',    'oddil' => '',                 'cas' => '1:48:09', 'ztrata' => '—'],
                    ['poradi' => 2, 'cislo' => 109, 'jmeno' => 'Karlík Tobiáš',     'oddil' => 'Vodáci Choceň',    'cas' => '2:04:12', 'ztrata' => '0:16:03'],
                    ['poradi' => 3, 'cislo' => 122, 'jmeno' => 'Klekar Viktor',     'oddil' => '',                 'cas' => '2:12:23', 'ztrata' => '0:24:14'],
                    ['poradi' => 4, 'cislo' => 139, 'jmeno' => 'Karlík Lukáš',      'oddil' => 'Vodáci Choceň',    'cas' => '2:53:19', 'ztrata' => '1:05:10'],
                    ['poradi' => 5, 'cislo' => 111, 'jmeno' => 'Voženílek Štěpán',  'oddil' => 'Vodáci Choceň',    'cas' => '2:53:19', 'ztrata' => '1:05:10'],
                    ['poradi' => 6, 'cislo' => 151, 'jmeno' => 'Jetmar Ondřej',     'oddil' => 'SKK Vysoké Mýto',  'cas' => '3:27:22', 'ztrata' => '1:39:13'],
                    ['poradi' => 7, 'cislo' => 102, 'jmeno' => 'Horký Jakub',       'oddil' => 'Vodáci Choceň',    'cas' => '5:02:29', 'ztrata' => '3:14:20'],
                    ['poradi' => 8, 'cislo' => 101, 'jmeno' => 'Voženílek Martin',  'oddil' => 'Vodáci Choceň',    'cas' => '5:11:03', 'ztrata' => '3:22:54'],
                ],
            ],
            [
                'klic' => 'zeny-pro',
                'nazev' => 'Ženy PRO – volně',
                'vysledky' => [
                    ['poradi' => 1, 'cislo' => 129, 'jmeno' => 'Smítalová Martina', 'oddil' => '',                      'cas' => '2:09:18', 'ztrata' => '—'],
                    ['poradi' => 2, 'cislo' => 150, 'jmeno' => 'Dočkalová Radka',   'oddil' => 'Dračí legie Pardubice', 'cas' => '2:12:47', 'ztrata' => '0:03:29'],
                    ['poradi' => 3, 'cislo' => 144, 'jmeno' => 'Suchá Tereza',      'oddil' => 'TJ Sokol Troja',        'cas' => '2:14:39', 'ztrata' => '0:05:21'],
                ],
            ],
            [
                'klic' => 'muzi-pro',
                'nazev' => 'Muži PRO – volně',
                'vysledky' => [
                    ['poradi' => 1,  'cislo' => 135, 'jmeno' => 'Skotálek Jan',     'oddil' => 'FORT SMC',                      'cas' => '1:43:08', 'ztrata' => '—'],
                    ['poradi' => 2,  'cislo' => 148, 'jmeno' => 'Suchý Jaroslav',   'oddil' => 'Dračí legie Pardubice',         'cas' => '1:44:11', 'ztrata' => '0:01:03'],
                    ['poradi' => 3,  'cislo' => 134, 'jmeno' => 'Strnad Tomáš',     'oddil' => '3D Fitness Race Team',          'cas' => '1:44:43', 'ztrata' => '0:01:35'],
                    ['poradi' => 4,  'cislo' => 133, 'jmeno' => 'Korcina Michal',   'oddil' => 'Střelka Brno',                  'cas' => '1:46:16', 'ztrata' => '0:03:08'],
                    ['poradi' => 5,  'cislo' => 142, 'jmeno' => 'Němec Jiří',       'oddil' => 'XCSPORT.CZ Nordic racing team', 'cas' => '1:49:44', 'ztrata' => '0:06:36'],
                    ['poradi' => 6,  'cislo' => 138, 'jmeno' => 'Horák Lukáš',      'oddil' => 'Sokol Hradec Králové',          'cas' => '1:51:56', 'ztrata' => '0:08:48'],
                    ['poradi' => 7,  'cislo' => 141, 'jmeno' => 'Bek Ondřej',       'oddil' => 'Žižkovský tygři',               'cas' => '1:52:56', 'ztrata' => '0:09:48'],
                    ['poradi' => 8,  'cislo' => 137, 'jmeno' => 'Dašek Oldřich',    'oddil' => 'Sokol Hradec Králové',          'cas' => '1:54:11', 'ztrata' => '0:11:03'],
                    ['poradi' => 9,  'cislo' => 124, 'jmeno' => 'Husarčík Teodor',  'oddil' => 'TJ ČZU Praha Rolníci',          'cas' => '1:54:24', 'ztrata' => '0:11:16'],
                    ['poradi' => 10, 'cislo' => 119, 'jmeno' => 'Šilar Jakub',      'oddil' => 'SKK Vysoké Mýto',               'cas' => '1:56:53', 'ztrata' => '0:13:45'],
                    ['poradi' => 11, 'cislo' => 120, 'jmeno' => 'Krejza Filip',     'oddil' => 'SKK Vysoké Mýto',               'cas' => '2:00:46', 'ztrata' => '0:17:38'],
                    ['poradi' => 12, 'cislo' => 149, 'jmeno' => 'Suchý Matěj',      'oddil' => 'Prosport Sezemice',             'cas' => '2:01:54', 'ztrata' => '0:18:46'],
                    ['poradi' => 13, 'cislo' => 131, 'jmeno' => 'Kos Michal',       'oddil' => 'OK Hradec Králové',             'cas' => '2:01:54', 'ztrata' => '0:18:46'],
                    ['poradi' => 14, 'cislo' => 108, 'jmeno' => 'Vincenc Ondřej',   'oddil' => 'Vodáci Choceň',                 'cas' => '2:04:12', 'ztrata' => '0:21:04'],
                    ['poradi' => 15, 'cislo' => 115, 'jmeno' => 'Sloupenský Jan',   'oddil' => 'Kačeři Chrudim',                'cas' => '2:05:13', 'ztrata' => '0:22:05'],
                    ['poradi' => 16, 'cislo' => 140, 'jmeno' => 'Dvořáček Jan',     'oddil' => 'TJ KVT Pardubice',              'cas' => '2:08:27', 'ztrata' => '0:25:19'],
                    ['poradi' => 17, 'cislo' => 146, 'jmeno' => 'Keindl Petr',      'oddil' => 'KVS Hradec Králové',            'cas' => '2:13:05', 'ztrata' => '0:29:57'],
                    ['poradi' => 18, 'cislo' => 52,  'jmeno' => 'Kunát David',      'oddil' => 'Běháme Ředice!',                'cas' => '2:14:42', 'ztrata' => '0:31:34'],
                    ['poradi' => 19, 'cislo' => 121, 'jmeno' => 'Sedlák Václav',    'oddil' => 'SKK Vysoké Mýto',               'cas' => '2:19:08', 'ztrata' => '0:36:00'],
                ],
            ],
            [
                'klic' => 'muzi-klasici',
                'nazev' => 'Muži KLASICI',
                'vysledky' => [
                    ['poradi' => 1, 'cislo' => 147, 'jmeno' => 'Ehl Jaroslav',   'oddil' => 'EHL Team',              'cas' => '1:38:28', 'ztrata' => '—'],
                    ['poradi' => 2, 'cislo' => 107, 'jmeno' => 'Roušavý Michal', 'oddil' => 'Wasserman',             'cas' => '2:04:53', 'ztrata' => '0:26:25'],
                    ['poradi' => 3, 'cislo' => 136, 'jmeno' => 'Krejsa Martin',  'oddil' => 'KUS Sopotnice',         'cas' => '2:28:06', 'ztrata' => '0:49:38'],
                    ['poradi' => 4, 'cislo' => 143, 'jmeno' => 'Toman Radek',    'oddil' => 'Elo-Toman',             'cas' => '2:33:02', 'ztrata' => '0:54:34'],
                    ['poradi' => 5, 'cislo' => 106, 'jmeno' => 'Kutlvašr Pavel', 'oddil' => 'Vodáci Choceň',         'cas' => '3:36:18', 'ztrata' => '1:57:50'],
                    ['poradi' => 6, 'cislo' => 105, 'jmeno' => 'Kutlvašr Petr',  'oddil' => 'Vodáci Choceň',         'cas' => '3:52:58', 'ztrata' => '2:14:30'],
                    ['poradi' => 7, 'cislo' => 132, 'jmeno' => 'Suk Vratislav',  'oddil' => 'Čiko',                  'cas' => '3:53:00', 'ztrata' => '2:14:32'],
                    ['poradi' => 'DNS', 'cislo' => '', 'jmeno' => 'Novotný Luděk', 'oddil' => '3D Fitness Race Team', 'cas' => '—', 'ztrata' => '—'],
                ],
            ],
            [
                'klic' => 'zeny-pro-radost',
                'nazev' => 'Ženy PRO RADOST',
                'vysledky' => [
                    ['poradi' => 1, 'cislo' => 118, 'jmeno' => 'Bílá Kateřina',       'oddil' => 'TJ Sokol Troja',                'cas' => '2:30:21', 'ztrata' => '—'],
                    ['poradi' => 2, 'cislo' => 145, 'jmeno' => 'Miláčková Andrea',    'oddil' => '',                              'cas' => '2:38:13', 'ztrata' => '0:07:52'],
                    ['poradi' => 3, 'cislo' => 103, 'jmeno' => 'Holečková Lenka',     'oddil' => 'Vodáci Choceň',                 'cas' => '4:37:03', 'ztrata' => '2:06:42'],
                    ['poradi' => 4, 'cislo' => 128, 'jmeno' => 'Nováková Veronika',   'oddil' => 'KV Čochtan Plzeň',              'cas' => '5:00:50', 'ztrata' => '2:30:29'],
                    ['poradi' => 5, 'cislo' => 126, 'jmeno' => 'Suchomelová Marcela', 'oddil' => 'KV DELI Lovosice – Lovosičandy', 'cas' => '5:00:50', 'ztrata' => '2:30:29'],
                    ['poradi' => 6, 'cislo' => 125, 'jmeno' => 'Kociánová Monika',    'oddil' => 'KV DELI Lovosice – Lovosičandy', 'cas' => '5:00:50', 'ztrata' => '2:30:29'],
                    ['poradi' => 7, 'cislo' => 127, 'jmeno' => 'Foralová Lucie',      'oddil' => 'KV Čochtan Plzeň',              'cas' => '5:00:54', 'ztrata' => '2:30:33'],
                    ['poradi' => 8, 'cislo' => 104, 'jmeno' => 'Voženílková Bohumila', 'oddil' => 'Vodáci Choceň',                'cas' => '5:11:42', 'ztrata' => '2:41:21'],
                    ['poradi' => 9, 'cislo' => 113, 'jmeno' => 'Němcová Eliška',      'oddil' => 'NeKell',                        'cas' => '5:13:24', 'ztrata' => '2:43:03'],
                ],
            ],
            [
                'klic' => 'muzi-pro-radost',
                'nazev' => 'Muži PRO RADOST',
                'vysledky' => [
                    ['poradi' => 1, 'cislo' => 116, 'jmeno' => 'Janda Vojtěch',     'oddil' => 'Kačeři Chrudim',                 'cas' => '2:01:48', 'ztrata' => '—'],
                    ['poradi' => 2, 'cislo' => 110, 'jmeno' => 'Doskočil Eda',      'oddil' => 'Vodáci Choceň',                  'cas' => '2:31:03', 'ztrata' => '0:29:15'],
                    ['poradi' => 3, 'cislo' => 130, 'jmeno' => 'Hlaváček Zdeněk',   'oddil' => 'SKP Rychnov nad Kněžnou',        'cas' => '2:40:50', 'ztrata' => '0:39:02'],
                    ['poradi' => 4, 'cislo' => 112, 'jmeno' => 'Němec René',        'oddil' => 'NeKell',                         'cas' => '3:09:40', 'ztrata' => '1:07:52'],
                    ['poradi' => 5, 'cislo' => 117, 'jmeno' => 'Dašek Michal',      'oddil' => 'TJ KVT Pardubice',               'cas' => '4:03:32', 'ztrata' => '2:01:44'],
                    ['poradi' => 6, 'cislo' => 12,  'jmeno' => 'Pánek Dan',         'oddil' => 'KV Starý Kolín – Baštečtí lyžníci', 'cas' => '4:04:25', 'ztrata' => '2:02:37'],
                    ['poradi' => 7, 'cislo' => 11,  'jmeno' => 'Kahoun Václav',     'oddil' => 'KV Starý Kolín – Baštečtí lyžníci', 'cas' => '4:09:55', 'ztrata' => '2:08:07'],
                    ['poradi' => 8, 'cislo' => 13,  'jmeno' => 'Melichar Lukáš',    'oddil' => 'KV Starý Kolín – Baštečtí lyžníci', 'cas' => '4:25:03', 'ztrata' => '2:23:15'],
                    ['poradi' => 9, 'cislo' => 114, 'jmeno' => 'Keller Jakub',      'oddil' => 'NeKell',                         'cas' => '5:13:25', 'ztrata' => '3:11:37'],
                ],
            ],
        ];

        // Nabídka kategorií pro filtr (nemodifikovaný seznam).;
    }

    public static function startList(): array
    {
        return [
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
        ];;
    }
}
