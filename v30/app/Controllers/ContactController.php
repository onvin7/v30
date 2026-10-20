<?php

namespace App\Controllers;

class ContactController
{
    public function index()
    {
        // Mock data – kontakty na pořadatele z jediného zdroje (config/zavod.php).
        $kontakty = array_values(zavod()['poradatele']);

        $title       = 'Kontakt | Vodácká 30';
        $description = 'Kontakty na pořadatele Vodácké 30 – TJ KVT Pardubice, ředitele závodu a hlavního rozhodčího.';
        $view        = APP_ROOT . '/app/Views/contact.php';

        require APP_ROOT . '/app/Views/layout.php';
    }
}
