<?php

namespace App\Controllers\Admin;

use App\Helpers\Client;
use App\Helpers\Log;
use App\Helpers\RateLimit;
use App\Middleware\AdminAuth;

/**
 * Přihlášení a odhlášení administrace.
 */
class AuthAdminController
{
    private const MAX_POKUSU = 5;
    private const OKNO       = 900; // 15 minut

    /**
     * Klíč pro rate limiting podle anonymizované IP.
     */
    private function klicLimitu(): string
    {
        return 'login:' . (Client::anonymizeIp(Client::ip()) ?? 'neznama');
    }

    /**
     * Přihlašovací formulář.
     */
    public function showLogin()
    {
        if (AdminAuth::check()) {
            redirect('/admin');
        }

        $adminTitle   = 'Přihlášení';
        $adminSection = 'prihlaseni';
        $adminView    = APP_ROOT . '/app/Views/admin/login.php';

        require APP_ROOT . '/app/Views/admin/layout.php';
    }

    /**
     * Zpracování přihlášení.
     */
    public function login()
    {
        // Rate limiting – při překročení se heslo vůbec neověřuje.
        if (! RateLimit::check($this->klicLimitu(), self::MAX_POKUSU, self::OKNO)) {
            Log::login('Překročen limit pokusů o přihlášení');
            flash_set('error', 'Příliš mnoho pokusů o přihlášení. Zkuste to prosím později.');
            redirect('/admin/prihlaseni');
        }

        // CSRF a honeypot – tiché odmítnutí.
        if (! csrf_verify($_POST['_csrf'] ?? null) || trim((string) ($_POST['website'] ?? '')) !== '') {
            Log::login('Neplatný CSRF token nebo vyplněný honeypot');
            flash_set('error', 'Přihlášení se nepodařilo. Zkuste to prosím znovu.');
            redirect('/admin/prihlaseni');
        }

        $uzivatel = trim((string) ($_POST['uzivatel'] ?? ''));
        $heslo    = (string) ($_POST['heslo'] ?? '');

        if ($uzivatel === '' || $heslo === '' || ! AdminAuth::attempt($uzivatel, $heslo)) {
            Log::login('Neúspěšné přihlášení pro uživatele "' . $uzivatel . '"');
            flash_set('error', 'Nesprávné jméno nebo heslo.');
            flash_set('old', ['uzivatel' => $uzivatel]);
            redirect('/admin/prihlaseni');
        }

        Log::login('Úspěšné přihlášení uživatele "' . $uzivatel . '"');
        flash_set('old', []);
        flash_set('success', 'Jste přihlášen.');

        redirect('/admin');
    }

    /**
     * Odhlášení.
     */
    public function logout()
    {
        if (! csrf_verify($_POST['_csrf'] ?? null)) {
            redirect('/admin');
        }

        Log::login('Odhlášení administrátora');
        AdminAuth::logout();
        flash_set('success', 'Byli jste odhlášeni.');

        redirect('/admin/prihlaseni');
    }
}
