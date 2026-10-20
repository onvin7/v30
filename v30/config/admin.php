<?php
/**
 * Přihlášení do administrace Vodácké 30.
 *
 * HESLO JE ULOŽENÉ JEN JAKO HASH – nikdy zde nedávej plaintext.
 * Výchozí přihlašovací údaje: jméno `admin`, heslo `admin`.
 *
 * Změna hesla:
 *   php -r "echo password_hash('nove-heslo', PASSWORD_DEFAULT);"
 * a výsledný hash vlož do `heslo_hash`.
 */
return [
    'uzivatel'        => 'admin',
    'heslo_hash'      => '$2y$10$yscyk.9RO.GdzUhbg0peP.HnExz.G8DgWhD3dq8aFCtQOuAS9j432',
    'session_timeout' => 1800, // 30 minut nečinnosti, po které se admin odhlásí
];
