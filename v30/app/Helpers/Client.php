<?php

namespace App\Helpers;

/**
 * Klient requestu: IP adresa, její anonymizace (GDPR) a základní detekce
 * zařízení / prohlížeče / OS pro potřeby logů.
 *
 * Zjednodušená obdoba UserAgentHelper + IPAnonymizerHelper z projektu Cyklistickey.
 */
class Client
{
    /**
     * IP adresa klienta (respektuje proxy hlavičky). Vrací null, pokud ji nelze zjistit.
     */
    public static function ip(): ?string
    {
        $kandidati = [];

        foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'] as $klic) {
            if (! empty($_SERVER[$klic])) {
                $kandidati[] = $_SERVER[$klic];
            }
        }

        foreach ($kandidati as $hodnota) {
            // X-Forwarded-For může obsahovat seznam "klient, proxy1, proxy2".
            foreach (explode(',', $hodnota) as $cast) {
                $ip = trim($cast);
                if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                    return $ip;
                }
            }
        }

        return null;
    }

    /**
     * Anonymizuje IP adresu – IPv4 vynuluje poslední oktet, IPv6 posledních 80 bitů.
     * Používá se pro zápisy do logů, aby se neukládaly úplné osobní údaje.
     */
    public static function anonymizeIp(?string $ip): ?string
    {
        if ($ip === null || $ip === '') {
            return null;
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
            $casti = explode('.', $ip);
            $casti[3] = '0';

            return implode('.', $casti);
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
            $binarni = inet_pton($ip);
            if ($binarni === false) {
                return null;
            }

            // Zachová první 48 bitů (6 bajtů), zbytek vynuluje.
            $zkracene = substr($binarni, 0, 6) . str_repeat("\x00", 10);

            return inet_ntop($zkracene) ?: null;
        }

        return null;
    }

    /**
     * Anonymizovaná IP klienta pro logy.
     */
    public static function anonymizedIp(): string
    {
        return self::anonymizeIp(self::ip()) ?? 'neznámá';
    }

    /**
     * Typ zařízení podle User-Agentu: mobile / tablet / desktop.
     */
    public static function deviceType(): string
    {
        $ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

        if ($ua === '') {
            return 'neznámé';
        }

        if (preg_match('/ipad|tablet|(android(?!.*mobile))/', $ua)) {
            return 'tablet';
        }

        if (preg_match('/mobile|iphone|ipod|android|blackberry|windows phone/', $ua)) {
            return 'mobil';
        }

        return 'desktop';
    }

    /**
     * Prohlížeč podle User-Agentu.
     */
    public static function browser(): string
    {
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

        return match (true) {
            stripos($ua, 'Edg') !== false        => 'Edge',
            stripos($ua, 'OPR') !== false,
            stripos($ua, 'Opera') !== false      => 'Opera',
            stripos($ua, 'Chrome') !== false     => 'Chrome',
            stripos($ua, 'Firefox') !== false    => 'Firefox',
            stripos($ua, 'Safari') !== false     => 'Safari',
            default                               => 'jiný',
        };
    }

    /**
     * Operační systém podle User-Agentu.
     */
    public static function os(): string
    {
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

        return match (true) {
            stripos($ua, 'Windows') !== false    => 'Windows',
            stripos($ua, 'Android') !== false    => 'Android',
            stripos($ua, 'iPhone') !== false,
            stripos($ua, 'iPad') !== false       => 'iOS',
            stripos($ua, 'Mac OS') !== false     => 'macOS',
            stripos($ua, 'Linux') !== false      => 'Linux',
            default                               => 'jiný',
        };
    }
}
