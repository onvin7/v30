<?php

namespace App\Controllers;

use App\Helpers\Client;
use App\Helpers\Log;
use App\Helpers\RateLimit;
use App\Helpers\Seo;
use App\Middleware\OddilAuth;
use App\Models\OddilRepository;

/**
 * Registrace a přihlášení oddílu + textová oznámení místo e-mailů.
 *
 * Účet vzniká samoobslužnou registrací a do schválení pořadatelem se z něj
 * nelze přihlásit. Napojení na e-maily bude potřeba později – zatím se doručená
 * zpráva jen vypíše jako prostý text na vlastní stránce (viz oznameni()).
 */
class OddilAuthController
{
    private const MAX_REGISTRACI    = 3;
    private const OKNO_REGISTRACE   = 3600; // hodina
    private const MAX_PRIHLASENI    = 5;
    private const OKNO_PRIHLASENI   = 900;  // 15 minut

    /**
     * Klíč pro rate limiting podle anonymizované IP.
     */
    private function klicLimitu(string $predpona): string
    {
        return $predpona . ':' . (Client::anonymizeIp(Client::ip()) ?? 'neznama');
    }

    /**
     * Přihlašovací formulář oddílu.
     */
    public function showLogin(): void
    {
        if (OddilAuth::check()) {
            redirect('/oddil');
        }

        $oddilTitle    = 'Přihlášení oddílu';
        $oddilAktivni  = 'prihlaseni';
        $oddilView     = APP_ROOT . '/app/Views/oddil/prihlaseni.php';

        require APP_ROOT . '/app/Views/oddil/layout.php';
    }

    /**
     * Zpracování přihlášení oddílu.
     */
    public function login(): void
    {
        // Rate limiting – při překročení se heslo vůbec neověřuje.
        if (! RateLimit::check($this->klicLimitu('oddil-login'), self::MAX_PRIHLASENI, self::OKNO_PRIHLASENI)) {
            Log::login('oddil: překročen limit pokusů o přihlášení');
            flash_set('error', 'Příliš mnoho pokusů o přihlášení. Zkuste to prosím později.');
            redirect('/oddil/prihlaseni');
        }

        // CSRF a honeypot – tiché odmítnutí.
        if (! csrf_verify($_POST['_csrf'] ?? null) || trim((string) ($_POST['website'] ?? '')) !== '') {
            Log::login('oddil: neplatný CSRF token nebo vyplněný honeypot');
            flash_set('error', 'Přihlášení se nepodařilo. Zkuste to prosím znovu.');
            redirect('/oddil/prihlaseni');
        }

        $email = trim((string) ($_POST['email'] ?? ''));
        $heslo = (string) ($_POST['heslo'] ?? '');

        if ($email === '' || $heslo === '') {
            flash_set('error', 'Zadejte prosím e-mail a heslo.');
            flash_set('old', ['email' => $email]);
            redirect('/oddil/prihlaseni');
        }

        $vysledek = OddilAuth::attempt($email, $heslo);

        switch ($vysledek) {
            case 'ok':
                Log::login('oddil: úspěšné přihlášení "' . $email . '"');
                flash_set('old', []);
                flash_set('success', 'Jste přihlášen.');
                redirect('/oddil');

            case 'cekajici':
                Log::login('oddil: přihlášení neschváleného účtu "' . $email . '"');
                flash_set('error', 'Účet oddílu zatím čeká na schválení pořadatelem. Přihlásit se bude možné po schválení.');
                flash_set('old', ['email' => $email]);
                redirect('/oddil/prihlaseni');

            case 'zamitnuty':
                Log::login('oddil: přihlášení zamítnutého účtu "' . $email . '"');
                flash_set('error', 'Žádost o účet oddílu byla pořadatelem zamítnuta. Ozvěte se prosím pořadateli závodu.');
                flash_set('old', ['email' => $email]);
                redirect('/oddil/prihlaseni');

            default:
                Log::login('oddil: neúspěšné přihlášení pro "' . $email . '"');
                flash_set('error', 'Nesprávný e-mail nebo heslo.');
                flash_set('old', ['email' => $email]);
                redirect('/oddil/prihlaseni');
        }
    }

