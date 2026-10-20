<?php

namespace App\Controllers;

use App\Models\RegistrationRepository;

class RegistrationController
{
    /**
     * Zobrazí přihlašovací formulář.
     */
    public function create()
    {
        // Kategorie pocházejí z jediného zdroje (config/zavod.php) – stejná sada
        // jako v propozicích. Ceny se do souhrnu odvozují z nich, neopisují se.
        $kategorie = zavod()['kategorie'];
        $startovne = [
            'detske'   => $kategorie['children-under-6']['cena'],
            'vcasna'   => $kategorie['men-pro']['cena'],
            'na_miste' => $kategorie['men-pro']['cena_na_miste'],
            'uzavierka' => zavod()['rocnik']['uzavierka'],
        ];
        $errors    = flash_get('errors', []);

        $title = 'Přihlášky | Vodácká 30';
        $view  = APP_ROOT . '/app/Views/registration.php';

        require APP_ROOT . '/app/Views/layout.php';
    }

    /**
     * Zpracuje odeslanou přihlášku (validace + simulované uložení do session).
     */
    public function store()
    {
        $kategorie = zavod()['kategorie'];

        // CSRF a honeypot – tiché odmítnutí bez uložení.
        if (! csrf_verify($_POST['_csrf'] ?? null) || trim((string) ($_POST['website'] ?? '')) !== '') {
            flash_set('error', 'Přihlášku se nepodařilo odeslat. Zkuste to prosím znovu.');
            redirect('/prihlaska');
        }

        $vstup = [
            'kategorie'        => trim((string) ($_POST['kategorie'] ?? '')),
            'oddil'            => trim((string) ($_POST['oddil'] ?? '')),
            'jmeno'            => trim((string) ($_POST['jmeno'] ?? '')),
            'prijmeni'         => trim((string) ($_POST['prijmeni'] ?? '')),
            'rocnik_narozeni'  => trim((string) ($_POST['rocnik_narozeni'] ?? '')),
            'email'            => trim((string) ($_POST['email'] ?? '')),
            'poznamka'         => trim((string) ($_POST['poznamka'] ?? '')),
            'souhlas'          => (string) ($_POST['souhlas'] ?? ''),
        ];

        $errors = [];

        if (! isset($kategorie[$vstup['kategorie']])) {
            $errors['kategorie'] = 'Vyberte prosím kategorii.';
        }

        if ($vstup['oddil'] === '') {
            $errors['oddil'] = 'Uveďte oddíl nebo tým.';
        } elseif (mb_strlen($vstup['oddil']) > 100) {
            $errors['oddil'] = 'Oddíl nebo tým může mít nejvýše 100 znaků.';
        }

        if ($vstup['jmeno'] === '') {
            $errors['jmeno'] = 'Uveďte jméno.';
        } elseif (mb_strlen($vstup['jmeno']) > 60) {
            $errors['jmeno'] = 'Jméno může mít nejvýše 60 znaků.';
        }

        if ($vstup['prijmeni'] === '') {
            $errors['prijmeni'] = 'Uveďte příjmení.';
        } elseif (mb_strlen($vstup['prijmeni']) > 60) {
            $errors['prijmeni'] = 'Příjmení může mít nejvýše 60 znaků.';
        }

        $rok = (int) $vstup['rocnik_narozeni'];
        if ($vstup['rocnik_narozeni'] === '' || ! ctype_digit($vstup['rocnik_narozeni']) || $rok < 1900 || $rok > (int) date('Y')) {
            $errors['rocnik_narozeni'] = 'Zadejte ročník narození jako čtyřmístné číslo.';
        }

        if ($vstup['email'] === '' || filter_var($vstup['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Zadejte platnou e-mailovou adresu.';
        }

        if ($vstup['souhlas'] !== '1') {
            $errors['souhlas'] = 'Bez souhlasu se zpracováním údajů nelze přihlášku odeslat.';
        }

        if ($errors !== []) {
            flash_set('errors', $errors);
            flash_set('old', $vstup);
            redirect('/prihlaska');
        }

        // Uložení do storage/prihlasky.json – jednotlivec má vlastní přihlášku
        // i vlastní variabilní symbol. Účtuje se včasná cena; platba na místě
        // startu je dražší (viz potvrzení).
        $kategorieInfo = $kategorie[$vstup['kategorie']];

        $prihlaska = (new RegistrationRepository())->create([
            'rok'         => (int) zavod()['rocnik']['rok'],
            'typ'         => 'jednotlivec',
            'oddil_id'    => null,
            'oddil_nazev' => $vstup['oddil'],
            'email'       => $vstup['email'],
            'castka'      => (int) $kategorieInfo['cena'],
            'poznamka'    => $vstup['poznamka'],
            'zavodnici'   => [[
                'jmeno'           => trim($vstup['jmeno'] . ' ' . $vstup['prijmeni']),
                'rocnik_narozeni' => (int) $vstup['rocnik_narozeni'],
                'kategorie'       => $vstup['kategorie'],
                'kategorie_nazev' => (string) $kategorieInfo['nazev'],
                'trat'            => (string) $kategorieInfo['trat'],
                'cena'            => (int) $kategorieInfo['cena'],
            ]],
        ]);

        $prihlaseni = [
            'id'                => (string) $prihlaska['id'],
            'variabilni_symbol' => (string) $prihlaska['variabilni_symbol'],
            'castka'            => (int) $prihlaska['castka'],
            'kategorie_kod'     => $vstup['kategorie'],
            'kategorie'         => $kategorieInfo['nazev'],
            'trat'              => $kategorieInfo['trat'],
            'oddil'             => $vstup['oddil'],
            'jmeno'             => $vstup['jmeno'],
            'prijmeni'          => $vstup['prijmeni'],
            'rocnik_narozeni'   => $vstup['rocnik_narozeni'],
            'email'             => $vstup['email'],
            'poznamka'          => $vstup['poznamka'],
            'vytvoreno'         => date('j. n. Y H:i'),
        ];

        flash_set('old', []);
        flash_set('registration', $prihlaseni);
        flash_set('success', 'Přihláška byla odeslána.');

        redirect('/prihlaska/potvrzeni');
    }

    /**
     * Rekapitulace odeslané přihlášky.
     */
    public function confirmation()
    {
        $prihlaseni = flash_get('registration');

        // Bez právě odeslané přihlášky nemá potvrzení co zobrazit.
        if (! is_array($prihlaseni)) {
            redirect('/prihlaska');
        }

        // Bankovní instrukce – bez QR, platbu ověřuje pořadatel ručně.
        // Číslo účtu je v config/zavod.php; dokud ho pořadatel nedodá, je prázdné
        // a potvrzení na to upozorní (aby nikdo neposílal peníze naslepo).
        $kategorieInfo = zavod()['kategorie'][$prihlaseni['kategorie_kod']] ?? null;

        $platba = [
            'ucet'      => zavod()['ucet'],
            'castka'    => $prihlaseni['castka'] . ' Kč',
            'vs'        => $prihlaseni['variabilni_symbol'],
            'poznamka'  => $prihlaseni['id'],
            'uzavierka' => zavod()['rocnik']['uzavierka'],
            // Doplatit na místě je potřeba jen u kategorií s dražší platbou na startu.
            'na_miste'  => $kategorieInfo !== null && $kategorieInfo['cena_na_miste'] > $prihlaseni['castka']
                ? $kategorieInfo['cena_na_miste'] . ' Kč'
                : '',
        ];

        $title = 'Potvrzení přihlášky | Vodácká 30';
        $view  = APP_ROOT . '/app/Views/registration_confirmation.php';

        require APP_ROOT . '/app/Views/layout.php';
    }
}
