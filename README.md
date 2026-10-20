# Vodácká 30

Veřejný web tradičního **běžkařského** závodu Vodácká 30, který pořádá **TJ KVT Pardubice** v Orlických horách. Nejbližší (pořadatelem potvrzený) **27. ročník** se pojede **16. ledna 2027**; naposledy odjetý **26. ročník** se jel **17. ledna 2026**. Start i cíl jsou u chaty **Bedřichovka** v Orlickém Záhoří, kontrolní bod na **Pěticestí**.

Repozitář `v30` obsahuje v kořeni pouze tuto dokumentaci. Vlastní aplikace je v podsložce **`v30/v30`**.

## Stav projektu

- Veřejné stránky jsou hotové a naplněné (propozice a trasy s mapami, startovní listina, výsledky, galerie, kontakt).
- Přihláška funguje pro **jednotlivce i pro oddíly**: serverová validace, CSRF ochrana, honeypot a limit počtu pokusů.
- **Zóna oddílu** (`/oddil`) – vedoucí oddílu se zaregistruje, pořadatel účet schválí, teprve pak se lze přihlásit. Uvnitř spravuje soupisku závodníků a podává hromadnou přihlášku.
- **Administrace** (`/admin`) – příspěvky na úvodní stránku, správa oddílů (schválení, reset hesla, import soupisek 26. ročníku) a přehled přihlášek s exportem do CSV.
- **Web zatím nepoužívá databázi.** Živá data (účty oddílů, přihlášky, příspěvky) se ukládají do JSON souborů ve složce `storage/`. Ukázková (mock) data závodu, startovní listiny a výsledků zůstávají v PHP/`config/`.
- Odesílání e-mailů zatím **není napojené** – text potvrzovací zprávy se zobrazí přímo na stránce.

## Technologie

- **Čisté PHP (MVC)** – vlastní router, kontrolery a šablony; bez frameworku, bez externích závislostí a bez Node.js buildu.
- **Tailwind CSS přes CDN** – vizuální styl „zimní expedice / editorial“: modrá paleta `brand`, `night`, `deep` a doplňky `snow` a `ice` (oranžový akcent se nepoužívá).
- **Alpine.js přes CDN** (včetně pluginu `intersect`) – mobilní menu a scroll-reveal efekty.
- **Fonty** Inter (tělo) a Archivo (nadpisy) z Google Fonts.
- **Bez databáze** – persistence přes JSON soubory v `storage/` (zámek + atomický zápis přes dočasný soubor).
- **Obrázky** z veřejných alb pořadatele na Zoneramě, definované v `config/images.php` a vykreslované s responzivním `srcset`; chybějící obrázek se nahradí grafickým blokem, ne generovanou fotkou.

## Struktura složek (zkráceně)

```text
v30/                              (kořen git repozitáře – jen dokumentace)
|-- README.md, AGENTS.md, CLAUDE.md
|-- PLAN_PROJEKTU_V30.md, PLAN_REALIZACE_V30.md, SPECIFIKACE_MVP_V30.md
|-- STRUKTURA_PROJEKTU_V30.md, designov_mantinely.md
`-- v30/                          (vlastní aplikace)
    |-- index.php                 (vstupní bod, zpevnění session → web/index.php)
    |-- .htaccess                 (přepis adres, blokace interních složek a archivů)
    |-- web/index.php             (front controller + mapa rout)
    |-- config/
    |   |-- autoloader.php        (PSR-4 autoloader: App\ → app/)
    |   |-- helpers.php           (e(), CSRF, flash, redirect, zavod(), img_url(), img_srcset())
    |   |-- zavod.php             (jediný zdroj pravdy – ročník, kategorie, ceny, kontakty)
    |   |-- images.php            (mapa obrázků), seo.php, admin.php, oddily-2026.php
    |-- app/
    |   |-- Controllers/          (8 veřejných + 3 pro zónu oddílu + Admin/ se 4)
    |   |-- Models/               (OddilRepository, RegistrationRepository, PostRepository)
    |   |-- Middleware/           (AdminAuth, OddilAuth)
    |   |-- Helpers/              (Client, Log, RateLimit, Seo, Text)
    |   `-- Views/                (layout + veřejné šablony, oddil/, admin/)
    `-- storage/                  (.htaccess; oddily.json, prihlasky.json, prispevky.json, logy)
```

Podrobný popis je v `STRUKTURA_PROJEKTU_V30.md`.

## Údaje o závodě na jednom místě

Ročník (termín, uzávěrka), kategorie s cenami a kontakty na pořadatele jsou v **`config/zavod.php`**; kontrolery i šablony je čtou přes `zavod()`. Kategorie ani ceny se tedy nemohou na jednotlivých stránkách rozejít a nový ročník se mění na jednom místě. Čísla vycházejí z oficiálních propozic (`vodacka30.cz`), nic se nedomýšlí – nepotvrzené údaje jsou `null` nebo prázdný řetězec.

## Jak spustit lokálně

Aplikace běží na Apache vhostu **`http://v30.test`**. Document root míří do kořene aplikace `v30/v30`, kde leží `.htaccess`; ten přepíše všechny požadavky, které nemíří na existující soubor, na `index.php`, a ten předá řízení front controlleru `web/index.php`.

Alternativa bez Apache (vestavěný server PHP): spusťte z kořene aplikace `v30/v30` příkaz

```powershell
php -S 127.0.0.1:8000 index.php
```

a otevřete `http://127.0.0.1:8000`. Vstupní `index.php` v tomto režimu slouží jako router. Apache je přesto preferovaný, protože `.htaccess` s přepisem adres vestavěný server nerespektuje.

