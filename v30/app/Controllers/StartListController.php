<?php

namespace App\Controllers;

class StartListController
{
    public function index()
    {
        // Startovní listina 26. ročníku (17. 1. 2026) sestavená z oficiální výsledkové
        // listiny na původním webu vodacka30.cz. Zveřejňují se pouze schválená veřejná
        // pole (startovní číslo, jméno, oddíl, kategorie).
        $startovniListina = \App\Models\PublicRaceRepository::startList();

        $title       = 'Startovní listina | Vodácká 30';
        $description = 'Startovní listina 26. ročníku Vodácké 30 – čísla, jména a oddíly podle kategorií.';
        $view        = APP_ROOT . '/app/Views/start_list.php';

        require APP_ROOT . '/app/Views/layout.php';
    }
}
