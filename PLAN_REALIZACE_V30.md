# Realizační plán: Vodácká 30

Tento plán doplňuje [hlavní plán](PLAN_PROJEKTU_V30.md), [specifikaci MVP](SPECIFIKACE_MVP_V30.md) a [strukturu projektu](STRUKTURA_PROJEKTU_V30.md). Popisuje, co je na webu hotové a co následuje.

## Potvrzená rozhodnutí

- Web je určen **pouze pro běžkařskou Vodáckou 30**. Východočeský vodácký maraton (lodní kategorie z původního formuláře `?menu=6`) není součástí webu ani přihlášky.
- Aplikace se staví jako **čisté PHP MVC bez frameworku**, bez externích závislostí, bez Node.js buildu a **bez databáze**.
- Data jsou zatím ukázková (mock) přímo v kontrolerech; přihláška se ukládá pouze do session.
- Platba je bankovní převod **bez QR** – samotné zobrazení platebních instrukcí neznamená zaplaceno.
- Údaje o ročníku 2026 jsou archivní; podobu dalšího ročníku musí potvrdit pořadatel.

## HOTOVO

### 1. Infrastruktura (čisté PHP MVC)

- Vstupní bod `index.php` a `.htaccess` v kořeni aplikace `v30/v30`.
- Front controller a router `web/index.php` s mapou rout podle HTTP metody (GET/POST) a hledáním shody přes `preg_match`.
- PSR-4 autoloader `config/autoloader.php` (namespace `App\` → `app/`).
- Pomocné funkce `config/helpers.php` – escapování `e()`, CSRF (`csrf_token`/`csrf_field`/`csrf_verify`), flash zprávy, `old_get()` a `redirect()`.
- Společný layout `app/Views/layout.php` – hlavička s responzivní navigací (Alpine.js), aktivní odkaz, flash zprávy, `main` a patička.
- Chybové stránky **404** a **405**.

### 2. Statické stránky

Hotové a napojené na router: Úvod (`/`), O závodu (`/zavod`), Propozice a trať (`/propozice`), Startovní listina (`/startovni-listina`), Výsledky a archiv (`/vysledky`), Galerie (`/galerie`), Kontakt (`/kontakt`).

### 3. Funkční přihláška

- `GET /prihlaska` – formulář s kategoriemi, oddílem, jménem, příjmením, ročníkem narození, e-mailem, poznámkou a souhlasem; CSRF pole a honeypot proti spamu.
- `POST /prihlaska` – serverová validace (kategorie z allowlistu, povinné údaje, formát e-mailu, ročník narození, souhlas); při chybě návrat na formulář s chybami a zachovanými hodnotami.
- `GET /prihlaska/potvrzeni` – rekapitulace přihlášky s identifikátorem a platebními instrukcemi (bankovní převod).
- Uložení je **simulované**: záznam zůstává v `$_SESSION['registrations']`, vygeneruje se identifikátor `V30-<rok>-XXXX` a náhodný variabilní symbol.

### 4. Naplnění daty ročníku 2026

- Údaje **26. ročníku (17. 1. 2026)**: hlavní trať 30 km, dětské tratě 3 km klasicky a 7 km volně, start a cíl u chaty Bedřichovka v Orlickém Záhoří, kontrolní bod Pěticestí.
- Startovné (200 Kč děti, 300 Kč bezhotovostně do 15. 1. 2026, 400 Kč na místě), časový limit 17:00.
- Startovní listina a výsledky ročníku 2026 převzaté z oficiálních listin, s filtrem výsledků podle kategorie.
- Kontakty na pořadatele (TJ KVT Pardubice, Aleš Křivka, Jiří Roleček).

### 5. Vložená mapa trati

- Na stránce Propozice a trať je vložena mapa hlavní trati (`https://mapy.com/s/calefedoho`) a odkaz na její otevření. Dětské tratě mapu zatím nemají.

## NÁSLEDUJE

### 1. Persistence a datová vrstva

- Zavedení databáze a modelů pro ročníky, kategorie, přihlášky a výsledky (nahrazení mock dat).
- Přesun dat z kontrolerů do datové vrstvy a napojení přihlášky na reálné ukládání.

### 2. Napojení na reálná data

- Potvrzení a naplnění skutečných dat dalšího ročníku pořadatelem (termín, kategorie, ceny, kapacity, uzávěrky).
- Doplnění ověřených starších ročníků do archivu (dosud neověřené zůstávají vyznačené).

### 3. Úplné propozice a PDF

- Doplnění kompletních propozic a oficiálního **PDF** k ročníku.
- Doplnění map dětských tratí.

### 4. Potvrzení přihlášky e-mailem

- Odeslání potvrzovacího e-mailu po přijetí přihlášky (v produkční verzi; nyní text na stránce potvrzení avizuje, že e-mail bude součástí produkce).

### 5. Administrace a správa obsahu

- Rozhraní pro pořadatele ke správě ročníku, kategorií, propozic a výsledků bez zásahu do kódu.
- Stavy přihlášek a ruční evidence plateb.

## Ověření hotové práce

- **Syntaxe:** `php -l` na každý upravený PHP soubor.
- **Průchod rout:** ověřit 200 pro `GET /`, `/zavod`, `/propozice`, `/prihlaska`, `/startovni-listina`, `/vysledky`, `/galerie`, `/kontakt`.
- **Přihláška:** validní `POST /prihlaska` → 302 na `/prihlaska/potvrzeni`; nevalidní vstup → zpět na formulář s chybou; neplatný CSRF nebo vyplněný honeypot → odmítnuto bez uložení.
- **Chybové stavy:** neexistující cesta → 404; existující cesta s jinou metodou → 405.
- **Bez vedlejších zápisů:** kromě session se nic neukládá (žádné soubory ani databáze).

## Lokální vývoj

- Aplikace je v `v30/v30`; projektové dokumenty zůstávají v kořeni repozitáře.
- Primárně Apache vhost **`http://v30.test`** s document rootem do kořene aplikace `v30/v30` (kde je `.htaccess`).
- Alternativa bez Apache: z `v30/v30` spustit `php -S 127.0.0.1:8000 index.php` a otevřít `http://127.0.0.1:8000`.
