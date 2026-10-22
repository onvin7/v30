# Technická dokumentace

Jak je web Vodácké 30 postavený, kde co leží a jak se změny dělají. Kontext a zadání je v [PROJEKT.md](PROJEKT.md), vzhled v [DESIGN.md](DESIGN.md), přehled v [README.md](README.md).

## 1. Stack a jeho hranice

- **Čisté PHP (MVC)** bez frameworku: vlastní router, kontrolery, repozitáře a šablony.
- **Bez externích závislostí** – žádný Composer, žádný Node.js build. Nepřidávej ani jedno.
- **Bez databáze.** Živá data (přihlášky, později účty oddílů a příspěvky) se ukládají do JSON souborů ve `storage/` přes repozitáře v `app/Models/`. Ukázková data závodu jsou v PHP a `config/zavod.php`.
- **Tailwind CSS přes CDN** v `<head>` `layout.php` (vlastní paleta v `tailwind.config`), **Alpine.js přes CDN** s pluginem `intersect` pro mobilní menu a scroll-reveal.
- **Fonty** Inter (tělo) a Archivo (nadpisy) z Google Fonts.
- **Obrázky nejsou v repozitáři.** `config/images.php` drží klíč → URL z alb pořadatele na Zoneramě, šablony je vykreslují pomocí `img_url()` a `img_srcset()`. Chybějící obrázek nahradí grafický blok, nikdy se negeneruje náhradní fotka.

Požadavek na běhové prostředí je jen PHP a zapisovatelná složka `storage/`.

## 2. Strom projektu

