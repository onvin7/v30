<?php

namespace App\Models;

/**
 * Úložiště účtů oddílů v JSON souboru – bez databáze.
 *
 * Struktura souboru storage/oddily.json:
 *   {
 *     "next_id": 3,
 *     "oddily": [
 *       { id, nazev, email, heslo_hash, kontakt_jmeno, telefon, stav,
 *         token_zadosti, vytvoreno, schvaleno, zavodnici: [ { id, jmeno,
 *         kategorie_2026, rocnik_narozeni, aktivni } ] }
 *     ]
 *   }
 *
 * `email` je zároveň přihlašovací jméno. `stav` je cekajici | schvaleny | zamitnuty.
 * Soupiska (`zavodnici`) patří k účtu oddílu, ne k závodníkovi.
 *
 * Zápis je serializovaný přes zámek (flock) a provádí se atomicky (zápis do
 * dočasného souboru + rename), stejně jako u PostRepository.
 */
class OddilRepository
{
    private string $soubor;
    private string $zamek;

    /** Povolené stavy účtu. */
    public const STAVY = ['cekajici', 'schvaleny', 'zamitnuty'];

    public function __construct()
    {
        $slozka       = APP_ROOT . '/storage';
        $this->soubor = $slozka . '/oddily.json';
        $this->zamek  = $slozka . '/oddily.lock';

        if (! is_dir($slozka)) {
            @mkdir($slozka, 0775, true);
        }
    }

    /**
     * Všechny účty oddílů v pořadí uložení.
     *
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        return $this->load()['oddily'];
    }

    /**
     * Účty čekající na schválení pořadatelem (nejstarší první).
     *
     * @return array<int, array<string, mixed>>
     */
    public function cekajici(): array
    {
        $oddily = array_values(array_filter(
            $this->load()['oddily'],
            static fn ($o) => ($o['stav'] ?? '') === 'cekajici'
        ));

        usort($oddily, static fn ($a, $b) => strcmp((string) ($a['vytvoreno'] ?? ''), (string) ($b['vytvoreno'] ?? '')));

        return $oddily;
    }

    /**
     * Počet účtů čekajících na schválení (badge v administraci).
     */
    public function pocetCekajicich(): int
    {
        return count($this->cekajici());
    }

    /**
     * Najde účet podle ID.
     *
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        foreach ($this->load()['oddily'] as $oddil) {
            if ((int) ($oddil['id'] ?? 0) === $id) {
                return $oddil;
            }
        }

        return null;
    }

    /**
     * Najde účet podle e-mailu (case-insensitive).
     *
     * @return array<string, mixed>|null
     */
    public function findByEmail(string $email): ?array
    {
        $hledany = mb_strtolower(trim($email), 'UTF-8');

        foreach ($this->load()['oddily'] as $oddil) {
            if (mb_strtolower((string) ($oddil['email'] ?? ''), 'UTF-8') === $hledany) {
                return $oddil;
            }
        }

        return null;
    }

    /**
     * Najde účet podle tokenu žádosti (odkaz na oznámení).
     *
     * @return array<string, mixed>|null
     */
    public function findByToken(string $token): ?array
    {
        if ($token === '') {
            return null;
        }

        foreach ($this->load()['oddily'] as $oddil) {
            if (hash_equals((string) ($oddil['token_zadosti'] ?? ''), $token)) {
                return $oddil;
            }
        }

        return null;
    }

    /**
     * Zjistí, zda už existuje oddíl se stejným názvem (tolerantně k diakritice).
     */
    public function nazevObsazen(string $nazev): bool
    {
        $hledany = self::normalizuj($nazev);

        foreach ($this->load()['oddily'] as $oddil) {
            if (self::normalizuj((string) ($oddil['nazev'] ?? '')) === $hledany) {
                return true;
            }
        }

        return false;
    }

