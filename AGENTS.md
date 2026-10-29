# Pokyny pro AI agenty: Vodácká 30

Repozitář obsahuje dokumentaci a web běžkařského závodu Vodácká 30 v Orlických horách. Než začneš pracovat, přečti si [README.md](README.md) a [PROJEKT.md](PROJEKT.md); technické detaily jsou v [TECHNICKA_DOKUMENTACE.md](TECHNICKA_DOKUMENTACE.md) a vzhled v [DESIGN.md](DESIGN.md).

**Tyto čtyři dokumenty jsou zdroje pravdy.** Neopisuj z nich obsah sem, nezakládej další dokumenty a neopravuj v nich jen formulace – měň je, jen když se změní skutečný stav projektu. Živý plán práce je v [`v30/PLAN.md`](v30/PLAN.md).

## Směr produktu

Web slouží **jednorázovému komunitnímu závodu** (akce 1× ročně). Cíl je mobilní UX, přihláška bez tření a viditelní partneři – ne další přihlašovací zóny.

- **Přihláška bez účtu.** Trenér ani závodník si nemá pamatovat heslo jednou za rok. Aktuální veřejný tok je jednoduchá individuální přihláška na `/prihlaska`; účty ani hromadná přihláška nejsou součástí `main`.
- **Účty oddílů a CMS neslučuj do `main`.** Větve `feature/ucty-oddilu` a `feature/administrace` existují jako nerealizovaný návrh; produktová cesta je hostující přihláška a aktuality v `config/zavod.php`, ne login `/oddil` ani redakční CRUD na čtyři novinky za sezónu.
- **Partneři jsou obsah ročníku.** Tři úrovně (hlavní / dodavatelé cen / instituce) v `config/zavod.php`, veřejné logo-partnerské bloky a kontextové zmínky u cen a servisu.
- **Operativa dne závodu** jde přes podmíněný pruh v `layout.php` řízený configem (`upozorneni`), ne přes nový redakční systém.

## Kde pracovat

- **Aplikace je v podsložce `v30/v30`** a tam patří veškeré úpravy kódu. Kořen repozitáře obsahuje jen dokumentaci (Markdown); nemaž zde soubory ani nepřidávej nové, pokud to zadání výslovně nevyžaduje.
- Na `main` běží jen veřejný web a přihláška jednotlivce. Soubory pro `/oddil` a `/admin` tam nejsou. Kdyby se feature větve slučovaly, v `web/index.php` vznikne konflikt v mapě rout – **to ale není další krok**; viz Směr produktu.
- Hotové a neaktuální plány jsou v `v30/v30/.trae/documents/archive/`. Neber je jako zdroj pravdy.

## Co nedělat

- **Nepřidávej závislosti** – žádný Composer, Node.js build, framework ani databázi. Web je čisté PHP MVC s JSON persistencí ve `storage/`.
- **Nepiš data mimo repozitář.** Živá data jdou jen přes repozitář v `app/Models/`, nikdy přímo ze šablony nebo kontroleru.
- **Neopisuj parametry závodu.** Kategorie, ceny, termín a kontakty se berou z `config/zavod.php` přes `zavod()`.
- **Nedávej osobní údaje do `config/` ani do Gitu.** Soubory ve `storage/` (JSON, logy, zámky) zůstávají v `.gitignore`.
- **Nezveřejňuj nic nad rámec** startovního čísla, jména, oddílu a kategorie. E-mail, telefon, ročník narození, poznámka ani stav platby se nesmí objevit ve výstupu, filtru ani exportu.
- **Neměň metodu ani pořadí rout bez důvodu.** Chybějící routa pro existující cestu vrací 405, ne 404.
- **Nepřidávej vlastní CSS třídy** ani nové abstrakce pro jednorázové použití. Drž vzory z [DESIGN.md](DESIGN.md).
- **Nestav login oddílu, soupisku za heslem ani blogový CMS.** Hromadnou přihlášku řeš na veřejném formuláři; novinky, partnery a krizový pruh drž v `config/zavod.php`.

## Než řekneš hotovo

1. `php -l` na každý upravený PHP soubor.
2. Projdi dotčené routy (200) a ověř 404 a 405.
3. U přihlášky zkus platný i neplatný `POST /prihlaska` a zkontroluj, že se uložilo jen to, co má.
4. Ukliď po sobě `storage/` – JSON soubory, logy i zámky z testů nemají v repu co dělat. (Rate limiter se na `main` zatím nevolá, takže `rate_limit.json` při přihlášce nevzniká; až se zapojí, blokuje po 5 neúspěšných pokusech a hlídej počty.)
5. Když se změní chování (routy, ukládání dat, vzhled), **aktualizuj odpovídající dokument ve stejném commitu**.

Podrobný postup ověření je v [TECHNICKA_DOKUMENTACE.md](TECHNICKA_DOKUMENTACE.md#11-jak-změny-ověřit), spuštění lokálně v [README.md](README.md#jak-to-spustit).
