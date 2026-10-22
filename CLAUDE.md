# CLAUDE.md

Pokyny pro tento repozitář jsou v **[AGENTS.md](AGENTS.md)** – přečti je jako první.

## Dokumentace (zdroje pravdy)

- [README.md](README.md) – co projekt je, co je hotové, jak ho spustit.
- [PROJEKT.md](PROJEKT.md) – zadání, obsah stránek, data závodu, co zbývá.
- [TECHNICKA_DOKUMENTACE.md](TECHNICKA_DOKUMENTACE.md) – struktura, routy, ukládání dat, bezpečnost, konvence, ověření.
- [DESIGN.md](DESIGN.md) – vizuální pravidla a komponenty.

## Nezbytné minimum

- Vlastní aplikace je v podsložce **`v30/v30`**; kořen repozitáře drží jen dokumentaci.
- **Čisté PHP MVC bez frameworku** – bez závislostí, bez Node.js buildu, bez databáze. Data se ukládají do JSON souborů ve `storage/` přes repozitáře v `app/Models/`, parametry závodu drží `config/zavod.php` (čte se přes `zavod()`).
- Větve `feature/administrace` a `feature/ucty-oddilu` **nejsou sloučené** do `main`, takže zóny `/admin` a `/oddil` na `main` neexistují.
- Escapuj výstup přes `e()`, formuláře chraň CSRF tokenem a honeypotem, osobní údaje nikdy neukládej do `config/` ani do Gitu.
- Po změně chování aktualizuj příslušný dokument ze seznamu výše.
