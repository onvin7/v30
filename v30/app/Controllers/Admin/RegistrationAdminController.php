<?php

namespace App\Controllers\Admin;

use App\Helpers\Log;
use App\Models\RegistrationRepository;

/**
 * Přehled přihlášek v administraci + CSV export pro pořadatele.
 */
class RegistrationAdminController
{
    private RegistrationRepository $repo;

    public function __construct()
    {
        $this->repo = new RegistrationRepository();
    }

    /**
     * Seznam přihlášek aktuálního ročníku, volitelně filtrovaný podle stavu.
     */
    public function index()
    {
        $rok  = (int) zavod()['rocnik']['rok'];
        $filtr = trim((string) ($_GET['stav'] ?? ''));

        if ($filtr !== '' && ! in_array($filtr, RegistrationRepository::STAVY, true)) {
            $filtr = '';
        }

        $vsechny   = $this->repo->proRok($rok);
        $prihlasky = $filtr === ''
            ? $vsechny
            : array_values(array_filter($vsechny, static fn ($p) => ($p['stav'] ?? '') === $filtr));

        $celkemZavodniku = 0;
        $celkemCastka    = 0;
        foreach ($prihlasky as $prihlaska) {
            if (($prihlaska['stav'] ?? '') === 'stornovano') {
                continue;
            }

            $celkemZavodniku += count((array) ($prihlaska['zavodnici'] ?? []));
            $celkemCastka    += (int) ($prihlaska['castka'] ?? 0);
        }

        $adminTitle   = 'Přihlášky ' . $rok;
        $adminSection = 'prihlasky';
        $adminView    = APP_ROOT . '/app/Views/admin/prihlasky/index.php';

        require APP_ROOT . '/app/Views/admin/layout.php';
    }

    /**
     * Změna stavu přihlášky (označení platby / storno).
     */
    public function setStav(string $id)
    {
        $stav = trim((string) ($_POST['stav'] ?? ''));

        if (! csrf_verify($_POST['_csrf'] ?? null) || ! in_array($stav, RegistrationRepository::STAVY, true)) {
            flash_set('error', 'Stav přihlášky se nepodařilo změnit.');
            redirect('/admin/prihlasky');
        }

        if (! $this->repo->setStav($id, $stav)) {
            flash_set('error', 'Přihláška nebyla nalezena.');
            redirect('/admin/prihlasky');
        }

        Log::admin('Přihláška ' . $id . ' → ' . $stav);

        $popis = ['nova' => 'nezaplaceno', 'zaplaceno' => 'zaplaceno', 'stornovano' => 'stornováno'][$stav];
        flash_set('success', 'Přihláška ' . $id . ' je nyní ' . $popis . '.');

        redirect('/admin/prihlasky');
    }

    /**
     * CSV export přihlášek aktuálního ročníku – jeden řádek na závodníka.
     */
    public function export()
    {
        $rok       = (int) zavod()['rocnik']['rok'];
        $prihlasky = $this->repo->proRok($rok);

        $nazev = 'prihlasky-vodacka30-' . $rok . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $nazev . '"');
        header('X-Content-Type-Options: nosniff');

        $vystup = fopen('php://output', 'w');

        // BOM, aby Excel poznal UTF-8.
        fwrite($vystup, "\xEF\xBB\xBF");

        fputcsv($vystup, [
            'prihlaska', 'typ', 'oddil', 'email', 'variabilni_symbol', 'zavodnik',
            'rocnik_narozeni', 'kategorie', 'trat', 'cena', 'castka_prihlasky', 'stav', 'vytvoreno',
        ], ';');

        foreach ($prihlasky as $prihlaska) {
            foreach ((array) ($prihlaska['zavodnici'] ?? []) as $zavodnik) {
                fputcsv($vystup, [
                    (string) ($prihlaska['id'] ?? ''),
                    ($prihlaska['typ'] ?? '') === 'oddil' ? 'oddíl' : 'jednotlivec',
                    (string) ($prihlaska['oddil_nazev'] ?? ''),
                    (string) ($prihlaska['email'] ?? ''),
                    (string) ($prihlaska['variabilni_symbol'] ?? ''),
                    (string) ($zavodnik['jmeno'] ?? ''),
                    (string) ($zavodnik['rocnik_narozeni'] ?? ''),
                    (string) ($zavodnik['kategorie_nazev'] ?? ($zavodnik['kategorie'] ?? '')),
                    (string) ($zavodnik['trat'] ?? ''),
                    (string) ($zavodnik['cena'] ?? ''),
                    (string) ($prihlaska['castka'] ?? ''),
                    (string) ($prihlaska['stav'] ?? ''),
                    (string) ($prihlaska['vytvoreno'] ?? ''),
                ], ';');
            }
        }

        fclose($vystup);

        Log::admin('Export přihlášek ročníku ' . $rok . ' do CSV');
        exit;
    }
}