    /**
     * Založí účet oddílu a vrátí jeho ID. Účet vzniká ve stavu `cekajici`.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): int
    {
        return (int) $this->withLock(function () use ($data) {
            $db = $this->load();
            $id = (int) $db['next_id'];

            $db['oddily'][] = [
                'id'            => $id,
                'nazev'         => (string) ($data['nazev'] ?? ''),
                'email'         => (string) ($data['email'] ?? ''),
                'heslo_hash'    => password_hash((string) ($data['heslo'] ?? ''), PASSWORD_DEFAULT),
                'kontakt_jmeno' => (string) ($data['kontakt_jmeno'] ?? ''),
                'telefon'       => (string) ($data['telefon'] ?? ''),
                'stav'          => 'cekajici',
                'token_zadosti' => bin2hex(random_bytes(16)),
                'vytvoreno'     => date('Y-m-d H:i:s'),
                'schvaleno'     => '',
                'zavodnici'     => [],
            ];
            $db['next_id'] = $id + 1;

            $this->save($db);

            return $id;
        });
    }

    /**
     * Změní stav účtu (schválení / zamítnutí). Vrací true při uložení.
     */
    public function setStav(int $id, string $stav): bool
    {
        if (! in_array($stav, self::STAVY, true)) {
            return false;
        }

        return (bool) $this->withLock(function () use ($id, $stav) {
            $db      = $this->load();
            $nalezen = false;

            foreach ($db['oddily'] as $index => $oddil) {
                if ((int) ($oddil['id'] ?? 0) !== $id) {
                    continue;
                }

                $db['oddily'][$index]['stav']     = $stav;
                $db['oddily'][$index]['schvaleno'] = $stav === 'schvaleny' ? date('Y-m-d H:i:s') : '';
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
     * Nastaví nové heslo účtu (admin reset i změna samotným oddílem).
     */
    public function setHeslo(int $id, string $heslo): bool
    {
        return (bool) $this->withLock(function () use ($id, $heslo) {
            $db      = $this->load();
            $nalezen = false;

            foreach ($db['oddily'] as $index => $oddil) {
                if ((int) ($oddil['id'] ?? 0) !== $id) {
                    continue;
                }

                $db['oddily'][$index]['heslo_hash'] = password_hash($heslo, PASSWORD_DEFAULT);
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
     * Přidá nebo upraví jednoho závodníka v soupisce. Vrací jeho ID.
     *
     * Nový závodník se pozná podle chybějícího (nebo cizího) `id`.
     *
     * @param array<string, mixed> $zavodnik
     */
    public function ulozZavodnika(int $oddilId, array $zavodnik): int
    {
        return (int) $this->withLock(function () use ($oddilId, $zavodnik) {
            $db     = $this->load();
            $vlozene = null;

            foreach ($db['oddily'] as $index => $oddil) {
                if ((int) ($oddil['id'] ?? 0) !== $oddilId) {
                    continue;
                }

                $soupiska = isset($oddil['zavodnici']) && is_array($oddil['zavodnici'])
                    ? $oddil['zavodnici']
                    : [];

                $zavodnikId = (int) ($zavodnik['id'] ?? 0);
                $upraveno   = false;

                foreach ($soupiska as $pozice => $stavajici) {
                    if ((int) ($stavajici['id'] ?? 0) !== $zavodnikId || $zavodnikId === 0) {
                        continue;
                    }

                    $soupiska[$pozice] = array_merge($stavajici, [
                        'jmeno'            => (string) ($zavodnik['jmeno'] ?? $stavajici['jmeno']),
                        'kategorie_2026'   => $zavodnik['kategorie_2026'] ?? ($stavajici['kategorie_2026'] ?? null),
                        'rocnik_narozeni'  => $zavodnik['rocnik_narozeni'] ?? ($stavajici['rocnik_narozeni'] ?? null),
                        'aktivni'          => (int) ($zavodnik['aktivni'] ?? ($stavajici['aktivni'] ?? 1)),
                    ]);
                    $vlozene  = $zavodnikId;
                    $upraveno = true;
                    break;
                }

                if (! $upraveno) {
                    $noveId = 1;
                    foreach ($soupiska as $stavajici) {
                        $noveId = max($noveId, (int) ($stavajici['id'] ?? 0) + 1);
                    }

                    $soupiska[] = [
                        'id'               => $noveId,
                        'jmeno'            => (string) ($zavodnik['jmeno'] ?? ''),
                        'kategorie_2026'   => $zavodnik['kategorie_2026'] ?? null,
                        'rocnik_narozeni'  => $zavodnik['rocnik_narozeni'] ?? null,
                        'aktivni'          => (int) ($zavodnik['aktivni'] ?? 1),
                    ];
                    $vlozene = $noveId;
                }

                $db['oddily'][$index]['zavodnici'] = array_values($soupiska);
                break;
            }

            if ($vlozene === null) {
                return 0;
            }

            $this->save($db);

            return $vlozene;
        });
    }

    /**
     * Odstraní závodníka ze soupisky. Vrací true, pokud byl nalezen.
     */
    public function smazZavodnika(int $oddilId, int $zavodnikId): bool
    {
        return (bool) $this->withLock(function () use ($oddilId, $zavodnikId) {
            $db      = $this->load();
            $nalezen = false;

            foreach ($db['oddily'] as $index => $oddil) {
                if ((int) ($oddil['id'] ?? 0) !== $oddilId) {
                    continue;
                }

                $soupiska = isset($oddil['zavodnici']) && is_array($oddil['zavodnici'])
                    ? $oddil['zavodnici']
                    : [];

                $pred = count($soupiska);
                $soupiska = array_values(array_filter(
                    $soupiska,
                    static fn ($z) => (int) ($z['id'] ?? 0) !== $zavodnikId
                ));

                if (count($soupiska) === $pred) {
                    break;
                }

                $db['oddily'][$index]['zavodnici'] = $soupiska;
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
     * Hromadně zapíše soupisku (import). Existující závodníci se nepřepisují,
     * doplňují se jen ti, kteří v oddílu ještě nejsou (podle jména).
     * Opakované spuštění tedy nic nezduplikuje ani nepřepíše ročník narození.
     *
     * @param array<int, array<string, mixed>> $zavodnici
     * @return int počet skutečně přidaných závodníků
     */
    public function doplnZavodniky(int $oddilId, array $zavodnici): int
    {
        return (int) $this->withLock(function () use ($oddilId, $zavodnici) {
            $db       = $this->load();
            $pridano  = 0;

            foreach ($db['oddily'] as $index => $oddil) {
                if ((int) ($oddil['id'] ?? 0) !== $oddilId) {
                    continue;
                }

                $soupiska = isset($oddil['zavodnici']) && is_array($oddil['zavodnici'])
                    ? $oddil['zavodnici']
                    : [];

                $jmena = [];
                $noveId = 1;
                foreach ($soupiska as $stavajici) {
                    $jmena[self::normalizuj((string) ($stavajici['jmeno'] ?? ''))] = true;
                    $noveId = max($noveId, (int) ($stavajici['id'] ?? 0) + 1);
                }

                foreach ($zavodnici as $zavodnik) {
                    $jmeno = trim((string) ($zavodnik['jmeno'] ?? ''));
                    $klic  = self::normalizuj($jmeno);

                    if ($jmeno === '' || isset($jmena[$klic])) {
                        continue;
                    }

                    $soupiska[] = [
                        'id'              => $noveId,
                        'jmeno'           => $jmeno,
                        'kategorie_2026'  => $zavodnik['kategorie'] ?? null,
                        'rocnik_narozeni' => null,
                        'aktivni'         => 1,
                    ];
                    $jmena[$klic] = true;
                    $noveId++;
                    $pridano++;
                }

                $db['oddily'][$index]['zavodnici'] = $soupiska;
                break;
            }

            if ($pridano > 0) {
                $this->save($db);
            }

            return $pridano;
        });
    }

    /**
     * Smaže účet oddílu i s celou soupiskou (GDPR).
     */
    public function smaz(int $id): bool
    {
        return (bool) $this->withLock(function () use ($id) {
            $db   = $this->load();
            $pred = count($db['oddily']);

            $db['oddily'] = array_values(array_filter(
                $db['oddily'],
                static fn ($o) => (int) ($o['id'] ?? 0) !== $id
            ));

            if (count($db['oddily']) === $pred) {
                return false;
            }

            $this->save($db);

            return true;
        });
    }

    /**
     * Porovnávací tvar názvu – bez diakritiky, malými písmeny.
     */
    public static function normalizuj(string $text): string
    {
        $prevod = [
            'á' => 'a', 'ä' => 'a', 'č' => 'c', 'ď' => 'd', 'é' => 'e', 'ě' => 'e',
            'í' => 'i', 'ľ' => 'l', 'ň' => 'n', 'ó' => 'o', 'ö' => 'o', 'ř' => 'r',
            'š' => 's', 'ť' => 't', 'ú' => 'u', 'ů' => 'u', 'ü' => 'u', 'ý' => 'y',
            'ž' => 'z',
        ];

        $text = mb_strtolower(trim($text), 'UTF-8');
        $text = strtr($text, $prevod);
        $text = (string) preg_replace('/\s+/u', ' ', $text);

        return $text;
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
     * @return array{next_id: int, oddily: array<int, array<string, mixed>>}
     */
    private function load(): array
    {
        $prazdno = ['next_id' => 1, 'oddily' => []];

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

        $oddily = isset($data['oddily']) && is_array($data['oddily']) ? array_values($data['oddily']) : [];
        $next   = isset($data['next_id']) ? (int) $data['next_id'] : 1;

        return ['next_id' => max(1, $next), 'oddily' => $oddily];
    }

    /**
     * @param array{next_id: int, oddily: array<int, array<string, mixed>>} $data
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
