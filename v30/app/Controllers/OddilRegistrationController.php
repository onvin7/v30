<?php

namespace App\Controllers;

use App\Helpers\Log;
use App\Middleware\OddilAuth;
use App\Models\OddilRepository;
use App\Models\RegistrationRepository;

/**
 * Hromadná přihláška oddílu do aktuálního ročníku.
 *
 * Oddíl vybere ze své soupisky závodníky a u každého kategorii. Ukládá se jeden
 * záznam s jedním variabilním symbolem a částkou za celek (viz plán, oddíl 8.1).
 * Oddíl vidí jen svoje závodníky i svoje přihlášky – vše se filtruje podle ID
 * ze session, nikdy podle parametru z URL.
 */
class OddilRegistrationController
{
    private OddilRepository $oddily;
    private RegistrationRepository $prihlasky;

    public function __construct()
    {
        $this->oddily    = new OddilRepository();
        $this->prihlasky = new RegistrationRepository();
    }

    /**
     * Formulář hromadné přihlášky.
     */
    public function create(): void
    {
        $oddil     = OddilAuth::user();
        $rok       = (int) zavod()['rocnik']['rok'];
        $kategorie = zavod()['kategorie'];
        $errors    = flash_get('errors', []);

        // Volby z neúspěšného odeslání, aby vedoucí nemusel vybírat znovu.
        $volby = flash_get('prihlaska_volby', []);
        if (! is_array($volby)) {
            $volby = [];
        }

        $jizPrihlaseni = $this->jmenaPrihlasenych((int) $oddil['id'], $rok);

        // Nejdřív aktivní závodníci, kteří ještě nejsou v tomto ročníku přihlášení.
        $radky = [];
        foreach ($this->seradProPrihlasku($this->soupiska($oddil), $jizPrihlaseni) as $zavodnik) {
            $id    = (int) ($zavodnik['id'] ?? 0);
            $jmeno = (string) ($zavodnik['jmeno'] ?? '');

            $radky[] = [
                'zavodnik'    => $zavodnik,
                'jizPrihlasen' => isset($jizPrihlaseni[OddilRepository::normalizuj($jmeno)]),
                'predvyplnena' => $this->predvyplnKategorii(
                    isset($zavodnik['rocnik_narozeni']) ? (int) $zavodnik['rocnik_narozeni'] : null,
                    isset($zavodnik['kategorie_2026']) ? (string) $zavodnik['kategorie_2026'] : null,
                    $rok,
                    $kategorie
                ),
            ];
        }

        $oddilTitle   = 'Přihláška do ročníku ' . $rok;
        $oddilAktivni = 'prihlaska';
        $oddilView    = APP_ROOT . '/app/Views/oddil/prihlaska.php';

        require APP_ROOT . '/app/Views/oddil/layout.php';
    }

