<?php

namespace App\Controllers\Admin;

use App\Helpers\Log;
use App\Models\OddilRepository;

/**
 * Správa účtů oddílů v administraci – schvalování, reset hesla, import soupisek.
 */
class OddilAdminController
{
    private OddilRepository $repo;

    public function __construct()
    {
        $this->repo = new OddilRepository();
    }

    /**
     * Seznam účtů oddílů – nejdřív ty, které čekají na schválení.
     */
    public function index()
    {
        $oddily = $this->repo->all();

        $poradi = ['cekajici' => 0, 'schvaleny' => 1, 'zamitnuty' => 2];
        usort($oddily, static function ($a, $b) use ($poradi) {
            $pa = $poradi[$a['stav'] ?? ''] ?? 3;
            $pb = $poradi[$b['stav'] ?? ''] ?? 3;

            return $pa !== $pb ? $pa <=> $pb : strcmp((string) ($a['vytvoreno'] ?? ''), (string) ($b['vytvoreno'] ?? ''));
        });

        $resetHeslo   = flash_get('reset_heslo');
        $adminTitle   = 'Oddíly';
        $adminSection = 'oddily';
        $adminView    = APP_ROOT . '/app/Views/admin/oddily/index.php';

        require APP_ROOT . '/app/Views/admin/layout.php';
    }

    /**
     * Schválení účtu oddílu.
     */
    public function schvalit(string $id)
    {
        $this->zmenStav((int) $id, 'schvaleny');
    }

    /**
     * Zamítnutí žádosti o účet.
     */
    public function zamitnout(string $id)
    {
        $this->zmenStav((int) $id, 'zamitnuty');
    }

    /**
     * Vygeneruje nové heslo a zobrazí je jednorázově v administraci.
     */
    public function resetHeslo(string $id)
    {
        $id = (int) $id;

        if (! csrf_verify($_POST['_csrf'] ?? null)) {
            flash_set('error', 'Heslo se nepodařilo změnit.');
            redirect('/admin/oddily');
        }

        $oddil = $this->repo->find($id);
        if ($oddil === null) {
            flash_set('error', 'Oddíl nebyl nalezen.');
            redirect('/admin/oddily');
        }

        // Čitelné, ale dostatečně dlouhé heslo – stejně ho pošleme e-mailem.
        $heslo = bin2hex(random_bytes(5));

        if (! $this->repo->setHeslo($id, $heslo)) {
            flash_set('error', 'Heslo se nepodařilo změnit.');
            redirect('/admin/oddily');
        }

        Log::admin('Reset hesla oddílu #' . $id . ' ("' . (string) $oddil['nazev'] . '")');
        flash_set('reset_heslo', ['oddil' => (string) $oddil['nazev'], 'heslo' => $heslo]);
        flash_set('success', 'Bylo vygenerováno nové heslo.');

        redirect('/admin/oddily');
    }

    /**
     * Smazání účtu oddílu i s celou soupiskou (GDPR).
     */
    public function smazat(string $id)
    {
        $id = (int) $id;

        if (! csrf_verify($_POST['_csrf'] ?? null)) {
            flash_set('error', 'Účet se nepodařilo smazat.');
            redirect('/admin/oddily');
        }

        if ($this->repo->smaz($id)) {
            Log::admin('Smazán účet oddílu #' . $id);
            flash_set('success', 'Účet oddílu byl smazán.');
        } else {
            flash_set('error', 'Oddíl nebyl nalezen.');
        }

        redirect('/admin/oddily');
    }

    /**
     * Naplní soupisky existujících účtů závodníky z 26. ročníku.
     *
     * Datový soubor config/oddily-2026.php se přiřazuje podle názvu oddílu.
     * Opakované spuštění nic nezduplikuje ani nepřepíše.
     */
    public function import()
    {
        if (! csrf_verify($_POST['_csrf'] ?? null)) {
            flash_set('error', 'Soupisky se nepodařilo importovat.');
            redirect('/admin/oddily');
        }

        $seed = require APP_ROOT . '/config/oddily-2026.php';
        if (! is_array($seed)) {
            flash_set('error', 'Zdrojový soubor soupisek chybí.');
            redirect('/admin/oddily');
        }

        // Účty oddílů podle porovnávacího tvaru názvu.
        $podleNazvu = [];
        foreach ($this->repo->all() as $oddil) {
            $podleNazvu[OddilRepository::normalizuj((string) ($oddil['nazev'] ?? ''))] = $oddil;
        }

        $prirazeno = 0;
        $pridano   = 0;
        $nenalezeno = [];

        foreach ($seed as $nazevOddilu => $zavodnici) {
            $klic  = OddilRepository::normalizuj((string) $nazevOddilu);
            $oddil = $podleNazvu[$klic] ?? null;

            if ($oddil === null) {
                $nenalezeno[] = (string) $nazevOddilu;
                continue;
            }

            $prirazeno++;
            $pridano += $this->repo->doplnZavodniky((int) $oddil['id'], (array) $zavodnici);
        }

        Log::admin('Import soupisek 26. ročníku: ' . $prirazeno . ' oddílů, ' . $pridano . ' závodníků');

        $zprava = 'Import dokončen: ' . $pridano . ' nových závodníků v ' . $prirazeno . ' oddílech.';
        if ($nenalezeno !== []) {
            $zprava .= ' Bez účtu zůstávají: ' . implode(', ', $nenalezeno) . '.';
        }

        flash_set('success', $zprava);

        redirect('/admin/oddily');
    }

    /**
     * Společný krok pro schválení i zamítnutí.
     */
    private function zmenStav(int $id, string $stav): void
    {
        if (! csrf_verify($_POST['_csrf'] ?? null)) {
            flash_set('error', 'Stav účtu se nepodařilo změnit.');
            redirect('/admin/oddily');
        }

        $oddil = $this->repo->find($id);
        if ($oddil === null) {
            flash_set('error', 'Oddíl nebyl nalezen.');
            redirect('/admin/oddily');
        }

        if (! $this->repo->setStav($id, $stav)) {
            flash_set('error', 'Stav účtu se nepodařilo změnit.');
            redirect('/admin/oddily');
        }

        Log::admin('Účet oddílu #' . $id . ' ("' . (string) $oddil['nazev'] . '") → ' . $stav);
        flash_set('success', $stav === 'schvaleny'
            ? 'Účet oddílu „' . (string) $oddil['nazev'] . '“ byl schválen.'
            : 'Žádost oddílu „' . (string) $oddil['nazev'] . '“ byla zamítnuta.');

        redirect('/admin/oddily');
    }
}
