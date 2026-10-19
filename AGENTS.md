# Pokyny pro AI agenty: projekt Vodácká 30

## Účel

Tento repozitář (`v30`) obsahuje dokumentaci a web tradičního **běžkařského** závodu Vodácká 30 v Orlických horách, který pořádá TJ KVT Pardubice. Návštěvník musí najít informace o závodě a bezpečně se přihlásit.

## Kde je aplikace

- Vlastní aplikace je v podsložce **`v30/v30`**. Tam patří veškeré úpravy kódu.
- V **kořeni repozitáře** jsou pouze tyto dokumenty (Markdown). Nezakládej zde nové soubory a nemaž existující, pokud to zadání výslovně nevyžaduje.

## Tech stack

- **Čisté PHP (MVC)** bez frameworku – vlastní router, kontrolery a šablony.
- **Bez externích závislostí, bez Node.js buildu** – nepřidávej závislosti ani build pipeline.
- **Bez databáze.** Data (startovní listina, výsledky, propozice, kontakty, galerie) jsou zatím ukázková (mock) data přímo v PHP kontrolerech. Přihláška se ukládá jen do `$_SESSION`.
- **Tailwind CSS přes CDN** a **Alpine.js přes CDN** (jen mobilní menu), fonty Inter + Archivo z Google Fonts.

## Jak funguje aplikace

1. `.htaccess` v kořeni aplikace přepíše neexistující cesty na `index.php`.
2. `index.php` předá řízení front controlleru `web/index.php`, který drží mapu rout.
3. Router hledá shodu přes `preg_match` nad normalizovaným URI (bez koncového lomítka) podle HTTP metody.
4. Kontroler z `app/Controllers/` připraví data a nastaví `$title` a `$view`, pak použije `require APP_ROOT . '/app/Views/layout.php'`.
5. `layout.php` vykreslí hlavičku s navigací, flash zprávy a `require` dané view ze `app/Views/`.
6. Neznámá cesta → `404.php`; existující cesta s jinou metodou → `405.php`.

## Konvence kódu

- **Kód i texty jsou česky** (komentáře, názvy proměnných, zprávy, obsah stránek). Kód drž česky konzistentně s okolím.
- **Escapuj výstup** přes `e($value)` z `config/helpers.php`; v souborech, kde ještě `e()` není, se používá `htmlspecialchars`. Nikdy nevkládej uživatelský vstup do HTML přímo.
- Formuláře chraň **CSRF tokenem** (`csrf_field()`, ověření `csrf_verify()`) a honeypot políčkem.
- Jednorázové zprávy a návrat vyplněných hodnot řeš přes `flash_set()`/`flash_get()` a `old_get()`.
- Přesměrování prováděj funkcí `redirect()` (PRG vzor, ochrana proti dvojímu odeslání).
- Drž jednotný vizuální jazyk (viz `designov_mantinely.md`) a existující vzory šablon. Nevytvářej nové abstrakce pro jednorázové použití.
- Neměň HTTP metodu rout ani pořadí bez důvodu; chybějící routa pro existující cestu vrací 405.

## Zásady soukromí (nepřekročitelné)

- Veřejně se zveřejňují pouze **startovní číslo, jméno, oddíl a kategorie**.
- **E-mail, telefon, datum (ročník) narození, poznámka a stav platby se nikdy nezobrazují** na veřejných stránkách, ve filtrech, exportech ani v odpovědích.
- **Historická osobní data se do databáze nepřenášejí** bez ověření účelu, právního titulu, retence a přístupových práv. Ve vývoji používej fiktivní/anonymizovaná data.
- Neznámá ani produkční databáze se nepřipojuje a nemigruje bez read-only auditu a výslovného rozhodnutí.

## Produktové mantinely

- Web je **jen pro běžkařskou Vodáckou 30**. Východočeský vodácký maraton (lodní kategorie C2/K1/K2 z formuláře `?menu=6` na původním webu) do tohoto webu ani přihlášky nepatří.
- Kategorie, ceny, termíny a kapacity musí pocházet z potvrzeného zdroje pořadatele, ne odhadu.
- Platba je bankovní převod **bez QR** (dosud potvrzené rozhodnutí); samotné vygenerování platebních instrukcí neznamená zaplaceno.
- Údaje o ročníku 2026 jsou archivní a na další ročník se automaticky nevztahují. Aktuální podobu závodu musí potvrdit pořadatel.

## Jak ověřit změny

- **Syntaxe:** pro každý upravený PHP soubor spusť `php -l <soubor>`.
- **Průchod rout:** ověř, že `GET /`, `/zavod`, `/propozice`, `/prihlaska`, `/startovni-listina`, `/vysledky`, `/galerie`, `/kontakt` vracejí 200 a očekávaný nadpis.
- **Přihláška:** validní `POST /prihlaska` → 302 na `/prihlaska/potvrzeni`; nevalidní vstup → zpět na `/prihlaska` s chybou a zachovanými hodnotami; neplatný CSRF nebo vyplněný honeypot → odmítnuto bez uložení.
- **Chybové stavy:** neexistující cesta → 404; existující cesta s nesprávnou metodou → 405.
- Preferuj Apache (`http://v30.test`), protože `.htaccess` s přepisem adres vestavěný PHP server nerespektuje.

## Jak spustit lokálně

- Apache vhost **`http://v30.test`**, document root do kořene aplikace `v30/v30` (kde je `.htaccess`).
- Alternativně z `v30/v30`: `php -S 127.0.0.1:8000 index.php` → `http://127.0.0.1:8000`.

## Zdroje pravdy

- `PLAN_PROJEKTU_V30.md` – cíle, rozsah, navigace a obsah stránek.
- `SPECIFIKACE_MVP_V30.md` – funkční požadavky, pole formuláře, validace, soukromí, akceptační kritéria.
- `STRUKTURA_PROJEKTU_V30.md` – struktura aplikace a tok požadavku.
- `designov_mantinely.md` – designová pravidla.
- `PLAN_REALIZACE_V30.md` – stav a další kroky.

## Dokumentace

Dokumentační soubory vytvářej jen na výslovné vyžádání. Změny mimo zadání (nesouvisející refaktoring) nedělej.
