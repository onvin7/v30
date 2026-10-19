<?php

namespace App\Controllers;

class ContactController
{
    public function index()
    {
        // Mock data – kontakty na pořadatele (ročník 2026).
        $kontakty = [
            ['role' => 'Pořadatel',       'jmeno' => 'TJ KVT Pardubice', 'telefon' => ''],
            ['role' => 'Ředitel závodu',  'jmeno' => 'Aleš Křivka',      'telefon' => '724 308 469'],
            ['role' => 'Hlavní rozhodčí', 'jmeno' => 'Jiří Roleček',     'telefon' => '775 125 883'],
        ];

        $title = 'Kontakt | Vodácká 30';
        $view  = APP_ROOT . '/app/Views/contact.php';

        require APP_ROOT . '/app/Views/layout.php';
    }
}
