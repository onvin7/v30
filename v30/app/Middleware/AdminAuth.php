<?php

namespace App\Middleware;

use App\Helpers\Log;

/**
 * Ochrana administrace – ověření přihlášení a práce se session.
 *
 * Jeden administrátor, jehož jméno a hash hesla jsou uložené v config/admin.php
 * (bez databáze). Middleware také hlídá nečinnost podle session_timeout.
 */
class AdminAuth
{
    private const SESSION_KEY   = 'admin_id';
    private const SESSION_LAST  = 'admin_last';
    private const SESSION_NAME  = 'admin_uzivatel';

    /**
     * Načte konfiguraci administrace.
     *
     * @return array{uzivatel: string, heslo_hash: string, session_timeout: int}
     */
    private static function config(): array
    {
        $config = require APP_ROOT . '/config/admin.php';

        return array_merge([
            'uzivatel'        => '',
            'heslo_hash'      => '',
            'session_timeout' => 1800,
        ], is_array($config) ? $config : []);
    }

    /**
     * Zda je administrátor přihlášený.
     */
    public static function check(): bool
    {
        return ! empty($_SESSION[self::SESSION_KEY]);
    }

    /**
     * Guard pro chráněné stránky administrace. Nepřihlášeného přesměruje na login
     * a při vypršení nečinnosti jej odhlásí.
     */
    public static function guard(): void
    {
        if (! self::check()) {
            Log::security('Neoprávněný přístup do administrace: ' . ($_SERVER['REQUEST_URI'] ?? '?'));
            redirect('/admin/prihlaseni');
        }

        $config  = self::config();
        $timeout = (int) $config['session_timeout'];
        $last    = (int) ($_SESSION[self::SESSION_LAST] ?? time());

        if ($timeout > 0 && (time() - $last) > $timeout) {
            self::logout();
            flash_set('error', 'Přihlášení vypršelo z důvodu nečinnosti. Přihlaste se prosím znovu.');
            redirect('/admin/prihlaseni');
        }

        $_SESSION[self::SESSION_LAST] = time();
    }

    /**
     * Pokus o přihlášení. Vrací true při úspěchu.
     */
    public static function attempt(string $uzivatel, string $heslo): bool
    {
        $config = self::config();

        $jmenoOk = hash_equals((string) $config['uzivatel'], $uzivatel);
        $hesloOk = password_verify($heslo, (string) $config['heslo_hash']);

        if (! $jmenoOk || ! $hesloOk) {
            return false;
        }

        // Nové ID session proti fixation.
        session_regenerate_id(true);

        $_SESSION[self::SESSION_KEY]    = true;
        $_SESSION[self::SESSION_NAME]   = $uzivatel;
        $_SESSION[self::SESSION_LAST]   = time();

        return true;
    }

    /**
     * Jméno přihlášeného administrátora.
     */
    public static function user(): string
    {
        return (string) ($_SESSION[self::SESSION_NAME] ?? '');
    }

    /**
     * Odhlášení – vyprázdní a zruší session.
     */
    public static function logout(): void
    {
        $_SESSION = [];

        if (session_status() === PHP_SESSION_ACTIVE) {
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }

            session_destroy();
        }
    }
}
