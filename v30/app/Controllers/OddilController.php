<?php

namespace App\Controllers;

use App\Helpers\Log;
use App\Middleware\OddilAuth;
use App\Models\OddilRepository;
use App\Models\RegistrationRepository;

/**
 * Zóna oddílu – nástěnka a správa soupisky.
 *
 * Vše je za `OddilAuth::guard()`. Data se vždy filtrují podle ID oddílu ze
 * session, nikdy podle parametru z URL.
 */
class OddilController
{
    private OddilRepository $oddily;

    public function __construct()
    {
        $this->oddily = new OddilRepository();
    }

    /**
     * Nástěnka oddílu: stav účtu, soupiska a přihlášky do aktuálního ročníku.
     */
    public function index(): void
    {
        $oddil      = OddilAuth::user();
        $prihlasky  = (new RegistrationRepository())->proOddil((int) $oddil['id'], (int) zavod()['rocnik']['rok']);
        $soupiska   = $this->soupiska($oddil);

        $celkem = 0;
        foreach ($prihlasky as $prihlaska) {
            if (($prihlaska['stav'] ?? '') !== 'stornovano') {
                $celkem += (int) ($prihlaska['castka'] ?? 0);
            }
        }

        $oddilTitle   = 'Nástěnka';
        $oddilAktivni = 'nastenka';
        $oddilView    = APP_ROOT . '/app/Views/oddil/index.php';

        require APP_ROOT . '/app/Views/oddil/layout.php';
    }

    /**
     * Soupiska závodníků + formulář na přidání.
     */
    public function zavodnici(): void
    {
        $oddil      = OddilAuth::user();
        $soupiska   = $this->soupiska($oddil);
        $errors     = flash_get('errors', []);

        $oddilTitle   = 'Soupiska';
        $oddilAktivni = 'soupiska';
        $oddilView    = APP_ROOT . '/app/Views/oddil/zavodnici.php';

        require APP_ROOT . '/app/Views/oddil/layout.php';
    }

    /**
     * Přidání závodníka do soupisky.
     */
    public function pridejZavodnika(): void
    {
        $oddil = OddilAuth::user();

        if (! csrf_verify($_POST['_csrf'] ?? null)) {
            flash_set('error', 'Závodníka se nepodařilo uložit. Zkuste to prosím znovu.');
            redirect('/oddil/zavodnici');
        }

        $jmeno  = trim((string) ($_POST['jmeno'] ?? ''));
        $rocnik = trim((string) ($_POST['rocnik_narozeni'] ?? ''));

        $errors = [];

        if ($jmeno === '') {
            $errors['jmeno'] = 'Uveďte jméno a příjmení závodníka.';
        } elseif (mb_strlen($jmeno) > 100) {
            $errors['jmeno'] = 'Jméno může mít nejvýše 100 znaků.';
        }

        if ($rocnik !== '' && ! $this->platnyRocnik($rocnik)) {
            $errors['rocnik_narozeni'] = 'Zadejte ročník narození jako čtyřmístné číslo.';
        }

        if ($errors !== []) {
            flash_set('errors', $errors);
            flash_set('old', ['jmeno' => $jmeno, 'rocnik_narozeni' => $rocnik]);
            redirect('/oddil/zavodnici');
        }

        $this->oddily->ulozZavodnika((int) $oddil['id'], [
            'jmeno'           => $jmeno,
            'rocnik_narozeni' => $rocnik !== '' ? (int) $rocnik : null,
            'aktivni'         => 1,
        ]);

        Log::login('oddil: přidán závodník do soupisky oddílu #' . (int) $oddil['id']);
        flash_set('old', []);
        flash_set('success', 'Závodník byl přidán do soupisky.');

        redirect('/oddil/zavodnici');
    }

    /**
     * Úprava závodníka v soupisce.
     */
    public function upravZavodnika(string $id): void
    {
        $oddil      = OddilAuth::user();
        $zavodnikId = (int) $id;

        if (! csrf_verify($_POST['_csrf'] ?? null) || ! $this->patriDoSoupisky($oddil, $zavodnikId)) {
            flash_set('error', 'Závodníka se nepodařilo uložit.');
            redirect('/oddil/zavodnici');
        }

        $jmeno  = trim((string) ($_POST['jmeno'] ?? ''));
        $rocnik = trim((string) ($_POST['rocnik_narozeni'] ?? ''));

        $errors = [];

        if ($jmeno === '') {
            $errors['jmeno'] = 'Uveďte jméno a příjmení závodníka.';
        } elseif (mb_strlen($jmeno) > 100) {
            $errors['jmeno'] = 'Jméno může mít nejvýše 100 znaků.';
        }

        if ($rocnik !== '' && ! $this->platnyRocnik($rocnik)) {
            $errors['rocnik_narozeni'] = 'Zadejte ročník narození jako čtyřmístné číslo.';
        }

        if ($errors !== []) {
            flash_set('errors', $errors);
            redirect('/oddil/zavodnici');
        }

        $this->oddily->ulozZavodnika((int) $oddil['id'], [
            'id'              => $zavodnikId,
            'jmeno'           => $jmeno,
            'rocnik_narozeni' => $rocnik !== '' ? (int) $rocnik : null,
            'aktivni'         => (string) ($_POST['aktivni'] ?? '0') === '1' ? 1 : 0,
        ]);

        flash_set('success', 'Údaje závodníka byly uloženy.');

        redirect('/oddil/zavodnici');
    }

    /**
     * Odstranění závodníka ze soupisky.
     */
    public function smazZavodnika(string $id): void
    {
        $oddil      = OddilAuth::user();
        $zavodnikId = (int) $id;

        if (! csrf_verify($_POST['_csrf'] ?? null) || ! $this->patriDoSoupisky($oddil, $zavodnikId)) {
            flash_set('error', 'Závodníka se nepodařilo smazat.');
            redirect('/oddil/zavodnici');
        }

        $this->oddily->smazZavodnika((int) $oddil['id'], $zavodnikId);
        flash_set('success', 'Závodník byl ze soupisky odstraněn.');

        redirect('/oddil/zavodnici');
    }

    /**
     * Soupiska jako seznam (nikdy null).
     *
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
     * Zda závodník patří do soupisky daného oddílu.
     *
     * @param array<string, mixed> $oddil
     */
    private function patriDoSoupisky(array $oddil, int $zavodnikId): bool
    {
        foreach ($this->soupiska($oddil) as $zavodnik) {
            if ((int) ($zavodnik['id'] ?? 0) === $zavodnikId) {
                return true;
            }
        }

        return false;
    }

    /**
     * Ročník narození – čtyřmístné číslo v rozumném rozsahu.
     */
    private function platnyRocnik(string $rocnik): bool
    {
        if (! ctype_digit($rocnik)) {
            return false;
        }

        $rok = (int) $rocnik;

        return $rok >= 1900 && $rok <= (int) date('Y');
    }
}
