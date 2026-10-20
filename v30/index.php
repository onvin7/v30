<?php
/**
 * Vstupní bod projektu Vodácká 30.
 * Předá řízení front controlleru ve složce /web (stejně jako referenční projekt).
 */

// Zpevnění session cookie – HttpOnly, SameSite, strict mode a secure za HTTPS.
// Musí proběhnout ještě před session_start() ve web/index.php.
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', '');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_secure', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? '1' : '0');

require __DIR__ . '/web/index.php';
