# Plán: dokončení webu Vodácká 30 (plain PHP MVC)

## Shrnutí

Cílem je doplnit zbytek veřejného webu v projektu [v30](file:///c:/Users/onvin/Documents/WEB/v30/v30) (čisté PHP MVC, běží na `http://v30.test`, bez DB a bez Composeru/Node). Dnes je hotová pouze úvodní stránka; router sice odkazuje na kontrolery, které neexistují, takže všechny ostatní adresy padají na fatální chybu (kromě 404 fallbacku).

Rozhodnutí potvrzená uživatelem:
- **Rozsah:** kompletní parita stránek podle plánu navigace.
- **Data:** mock data přímo v kontrolerech (jako v `HomeController`), žádná DB ani sdílený datový soubor.
- **Přihláška:** funkční formulář (GET + POST + validace + potvrzení), data se pouze simulují v session.

**Design zůstává na později** – použijeme stávající vizuální jazyk ze [home.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/home.php) a [layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php) (Tailwind CDN, slate + oranžová akcent, bez gradientů a velkých stínů), bez nového designu a bez nových UI komponent.

## Analýza současného stavu

Zdroj pravdy pro obsah byl původní web `vodacka30.cz` a dřívější plánovací dokumenty v kořeni workspace (návrh navigace a obsahu, rozsah MVP, designové mantinely); ty už v repozitáři nejsou. Vzorové stránky a datový model dříve poskytovala Laravel reference, která byla z repozitáře odstraněna.

Co existuje a funguje:
- `index.php` → [web/index.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/web/index.php) (front controller s mapou rout `path => [Controller, metoda]`, `preg_match` nad normalizovaným URI) a [autoloader.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/config/autoloader.php) (PSR-4 `App\` → `/app`).
- [layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php) – hlavička s responzivní navigací (Alpine.js), patička, `main` s `require $view`.
- [HomeController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/HomeController.php) + [home.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/home.php), [404.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/404.php).

Mezery a problémy:
1. Router v [web/index.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/web/index.php#L27-L49) importuje `RaceController`, `RegistrationController`, `ResultsController` – tyto třídy **neexistují**. Každý požadavek na `/propozice`, `/prihlaska`, `/vysledky` skončí fatální chybou.
2. Navigace v [layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php#L44-L47) odkazuje na `/startovni-listina`, ale tato routa **v routeru chybí** → 404.
3. Chybí stránky podle návrhu navigace: O závodu (`/zavod`), Galerie (`/galerie`), Kontakt (`/kontakt`).
4. Router umí pouze GET – pro funkční přihlášku je potřeba rozlišit HTTP metodu.
5. Chybí mechanismus pro flash zprávy, „old input" a CSRF token (session je už založená ve [web/index.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/web/index.php#L17-L20)).

## Navrhované změny

### 1. `config/helpers.php` (nový soubor)

Drobné pomocné funkce sdílené kontrolery a šablonami. Vzniká proto, že funkční formulář potřebuje flash zprávy, návrat vstupů a CSRF; jinak by se stejný kód opakoval.

- `e(mixed $value): string` – obal nad `htmlspecialchars` (nové šablony); stávající šablony ponechávají `htmlspecialchars`.
- `csrf_token(): string` – vytvoří/uloží token v `$_SESSION['_csrf']`.
- `csrf_field(): string` – skryté pole s tokenem.
- `csrf_verify(?string $token): bool` – konstantní porovnání (`hash_equals`).
- `flash_set(string $key, mixed $value): void` / `flash_get(string $key, mixed $default = null): mixed` – jednorázové zprávy v session.
- `old_get(string $key, string $default = ''): string` – vrací odeslanou hodnotu po neúspěšné validaci.
- `redirect(string $path): never` – `header('Location: ...')` + `exit`.

Soubor se načte z [web/index.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/web/index.php) hned po autoloaderu.

### 2. `web/index.php` (úprava)

**Co:** mapa rout rozdělená podle HTTP metody + nové routy.
**Proč:** odblokuje všechny chybějící stránky a umožní funkční přihlášku.

```php
$routes = [
    'GET' => [
        '/'                  => [HomeController::class, 'index'],
        '/zavod'             => [AboutController::class, 'index'],
        '/propozice'         => [RaceController::class, 'propositions'],
        '/prihlaska'         => [RegistrationController::class, 'create'],
        '/prihlaska/potvrzeni' => [RegistrationController::class, 'confirmation'],
        '/startovni-listina' => [StartListController::class, 'index'],
        '/vysledky'          => [ResultsController::class, 'index'],
        '/galerie'           => [GalleryController::class, 'index'],
        '/kontakt'           => [ContactController::class, 'index'],
    ],
    'POST' => [
        '/prihlaska'         => [RegistrationController::class, 'store'],
    ],
];
```

Vyhledávání: nejprve přes `$_SERVER['REQUEST_METHOD']`; pokud cesta existuje v druhé metodě, vrátí se 405, jinak 404 (zachovat stávající 404 blok a nově přidat 405).

### 3. `app/Views/layout.php` (úprava)

- Navigace (desktop i mobil) rozšířena na plný seznam: O závodu, Propozice a trať, Přihlášky, Startovní listina, Výsledky a archiv, Galerie, Kontakt + CTA „Přihlásit se".
- Aktivní odkaz zvýrazněn podle aktuální cesty (`$currentPath` z `parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)`), porovnáním s `href`.
- Nad `main` blok pro flash zprávu (`flash_get('success')` / `flash_get('error')`).
- Patička doplněna o Galerii, Kontakt a pořadatele (TJ KVT Pardubice).

### 4. Nové kontrolery (`app/Controllers/`)

Všechny dodržují vzor z [HomeController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/HomeController.php): sestaví mock `$title`, nastaví `$view = APP_ROOT . '/app/Views/<soubor>.php'` a `require` layout.

| Kontroler | Metoda | Routa | View |
|---|---|---|---|
| `AboutController` | `index` | `/zavod` | `about.php` |
| `RaceController` | `propositions` | `/propozice` | `propositions.php` |
| `RegistrationController` | `create` | `/prihlaska` (GET) | `registration.php` |
| `RegistrationController` | `store` | `/prihlaska` (POST) | – (redirect) |
| `RegistrationController` | `confirmation` | `/prihlaska/potvrzeni` | `registration_confirmation.php` |
| `StartListController` | `index` | `/startovni-listina` | `start_list.php` |
| `ResultsController` | `index` | `/vysledky` | `results.php` |
| `GalleryController` | `index` | `/galerie` | `gallery.php` |
| `ContactController` | `index` | `/kontakt` | `contact.php` |

### 5. Registrace – funkční formulář (`RegistrationController`)

**GET `/prihlaska`** – zobrazí formulář s mock kategoriemi (děti 3/7 km, junioři, Muži/Ženy PRO, Muži/Ženy KLASICI, PRO RADOST, Veteráni – vše 30 km):
- Pole: `kategorie` (select), `oddil`, `jmeno`, `prijmeni`, `rocnik_narozeni`, `email`, `poznamka` (nepovinné), `souhlas` (GDPR checkbox), skrytý honeypot `website`, `csrf_field()`.
- Chybové zprávy z `flash_get('errors')`, hodnoty z `old_get()`.
- Viditelné označení, že jde o ukázkový režim (data se neukládají do DB).

**POST `/prihlaska`** (`store`) – serverová validace:
- CSRF token platný; honeypot `website` musí být prázdný.
- `kategorie` povinná a musí být v povoleném seznamu (allowlist kódů); `oddil` povinný, max 100; `jmeno`/`prijmeni` povinné, max 60; `rocnik_narozeni` povinný, celé číslo 1900..aktuální rok; `email` povinný a `filter_var(..., FILTER_VALIDATE_EMAIL)`; `souhlas` musí být `1`.
- Při chybě: `flash_set('errors', …)`, `flash_set('old', …)`, `redirect('/prihlaska')`.
- Při úspěchu: vygeneruje `id` (`V30-2027-XXXX`) a číselný variabilní symbol, uloží záznam do `$_SESSION['registrations'][]` (simulace), `flash_set('success', …)`, `redirect('/prihlaska/potvrzeni')` (PRG, ochrana proti dvojímu odeslání).

**GET `/prihlaska/potvrzeni`** (`confirmation`) – přečte flash; pokud není, přesměruje zpět na `/prihlaska`. Zobrazí rekapitulaci, identifikátor a bankovní platební instrukce **bez QR** (potvrzené rozhodnutí MVP).

### 6. Nové šablony (`app/Views/`)

Všechny bez nového designu, ve stylu `home.php` (karty `bg-white border border-slate-200 rounded-lg`, sekce `py-16`, oranžová jen na akce). Obsah vychází z podkladů původního webu `vodacka30.cz`:

- `about.php` – O závodu: charakter závodu (běžky v Orlických horách, pořadatel TJ KVT Pardubice), fakta ročníku 2026 (místo Bedřichovka/Orlické Záhoří, dětské tratě 3 a 7 km, kontrolní bod Pěticestí), odkazy na propozice a výsledky.
- `propositions.php` – Propozice a trať: oznámení, že 2027 zatím není potvrzeno; strukturované oddíly (pořadatelé, program, kategorie – tabulka, tratě, startovné, platba, časový limit, bezpečnost, ubytování, kontakty) + odkaz na oficiální PDF na `vodacka30.cz`.
- `registration.php` – formulář viz bod 5.
- `registration_confirmation.php` – rekapitulace + platební instrukce.
- `start_list.php` – startovní listina ročníku 2026 (mock, jen veřejná pole: start. číslo, jméno, oddíl, kategorie) + vysvětlení, že se nezveřejňují e-mail, telefon, datum narození ani platební stav.
- `results.php` – výsledky 2026 (mock: pořadí, start. číslo, závodník, oddíl, čas, ztráta) s filtrem kategorie přes `?kategorie=`, souhrnné tabulky podle kategorií, odkaz na oficiální PDF; sekce archiv 2015–2026 s vyznačením neověřených ročníků.
- `gallery.php` – alba podle ročníků (mock seznam), novější odkazy na externí galerii; bez nahrávání.
- `contact.php` – pořadatel a kontakty (organizační výbor, ředitel závodu, hlavní rozhodčí – mock), odkaz na TJ KVT Pardubice a fotogalerii.

### Souhrn souborů

**Nové:** `config/helpers.php`, `app/Controllers/{About,Race,Registration,StartList,Results,Gallery,Contact}Controller.php`, `app/Views/{about,propositions,registration,registration_confirmation,start_list,results,gallery,contact}.php`.

**Upravené:** `web/index.php`, `app/Views/layout.php`.

**Bez změny:** `index.php`, `.htaccess`, `config/autoloader.php`, `app/Controllers/HomeController.php`, `app/Views/{home,404}.php`.

## Předpoklady a rozhodnutí

- Cílová (a jediná) aplikace je [v30](file:///c:/Users/onvin/Documents/WEB/v30/v30); žádná další reference v repozitáři není.
- Žádná DB, Composer ani Node – mock data v kontrolerech (volba uživatele).
- Přihláška je funkční, ale ukládá se pouze do session; v UI to bude výslovně uvedeno.
- Platba = bankovní převod bez QR (potvrzené rozhodnutí MVP).
- Ročník 2027 je nepotvrzený; obsah stránek se proto vztahuje k ročníku 2026 jako k archivnímu.
- Kontakt a Galerie jsou informační stránky bez formuláře a bez nahrávání souborů.
- Design se neřeší; používají se existující Tailwind utility a vzory.
- Staré URL na `vodacka30.cz` se neřeší ani nepřesměrovávají; externí odkazy zůstávají tam, kde je uváděl původní web.

## Pořadí implementace

1. **Infrastruktura** – `config/helpers.php`, úprava `web/index.php` (metody + nové routy, 405), úprava `layout.php` (navigace, patička, aktivní odkaz, flash). Po tomto kroku přestane `/vysledky` padat a všechny odkazy z navigace vedou na existující routu.
2. **Statické stránky** v pořadí navigace: O závodu → Propozice a trať → Startovní listina → Výsledky a archiv → Galerie → Kontakt.
3. **Funkční přihláška** – `create` (GET) → `store` (POST, validace, CSRF, session) → `confirmation`.
4. **Sladění a kontrola** – konzistence mock dat a odkazů, průchod všech rout, ověření 404/405.

## Ověření

- **Syntaxe:** `C:\xampp\php\php.exe -l` na každý nový i upravený PHP soubor.
- **HTTP (přes Apache na `http://v30.test`):** projít `GET /`, `/zavod`, `/propozice`, `/prihlaska`, `/startovni-listina`, `/vysledky`, `/galerie`, `/kontakt` a ověřit návratový kód 200 a očekávaný nadpis (např. přes `Invoke-WebRequest`). Preferovat Apache, protože `.htaccess` s přepisem URL vestavěný PHP server nerespektuje.
- **Přihláška:**
  - Validní POST → přesměrování 302 na `/prihlaska/potvrzeni`, stránka obsahuje vygenerované ID a platební instrukce.
  - Nevalidní vstup (chybějící/neplatný e-mail, chybějící souhlas) → zpět na `/prihlaska` s chybovou zprávou a zachovanými hodnotami.
  - Neplatný CSRF token nebo vyplněný honeypot → formulář odmítnut bez uložení.
- **Chybové stavy:** neexistující cesta → 404; existující cesta s nesprávnou metodou → 405.
- **Regrese:** úvodní stránka a 404 zůstávají funkční a beze změny chování.
- **Bez zápisů mimo session:** žádné soubory ani DB se nemění.
