<?php
/**
 * Údaje o závodě Vodácká 30 – jediný zdroj pravdy pro ročník, kategorie a startovné.
 *
 * Mock data (bez databáze): vycházejí z oficiálních propozic ročníku 2026
 * (vodacka30.cz) a z termínu 27. ročníku, který pořadatel potvrdil na 16. ledna 2027.
 * Kontrolery a šablony si hodnoty vytahují přes `zavod()`, aby se kategorie ani ceny
 * nemohly na jednotlivých stránkách rozejít.
 */

return [
    // Nejbližší (pořadatelem potvrzený) ročník.
    'rocnik' => [
        'cislo'        => 27,
        'rok'          => 2027,
        'datum'        => '16. ledna 2027',
        'datum_kratce' => '16. 1. 2027',
        // Uzávěrka včasné platby – odhad podle ročníku 2026 (přihláška i platba do 15. ledna).
        'uzavierka'    => '15. ledna 2027',
    ],

    // Poslední odjetý ročník – archivní údaje webu.
    'archiv' => [
        'cislo'        => 26,
        'rok'          => 2026,
        'datum'        => '17. ledna 2026',
        'datum_kratce' => '17. 1. 2026',
    ],

    // Místo konání a trať.
    'misto'         => 'Chata Bedřichovka, Orlické hory',
    'start_cil'     => 'Bedřichovka, Orlické Záhoří',
    'kontrolni_bod' => 'Pěticestí',

    // Číslo účtu zveřejněné není (propozice mluví jen o „platbě na účet“).
    // Doplní pořadatel; dokud je prázdné, potvrzení přihlášky na to upozorní,
    // aby nikdo neposílal peníze na neexistující účet.
    'ucet' => '',

    // Pořadatelé (propozice, kontakt, o závodu).
    'poradatele' => [
        'poradatel' => ['role' => 'Pořadatel',       'jmeno' => 'TJ KVT Pardubice', 'telefon' => ''],
        'reditel'   => ['role' => 'Ředitel závodu',  'jmeno' => 'Aleš Křivka',      'telefon' => '724 308 469'],
        'rozhodci'  => ['role' => 'Hlavní rozhodčí', 'jmeno' => 'Jiří Roleček',     'telefon' => '775 125 883'],
    ],

    // kod => [nazev, trat, styl, cena (včasná platba), cena_na_miste, vek_od, vek_do].
    // Veteráni jsou vypsaní i v oficiálních propozicích, proto je nabízí i přihláška.
    // Věkové hranice uvádějí propozice jen u dětských kategorií; `null` znamená,
    // že rozsah vypsaný není, a proto se u kategorie věk nekontroluje ani nedopočítá.
    'kategorie' => [
        'children-under-6'  => ['nazev' => 'Chlapci a dívky do 6 let',           'trat' => '3 km',  'styl' => 'klasicky', 'cena' => 200, 'cena_na_miste' => 200, 'vek_od' => 0,    'vek_do' => 6],
        'children-7-10'     => ['nazev' => 'Chlapci a dívky od 7 do 10 let',     'trat' => '7 km',  'styl' => 'volně',    'cena' => 200, 'cena_na_miste' => 200, 'vek_od' => 7,    'vek_do' => 10],
        'children-11-14'    => ['nazev' => 'Chlapci a dívky od 11 do 14 let',    'trat' => '7 km',  'styl' => 'volně',    'cena' => 200, 'cena_na_miste' => 200, 'vek_od' => 11,   'vek_do' => 14],
        'children-pro-7-14' => ['nazev' => 'Chlapci a dívky PRO od 7 do 14 let', 'trat' => '7 km',  'styl' => 'volně',    'cena' => 200, 'cena_na_miste' => 200, 'vek_od' => 7,    'vek_do' => 14],
        'juniors'           => ['nazev' => 'Junioři',                            'trat' => '30 km', 'styl' => 'volně',    'cena' => 300, 'cena_na_miste' => 400, 'vek_od' => null, 'vek_do' => null],
        'men-pro'           => ['nazev' => 'Muži PRO',                           'trat' => '30 km', 'styl' => 'volně',    'cena' => 300, 'cena_na_miste' => 400, 'vek_od' => null, 'vek_do' => null],
        'women-pro'         => ['nazev' => 'Ženy PRO',                           'trat' => '30 km', 'styl' => 'volně',    'cena' => 300, 'cena_na_miste' => 400, 'vek_od' => null, 'vek_do' => null],
        'men-classic'       => ['nazev' => 'Muži KLASICI',                       'trat' => '30 km', 'styl' => 'klasicky', 'cena' => 300, 'cena_na_miste' => 400, 'vek_od' => null, 'vek_do' => null],
        'women-classic'     => ['nazev' => 'Ženy KLASIČKY',                      'trat' => '30 km', 'styl' => 'klasicky', 'cena' => 300, 'cena_na_miste' => 400, 'vek_od' => null, 'vek_do' => null],
        'pro-for-fun'       => ['nazev' => 'Muži a ženy PRO RADOST',             'trat' => '30 km', 'styl' => 'volně',    'cena' => 300, 'cena_na_miste' => 400, 'vek_od' => null, 'vek_do' => null],
        'veterans'          => ['nazev' => 'Veteráni a veteránky',               'trat' => '30 km', 'styl' => 'volně',    'cena' => 300, 'cena_na_miste' => 400, 'vek_od' => null, 'vek_do' => null],
    ],
];
