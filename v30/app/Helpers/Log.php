<?php

namespace App\Helpers;

/**
 * Jednoduché logování do souborů ve složce /storage.
 *
 * Zapisuje se vždy anonymizovaná IP adresa (GDPR) a čas.
 * Konvence souborů:
 *   - security.log – neoprávněný přístup, podezřelé chování
 *   - login.log    – pokusy o přihlášení (úspěch i neúspěch)
 *   - admin.log    – změny příspěvků v administraci
 */
class Log
{
    /**
     * Zapíše řádek do zvoleného logu.
     */
    public static function write(string $soubor, string $zprava): void
    {
        $slozka = APP_ROOT . '/storage';
        if (! is_dir($slozka)) {
            @mkdir($slozka, 0775, true);
        }

        $radek = sprintf(
            "[%s] ip=%s zařízení=%s prohlížeč=%s os=%s | %s\n",
            date('Y-m-d H:i:s'),
            Client::anonymizedIp(),
            Client::deviceType(),
            Client::browser(),
            Client::os(),
            $zprava
        );

        @file_put_contents($slozka . '/' . $soubor, $radek, FILE_APPEND | LOCK_EX);
    }

    public static function security(string $zprava): void
    {
        self::write('security.log', $zprava);
    }

    public static function login(string $zprava): void
    {
        self::write('login.log', $zprava);
    }

    public static function admin(string $zprava): void
    {
        self::write('admin.log', $zprava);
    }
}
