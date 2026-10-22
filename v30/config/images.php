<?php
/**
 * Mapa obrázků webu: klíč => cesta k souboru.
 *
 * Jediné místo, kde se definují cesty k fotografiím. Šablony si je vytahují
 * přes `img_url('klíč')` / `has_img('klíč')` a při prázdné hodnotě vykreslí
 * grafický blok místo fotky – nikdy se negeneruje náhradní „AI fotka“.
 *
 * Fotky se servírují z `assets/img/` a jsou připravené ve více šířkách;
 * název má tvar `{klíč}-{šířka}.jpg`. Hodnota níže je výchozí (největší)
 * varianta pro atribut `src`, zbytek odvodí `img_srcset()`.
 *
 * Předlohou jsou fotky z veřejných alb pořadatele (účet milvin) na Zoneramě,
 * ročníky 2024–2026. Ta alba hotlinkování blokují a cizím požadavkům posílají
 * náhradní obrázek, proto se fotky hostují lokálně.
 */

return [
    // Úvodní stránka.
    'home-hero'  => '/assets/img/home-hero-1920.jpg',
    'home-trail' => '/assets/img/home-trail-1200.jpg',

    // Záhlaví podstránek.
    'about'                 => '/assets/img/about-1600.jpg',
    'header-propositions'   => '/assets/img/header-propositions-1600.jpg',
    'header-startlist'      => '/assets/img/header-startlist-1600.jpg',
    'header-results'        => '/assets/img/header-results-1600.jpg',
    'header-gallery'        => '/assets/img/header-gallery-1600.jpg',
    'header-contact'        => '/assets/img/header-contact-1600.jpg',
    'header-registration'   => '/assets/img/header-registration-1600.jpg',

    // Náhledy alb v galerii.
    'album-2026' => '/assets/img/album-2026-900.jpg',
    'album-2025' => '/assets/img/album-2025-900.jpg',
    'album-2024' => '/assets/img/album-2024-900.jpg',

    // Mozaika fotografií z ročníku 2026.
    'photo-1' => '/assets/img/photo-1-900.jpg',
    'photo-2' => '/assets/img/photo-2-900.jpg',
    'photo-3' => '/assets/img/photo-3-900.jpg',
    'photo-4' => '/assets/img/photo-4-900.jpg',
    'photo-5' => '/assets/img/photo-5-900.jpg',
    'photo-6' => '/assets/img/photo-6-900.jpg',
    'photo-7' => '/assets/img/photo-7-900.jpg',
    'photo-8' => '/assets/img/photo-8-900.jpg',
    'photo-9' => '/assets/img/photo-9-900.jpg',
];