    /**
     * Zpracování a uložení hromadné přihlášky.
     */
    public function store(): void
    {
        if (! csrf_verify($_POST['_csrf'] ?? null)) {
            flash_set('error', 'Přihlášku se nepodařilo odeslat. Zkuste to prosím znovu.');
            redirect('/oddil/prihlaska');
        }

        $oddil     = OddilAuth::user();
        $rok       = (int) zavod()['rocnik']['rok'];
        $kategorie = zavod()['kategorie'];
        $soupiska  = $this->soupiska($oddil);

        $podleId = [];
        foreach ($soupiska as $zavodnik) {
            $podleId[(int) ($zavodnik['id'] ?? 0)] = $zavodnik;
        }

        $jede        = isset($_POST['jede']) && is_array($_POST['jede']) ? $_POST['jede'] : [];
        $volbaKat    = isset($_POST['kategorie']) && is_array($_POST['kategorie']) ? $_POST['kategorie'] : [];
        $volbaRocnik = isset($_POST['rocnik_narozeni']) && is_array($_POST['rocnik_narozeni']) ? $_POST['rocnik_narozeni'] : [];

        $errors    = [];
        $zavodnici = [];
        $castka    = 0;

        foreach ($jede as $id => $hodnota) {
            $id = (int) $id;

            if ((string) $hodnota !== '1' || ! isset($podleId[$id])) {
                continue;
            }

            $zavodnik = $podleId[$id];
            $jmeno    = (string) ($zavodnik['jmeno'] ?? '');
            $kod      = trim((string) ($volbaKat[$id] ?? ''));

            if (! isset($kategorie[$kod])) {
                $errors['kategorie_' . $id] = 'U závodníka ' . $jmeno . ' vyberte kategorii.';
                continue;
            }

            // Ročník narození – z formuláře, jinak ze soupisky (seed z 26. ročníku ho nemá).
            $rocnikRaw = trim((string) ($volbaRocnik[$id] ?? ''));
            if ($rocnikRaw === '' && ! empty($zavodnik['rocnik_narozeni'])) {
                $rocnikRaw = (string) $zavodnik['rocnik_narozeni'];
            }

            if ($rocnikRaw === '' || ! ctype_digit($rocnikRaw) || (int) $rocnikRaw < 1900 || (int) $rocnikRaw > (int) date('Y')) {
                $errors['rocnik_' . $id] = 'U závodníka ' . $jmeno . ' doplňte ročník narození.';
                continue;
            }

            $rocnik = (int) $rocnikRaw;
            $kat    = $kategorie[$kod];

            if (! $this->vekSedí($rok, $rocnik, $kat)) {
                $errors['kategorie_' . $id] = 'Kategorie „' . $kat['nazev'] . '“ nesouhlasí s ročníkem narození závodníka ' . $jmeno . '.';
                continue;
            }

            $zavodnici[] = [
                'soupiska_id'      => $id,
                'jmeno'            => $jmeno,
                'rocnik_narozeni'  => $rocnik,
                'kategorie'        => $kod,
                'kategorie_nazev'  => (string) $kat['nazev'],
                'trat'             => (string) $kat['trat'],
                'cena'             => (int) $kat['cena'],
            ];

            $castka += (int) $kat['cena'];
        }

        if ($zavodnici === [] && $errors === []) {
            $errors['obecne'] = 'Vyberte alespoň jednoho závodníka, kterého chcete přihlásit.';
        }

        if ($errors !== []) {
            flash_set('errors', $errors);
            flash_set('prihlaska_volby', [
                'jede'             => $jede,
                'kategorie'        => $volbaKat,
                'rocnik_narozeni'  => $volbaRocnik,
            ]);
            redirect('/oddil/prihlaska');
        }

        // Doplníme ročníky narození do soupisky, aby se příště nemusely psát znovu.
        foreach ($zavodnici as $zavodnik) {
            $this->oddily->ulozZavodnika((int) $oddil['id'], [
                'id'              => (int) $zavodnik['soupiska_id'],
                'jmeno'           => (string) $zavodnik['jmeno'],
                'rocnik_narozeni' => (int) $zavodnik['rocnik_narozeni'],
            ]);
        }

        $prihlaska = $this->prihlasky->create([
            'rok'         => $rok,
            'typ'         => 'oddil',
            'oddil_id'    => (int) $oddil['id'],
            'oddil_nazev' => (string) $oddil['nazev'],
            'email'       => (string) $oddil['email'],
            'castka'      => $castka,
            'zavodnici'   => $zavodnici,
        ]);

        Log::login('oddil: přihláška ' . $prihlaska['id'] . ' oddílu #' . (int) $oddil['id'] . ' (' . count($zavodnici) . ' závodníků)');

        flash_set('registration', $prihlaska);
        flash_set('success', 'Přihláška byla odeslána.');

        redirect('/oddil/prihlaska/potvrzeni');
    }

    /**
     * Potvrzení odeslané přihlášky s rozpisem a jedním variabilním symbolem.
     */
    public function confirmation(): void
    {
        $prihlaska = flash_get('registration');

        if (! is_array($prihlaska)) {
            redirect('/oddil/prihlaska');
        }

        $oddil = OddilAuth::user();
        $platba = [
            'ucet'      => (string) zavod()['ucet'],
            'castka'    => (int) ($prihlaska['castka'] ?? 0) . ' Kč',
            'vs'        => (string) ($prihlaska['variabilni_symbol'] ?? ''),
            'poznamka'  => (string) ($prihlaska['id'] ?? ''),
            'uzavierka' => (string) zavod()['rocnik']['uzavierka'],
        ];

        $oddilTitle   = 'Potvrzení přihlášky';
        $oddilAktivni = 'prihlaska';
        $oddilView    = APP_ROOT . '/app/Views/oddil/potvrzeni.php';

        require APP_ROOT . '/app/Views/oddil/layout.php';
    }

    /**
     * Detail dřívější přihlášky oddílu.
     */
    public function show(string $id): void
    {
        $oddil     = OddilAuth::user();
        $prihlaska = $this->prihlasky->find($id);

        // Cizí přihlášku zobrazovat nebudeme – jako by neexistovala.
        if ($prihlaska === null
            || ($prihlaska['typ'] ?? '') !== 'oddil'
            || (int) ($prihlaska['oddil_id'] ?? 0) !== (int) $oddil['id']) {
            $this->nenalezeno();
            return;
        }

        $platba = [
            'ucet'      => (string) zavod()['ucet'],
            'castka'    => (int) ($prihlaska['castka'] ?? 0) . ' Kč',
            'vs'        => (string) ($prihlaska['variabilni_symbol'] ?? ''),
            'poznamka'  => (string) ($prihlaska['id'] ?? ''),
            'uzavierka' => (string) zavod()['rocnik']['uzavierka'],
        ];

        $oddilTitle   = 'Přihláška ' . (string) ($prihlaska['id'] ?? '');
        $oddilAktivni = 'prihlaska';
        $oddilView    = APP_ROOT . '/app/Views/oddil/detail.php';

        require APP_ROOT . '/app/Views/oddil/layout.php';
    }

