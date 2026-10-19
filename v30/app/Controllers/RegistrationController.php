<?php

namespace App\Controllers;

class RegistrationController
{
    /**
     * Povolené kategorie přihlášky (allowlist).
     * Mock data – později nahradí Model / databáze.
     *
     * @return array<string, array{nazev: string, trat: string, cena: int}>
     */
    private function kategorie(): array
    {
        return [
            'children-under-6'  => ['nazev' => 'Chlapci a dívky do 6 let',          'trat' => '3 km',  'cena' => 200],
            'children-7-10'     => ['nazev' => 'Chlapci a dívky od 7 do 10 let',    'trat' => '7 km',  'cena' => 200],
            'children-11-14'    => ['nazev' => 'Chlapci a dívky od 11 do 14 let',   'trat' => '7 km',  'cena' => 200],
            'children-pro-7-14' => ['nazev' => 'Chlapci a dívky PRO od 7 do 14 let','trat' => '7 km',  'cena' => 200],
            'juniors'           => ['nazev' => 'Junioři',                           'trat' => '30 km', 'cena' => 300],
            'men-pro'           => ['nazev' => 'Muži PRO',                          'trat' => '30 km', 'cena' => 300],
            'women-pro'         => ['nazev' => 'Ženy PRO',                          'trat' => '30 km', 'cena' => 300],
            'men-classic'       => ['nazev' => 'Muži KLASICI',                      'trat' => '30 km', 'cena' => 300],
            'women-classic'     => ['nazev' => 'Ženy KLASIČKY',                     'trat' => '30 km', 'cena' => 300],
            'pro-for-fun'       => ['nazev' => 'Muži a ženy PRO RADOST',            'trat' => '30 km', 'cena' => 300],
            'veterans'          => ['nazev' => 'Veteráni / veteránky',              'trat' => '30 km', 'cena' => 300],
        ];
    }

    /**
     * Zobrazí přihlašovací formulář.
     */
    public function create()
    {
        $kategorie = $this->kategorie();
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
        $kategorie = $this->kategorie();

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

        // Simulované uložení – bez databáze, záznam zůstává pouze v session.
        $id = sprintf('V30-%s-%04d', date('Y'), random_int(1, 9999));
        $variabilniSymbol = (string) random_int(1000000000, 9999999999);
        $kategorieInfo = $kategorie[$vstup['kategorie']];

        $prihlaseni = [
            'id'                => $id,
            'variabilni_symbol' => $variabilniSymbol,
            'castka'            => $kategorieInfo['cena'],
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

        if (! isset($_SESSION['registrations']) || ! is_array($_SESSION['registrations'])) {
            $_SESSION['registrations'] = [];
        }
        $_SESSION['registrations'][$id] = $prihlaseni;

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

        // Bankovní instrukce (mock) – bez QR, platbu ověřuje pořadatel ručně.
        $platba = [
            'ucet'   => '123456789/0300',
            'castka' => $prihlaseni['castka'] . ' Kč',
            'vs'     => $prihlaseni['variabilni_symbol'],
            'poznamka' => $prihlaseni['id'],
        ];

        $title = 'Potvrzení přihlášky | Vodácká 30';
        $view  = APP_ROOT . '/app/Views/registration_confirmation.php';

        require APP_ROOT . '/app/Views/layout.php';
    }
}
