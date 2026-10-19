<?php

namespace App\Controllers;

class GalleryController
{
    public function index()
    {
        // Alba pořadatele (účet milvin) – tři poslední ročníky závodu.
        // Náhledy se berou z config/images.php; prázdná hodnota znamená,
        // že šablona vykreslí grafický blok místo fotky.
        $alba = [
            [
                'rok'      => 2026,
                'nazev'    => 'Vodácká 30tka 2026',
                'datum'    => '17. ledna 2026',
                'fotograf' => 'milvin',
                'pocet'    => 52,
                'odkaz'    => 'https://eu.zonerama.com/milvin/Album/14562205',
                'nahled'   => img_url('album-2026'),
            ],
            [
                'rok'      => 2025,
                'nazev'    => 'Vodácká 30tka 2025',
                'datum'    => '18. ledna 2025',
                'fotograf' => 'milvin',
                'pocet'    => 155,
                'odkaz'    => 'https://eu.zonerama.com/milvin/Album/12759760',
                'nahled'   => img_url('album-2025'),
            ],
            [
                'rok'      => 2024,
                'nazev'    => 'Vodácká 30tka 2024',
                'datum'    => '20.–21. ledna 2024',
                'fotograf' => 'milvin',
                'pocet'    => 201,
                'odkaz'    => 'https://eu.zonerama.com/milvin/Album/10982395',
                'nahled'   => img_url('album-2024'),
            ],
        ];

        // Výběr z ročníku 2026 do mozaiky pod alby.
        $fotky = [
            ['klic' => 'photo-1', 'alt' => 'Závodník na trati 26. ročníku'],
            ['klic' => 'photo-2', 'alt' => 'Dětská kategorie na trati'],
            ['klic' => 'photo-3', 'alt' => 'Závodníci na upravené stopě'],
            ['klic' => 'photo-4', 'alt' => 'Průběh závodu u chaty Bedřichovka'],
            ['klic' => 'photo-5', 'alt' => 'Zimní krajina Orlických hor'],
            ['klic' => 'photo-6', 'alt' => 'Závodník v cíli'],
            ['klic' => 'photo-7', 'alt' => 'Vyhlášení výsledků'],
            ['klic' => 'photo-8', 'alt' => 'Zázemí závodu v Orlickém Záhoří'],
            ['klic' => 'photo-9', 'alt' => 'Závodníci na trati'],
        ];

        // Do mozaiky patří jen fotky, které jsou skutečně k dispozici.
        $fotky = array_values(array_filter(
            $fotky,
            static fn (array $foto): bool => has_img($foto['klic'])
        ));

        $title = 'Galerie | Vodácká 30';
        $view  = APP_ROOT . '/app/Views/gallery.php';

        require APP_ROOT . '/app/Views/layout.php';
    }
}
