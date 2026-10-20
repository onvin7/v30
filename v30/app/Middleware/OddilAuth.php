<?php

namespace App\Middleware;

use App\Helpers\Log;
use App\Models\OddilRepository;

/**
 * Ochrana zóny oddílu – přihlášení vedoucího oddílu.
 *
 * Na rozdíl od administrace nejde o jednoho uživatele z konfigurace, ale o účty
 * z OddilRepository. Session klíče jsou oddělené od administrace, takže vedoucí
 * oddílu i pořadatel mohou být přihlášeni současně a navzájem se neovlivní.
 *
 * Do schválení pořadatelem se přihlásit nelze – `attempt()` to odliší od
 * špatného hesla, aby se vedoucí dozvěděl skutečný důvod.
 */
class OddilAuth
{
    private const SESSION_KEY   = 'oddil_id';
    private const SESSION_EMAIL = 'oddil_email';
    private const SESSION_LAST  = 'oddil_last';

    /** Doba nečinnosti, po které se přihlášení ukončí (30 minut). */
    private const TIMEOUT = 1800;

    /**
     * Zda je vedoucí oddílu přihlášený.
     */
    public static function check(): bool
    {
        return ! empty($_SESSION[self::SESSION_KEY]);
    }

    /**
     * Guard pro chráněné stránky zóny oddílu. Nepřihlášeného přesměruje na login
     * a při vypršení nečinnosti jej odhlásí.
     */
    public static function guard(): void
    {
        if (! self::check()) {
            Log::security('Neoprávněný přístup do zóny oddílu: ' . ($_SERVER['REQUEST_URI'] ?? '?'));
            redirect('/oddil/prihlaseni');
        }

        // Účet mohl mezitím smazat pořadatel – pak session neplatí.
        if (self::user() === null) {
            self::logout();
            flash_set('error', 'Účet oddílu už není dostupný. Přihlaste se prosím znovu.');
            redirect('/oddil/prihlaseni');
        }

        $last = (int) ($_SESSION[self::SESSION_LAST] ?? time());

        if ((time() - $last) > self::TIMEOUT) {
            self::logout();
            flash_set('error', 'Přihlášení vypršelo z důvodu nečinnosti. Přihlaste se prosím znovu.');
            redirect('/oddil/prihlaseni');
        }

        $_SESSION[self::SESSION_LAST] = time();
    }

    /**
     * Pokus o přihlášení. Vrací výsledek jako řetězec:
     *   ok | cekajici | zamitnuty | spatne
     */
    public static function attempt(string $email, string $heslo): string
    {
        $repo  = new OddilRepository();
        $oddil = $repo->findByEmail($email);

        // Neexistující e-mail i špatné heslo vrací stejný výsledek (neprozrazujeme,
        // které e-maily jsou registrované).
        if ($oddil === null || ! password_verify($heslo, (string) ($oddil['heslo_hash'] ?? ''))) {
            return 'spatne';
        }

        $stav = (string) ($oddil['stav'] ?? '');

        if ($stav === 'cekajici') {
            return 'cekajici';
        }

        if ($stav !== 'schvaleny') {
            return 'zamitnuty';
        }

        // Nové ID session proti fixation.
        session_regenerate_id(true);

        $_SESSION[self::SESSION_KEY]   = (int) $oddil['id'];
        $_SESSION[self::SESSION_EMAIL] = (string) $oddil['email'];
        $_SESSION[self::SESSION_LAST]  = time();

        return 'ok';
    }

    /**
     * Přihlášený oddíl, nebo null.
     *
     * @return array<string, mixed>|null
     */
    public static function user(): ?array
    {
        $id = (int) ($_SESSION[self::SESSION_KEY] ?? 0);

        if ($id <= 0) {
            return null;
        }

        return (new OddilRepository())->find($id);
    }

    /**
     * Odhlášení – vyprázdní a zruší session.
     */
    public static function logout(): void
    {
        unset(
            $_SESSION[self::SESSION_KEY],
            $_SESSION[self::SESSION_EMAIL],
            $_SESSION[self::SESSION_LAST]
        );
    }
}