    /**
     * Návrh kategorie: nejdřív podle ročníku narození, jinak podle kategorie
     * z 26. ročníku. Je to jen předvyplnění, vedoucí má poslední slovo.
     *
     * @param array<string, mixed> $kategorie
     */
    private function predvyplnKategorii(?int $rocnikNarozeni, ?string $kategorie2026, int $rok, array $kategorie): string
    {
        if ($rocnikNarozeni !== null && $rocnikNarozeni > 0) {
            $vek = $rok - $rocnikNarozeni;

            foreach ($kategorie as $kod => $kat) {
                if ($kat['vek_od'] === null || $kat['vek_do'] === null) {
                    continue;
                }

                if ($vek >= $kat['vek_od'] && $vek <= $kat['vek_do']) {
                    return (string) $kod;
                }
            }
        }

        if ($kategorie2026 !== null && $kategorie2026 !== '' && isset($kategorie[$kategorie2026])) {
            return (string) $kategorie2026;
        }

        return '';
    }

    /**
     * Zda věk odpovídá kategorii. U kategorií bez vypsaného rozsahu se neověřuje.
     *
     * @param array<string, mixed> $kategorie
     */
    private function vekSedí(int $rok, int $rocnikNarozeni, array $kategorie): bool
    {
        if ($kategorie['vek_od'] === null || $kategorie['vek_do'] === null) {
            return true;
        }

        $vek = $rok - $rocnikNarozeni;

        return $vek >= $kategorie['vek_od'] && $vek <= $kategorie['vek_do'];
    }

    /**
     * Jména závodníků už přihlášených v daném ročníku (normalizovaná).
     *
     * @return array<string, true>
     */
    private function jmenaPrihlasenych(int $oddilId, int $rok): array
    {
        $jmena = [];

        foreach ($this->prihlasky->proOddil($oddilId, $rok) as $prihlaska) {
            foreach ((array) ($prihlaska['zavodnici'] ?? []) as $zavodnik) {
                $klic = OddilRepository::normalizuj((string) ($zavodnik['jmeno'] ?? ''));

                if ($klic !== '') {
                    $jmena[$klic] = true;
                }
            }
        }

        return $jmena;
    }

    /**
     * Seřadí soupisku pro přihlášku: aktivní a dosud nepřihlášení první.
     *
     * @param array<int, array<string, mixed>> $soupiska
     * @param array<string, true>              $jizPrihlaseni
     * @return array<int, array<string, mixed>>
     */
    private function seradProPrihlasku(array $soupiska, array $jizPrihlaseni): array
    {
        usort($soupiska, static function ($a, $b) use ($jizPrihlaseni) {
            $aHotovo = isset($jizPrihlaseni[OddilRepository::normalizuj((string) ($a['jmeno'] ?? ''))]) ? 1 : 0;
            $bHotovo = isset($jizPrihlaseni[OddilRepository::normalizuj((string) ($b['jmeno'] ?? ''))]) ? 1 : 0;

            if ($aHotovo !== $bHotovo) {
                return $aHotovo <=> $bHotovo;
            }

            $aAktivni = (int) ($a['aktivni'] ?? 1) === 1 ? 0 : 1;
            $bAktivni = (int) ($b['aktivni'] ?? 1) === 1 ? 0 : 1;

            if ($aAktivni !== $bAktivni) {
                return $aAktivni <=> $bAktivni;
            }

            return strcmp((string) ($a['jmeno'] ?? ''), (string) ($b['jmeno'] ?? ''));
        });

        return $soupiska;
    }

    /**
     * @param array<string, mixed> $oddil
     * @return array<int, array<string, mixed>>
     */
    private function soupiska(array $oddil): array
    {
        return isset($oddil['zavodnici']) && is_array($oddil['zavodnici'])
            ? array_values($oddil['zavodnici'])
            : [];
    }

    /**
     * Vykreslí 404 stejně jako router v index.php.
     */
    private function nenalezeno(): void
    {
        http_response_code(404);
        $title       = 'Stránka nenalezena | Vodácká 30';
        $description = 'Hledaná stránka na webu Vodácká 30 neexistuje.';
        $robots      = false;
        $view        = APP_ROOT . '/app/Views/404.php';
        require APP_ROOT . '/app/Views/layout.php';
        exit;
    }
}