    /**
     * Formulář žádosti o účet oddílu.
     */
    public function showRegister(): void
    {
        if (OddilAuth::check()) {
            redirect('/oddil');
        }

        $oddilTitle   = 'Registrace oddílu';
        $oddilAktivni = 'registrace';
        $oddilView    = APP_ROOT . '/app/Views/oddil/registrace.php';

        require APP_ROOT . '/app/Views/oddil/layout.php';
    }

    /**
     * Zpracování žádosti o účet oddílu.
     */
    public function register(): void
    {
        if (! RateLimit::check($this->klicLimitu('oddil-registrace'), self::MAX_REGISTRACI, self::OKNO_REGISTRACE)) {
            Log::security('oddil: překročen limit registrací oddílů');
            flash_set('error', 'Příliš mnoho žádostí o účet z této adresy. Zkuste to prosím později.');
            redirect('/oddil/registrace');
        }

        if (! csrf_verify($_POST['_csrf'] ?? null) || trim((string) ($_POST['website'] ?? '')) !== '') {
            Log::security('oddil: neplatný CSRF token nebo vyplněný honeypot při registraci');
            flash_set('error', 'Žádost se nepodařilo odeslat. Zkuste to prosím znovu.');
            redirect('/oddil/registrace');
        }

        $repo  = new OddilRepository();
        $vstup = [
            'nazev'         => trim((string) ($_POST['nazev'] ?? '')),
            'kontakt_jmeno' => trim((string) ($_POST['kontakt_jmeno'] ?? '')),
            'email'         => trim((string) ($_POST['email'] ?? '')),
            'telefon'       => trim((string) ($_POST['telefon'] ?? '')),
            'souhlas'       => (string) ($_POST['souhlas'] ?? ''),
        ];
        $heslo = (string) ($_POST['heslo'] ?? '');
        $heslo2 = (string) ($_POST['heslo2'] ?? '');

        $errors = [];

        if ($vstup['nazev'] === '') {
            $errors['nazev'] = 'Uveďte název oddílu.';
        } elseif (mb_strlen($vstup['nazev']) < 3 || mb_strlen($vstup['nazev']) > 100) {
            $errors['nazev'] = 'Název oddílu musí mít 3 až 100 znaků.';
        } elseif ($repo->nazevObsazen($vstup['nazev'])) {
            $errors['nazev'] = 'Účet pro tento oddíl už existuje. Zkuste se přihlásit, nebo se ozvěte pořadateli.';
        }

        if ($vstup['kontakt_jmeno'] === '') {
            $errors['kontakt_jmeno'] = 'Uveďte jméno kontaktní osoby.';
        } elseif (mb_strlen($vstup['kontakt_jmeno']) > 100) {
            $errors['kontakt_jmeno'] = 'Jméno může mít nejvýše 100 znaků.';
        }

        if ($vstup['email'] === '' || filter_var($vstup['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Zadejte platnou e-mailovou adresu.';
        } elseif ($repo->findByEmail($vstup['email']) !== null) {
            $errors['email'] = 'Účet s tímto e-mailem už existuje. Zkuste se přihlásit.';
        }

        if ($vstup['telefon'] !== '' && mb_strlen($vstup['telefon']) > 30) {
            $errors['telefon'] = 'Telefon může mít nejvýše 30 znaků.';
        }

        if (mb_strlen($heslo) < 8) {
            $errors['heslo'] = 'Heslo musí mít alespoň 8 znaků.';
        } elseif ($heslo !== $heslo2) {
            $errors['heslo'] = 'Hesla se neshodují.';
        }

        if ($vstup['souhlas'] !== '1') {
            $errors['souhlas'] = 'Bez souhlasu se zpracováním údajů nelze žádost odeslat.';
        }

        if ($errors !== []) {
            flash_set('errors', $errors);
            // Heslo se do relace nevrací.
            flash_set('old', $vstup);
            redirect('/oddil/registrace');
        }

        $id    = $repo->create($vstup + ['heslo' => $heslo]);
        $oddil = $repo->find($id);

        Log::login('oddil: nová žádost o účet "' . $vstup['nazev'] . '" (#' . $id . ')');

        flash_set('old', []);
        redirect('/oddil/oznameni/zadost-prijata/' . (string) ($oddil['token_zadosti'] ?? ''));
    }

    /**
     * Odhlášení oddílu.
     */
    public function logout(): void
    {
        if (! csrf_verify($_POST['_csrf'] ?? null)) {
            redirect('/oddil');
        }

        Log::login('oddil: odhlášení');
        OddilAuth::logout();
        flash_set('success', 'Byli jste odhlášeni.');

        redirect('/oddil/prihlaseni');
    }

    /**
     * Text oznámení místo e-mailu. Zatím se jen zobrazí na stránce; po napojení
     * SMTP se pošle přesně ten samý text, proto je na jednom místě.
     */
    public function oznameni(string $typ, string $token): void
    {
        $oddil = (new OddilRepository())->findByToken($token);

        if ($oddil === null) {
            $this->nenalezeno();
            return;
        }

        $text = $this->textOznameni($typ, $oddil);

        if ($text === '') {
            $this->nenalezeno();
            return;
        }

        $title = 'Oznámení | Vodácká 30';

        require APP_ROOT . '/app/Views/oddil/oznameni.php';
    }

    /**
     * Sestaví text oznámení pro daný typ a oddíl.
     *
     * Odkazy jsou absolutní, aby byly použitelné i po odeslání e-mailem.
     * Vrací prázdný řetězec pro neznámý typ.
     */
    public function textOznameni(string $typ, array $oddil): string
    {
        $nazev = (string) ($oddil['nazev'] ?? '');
        $base  = Seo::baseUrl();
        $token = (string) ($oddil['token_zadosti'] ?? '');
        $stav  = (string) ($oddil['stav'] ?? '');

        $odkazStav   = $base . '/oddil/oznameni/stav-zadosti/' . $token;
        $odkazLogin  = $base . '/oddil/prihlaseni';

        return match ($typ) {
            'zadost-prijata' => implode("\n", [
                'Dobrý den,',
                '',
                'žádost o účet oddílu „' . $nazev . '“ byla přijata a čeká na schválení pořadatelem.',
                '',
                'Stav žádosti můžete kdykoli zkontrolovat na této adrese:',
                $odkazStav,
                '',
                'Přihlásit se bude možné až po schválení účtu. O schválení vám dáme vědět.',
                '',
                'Vodácká 30',
            ]),

            'stav-zadosti' => match ($stav) {
                'schvaleny' => implode("\n", [
                    'Dobrý den,',
                    '',
                    'účet oddílu „' . $nazev . '“ byl schválen. Můžete se přihlásit:',
                    $odkazLogin,
                    '',
                    'Vodácká 30',
                ]),
                'zamitnuty' => implode("\n", [
                    'Dobrý den,',
                    '',
                    'žádost o účet oddílu „' . $nazev . '“ byla pořadatelem zamítnuta.',
                    '',
                    'Pokud si myslíte, že jde o omyl, ozvěte se prosím pořadateli závodu.',
                    '',
                    'Vodácká 30',
                ]),
                default => implode("\n", [
                    'Dobrý den,',
                    '',
                    'účet oddílu „' . $nazev . '“ zatím čeká na schválení pořadatelem.',
                    '',
                    'Tuto adresu si můžete uložit a stav kdykoli zkontrolovat:',
                    $odkazStav,
                    '',
                    'Vodácká 30',
                ]),
            },

            'ucet-schvalen' => implode("\n", [
                'Dobrý den,',
                '',
                'účet oddílu „' . $nazev . '“ byl schválen, můžete se přihlásit:',
                $odkazLogin,
                '',
                'Vodácká 30',
            ]),

            default => '',
        };
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