```text
v30/                                  kořen repozitáře – dokumentace
|-- README.md, PROJEKT.md, TECHNICKA_DOKUMENTACE.md, DESIGN.md
`-- v30/                              vlastní aplikace
    |-- index.php                     vstupní bod, zpevní session → web/index.php
    |-- .htaccess                     přepis cest, blokace interních složek, hlavičky, gzip, cache
    |-- .gitignore                    storage/*.json, storage/*.log, storage/*.lock
    |-- .trae/documents/              archiv plánů a designové koncepty
    |-- web/index.php                 front controller: router, sitemap.xml, robots.txt, 404/405
    |-- config/
    |   |-- autoloader.php            PSR-4: App\ → app/
    |   |-- helpers.php               e(), CSRF, flash, redirect, zavod(), img_url(), img_srcset()
    |   |-- zavod.php                 jediný zdroj pravdy: ročník, místo, kontakty, kategorie, ceny
    |   |-- images.php                klíč → URL obrázku v albech na Zoneramě
    |   `-- seo.php                   adresa webu, seznam URL pro sitemapu, strukturovaná data
    |-- app/
    |   |-- Controllers/              8 veřejných kontrolerů
    |   |-- Models/                   RegistrationRepository (zatím jediný repozitář)
    |   |-- Helpers/                  Client, Log, RateLimit, Seo, Text
    |   `-- Views/                    layout.php, šablony stránek, partials/, 404.php, 405.php
    `-- storage/                      .htaccess; JSON soubory a logy (mimo Git)
```

`app/Controllers/Admin/`, `app/Middleware/`, `app/Views/admin/`, `app/Views/oddil/`, `app/Models/OddilRepository.php`, `config/admin.php` a `config/oddily-2026.php` **na `main` neexistují** – patří do větví `feature/administrace` a `feature/ucty-oddilu` (viz část 8).

## 3. Tok požadavku

```text
Prohlížeč
  → Apache (.htaccess: neexistující cesta → index.php)
  → index.php (zpevní session cookie, předá řízení web/index.php)
  → web/index.php:
       definuje APP_ROOT, načte autoloader a helpery
       obslouží /sitemap.xml a /robots.txt
       hledá shodu v mapě rout přes preg_match nad cestou bez koncového lomítka
  → App\Controllers\XController::metoda
       připraví data (případně přes repozitář z app/Models/)
       nastaví $title, $description, $view
  → require app/Views/layout.php
       hlavička s navigací, SEO metadata, flash zprávy
       require $view  (obsah stránky)
       patička
  → HTML odpověď
```

Když cesta nesedí na žádnou routu, vrací se **404**. Když sedí, ale jen pro jinou HTTP metodu, vrací se **405** s hlavičkou `Allow`.

## 4. Adresy (routy)

Mapa rout je v `web/index.php` a hledá se podle metody (`GET` / `POST`) a regulárního výrazu nad cestou.

### Veřejné stránky (na `main`)

| Metoda | Cesta | Kontroler :: metoda |
|---|---|---|
| GET | `/` | `HomeController::index` |
| GET | `/zavod` | `AboutController::index` |
| GET | `/propozice` | `RaceController::propositions` |
| GET | `/prihlaska` | `RegistrationController::create` |
| POST | `/prihlaska` | `RegistrationController::store` |
| GET | `/prihlaska/potvrzeni` | `RegistrationController::confirmation` |
| GET | `/startovni-listina` | `StartListController::index` |
| GET | `/vysledky` | `ResultsController::index` (filtr `?kategorie=`) |
| GET | `/galerie` | `GalleryController::index` |
| GET | `/kontakt` | `ContactController::index` |
| GET | `/sitemap.xml`, `/robots.txt` | generuje router, bez kontroleru |

### Zóna oddílu (větev `feature/ucty-oddilu`, guard `OddilAuth`)

| Metoda | Cesta | Akce |
|---|---|---|
| GET/POST | `/oddil/prihlaseni` | přihlášení vedoucího oddílu |
| GET/POST | `/oddil/registrace` | registrace oddílu (čeká na schválení) |
| GET | `/oddil` | přehled oddílu |
| GET/POST | `/oddil/zavodnici`, `/oddil/zavodnici/(\d+)`, `/oddil/zavodnici/(\d+)/smazat` | správa soupisky |
| GET/POST | `/oddil/prihlaska` | hromadná přihláška oddílu |
| GET | `/oddil/prihlaska/potvrzeni`, `/oddil/prihlaska/V30-\d{4}-\d{4}` | potvrzení a detail přihlášky |
| GET | `/oddil/oznameni/([a-z-]+)/([A-Za-z0-9]+)` | veřejný text oznámení (simulace e-mailu) |
| POST | `/oddil/odhlaseni` | odhlášení |

### Administrace (větev `feature/administrace`, guard `AdminAuth`)

| Metoda | Cesta | Akce |
|---|---|---|
| GET/POST | `/admin/prihlaseni` | přihlášení administrátora |
| GET | `/admin` | přehled příspěvků na úvodní stránce |
| GET/POST | `/admin/prispevky/novy`, `/admin/prispevky/(\d+)/upravit`, `/admin/prispevky/(\d+)`, `/admin/prispevky/(\d+)/smazat` | správa příspěvku |
| GET | `/admin/oddily` | seznam oddílů |
| POST | `/admin/oddily/(\d+)/schvalit`, `/zamitnout`, `/reset-hesla`, `/smazat`, `/admin/oddily/import` | správa oddílů a import soupisek 26. ročníku |
| GET | `/admin/prihlasky`, `/admin/prihlasky/export` | přehled přihlášek a export do CSV |
| POST | `/admin/prihlasky/V30-\d{4}-\d{4}`, `/admin/odhlaseni` | změna stavu přihlášky, odhlášení |

## 5. Konfigurace a pomocné funkce

### `config/zavod.php`

Jediný zdroj pravdy pro parametry závodu: číslo a termín ročníku, uzávěrka včasné platby, archivní ročník, místo a trať, kontakty na pořadatele, číslo účtu (zatím prázdné) a seznam kategorií (kód, název, trať, styl, cena, cena na místě, věkové hranice). Kontrolery ani šablony hodnoty neopisují, čtou je přes `zavod()`. Kategorie a ceny se proto nemůžou na jednotlivých stránkách rozejít a nový ročník se mění na jednom místě.

### `config/helpers.php`

- `e($value)` – escapování výstupu.
- `csrf_token()`, `csrf_field()`, `csrf_verify()` – CSRF ochrana formulářů.
- `flash_set()`, `flash_get()` – jednorázové zprávy v session.
- `old_get($key)` – vrací vyplněnou hodnotu po neúspěšné validaci.
- `redirect($path)` – přesměrování a ukončení požadavku (PRG vzor).
- `zavod()` – načte `config/zavod.php`, jednou za požadavek.
- `images()`, `img_url($key)`, `has_img($key)` – mapa obrázků; prázdný řetězec znamená „obrázek není k dispozici“.
- `img_srcset($key, $sirky)` – sestaví varianty pro `srcset` z URL na Zoneramě.

### `config/seo.php`, `config/images.php`, `app/Helpers/`

- `seo.php` – adresa webu, seznam URL pro sitemapu, strukturovaná data.
- `images.php` – klíč → URL obrázku (alba pořadatele na Zoneramě).
- `Seo` – titulek, popis, canonical, Open Graph, Twitter card, sitemap a strukturovaná data (používá i router pro `/sitemap.xml`).
- `Client` – IP adresa klienta a její anonymizace pro logy.
- `Log` – zápisy o přihlášeních a zásazích do `storage/*.log`.
- `RateLimit` – limit pokusů přes `storage/rate_limit.json` (blokace po 5 neúspěšných pokusech).
- `Text` – drobné úpravy textů a tvorba slugu.

## 6. Data a jejich ukládání

- **Data závodu** (ročník, termín, kategorie, ceny, kontakty, místo, trať) jsou v `config/zavod.php`. Ostatní ukázkový obsah (startovní listina, výsledky, alba galerie) je napevno v kontrolerech.
- **Živá data** ukládají repozitáře z `app/Models/` do JSON souborů ve `storage/`: zámek a atomický zápis přes dočasný soubor, žádná databáze. Do souborů se nesahá přímo z kontroleru ani ze šablony.
- `RegistrationRepository` spravuje přihlášky jednotlivců (`storage/prihlasky.json`), jejich stav a identifikátor `V30-<rok>-XXXX` s náhodným variabilním symbolem. Ve větvích k němu přibývají `OddilRepository` (`storage/oddily.json`) a `PostRepository` (`storage/prispevky.json`).
- Soubory ve `storage/` obsahují osobní údaje, proto jsou v `.gitignore` a nesmí se commitovat. Složka `storage/` musí být pro PHP zapisovatelná.

## 7. Bezpečnost

- **Formuláře:** serverová validace, CSRF token (`csrf_field()`, ověření `csrf_verify()`), honeypot pole `website` a limitování pokusů (blokace po 5 neúspěšných) přes `RateLimit`.
- **Vstup do zón:** hesla se ukládají jen jako hash (`password_hash()`), přihlášení administrátora se po 30 minutách nečinnosti ukončí, obnova hesla oddílu je možná jen přes administraci. Guardy `AdminAuth` a `OddilAuth` (ve větvích) přesměrují nepřihlášeného na přihlášení.
- **Session:** nastavení cookie se zpevňuje v `v30/v30/index.php` ještě před `session_start()` v routeru.
- **`.htaccess`:** blokuje přístup do `app/`, `config/` a `storage/`, skryté soubory, archivy (`.zip`, `.tar.gz` a další) a vývojové pomocníky ve webrootu; vynucuje HTTPS mimo lokální vývoj a Webzdarma; nastavuje hlavičky `X-Content-Type-Options`, `X-Frame-Options`, `X-XSS-Protection` a `Referrer-Policy` a doplňuje gzip a cache statických souborů.
- **Escapování:** veškerý výstup jde přes `e()`; uživatelský vstup se nikdy nevkládá do HTML přímo.
- **Indexace:** `/sitemap.xml` a `/robots.txt` generuje router; `/admin` a veřejné texty oznámení oddílů jsou z indexace vyjmuty.
- **Soukromí:** veřejně se zobrazuje jen startovní číslo, jméno, oddíl a kategorie. E-mail, telefon, ročník narození, poznámka ani stav platby se nezobrazují nikdy – ani ve filtrech, ani v exportech.

## 8. Větve a co v nich je

| Větev | Obsah | Stav |
|---|---|---|
| `main` | Veřejný web a přihláška jednotlivce | Aktivní |
| `feature/administrace` | Administrace `/admin`: příspěvky, správa oddílů, přehled přihlášek, CSV export (oddělovač `;`, UTF-8 BOM) | Nesloučeno |
| `feature/ucty-oddilu` | Zóna oddílu `/oddil`: registrace se schválením, soupiska, hromadná přihláška | Nesloučeno |
| `backup/ucty-oddilu-pred-rozdelenim` | Záloha před rozdělením zóny oddílu | Archiv |

Obě funkční větve přidávají routy do `web/index.php`, takže při sloučení vznikne v mapě rout konflikt. Na `main` proto odkaz `/oddil/registrace` ze stránky přihlášky vede na 404 – opraví to až sloučení (první bod v [PROJEKT.md](PROJEKT.md#co-zbývá)).

## 9. Přihláška jednotlivce

### Pole formuláře

| Pole | Význam | Typ |
|---|---|---|
| `kategorie` | Kategorie závodu | výběr (allowlist z `config/zavod.php`) |
| `oddil` | Oddíl / tým | text (max 100 znaků) |
| `jmeno` | Jméno | text (max 60 znaků) |
| `prijmeni` | Příjmení | text (max 60 znaků) |
| `rocnik_narozeni` | Ročník narození | čtyři číslice, 1900 až aktuální rok |
| `email` | E-mailová adresa | platný formát |
| `poznamka` | Poznámka | textarea, nepovinné |
| `souhlas` | Souhlas se zpracováním údajů | zaškrtávací pole, musí být zaškrtnuté |
| `_csrf` | CSRF token | skryté |
| `website` | Honeypot proti spamu | skryté, musí zůstat prázdné |

### Chování

- Při chybě validace se uživatel vrátí na formulář s chybovými zprávami u polí a se zachovanými hodnotami (`old_get()`).
- Při úspěchu se přesměruje na `/prihlaska/potvrzeni` s rekapitulací, identifikátorem `V30-<rok>-XXXX` a platebními instrukcemi. Uložení probíhá přes `RegistrationRepository`.
- Neplatný CSRF token nebo vyplněný honeypot se odmítne bez uložení.
- Potvrzovací e-mail se neposílá. Text, který by e-mail obsahoval, se zobrazí přímo na stránce.

## 10. Jak pracovat s kódem

- **Kód i texty jsou česky** – komentáře, názvy proměnných, zprávy i obsah stránek.
- **Parametry závodu neopisuj.** Kategorie, ceny, termín a kontakty se berou z `config/zavod.php` přes `zavod()`.
- **Data ukládej jen přes repozitář** v `app/Models/`, nikdy nepiš JSON ze šablony ani z kontroleru.
- **Drž existující vzory** vzhledu (viz [DESIGN.md](DESIGN.md)) a šablon; nevytvářej nové abstrakce pro jednorázové použití.
- **Neměň metodu ani pořadí rout bez důvodu** – chybějící routa pro existující cestu vrací 405.
- **Osobní údaje nepatří do `config/`** ani do repozitáře. Ve vývoji používej fiktivní data a údaje vkládej jen při zpracování požadavku.
- Dokumentaci aktualizuj ve stejném commitu jako změnu chování (routy, ukládání dat, vzhled).

## 11. Jak změny ověřit

- **Syntaxe:** `php -l <soubor>` pro každý upravený PHP soubor.
- **Routy:** `GET /`, `/zavod`, `/propozice`, `/prihlaska`, `/startovni-listina`, `/vysledky`, `/galerie`, `/kontakt`, `/sitemap.xml` a `/robots.txt` musí vracet 200 a očekávaný obsah.
- **Přihláška:** platný `POST /prihlaska` vede na `/prihlaska/potvrzeni`; nevalidní vstup zpět na formulář s chybou a zachovanými hodnotami; neplatný CSRF nebo vyplněný honeypot se odmítne bez uložení.
- **Uložení:** po odeslání přibude záznam ve `storage/prihlasky.json`; neúspěšné pokusy se projeví ve `storage/rate_limit.json`.
- **Chybové stavy:** neexistující cesta vrací 404, existující cesta s jinou metodou 405.
- **Zóna oddílu a administrace (po sloučení větví):** registrace oddílu → schválení v `/admin` → přihlášení do `/oddil` → hromadná přihláška → CSV export s oddělovačem `;` a UTF-8 BOM.
- **Responzivita:** v DevTools na šířkách 320, 360, 390, 640, 768, 1024, 1280 a 1920 px zkontroluj, že stránka nemá horizontální scroll (`document.documentElement.scrollWidth <= window.innerWidth`).
- **Úklid:** po testech nenechávej ve `storage/` testovací záznamy. Soubor `storage/rate_limit.json` blokuje přihlášení po 5 neúspěšných pokusech, takže počítej s tím při testování.

## 12. Lokální spuštění

- **Apache (doporučeno):** vhost `http://v30.test`, document root do `v30/v30` (tam leží `.htaccess`).
- **Vestavěný server PHP:** z `v30/v30` spusť `php -S 127.0.0.1:8000 index.php` a otevři `http://127.0.0.1:8000`. `.htaccess` se v tomto režimu neuplatní – routy fungují, ale přepis adres, blokace složek a hlavičky ne.
