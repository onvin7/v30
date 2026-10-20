<?php

namespace App\Models;

/**
 * Úložiště příspěvků (novinek) v JSON souboru – bez databáze.
 *
 * Struktura souboru storage/prispevky.json:
 *   { "next_id": 3, "posts": [ { id, nadpis, datum, perex, text, zverejneno, vytvoreno, upraveno } ] }
 *
 * Zápis je serializovaný přes zámek (flock) a provádí se atomicky
 * (zápis do dočasného souboru + rename), aby čtenář nikdy nedostal půlku souboru.
 */
class PostRepository
{
    private string $soubor;
    private string $zamek;

    public function __construct()
    {
        $slozka        = APP_ROOT . '/storage';
        $this->soubor  = $slozka . '/prispevky.json';
        $this->zamek   = $slozka . '/prispevky.lock';

        if (! is_dir($slozka)) {
            @mkdir($slozka, 0775, true);
        }
    }

    /**
     * Všechny příspěvky (nejnovější první).
     *
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        $posts = $this->load()['posts'];
        usort($posts, [$this, 'porovnej']);

        return $posts;
    }

    /**
     * Jen zveřejněné příspěvky, nejnovější první, omezené na $limit.
     *
     * @return array<int, array<string, mixed>>
     */
    public function published(int $limit): array
    {
        $posts = array_values(array_filter(
            $this->load()['posts'],
            static fn ($p) => (int) ($p['zverejneno'] ?? 0) === 1
        ));

        usort($posts, [$this, 'porovnej']);

        return array_slice($posts, 0, max(0, $limit));
    }

    /**
     * Najde příspěvek podle ID.
     *
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        foreach ($this->load()['posts'] as $post) {
            if ((int) ($post['id'] ?? 0) === $id) {
                return $post;
            }
        }

        return null;
    }

    /**
     * Vytvoří nový příspěvek a vrátí jeho ID.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): int
    {
        return $this->withLock(function () use ($data) {
            $db  = $this->load();
            $id  = (int) $db['next_id'];
            $now = date('Y-m-d H:i:s');

            $db['posts'][] = [
                'id'         => $id,
                'nadpis'     => (string) ($data['nadpis'] ?? ''),
                'datum'      => (string) ($data['datum'] ?? ''),
                'perex'      => (string) ($data['perex'] ?? ''),
                'text'       => (string) ($data['text'] ?? ''),
                'zverejneno' => (int) ($data['zverejneno'] ?? 0),
                'vytvoreno'  => $now,
                'upraveno'  => $now,
            ];
            $db['next_id'] = $id + 1;

            $this->save($db);

            return $id;
        });
    }

    /**
     * Upraví příspěvek. Vrací true, pokud byl nalezen a uložen.
     *
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool
    {
        return $this->withLock(function () use ($id, $data) {
            $db      = $this->load();
            $nalezen = false;

            foreach ($db['posts'] as $index => $post) {
                if ((int) ($post['id'] ?? 0) !== $id) {
                    continue;
                }

                $db['posts'][$index] = array_merge($post, [
                    'nadpis'     => (string) ($data['nadpis'] ?? $post['nadpis']),
                    'datum'      => (string) ($data['datum'] ?? $post['datum']),
                    'perex'      => (string) ($data['perex'] ?? ''),
                    'text'       => (string) ($data['text'] ?? ''),
                    'zverejneno' => (int) ($data['zverejneno'] ?? 0),
                    'upraveno'  => date('Y-m-d H:i:s'),
                ]);
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
     * Smaže příspěvek. Vrací true, pokud byl nalezen a smazán.
     */
    public function delete(int $id): bool
    {
        return $this->withLock(function () use ($id) {
            $db     = $this->load();
            $pred   = count($db['posts']);
            $db['posts'] = array_values(array_filter(
                $db['posts'],
                static fn ($p) => (int) ($p['id'] ?? 0) !== $id
            ));

            if (count($db['posts']) === $pred) {
                return false;
            }

            $this->save($db);

            return true;
        });
    }

    /**
     * Porovnání pro řazení: nejnovější datum první, při shodě vyšší ID první.
     *
     * @param array<string, mixed> $a
     * @param array<string, mixed> $b
     */
    private function porovnej(array $a, array $b): int
    {
        $datum = strcmp((string) ($b['datum'] ?? ''), (string) ($a['datum'] ?? ''));
        if ($datum !== 0) {
            return $datum;
        }

        return (int) ($b['id'] ?? 0) <=> (int) ($a['id'] ?? 0);
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
     * @return array{next_id: int, posts: array<int, array<string, mixed>>}
     */
    private function load(): array
    {
        if (! is_file($this->soubor)) {
            return ['next_id' => 1, 'posts' => []];
        }

        $obsah = @file_get_contents($this->soubor);
        if ($obsah === false || $obsah === '') {
            return ['next_id' => 1, 'posts' => []];
        }

        $data = json_decode($obsah, true);
        if (! is_array($data)) {
            return ['next_id' => 1, 'posts' => []];
        }

        $posts = isset($data['posts']) && is_array($data['posts']) ? array_values($data['posts']) : [];
        $next  = isset($data['next_id']) ? (int) $data['next_id'] : 1;

        return ['next_id' => max(1, $next), 'posts' => $posts];
    }

    /**
     * @param array{next_id: int, posts: array<int, array<string, mixed>>} $data
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
