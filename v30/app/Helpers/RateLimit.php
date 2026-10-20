<?php

namespace App\Helpers;

/**
 * Jednoduchý souborový rate limiter (bez databáze).
 *
 * Pokusy se drží v JSON souboru ve /storage podle klíče (např. IP adresa).
 * Používá se pro omezení počtu pokusů o přihlášení do administrace.
 */
class RateLimit
{
    private const SOUBOR = '/storage/rate_limit.json';

    /**
     * Zaznamená pokus a vrátí true, pokud ještě není překročen limit.
     *
     * @param string $klic Jednoznačný klíč (např. "login:127.0.0.0").
     * @param int    $max  Maximální počet pokusů v okně.
     * @param int    $okno Délka okna v sekundách.
     */
    public static function check(string $klic, int $max, int $okno): bool
    {
        $nyni  = time();
        $data  = self::nacti();

        $pokusy = self::aktivni($data[$klic] ?? [], $nyni, $okno);

        if (count($pokusy) >= $max) {
            $data[$klic] = $pokusy;
            self::uloz($data);

            return false;
        }

        $pokusy[]    = $nyni;
        $data[$klic] = $pokusy;
        self::uloz($data);

        return true;
    }

    /**
     * Kolik pokusů ještě zbývá v aktuálním okně.
     */
    public static function remaining(string $klic, int $max, int $okno): int
    {
        $pokusy = self::aktivni(self::nacti()[$klic] ?? [], time(), $okno);

        return max(0, $max - count($pokusy));
    }

    /**
     * Čas (unix timestamp), kdy se okno posune a limit se uvolní.
     * Vrací 0, pokud pro klíč nejsou žádné aktivní pokusy.
     */
    public static function resetTime(string $klic, int $okno): int
    {
        $pokusy = self::aktivni(self::nacti()[$klic] ?? [], time(), $okno);

        if ($pokusy === []) {
            return 0;
        }

        return min($pokusy) + $okno;
    }

    /**
     * Ponechá jen časová razítka spadající do aktuálního okna.
     *
     * @param array<int, int> $pokusy
     * @return array<int, int>
     */
    private static function aktivni(array $pokusy, int $nyni, int $okno): array
    {
        $hranice = $nyni - $okno;

        return array_values(array_filter(
            $pokusy,
            static fn ($t) => is_int($t) && $t > $hranice
        ));
    }

    /**
     * @return array<string, array<int, int>>
     */
    private static function nacti(): array
    {
        $cesta = APP_ROOT . self::SOUBOR;
        if (! is_file($cesta)) {
            return [];
        }

        $obsah = @file_get_contents($cesta);
        if ($obsah === false || $obsah === '') {
            return [];
        }

        $data = json_decode($obsah, true);

        return is_array($data) ? $data : [];
    }

    /**
     * @param array<string, array<int, int>> $data
     */
    private static function uloz(array $data): void
    {
        $slozka = APP_ROOT . '/storage';
        if (! is_dir($slozka)) {
            @mkdir($slozka, 0775, true);
        }

        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return;
        }

        $soubor  = $slozka . '/rate_limit.json';
        $docasny = $soubor . '.' . bin2hex(random_bytes(4));

        if (@file_put_contents($docasny, $json, LOCK_EX) !== false) {
            @rename($docasny, $soubor);
        }
    }
}
