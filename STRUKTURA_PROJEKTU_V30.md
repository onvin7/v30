# Struktura projektu a tok požadavku (Vodácká 30)

Tento dokument doplňuje [hlavní plán projektu](PLAN_PROJEKTU_V30.md) a popisuje, jak je uspořádán kód webu Vodácká 30 v podsložce `v30/` a jak funguje zpracování HTTP požadavku.

Aplikace je postavená jako **čisté PHP MVC bez frameworku**: vlastní router, kontrolery a šablony. Nepoužívá se žádný balíčkovací systém ani databáze.

## Zásady struktury

- Vlastní router drží mapu rout a předává požadavek kontroleru.
- Kontrolery jsou krátké: připraví data a zvolí šablonu, nic dalšího neřeší.
- Šablony vykreslují výstup a escapují přes `e()`.
- Data jsou zatím ukázková (mock) přímo v kontrolerech – bez database vrstvy a bez modelů.
- Veřejné čtení (startovní listina, výsledky) zobrazuje jen schválená pole; osobní údaje zůstávají mimo veřejný výstup.

## Strom projektu

```text
v30/                                  (kořen git repozitáře – dokumentace)
|-- README.md
|-- AGENTS.md
|-- CLAUDE.md
|-- PLAN_PROJEKTU_V30.md
|-- PLAN_REALIZACE_V30.md
|-- SPECIFIKACE_MVP_V30.md
|-- STRUKTURA_PROJEKTU_V30.md
|-- designov_mantinely.md
`-- v30/                              (vlastní aplikace)
    |-- index.php                     (vstupní bod → web/index.php)
    |-- .htaccess                     (přepis neexistujících cest na index.php)
    |-- .trae/
    |   `-- documents/
    |       `-- plan-dokonceni-webu-v30.md   (interní poznámky k dokončení)
    |-- web/
    |   `-- index.php                 (front controller + mapa rout)
    |-- config/
    |   |-- autoloader.php            (PSR-4: App\ → app/)
    |   `-- helpers.php               (e(), CSRF, flash, redirect)
    |-- app/
    |   |-- Controllers/
    |   |   |-- HomeController.php
    |   |   |-- AboutController.php
    |   |   |-- RaceController.php
    |   |   |-- RegistrationController.php
    |   |   |-- StartListController.php
    |   |   |-- ResultsController.php
    |   |   |-- GalleryController.php
    |   |   `-- ContactController.php
    |   `-- Views/
    |       |-- layout.php
    |       |-- home.php
    |       |-- about.php
    |       |-- propositions.php
    |       |-- registration.php
    |       |-- registration_confirmation.php
    |       |-- start_list.php
    |       |-- results.php
    |       |-- gallery.php
    |       |-- contact.php
    |       |-- 404.php
    |       `-- 405.php
    `-- (žádné další složky – bez vendor, bez public, bez databáze)
