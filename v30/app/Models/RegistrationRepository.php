<?php

namespace App\Models;

/**
 * Úložiště přihlášek (oddílů i jednotlivců) v JSON souboru – bez databáze.
 *
 * Struktura souboru storage/prihlasky.json:
 *   {
 *     "next_id": 12,
 *     "next_vs": 1000000012,
 *     "prihlasky": [
 *       { id (V30-2027-0001), rok, typ (oddil|jednotlivec), oddil_id, oddil_nazev,
 *         email, variabilni_symbol, castka, zavodnici: [...], poznamka, stav,
 *         vytvoreno }
 *     ]
 *   }
 *
 * `oddil_nazev` i `kategorie_nazev` se do přihlášky kopírují, aby zůstala čitelná
 * i po přejmenování oddílu nebo kategorie. `cena` se ukládá v okamžiku přihlášky –
 * pozdější změna ceníku nesmí zpětně měnit vystavené přihlášky.
 */
class RegistrationRepository
{
    private string $soubor;
    private string $zamek;

    /** Povolené stavy přihlášky. */
    public const STAVY = ['nova', 'zaplaceno', 'stornovano'];

    /** První přidělený variabilní symbol. */
    private const PRVNI_VS = 1000000001;

    public function __construct()
    {
        $slozka       = APP_ROOT . '/storage';
        $this->soubor = $slozka . '/prihlasky.json';
        $this->zamek  = $slozka . '/prihlasky.lock';

        if (! is_dir($slozka)) {
            @mkdir($slozka, 0775, true);
        }
    }

    /**
     * Vytvoří přihlášku – přidělí ID a variabilní symbol. Vrací uložený záznam.
     *
     * @param array<string, mixed> $prihlaska
     * @return array<string, mixed>
     */
    public function create(array $prihlaska): array
    {
        return (array) $this->withLock(function () use ($prihlaska) {
            $db  = $this->load();
            $id  = (int) $db['next_id'];
            $vs  = (int) $db['next_vs'];
            $rok = (int) ($prihlaska['rok'] ?? date('Y'));

            $zaznam = array_merge($prihlaska, [
                'id'                => sprintf('V30-%d-%04d', $rok, $id),
                'rok'               => $rok,
                'typ'               => (string) ($prihlaska['typ'] ?? 'jednotlivec'),
                'oddil_id'          => $prihlaska['oddil_id'] ?? null,
                'oddil_nazev'       => (string) ($prihlaska['oddil_nazev'] ?? ''),
                'email'             => (string) ($prihlaska['email'] ?? ''),
                'variabilni_symbol' => (string) $vs,
                'castka'            => (int) ($prihlaska['castka'] ?? 0),
                'zavodnici'         => isset($prihlaska['zavodnici']) && is_array($prihlaska['zavodnici'])
                    ? array_values($prihlaska['zavodnici'])
                    : [],
                'poznamka'          => (string) ($prihlaska['poznamka'] ?? ''),
                'stav'              => 'nova',
                'vytvoreno'         => date('Y-m-d H:i:s'),
            ]);

            $db['prihlasky'][] = $zaznam;
            $db['next_id']     = $id + 1;
            $db['next_vs']     = $vs + 1;

            $this->save($db);

            return $zaznam;
        });
    }

    /**
     * Přihlášky jednoho oddílu v daném ročníku (nejnovější první).
     *
     * @return array<int, array<string, mixed>>
     */
    public function proOddil(int $oddilId, int $rok): array
    {
        $prihlasky = array_values(array_filter(
            $this->load()['prihlasky'],
            static fn ($p) => (int) ($p['rok'] ?? 0) === $rok
                && $p['typ'] === 'oddil'
                && (int) ($p['oddil_id'] ?? 0) === $oddilId
        ));

        return $this->serad($prihlasky);
    }

    /**
     * Všechny přihlášky ročníku (admin, startovní listina) – nejnovější první.
     *
     * @return array<int, array<string, mixed>>
     */
    public function proRok(int $rok): array
    {
        $prihlasky = array_values(array_filter(
            $this->load()['prihlasky'],
            static fn ($p) => (int) ($p['rok'] ?? 0) === $rok
        ));

        return $this->serad($prihlasky);
    }

    /**
     * Najde přihlášku podle ID.
     *
     * @return array<string, mixed>|null
     */
    public function find(string $id): ?array
    {
        foreach ($this->load()['prihlasky'] as $prihlaska) {
            if ((string) ($prihlaska['id'] ?? '') === $id) {
                return $prihlaska;
            }
        }

        return null;
    }

    /**
     * Změní stav přihlášky (označení platby). Vrací true při uložení.
     */
    public function setStav(string $id, string $stav): bool
    {
        if (! in_array($stav, self::STAVY, true)) {
            return false;
        }

        return (bool) $this->withLock(function () use ($id, $stav) {
            $db      = $this->load();
            $nalezen = false;

            foreach ($db['prihlasky'] as $index => $prihlaska) {
                if ((string) ($prihlaska['id'] ?? '') !== $id) {
                    continue;
                }

                $db['prihlasky'][$index]['stav'] = $stav;
                $nalezen = true;
                break;
            }

            if ($nalezen) {
                $this->save($db);
            }

            return $nalezen;
        });
    }

    /**
     * @param array<int, array<string, mixed>> $prihlasky
     * @return array<int, array<string, mixed>>
     */
    private function serad(array $prihlasky): array
    {
        usort($prihlasky, static fn ($a, $b) => strcmp((string) ($b['id'] ?? ''), (string) ($a['id'] ?? '')));

        return $prihlasky;
    }

    /**
     * Provede zápis pod výhradním zámkem.
     *
     * @template T
     * @param callable(): T $operace
     * @return T
     */
    private function withLock(callable $operace)
    {
        $zamek = @fopen($this->zamek, 'c');
        if ($zamek === false) {
            // Bez zámku raději nezapisovat.
            return null;
        }

        try {
            flock($zamek, LOCK_EX);

            return $operace();
        } finally {
            flock($zamek, LOCK_UN);
            fclose($zamek);
        }
    }

    /**
     * @return array{next_id: int, next_vs: int, prihlasky: array<int, array<string, mixed>>}
     */
    private function load(): array
    {
        $prazdno = ['next_id' => 1, 'next_vs' => self::PRVNI_VS, 'prihlasky' => []];

        if (! is_file($this->soubor)) {
            return $prazdno;
        }

        $obsah = @file_get_contents($this->soubor);
        if ($obsah === false || $obsah === '') {
            return $prazdno;
        }

        $data = json_decode($obsah, true);
        if (! is_array($data)) {
            return $prazdno;
        }

        $prihlasky = isset($data['prihlasky']) && is_array($data['prihlasky']) ? array_values($data['prihlasky']) : [];
        $next      = isset($data['next_id']) ? (int) $data['next_id'] : 1;
        $nextVs    = isset($data['next_vs']) ? (int) $data['next_vs'] : self::PRVNI_VS;

        return [
            'next_id'   => max(1, $next),
            'next_vs'   => max(self::PRVNI_VS, $nextVs),
            'prihlasky' => $prihlasky,
        ];
    }

    /**
     * @param array{next_id: int, next_vs: int, prihlasky: array<int, array<string, mixed>>} $data
     */
    private function save(array $data): void
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return;
        }

        $docasny = $this->soubor . '.' . bin2hex(random_bytes(4));

        if (@file_put_contents($docasny, $json, LOCK_EX) !== false) {
            @rename($docasny, $this->soubor);
        }
    }
}
