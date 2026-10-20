<?php
/**
 * Základní SEO nastavení webu.
 *
 * Hodnoty se používají v <head> (meta, Open Graph, Twitter Card, strukturovaná
 * data) a pro sitemap.xml / robots.txt. `og_obrazek_klic` odkazuje na klíč
 * v config/images.php – pokud obrázek chybí, og:image se vynechá.
 */
return [
    // Adresa webu BEZ koncového lomítka. Při nasazení změnit na produkční doménu.
    'url'              => 'http://v30.test',

    'nazev'            => 'Vodácká 30',
    'popis'            => 'Tradiční běžkařský závod pro vodáky v Orlických horách. Pořádá TJ KVT Pardubice.',
    'klicova_slova'    => 'Vodácká 30, běžkařský závod, Orlické hory, Bedřichovka, běžky, závod vodáků',
    'autor'            => 'TJ KVT Pardubice',
    'locale'           => 'cs_CZ',
    'theme_color'      => '#06152A',

    // Klíč obrázku v config/images.php použitý jako og:image.
    'og_obrazek_klic'  => 'home-hero',

    // Profily organizace (sameAs ve strukturovaných datech). Prázdné = vynechá se.
    'socialni'         => [
        'https://kvt.pardubice.cz/',
    ],

    // Pořadatel (pro strukturovaná data Organization/Event).
    'poradatel'        => 'TJ KVT Pardubice',
    'misto'            => 'Chata Bedřichovka, Orlické Záhoří, Orlické hory',
    'datum_zacatku'    => '2026-01-17',
    'datum_konce'      => '2026-01-17',
];
