<?php
/**
 * Pomocné funkce sdílené kontrolery a šablonami.
 *
 * Drží drobnou infrastrukturu, kterou potřebují formuláře (CSRF, flash zprávy,
 * návrat vyplněných hodnot) a přesměrování. Bez tohoto souboru by se stejný
 * kód opakoval v každém kontroleru.
 */

/**
 * Escapování výstupu do HTML.
 */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Vrátí CSRF token z aktuální session (případně jej vytvoří).
 */
function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

/**
 * Skryté formulářové pole s CSRF tokenem.
 */
function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

/**
 * Ověří CSRF token konstantním porovnáním.
 */
function csrf_verify(?string $token): bool
{
    $expected = $_SESSION['_csrf'] ?? null;

    if (! is_string($expected) || $expected === '' || ! is_string($token) || $token === '') {
        return false;
    }

    return hash_equals($expected, $token);
}

/**
 * Uloží jednorázovou zprávu do session.
 */
function flash_set(string $key, mixed $value): void
{
    $_SESSION['_flash'][$key] = $value;
}

/**
 * Přečte a zároveň smaže jednorázovou zprávu.
 */
function flash_get(string $key, mixed $default = null): mixed
{
    if (! isset($_SESSION['_flash'][$key])) {
        return $default;
    }

    $value = $_SESSION['_flash'][$key];
    unset($_SESSION['_flash'][$key]);

    return $value;
}

/**
 * Vrátí vyplněnou hodnotu z neúspěšného odeslání formuláře.
 */
function old_get(string $key, string $default = ''): string
{
    $old = $_SESSION['_flash']['old'] ?? [];

    return isset($old[$key]) ? (string) $old[$key] : $default;
}

/**
 * Přesměruje na jinou cestu a ukončí požadavek.
 */
function redirect(string $path): never
{
    header('Location: ' . $path, true, 302);
    exit;
}

/**
 * Mapa obrázků webu (config/images.php) – načte se jednou za požadavek.
 *
 * @return array<string, string>
 */
function images(): array
{
    static $map = null;

    if ($map === null) {
        $loaded = require APP_ROOT . '/config/images.php';
        $map    = is_array($loaded) ? $loaded : [];
    }

    return $map;
}

/**
 * Údaje o závodě (config/zavod.php) – načte se jednou za požadavek.
 *
 * @return array<string, mixed>
 */
function zavod(): array
{
    static $data = null;

    if ($data === null) {
        $loaded = require APP_ROOT . '/config/zavod.php';
        $data   = is_array($loaded) ? $loaded : [];
    }

    return $data;
}

/**
 * URL obrázku podle klíče; prázdný řetězec znamená „obrázek není k dispozici“.
 */
function img_url(string $key): string
{
    $url = images()[$key] ?? '';

    return is_string($url) ? trim($url) : '';
}

/**
 * Zda je pro daný klíč k dispozici obrázek.
 */
function has_img(string $key): bool
{
    return img_url($key) !== '';
}

/**
 * Sada variant obrázku pro atribut `srcset`.
 *
 * Fotky v `assets/img/` jsou připravené ve více šířkách a liší se jen číslem
 * v názvu (`{klíč}-{šířka}.jpg`), takže varianty odvodíme z výchozí cesty
 * v config/images.php. Prázdný řetězec znamená „použij jen samotné `src`“.
 *
 * @param list<int|string> $sirky požadované šířky v pixelech
 */
function img_srcset(string $key, array $sirky): string
{
    $url = img_url($key);

    if ($url === '' || ! preg_match('#^(.*)-(\d+)(\.[A-Za-z]+)$#', $url, $casti)) {
        return '';
    }

    [, $zaklad, , $pripona] = $casti;

    $varianty = [];
    foreach ($sirky as $w) {
        $w = (int) $w;

        if ($w > 0) {
            $varianty[$w] = sprintf('%s-%d%s %dw', $zaklad, $w, $pripona, $w);
        }
    }

    return implode(', ', $varianty);
}
