<?php
/**
 * Soupisky oddílů z 26. ročníku (17. ledna 2026) pro první naplnění účtů oddílů.
 *
 * Data jsou opsaná z oficiální startovní listiny na vodacka30.cz – stejný zdroj,
 * ze kterého čte StartListController. Slouží výhradně k jednorázovému importu
 * (admin → Oddíly → Import soupisek); účty oddílů samy nezakládá.
 *
 * `kategorie` je klíč z config/zavod.php, nikoli název z listiny. Listina u dětí
 * nerozlišuje 7–10 a 11–14 let (obě kategorie běží 7 km), proto je u „Mládežníci
 * 7 km“ hodnota `null` – vedoucí kategorii při přihlášce zvolí ručně. Nic se
 * nedomýšlí.
 *
 * Závodníci bez oddílu (Klekar Theodor, Klekar Viktor, Klekarová Martina,
 * Smítalová Martina) v seedu nejsou – import je přeskakuje.
 */

return [
    'Vodáci Choceň' => [
        ['jmeno' => 'Karlík Tobiáš',      'kategorie' => 'juniors'],
        ['jmeno' => 'Karlík Lukáš',       'kategorie' => 'juniors'],
        ['jmeno' => 'Horký Jakub',        'kategorie' => 'juniors'],
        ['jmeno' => 'Voženílek Štěpán',   'kategorie' => 'juniors'],
        ['jmeno' => 'Voženílek Martin',   'kategorie' => 'juniors'],
        ['jmeno' => 'Vincenc Ondřej',     'kategorie' => 'men-pro'],
        ['jmeno' => 'Kutlvašr Pavel',     'kategorie' => 'men-classic'],
        ['jmeno' => 'Kutlvašr Petr',      'kategorie' => 'men-classic'],
        ['jmeno' => 'Doskočil Eda',       'kategorie' => 'pro-for-fun'],
        ['jmeno' => 'Holečková Lenka',    'kategorie' => 'pro-for-fun'],
        ['jmeno' => 'Voženílková Bohumila', 'kategorie' => 'pro-for-fun'],
        ['jmeno' => 'Doskočilová Ela',    'kategorie' => null],
        ['jmeno' => 'Kutlvašrová Marie',  'kategorie' => null],
        ['jmeno' => 'Kutlvašr Luděk',     'kategorie' => null],
    ],

    'TJ KVT Pardubice' => [
        ['jmeno' => 'Dvořáček Jan',        'kategorie' => 'men-pro'],
        ['jmeno' => 'Dašek Michal',        'kategorie' => 'pro-for-fun'],
        ['jmeno' => 'Andrejsová Rozmarinka', 'kategorie' => 'children-under-6'],
        ['jmeno' => 'Andrejsová Mariánka', 'kategorie' => 'children-under-6'],
    ],

    'TJ Sokol Troja' => [
        ['jmeno' => 'Suchá Tereza',    'kategorie' => 'women-pro'],
        ['jmeno' => 'Bílá Kateřina',   'kategorie' => 'pro-for-fun'],
        ['jmeno' => 'Suchá Karla',     'kategorie' => 'children-under-6'],
        ['jmeno' => 'Bílý Ferdinand',  'kategorie' => 'children-under-6'],
    ],

    'SKK Vysoké Mýto' => [
        ['jmeno' => 'Jetmar Ondřej',    'kategorie' => 'juniors'],
        ['jmeno' => 'Šilar Jakub',      'kategorie' => 'men-pro'],
        ['jmeno' => 'Krejza Filip',     'kategorie' => 'men-pro'],
        ['jmeno' => 'Sedlák Václav',    'kategorie' => 'men-pro'],
        ['jmeno' => 'Lokvencová Anežka', 'kategorie' => null],
        ['jmeno' => 'Sedláková Ema',    'kategorie' => null],
        ['jmeno' => 'Jiroušek Milan',   'kategorie' => null],
    ],

    'Kačeři Chrudim' => [
        ['jmeno' => 'Sloupenský Jan',  'kategorie' => 'men-pro'],
        ['jmeno' => 'Janda Vojtěch',   'kategorie' => 'pro-for-fun'],
        ['jmeno' => 'Sloupenký Jakub', 'kategorie' => null],
    ],

    'Dračí legie Pardubice' => [
        ['jmeno' => 'Suchý Jaroslav', 'kategorie' => 'men-pro'],
        ['jmeno' => 'Dočkalová Radka', 'kategorie' => 'women-pro'],
    ],

    'TJ Synthesia Pardubice' => [
        ['jmeno' => 'Čapská Valerie', 'kategorie' => null],
    ],

    'Spartak Letohrad' => [
        ['jmeno' => 'Roušavá Nela', 'kategorie' => null],
    ],

    'FORT SMC' => [
        ['jmeno' => 'Skotálek Jan', 'kategorie' => 'men-pro'],
    ],

    '3D Fitness Race Team' => [
        ['jmeno' => 'Strnad Tomáš',  'kategorie' => 'men-pro'],
        ['jmeno' => 'Novotný Luděk', 'kategorie' => 'men-classic'],
    ],

    'Střelka Brno' => [
        ['jmeno' => 'Korcina Michal', 'kategorie' => 'men-pro'],
    ],

    'XCSPORT.CZ Nordic racing team' => [
        ['jmeno' => 'Němec Jiří', 'kategorie' => 'men-pro'],
    ],

    'Sokol Hradec Králové' => [
        ['jmeno' => 'Horák Lukáš',   'kategorie' => 'men-pro'],
        ['jmeno' => 'Dašek Oldřich', 'kategorie' => 'men-pro'],
    ],

    'Žižkovský tygři' => [
        ['jmeno' => 'Bek Ondřej', 'kategorie' => 'men-pro'],
    ],

    'TJ ČZU Praha Rolníci' => [
        ['jmeno' => 'Husarčík Teodor', 'kategorie' => 'men-pro'],
    ],

    'Prosport Sezemice' => [
        ['jmeno' => 'Suchý Matěj', 'kategorie' => 'men-pro'],
    ],

    'OK Hradec Králové' => [
        ['jmeno' => 'Kos Michal', 'kategorie' => 'men-pro'],
    ],

    'KVS Hradec Králové' => [
        ['jmeno' => 'Keindl Petr', 'kategorie' => 'men-pro'],
    ],

    'Běháme Ředice!' => [
        ['jmeno' => 'Kunát David', 'kategorie' => 'men-pro'],
    ],

    'EHL Team' => [
        ['jmeno' => 'Ehl Jaroslav', 'kategorie' => 'men-classic'],
    ],

    'Wasserman' => [
        ['jmeno' => 'Roušavý Michal', 'kategorie' => 'men-classic'],
    ],

    'KUS Sopotnice' => [
        ['jmeno' => 'Krejsa Martin', 'kategorie' => 'men-classic'],
    ],

    'Elo-Toman' => [
        ['jmeno' => 'Toman Radek', 'kategorie' => 'men-classic'],
    ],

    'Čiko' => [
        ['jmeno' => 'Suk Vratislav', 'kategorie' => 'men-classic'],
    ],

    'KV Čochtan Plzeň' => [
        ['jmeno' => 'Nováková Veronika', 'kategorie' => 'pro-for-fun'],
        ['jmeno' => 'Foralová Lucie',    'kategorie' => 'pro-for-fun'],
    ],

    'KV DELI Lovosice – Lovosičandy' => [
        ['jmeno' => 'Suchomelová Marcela', 'kategorie' => 'pro-for-fun'],
        ['jmeno' => 'Kociánová Monika',    'kategorie' => 'pro-for-fun'],
    ],

    'NeKell' => [
        ['jmeno' => 'Němcová Eliška', 'kategorie' => 'pro-for-fun'],
        ['jmeno' => 'Němec René',     'kategorie' => 'pro-for-fun'],
        ['jmeno' => 'Keller Jakub',   'kategorie' => 'pro-for-fun'],
    ],

    'SKP Rychnov nad Kněžnou' => [
        ['jmeno' => 'Hlaváček Zdeněk', 'kategorie' => 'pro-for-fun'],
    ],

    'KV Starý Kolín – Baštečtí lyžníci' => [
        ['jmeno' => 'Pánek Dan',     'kategorie' => 'pro-for-fun'],
        ['jmeno' => 'Kahoun Václav', 'kategorie' => 'pro-for-fun'],
        ['jmeno' => 'Melichar Lukáš', 'kategorie' => 'pro-for-fun'],
    ],
];
