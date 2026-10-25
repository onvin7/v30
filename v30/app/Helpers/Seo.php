<?php

namespace App\Helpers;

/**
 * SEO pomocník – sestavuje metadata pro <head> (titulek, popis, canonical,
 * robots, Open Graph, Twitter Card) a strukturovaná data (JSON-LD).
 *
 * Zjednodušená verze SEOHelper z projektu Cyklistickey, omezená na to, co
 * Vodácká 30 skutečně používá.
 */
class Seo
{
    /** @var array<string, mixed>|null */
    private static ?array $config = null;

    /**
     * @return array<string, mixed>
     */
    public static function config(): array
    {
        if (self::$config === null) {
            $nacteno = require APP_ROOT . '/config/seo.php';
            self::$config = is_array($nacteno) ? $nacteno : [];
        }

        return self::$config;
    }

    /**
     * Základní adresa webu bez koncového lomítka.
     */
    public static function baseUrl(): string
    {
        return rtrim((string) (self::config()['url'] ?? ''), '/');
    }

    /**
     * Titulek stránky. Prázdný vstup → název webu; jinak doplní suffix názvu
     * webu (pokud už v titulku není).
     */
    public static function title(?string $t): string
    {
        $nazev = (string) (self::config()['nazev'] ?? 'Vodácká 30');
        $t     = trim((string) $t);

        if ($t === '') {
            return $nazev;
        }

        if (mb_stripos($t, $nazev) !== false) {
            return $t;
        }

        return $t . ' | ' . $nazev;
    }

    /**
     * Meta description – zkrácená na ~160 znaků. Prázdný vstup → výchozí popis.
     */
    public static function description(?string $text): string
    {
        $text = trim((string) $text);

        if ($text === '') {
            $text = (string) (self::config()['popis'] ?? '');
        }

        return Text::truncate($text, 160);
    }

    /**
     * Absolutní canonical URL pro danou cestu.
     */
    public static function canonical(string $path): string
    {
        if ($path === '' || $path[0] !== '/') {
            $path = '/' . $path;
        }

        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        return self::baseUrl() . $path;
    }

    /**
     * Obsah meta robots.
     */
    public static function robots(bool $index = true): string
    {
        return $index ? 'index, follow' : 'noindex, nofollow';
    }

    /**
     * Absolutní URL og:image (nebo prázdný řetězec, pokud obrázek není).
     */
    public static function image(): string
    {
        $klic  = (string) (self::config()['og_obrazek_klic'] ?? '');
        $cesta = $klic !== '' ? img_url($klic) : '';

        // og:image musí být absolutní URL; lokální cesty doplníme o adresu webu.
        if ($cesta !== '' && $cesta[0] === '/') {
            return self::baseUrl() . $cesta;
        }

        return $cesta;
    }

    /**
     * Open Graph metadata.
     *
     * @param array{title?: string, description?: string, url?: string, type?: string} $data
     * @return array<string, string>
     */
    public static function openGraph(array $data): array
    {
        $og = [
            'og:type'        => $data['type'] ?? 'website',
            'og:site_name'   => (string) (self::config()['nazev'] ?? ''),
            'og:title'       => $data['title'] ?? self::title(null),
            'og:description' => $data['description'] ?? self::description(null),
            'og:url'         => $data['url'] ?? self::canonical('/'),
            'og:locale'      => (string) (self::config()['locale'] ?? 'cs_CZ'),
        ];

        $obrazek = self::image();
        if ($obrazek !== '') {
            $og['og:image'] = $obrazek;
        }

        return $og;
    }

    /**
     * Twitter Card metadata.
     *
     * @param array{title?: string, description?: string} $data
     * @return array<string, string>
     */
    public static function twitterCard(array $data): array
    {
        $card = [
            'twitter:card'        => 'summary_large_image',
            'twitter:title'       => $data['title'] ?? self::title(null),
            'twitter:description' => $data['description'] ?? self::description(null),
        ];

        $obrazek = self::image();
        if ($obrazek !== '') {
            $card['twitter:image'] = $obrazek;
        }

        return $card;
    }

    /**
     * Strukturovaná data (JSON-LD): organizace, web a závod.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function structuredData(): array
    {
        $c    = self::config();
        $url  = self::baseUrl();
        $nazev = (string) ($c['nazev'] ?? 'Vodácká 30');

        $organizace = [
            '@context' => 'https://schema.org',
            '@type'    => 'Organization',
            'name'     => (string) ($c['poradatel'] ?? ''),
            'url'      => $url,
        ];
        if (! empty($c['socialni'])) {
            $organizace['sameAs'] = array_values((array) $c['socialni']);
        }

        $web = [
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => $nazev,
            'url'      => $url,
            'inLanguage' => 'cs',
        ];

        // Termín nejbližšího ročníku se bere z config/zavod.php, aby se ve
        // strukturovaných datech nemohlo rozejít s tím, co tvrdí web.
        $rocnik    = zavod()['rocnik'] ?? [];
        $datumIso  = trim((string) ($rocnik['datum_iso'] ?? ''));
        $zacatek   = $datumIso !== '' ? $datumIso : (string) ($c['datum_zacatku'] ?? '');
        $konec     = $datumIso !== '' ? $datumIso : (string) ($c['datum_konce'] ?? '');

        $zavod = [
            '@context'  => 'https://schema.org',
            '@type'     => 'SportsEvent',
            'name'      => $nazev,
            'startDate' => $zacatek,
            'endDate'   => $konec,
            'eventStatus' => 'https://schema.org/EventScheduled',
            'location'  => [
                '@type' => 'Place',
                'name'  => (string) ($c['misto'] ?? ''),
            ],
            'organizer' => [
                '@type' => 'Organization',
                'name'  => (string) ($c['poradatel'] ?? ''),
            ],
        ];

        return [$organizace, $web, $zavod];
    }

    /**
     * Adresy veřejných stránek pro sitemap.xml.
     *
     * @return array<int, array{loc: string, changefreq: string, priority: string}>
     */
    public static function sitemapUrls(): array
    {
        $routy = [
            '/'                  => ['weekly', '1.0'],
            '/zavod'             => ['monthly', '0.8'],
            '/propozice'         => ['monthly', '0.8'],
            '/prihlaska'         => ['monthly', '0.7'],
            '/startovni-listina' => ['weekly', '0.6'],
            '/vysledky'          => ['monthly', '0.7'],
            '/galerie'           => ['monthly', '0.6'],
            '/kontakt'           => ['yearly', '0.5'],
        ];

        $adresy = [];
        foreach ($routy as $cesta => [$changefreq, $priorita]) {
            $adresy[] = [
                'loc'        => self::canonical($cesta),
                'changefreq' => $changefreq,
                'priority'   => $priorita,
            ];
        }

        return $adresy;
    }
}