```

## Odpovědnost složek a souborů

### `index.php` a `.htaccess` (kořen aplikace)

- `.htaccess` zapne `mod_rewrite` a přesměruje každý požadavek, který nemíří na existující soubor nebo složku, na `index.php`.
- `index.php` je minimální vstupní bod – přes `require` předá řízení front controlleru `web/index.php`.

### `web/index.php` (front controller a router)

- Spustí output buffering a session.
- Definuje konstantu `APP_ROOT` (kořen projektu) a načte `config/autoloader.php` a `config/helpers.php`.
- Z `$_SERVER['REQUEST_URI']` vytáhne cestu, odstraní koncové lomítko (kromě `/`) a zjistí HTTP metodu.
- Drží mapu rout podle metody a hledá shodu přes `preg_match('#^cesta$#', $uri)`.
- Když najde shodu, vytvoří instanci kontroleru a zavolá jeho metodu.
- Když cesta existuje, ale jen pro jinou metodu, vrátí **405** (`app/Views/405.php`).
- Když nesedí žádná routa, vrátí **404** (`app/Views/404.php`).

Mapa rout:

| Metoda | Cesta | Kontroler :: metoda |
|---|---|---|
| GET | `/` | `HomeController::index` |
| GET | `/zavod` | `AboutController::index` |
| GET | `/propozice` | `RaceController::propositions` |
| GET | `/prihlaska` | `RegistrationController::create` |
| POST | `/prihlaska` | `RegistrationController::store` |
| GET | `/prihlaska/potvrzeni` | `RegistrationController::confirmation` |
| GET | `/startovni-listina` | `StartListController::index` |
| GET | `/vysledky` | `ResultsController::index` |
| GET | `/galerie` | `GalleryController::index` |
| GET | `/kontakt` | `ContactController::index` |

### `config/autoloader.php`

Jednoduchý PSR-4 autoloader: prefix `App\` mapuje na složku `app/`. Třída `App\Controllers\HomeController` tedy odpovídá souboru `app/Controllers/HomeController.php`.

### `config/helpers.php`

Sdílené pomocné funkce pro kontrolery i šablony:

- `e($value): string` – escapování výstupu (`htmlspecialchars`).
- `csrf_token()`, `csrf_field()`, `csrf_verify($token)` – CSRF ochrana formulářů.
- `flash_set()`, `flash_get()` – jednorázové zprávy v session.
- `old_get()` – vrací vyplněnou hodnotu po neúspěšné validaci.
- `redirect($path)` – přesměrování a ukončení požadavku.

### `app/Controllers/`

Kontrolery připravují data (aktuálně mock) a nastavují `$title` a `$view`, poté vykreslí `layout.php`.

- `HomeController` – úvodní stránka (hero s datem a místem, trať a místo).
- `AboutController` – stránka O závodu + fakta ročníku 2026.
- `RaceController` – propozice: pořadatelé, program, kategorie, tratě s mapou, startovné a platba.
- `RegistrationController` – přihláška: `create` (formulář), `store` (validace, CSRF, honeypot, uložení do session), `confirmation` (rekapitulace a platební instrukce).
- `StartListController` – startovní listina ročníku 2026 podle kategorií.
- `ResultsController` – výsledky ročníku 2026 podle kategorií s filtrem `?kategorie=`, odkaz na oficiální PDF a archiv ročníků.
- `GalleryController` – alba podle ročníků a fotografie z ročníku 2026 (odkazy do externí galerie).
- `ContactController` – kontakty na pořadatele.

### `app/Views/`

- `layout.php` – společný obal: hlavička s responzivní navigací (Alpine.js) a aktivním odkazem, flash zprávy, `main` s `require $view`, patička.
- Jednotlivé šablony (`home.php`, `about.php`, `propositions.php`, `registration.php`, `registration_confirmation.php`, `start_list.php`, `results.php`, `gallery.php`, `contact.php`) obsahují jen obsah dané stránky.
- `404.php` a `405.php` – chybové stránky.

## Tok požadavku

```text
Prohlížeč
  → Apache (.htaccess: neexistující cesta → index.php)
  → index.php (znovu načte web/index.php)
  → web/index.php: router (preg_match nad cestou podle HTTP metody)
  → App\Controllers\<X>Controller::<metoda>
      → nastaví $title a $view
  → require app/Views/layout.php
      → vykreslí hlavičku, flash zprávy
      → require $view (obsah stránky)
      → vykreslí patičku
  → HTML odpověď
```

Větvení:

- Žádná shoda → **404** (`404.php`).
- Shoda jen pro jinou metodu → **405** (`405.php`, hlavička `Allow`).

## Kde jsou data a proč zatím není databáze

- Data (startovní listina, výsledky, propozice, kategorie, kontakty, galerie) jsou **napevno v PHP kontrolerech** jako ukázková/mock data. Žádný model ani databáze neexistují.
- Tato data popisují **26. ročník (17. 1. 2026)** a vycházejí z oficiálních podkladů na původním webu `vodacka30.cz`.
- Odeslaná přihláška se pouze uloží do `$_SESSION['registrations']`, vygeneruje se identifikátor (`V30-<rok>-XXXX`) a náhodný variabilní symbol; jde o simulaci, ne o závaznou registraci.
- Zavedení persistence (databáze) a napojení na reálná data je **plánováno**, nikoli implementováno – viz [PLAN_REALIZACE_V30.md](PLAN_REALIZACE_V30.md).

## Co dokument neurčuje

Toto je popis skutečného stavu, ne definitivní návrh budoucí architektury. Podoba datové vrstvy, administrace a napojení na reálná data se teprve rozhodne; do té doby platí, že web běží bez databáze a s mock daty.
