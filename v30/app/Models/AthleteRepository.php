<?php

namespace App\Models;

/**
 * Veřejná, normalizovaná projekce ověřených závodních listin.
 */
class AthleteRepository
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        $stored = $this->loadStored();
        if ($stored !== []) {
            return $stored;
        }

        $zavodnici = [];

        foreach (PublicRaceRepository::results() as $skupina) {
            foreach ($skupina['vysledky'] as $radek) {
                $zavodnici[] = [
                    'rok'       => 2026,
                    'jmeno'     => (string) $radek['jmeno'],
                    'oddil'     => (string) $radek['oddil'],
                    'kategorie' => (string) $skupina['nazev'],
                    'cislo'     => (string) $radek['cislo'],
                    'poradi'    => (string) $radek['poradi'],
                    'cas'       => (string) $radek['cas'],
                    'overeno'   => true,
                ];
            }
        }

        return $zavodnici;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadStored(): array
    {
        $path = APP_ROOT . '/storage/zavodnici.json';
        if (! is_file($path)) {
            return [];
        }

        $content = @file_get_contents($path);
        $data = $content === false ? null : json_decode($content, true);
        if (! is_array($data)) {
            return [];
        }

        $rows = [];
        foreach ($data as $row) {
            if (! is_array($row) || ($row['overeno'] ?? false) !== true) {
                continue;
            }
            if (! isset($row['rok'], $row['jmeno'], $row['oddil'], $row['kategorie'], $row['cislo'])) {
                continue;
            }
            $rows[] = [
                'rok'       => (int) $row['rok'],
                'jmeno'     => (string) $row['jmeno'],
                'oddil'     => (string) $row['oddil'],
                'kategorie' => (string) $row['kategorie'],
                'cislo'     => (string) $row['cislo'],
                'poradi'    => (string) ($row['poradi'] ?? ''),
                'cas'       => (string) ($row['cas'] ?? ''),
                'overeno'   => true,
            ];
        }

        return $rows;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function search(string $query, ?int $year): array
    {
        $query = $this->normalize($query);
        $rows  = array_filter($this->all(), function (array $row) use ($query, $year): bool {
            if ($year !== null && (int) $row['rok'] !== $year) {
                return false;
            }

            if ($query === '') {
                return true;
            }

            return str_contains($this->normalize($row['jmeno']), $query)
                || str_contains($this->normalize($row['oddil']), $query);
        });

        return array_slice(array_values($rows), 0, 100);
    }

    /**
     * @return array<int, int>
     */
    public function verifiedYears(): array
    {
        $years = array_map(static fn (array $row): int => (int) $row['rok'], $this->all());
        $years = array_values(array_unique($years));
        rsort($years);

        return $years;
    }

    private function normalize(string $text): string
    {
        $text = mb_strtolower($text);
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);

        return $ascii !== false ? $ascii : $text;
    }
}
