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