Složka `storage/` musí být pro PHP zapisovatelná (vznikají v ní JSON soubory a logy).

## Přehled adres (rout)

### Veřejné stránky

| Metoda | Cesta | Stránka |
|---|---|---|
| GET | `/` | Úvod (včetně příspěvků) |
| GET | `/zavod` | O závodu |
| GET | `/propozice` | Propozice, trasy a mapy |
| GET | `/prihlaska` | Přihláška jednotlivce (formulář) |
| POST | `/prihlaska` | Zpracování přihlášky |
| GET | `/prihlaska/potvrzeni` | Potvrzení přihlášky |
| GET | `/startovni-listina` | Startovní listina |
| GET | `/vysledky` | Výsledky a archiv |
| GET | `/galerie` | Galerie |
| GET | `/kontakt` | Kontakt |

### Zóna oddílu

| Metoda | Cesta | Akce |
|---|---|---|
| GET | `/oddil/prihlaseni` | Přihlášení vedoucího oddílu |
| POST | `/oddil/prihlaseni` | Ověření přihlášení |
| GET | `/oddil/registrace` | Registrace nového oddílu |
| POST | `/oddil/registrace` | Odeslání žádosti o účet |
| GET | `/oddil` | Přehled oddílu |
| GET/POST | `/oddil/zavodnici` | Soupiska – přidání závodníka |
| POST | `/oddil/zavodnici/(\d+)` | Úprava závodníka |
| POST | `/oddil/zavodnici/(\d+)/smazat` | Smazání závodníka |
| GET/POST | `/oddil/prihlaska` | Hromadná přihláška oddílu |
| GET | `/oddil/prihlaska/potvrzeni` | Potvrzení přihlášky |
| GET | `/oddil/prihlaska/V30-\d{4}-\d{4}` | Detail dřívější přihlášky |
| GET | `/oddil/oznameni/([a-z-]+)/([A-Za-z0-9]+)` | Veřejný text oznámení (simulace e-mailu) |
| POST | `/oddil/odhlaseni` | Odhlášení |

### Administrace

| Metoda | Cesta | Akce |
|---|---|---|
| GET | `/admin/prihlaseni` | Přihlášení administrátora |
| POST | `/admin/prihlaseni` | Ověření přihlášení |
| GET | `/admin` | Přehled příspěvků |
| GET | `/admin/prispevky/novy`, `/admin/prispevky/(\d+)/upravit` | Formulář příspěvku |
| POST | `/admin/prispevky`, `/admin/prispevky/(\d+)`, `/admin/prispevky/(\d+)/smazat` | CRUD příspěvku |
| GET | `/admin/oddily` | Seznam oddílů |
| POST | `/admin/oddily/(\d+)/schvalit`, `/zamitnout`, `/reset-hesla`, `/smazat` | Správa účtu oddílu |
| POST | `/admin/oddily/import` | Import soupisek 26. ročníku |
| GET | `/admin/prihlasky` | Přehled přihlášek |
| GET | `/admin/prihlasky/export` | Export přihlášek do CSV |
| POST | `/admin/prihlasky/V30-\d{4}-\d{4}` | Změna stavu přihlášky |
| POST | `/admin/odhlaseni` | Odhlášení |

### Generované pro vyhledávače

| Cesta | Obsah |
|---|---|
| `/sitemap.xml` | Mapa stránek (z `config/seo.php`) |
| `/robots.txt` | Pravidla s odkazem na sitemapu, `/admin` a oznámení oddílů vyjmuty |

Neznámá cesta vrací stránku **404**, existující cesta s jinou HTTP metodou vrací **405**.

## Přístupy a správa

- **Administrace:** jediný účet z `config/admin.php`. Heslo je v souboru uložené pouze jako hash (`password_hash()`), přihlášení se po 30 minutách nečinnosti ukončí a opakované pokusy jsou limitované.
- **Oddíly:** účty vznikají registrací a teprve schválením pořadatelem; heslo je hashované, obnova hesla je možná jen přes administraci. Soupisky pro první naplnění jsou v `config/oddily-2026.php`.
- **Ochrana:** CSRF tokeny a honeypot u všech formulářů, limitování pokusů (`storage/rate_limit.json`), záznamy o přihlášeních a zásazích administrace v `storage/*.log` (IP pouze anonymizovaně). Složky `app/`, `config/` a `storage/` jsou z webu nedostupné.

## Zásady soukromí

Ve veřejné startovní listině a ve výsledcích se zveřejňují pouze **startovní číslo, jméno, oddíl a kategorie**. E-mail, telefon, datum (ročník) narození, poznámka ani stav platby se nikdy nezobrazují.

## Kontakty na pořadatele

- **Pořadatel:** TJ KVT Pardubice
- **Ředitel závodu:** Aleš Křivka – 724 308 469
- **Hlavní rozhodčí:** Jiří Roleček – 775 125 883

## Další dokumenty

- `AGENTS.md` – pokyny pro AI agenty pracující v tomto repu.
- `CLAUDE.md` – krátké pokyny pro nástroje typu Claude.
- `STRUKTURA_PROJEKTU_V30.md` – struktura aplikace a tok požadavku.
- `PLAN_PROJEKTU_V30.md` – produktový plán a návrh obsahu.
- `PLAN_REALIZACE_V30.md` – co je hotové a co následuje.
- `SPECIFIKACE_MVP_V30.md` – funkční požadavky a akceptační kritéria.
- `designov_mantinely.md` – designová pravidla a komponenty.
