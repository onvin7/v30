# Vodácká 30

Veřejný web tradičního **běžkařského** závodu Vodácká 30, který pořádá **TJ KVT Pardubice** v Orlických horách. Poslední zveřejněný **26. ročník** se jel **17. ledna 2026** se startem a cílem u chaty **Bedřichovka** v Orlickém Záhoří.

Repozitář `v30` obsahuje v kořeni pouze tuto dokumentaci. Vlastní aplikace je v podsložce **`v30/v30`**.

## Stav projektu

- Veřejné stránky jsou hotové a naplněné údaji ročníku 2026 (propozice, trať, startovní listina, výsledky, galerie, kontakt).
- Přihláška je funkční: serverová validace, CSRF ochrana a honeypot proti spamu.
- Web **zatím nepoužívá databázi**. Startovní listina, výsledky, propozice, kontakty i galerie jsou v PHP kontrolerech jako ukázková (mock) data a odeslaná přihláška se ukládá pouze do relace prohlížeče (session). Nejde o závaznou registraci.

## Technologie

- **Čisté PHP (MVC)** – vlastní router, kontrolery a šablony, bez frameworku.
- **Bez externích závislostí, bez Node.js buildu** – žádný správce balíčků ani sestavování assetů.
- **Tailwind CSS přes CDN** (styl "Winter Outdoor Utility": slate paleta + oranžový akcent).
- **Alpine.js přes CDN** – pouze pro mobilní menu v hlavičce.
- **Fonty** Inter (tělo) a Archivo (nadpisy) z Google Fonts.
- **Bez databáze** – žádná persistence (viz plán v `PLAN_REALIZACE_V30.md`).

## Struktura složek (zkráceně)

```text
v30/                              (kořen git repozitáře – jen dokumentace)
|-- README.md, AGENTS.md, CLAUDE.md
|-- PLAN_PROJEKTU_V30.md, PLAN_REALIZACE_V30.md, SPECIFIKACE_MVP_V30.md
|-- STRUKTURA_PROJEKTU_V30.md, designov_mantinely.md
`-- v30/                          (vlastní aplikace)
    |-- index.php                 (vstupní bod → web/index.php)
    |-- .htaccess                 (přepis adres na index.php)
    |-- web/index.php             (front controller + mapa rout)
    |-- config/autoloader.php     (PSR-4 autoloader: App\ → app/)
    |-- config/helpers.php        (e(), CSRF, flash, redirect)
    |-- app/Controllers/          (8 kontrolerů)
    `-- app/Views/               (layout + 12 šablon)
```

Podrobný popis je v `STRUKTURA_PROJEKTU_V30.md`.

## Jak spustit lokálně

Aplikace běží na Apache vhostu **`http://v30.test`**. Document root míří do kořene aplikace `v30/v30`, kde leží `.htaccess`; ten přepíše všechny požadavky, které nemíří na existující soubor, na `index.php`, a ten předá řízení front controlleru `web/index.php`.

Alternativa bez Apache (vestavěný server PHP): spusťte z kořene aplikace `v30/v30` příkaz

```powershell
php -S 127.0.0.1:8000 index.php
```

a otevřete `http://127.0.0.1:8000`. Vstupní `index.php` v tomto režimu slouží jako router. Apache je přesto preferovaný, protože `.htaccess` s přepisem adres vestavěný server nerespektuje.

## Přehled adres (rout)

| Metoda | Cesta | Stránka |
|---|---|---|
| GET | `/` | Úvod |
| GET | `/zavod` | O závodu |
| GET | `/propozice` | Propozice a trať |
| GET | `/prihlaska` | Přihlášky (formulář) |
| POST | `/prihlaska` | Zpracování přihlášky |
| GET | `/prihlaska/potvrzeni` | Potvrzení přihlášky |
| GET | `/startovni-listina` | Startovní listina |
| GET | `/vysledky` | Výsledky a archiv |
| GET | `/galerie` | Galerie |
| GET | `/kontakt` | Kontakt |

Neznámá cesta vrací stránku **404**, existující cesta s jinou HTTP metodou vrací **405**.

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
