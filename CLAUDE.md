# CLAUDE.md

Pokyny pro tento repozitář jsou v **[AGENTS.md](AGENTS.md)** – přečti je jako první.

## Klíčové body

- Vlastní aplikace je v podsložce **`v30/v30`**; kořen repozitáře je jen dokumentace.
- **Čisté PHP MVC bez frameworku**, bez externích závislostí, bez Node.js buildu a **bez databáze**.
- Vlastní router v `web/index.php`, kontrolery v `app/Controllers/`, šablony v `app/Views/`, vykreslování přes `layout.php`.
- Data jsou zatím ukázková (mock) v kontrolerech; přihláška se ukládá jen do session.
- Escapuj výstup přes `e()`; formuláře chraň CSRF a honeypotem.
- Soukromí: veřejně jen startovní číslo, jméno, oddíl a kategorie.

## Spuštění a ověření

- Lokálně: Apache vhost `http://v30.test`, nebo z `v30/v30` příkaz `php -S 127.0.0.1:8000 index.php`.
- Syntaxe: `php -l <soubor>`.
- Projdi routy a ověř 200, 404 i 405; otestuj přihlášku (`POST /prihlaska`).

Podrobnosti k designu a požadavkům viz `designov_mantinely.md`, `SPECIFIKACE_MVP_V30.md` a `STRUKTURA_PROJEKTU_V30.md`.
