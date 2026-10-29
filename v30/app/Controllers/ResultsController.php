<?php

namespace App\Controllers;

class ResultsController
{
    public function index()
    {
        // Data – oficiální výsledková listina 26. ročníku (17. 1. 2026),
        // převzato z původního webu vodacka30.cz. Ztráta je počítána na vítěze kategorie.
        $kategorie = \App\Models\PublicRaceRepository::results();

        $vsechnyKategorie = $kategorie;

        // Filtr kategorie přes ?kategorie= (musí odpovídat nabídce).
        $vybranaKategorie = isset($_GET['kategorie']) ? (string) $_GET['kategorie'] : '';

        if ($vybranaKategorie !== '') {
            $kategorie = array_values(array_filter(
                $kategorie,
                static fn (array $skupina): bool => $skupina['klic'] === $vybranaKategorie
            ));
        }

        // Archiv ročníků – převzato z původního webu. Ověřená je pouze výsledková
        // listina 2026, starší ročníky zůstávají neověřené. Ročník 2021 se nejel.
        $archiv = [
            ['rok' => 2026, 'rocnik' => 26, 'datum' => '17. 1. 2026', 'overeno' => true],
            ['rok' => 2025, 'rocnik' => 25, 'datum' => '18. 1. 2025', 'overeno' => false],
            ['rok' => 2024, 'rocnik' => 24, 'datum' => '20. 1. 2024', 'overeno' => false],
            ['rok' => 2023, 'rocnik' => 23, 'datum' => '21. 1. 2023', 'overeno' => false],
            ['rok' => 2022, 'rocnik' => 22, 'datum' => '22. 1. 2022', 'overeno' => false],
            ['rok' => 2020, 'rocnik' => 21, 'datum' => '18. 1. 2020', 'overeno' => false],
            ['rok' => 2019, 'rocnik' => 20, 'datum' => '19. 1. 2019', 'overeno' => false],
            ['rok' => 2018, 'rocnik' => 19, 'datum' => '13. 1. 2018', 'overeno' => false],
            ['rok' => 2017, 'rocnik' => 18, 'datum' => '14. 1. 2017', 'overeno' => false],
            ['rok' => 2016, 'rocnik' => 17, 'datum' => '23. 1. 2016', 'overeno' => false],
            ['rok' => 2015, 'rocnik' => 16, 'datum' => '24. 1. 2015', 'overeno' => false],
        ];

        $title       = 'Výsledky a archiv | Vodácká 30';
        $description = 'Výsledky 26. ročníku Vodácké 30 (17. ledna 2026) podle kategorií.';
        $view        = APP_ROOT . '/app/Views/results.php';

        require APP_ROOT . '/app/Views/layout.php';
    }
}
