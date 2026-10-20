<?php
/**
 * Mapa obrázků webu: klíč => URL.
 *
 * Jediné místo, kde se definují cesty k fotografiím. Šablony si je vytahují
 * přes `img_url('klíč')` / `has_img('klíč')` a při prázdné hodnotě vykreslí
 * grafický blok místo fotky – nikdy se negeneruje náhradní „AI fotka“.
 *
 * Zdroje: veřejná alba pořadatele (účet milvin) na Zoneramě
 *   - Vodácká 30tka 2026 – https://eu.zonerama.com/milvin/Album/14562205
 *   - Vodácká 30tka 2025 – https://eu.zonerama.com/milvin/Album/12759760
 *   - Vodácká 30tka 2024 – https://eu.zonerama.com/milvin/Album/10982395
 *
 * Zonerama servíruje zmenšeniny ve formátu `{ID}_{šířka}x{výška}_2.jpg`.
 * Originály jsou 3:2, proto se používají ořezy ve stejném poměru (bez
 * zbytečného krácení scény) a výška se volí podle místa v layoutu:
 *   1920×1280 – full-bleed hero, 1600×900 – záhlaví stránek,
 *   1200×800 – obsahové bloky a náhledy alb, 800×533 – mozaika.
 */

return [
    // Úvodní stránka.
    'home-hero'  => '',
    'home-trail' => 'https://eu.zonerama.com/photos/592525360_1200x800_2.jpg',

    // Záhlaví podstránek.
    'about'                 => 'https://eu.zonerama.com/photos/592525370_1600x900_2.jpg',
    'header-propositions'   => 'https://eu.zonerama.com/photos/592525383_1600x900_2.jpg',
    'header-startlist'      => 'https://eu.zonerama.com/photos/592525391_1600x900_2.jpg',
    'header-results'        => 'https://eu.zonerama.com/photos/592525355_1600x900_2.jpg',
    'header-gallery'        => 'https://eu.zonerama.com/photos/592525338_1600x900_2.jpg',
    'header-contact'        => 'https://eu.zonerama.com/photos/592525396_1600x900_2.jpg',
    'header-registration'   => 'https://eu.zonerama.com/photos/592525348_1600x900_2.jpg',

    // Náhledy alb v galerii.
    'album-2026' => 'https://eu.zonerama.com/photos/592525397_1200x800_2.jpg',
    'album-2025' => 'https://eu.zonerama.com/photos/510724205_1200x800_2.jpg',
    'album-2024' => 'https://eu.zonerama.com/photos/436112133_1200x800_2.jpg',

    // Mozaika fotografií z ročníku 2026.
    'photo-1' => 'https://eu.zonerama.com/photos/592525337_800x533_2.jpg',
    'photo-2' => 'https://eu.zonerama.com/photos/592525340_800x533_2.jpg',
    'photo-3' => 'https://eu.zonerama.com/photos/592525343_800x533_2.jpg',
    'photo-4' => 'https://eu.zonerama.com/photos/592525350_800x533_2.jpg',
    'photo-5' => 'https://eu.zonerama.com/photos/592525356_800x533_2.jpg',
    'photo-6' => 'https://eu.zonerama.com/photos/592525359_800x533_2.jpg',
    'photo-7' => 'https://eu.zonerama.com/photos/592525363_800x533_2.jpg',
    'photo-8' => 'https://eu.zonerama.com/photos/592525366_800x533_2.jpg',
    'photo-9' => 'https://eu.zonerama.com/photos/592525372_800x533_2.jpg',
];
