# Vodácká 30 – rozhodnutí a aktuální stav

Tento dokument shrnuje dosavadní rozhodnutí k produktu, stav práce a doporučený další směr.

## Produktový směr

Vodácká 30 je jednorázový komunitní běžkařský závod, který se koná jednou ročně. Web má být jednoduchý, rychlý na mobilu a zaměřený na:

- informace k aktuálnímu ročníku,
- přihlášení bez zbytečného tření,
- startovní listinu a výsledky,
- viditelné partnery,
- praktické informace v den závodu.

Web nemá stavět dlouhodobý uživatelský portál, účty oddílů ani redakční CMS.

## Přihlášky

### Aktuální stav

Přihláška jednotlivce funguje end-to-end na veřejné adrese `/prihlaska`:

- formulář bez přihlášení,
- serverová validace,
- CSRF ochrana,
- honeypot proti spamu,
- kontrola povolených kategorií,
- uložení přes `RegistrationRepository` do `storage/prihlasky.json`,
- přidělení identifikátoru `V30-2027-XXXX`,
- přidělení variabilního symbolu,
- rekapitulace na `/prihlaska/potvrzeni`,
- platební instrukce podle konfigurace závodu.

Údaje se ukládají přes repozitář, ne přímo ze šablony nebo kontroleru do JSON.

### Rozhodnutí: žádné účty

Účty závodníků ani oddílů se pro tento web nezavádějí.

Důvody:

- závodník web používá typicky jen jednou ročně,
- účet znamená hesla, obnovu hesel, přihlašování a další podporu,
- účet nepřináší dostatečnou hodnotu oproti jednoduchému formuláři,
- produktový směr je „guest checkout“ bez účtu,
- hromadná přihláška může fungovat přes jeden veřejný formulář.

Větve `feature/ucty-oddilu` a `feature/administrace` zůstávají jako nerealizovaný návrh. Do `main` se neslučují.

### Doporučený cílový stav přihlášky

Na stejné adrese `/prihlaska` má být:

- jeden kontakt,
- možnost přidat jednoho nebo více závodníků,
- kategorie u každého závodníka,
- součet startovného,
- jeden variabilní symbol,
- jedna rekapitulace,
- později případně jeden QR kód.

Tím se pokryje jednotlivec i skupina bez účtů a bez samostatné zóny oddílu.

### Co u přihlášek ještě chybí

- hromadná přihláška více závodníků pod jedním kontaktem,
- skutečný součet částek z více kategorií,
- zapojení rate limiteru,
- bezpečnostní logování,
- potvrzovací e-mail,
- číslo účtu od pořadatele,
- případný QR kód,
- odstranění starého odkazu `/oddil/registrace`.

Dokud není známé číslo účtu, potvrzení správně upozorňuje, aby návštěvník neposílal peníze naslepo.

## Hledání závodníků

Jako náhrada za účty dává smysl veřejné hledání historických účastí:

- hledání podle jména nebo oddílu,
- filtr podle ročníku nebo všechny ročníky,
- zobrazení pouze veřejných údajů,
- u výsledků pořadí, čas a další už zveřejněné závodní údaje.

Veřejně se nemají zobrazovat:

- e-mail,
- telefon,
- rok narození,
- poznámka,
- stav platby,
- jiné neveřejné údaje.

Je potřeba počítat s ochranou proti hromadnému stahování a s citlivostí údajů dětí. Hledání má vycházet pouze z oficiálně zveřejněných startovních listin a výsledků.

## Stav projektu podle živého plánu

V `v30/PLAN.md` je aktuálně:

- 41 hotových úkolů,
- 13 otevřených úkolů,
- 0 právě rozpracovaných úkolů.

To odpovídá přibližně 76 % evidovaných úkolů. Hlavní veřejné funkce už jsou z velké části hotové.

### Hotové nebo doplněné v pracovní kopii

- veřejné stránky závodu,
- responzivní zimní design,
- 404 a 405 včetně hlavičky `Allow`,
- data závodu z `config/zavod.php`,
- SEO a strukturovaná data,
- lokálně uložené fotografie,
- novinky z konfigurace,
- krizový pruh z konfigurace,
- partneři nad patičkou,
- filtrování startovní listiny v prohlížeči,
- GPX, Mapy.cz a profil trati,
- validace a rekapitulace přihlášky jednotlivce.

### Hlavní otevřené úkoly

1. Hromadná přihláška bez účtu.
2. Mobilní rychlé akce Startovka / Trasa / Registrace.
3. Sjednocení breakpointů hlavičky.
4. Zapojení rate limiteru a bezpečnostního logu.
5. Potvrzená data ročníku 2027.
6. Číslo účtu a PDF propozic.
7. Případné potvrzení e-mailem.
8. Veřejné hledání historických závodníků.

Lokální Tailwind místo CDN je technický úkol s nižší prioritou než přihlášky a data od pořadatele.

## Větve, které se nemají slučovat

Do `main` se nemají slučovat:

- `feature/ucty-oddilu`,
- `feature/administrace`.

Důvodem je produktové rozhodnutí, ne technická nedokončenost. Login oddílu a CMS by zbytečně zvětšily systém a zavedly jiný způsob práce, než odpovídá závodu jednou ročně.

## Desktop Commander na Ubuntu Serveru

Desktop Commander je MCP server pro práci s autorizovaným počítačem přes:

- soubory a složky,
- vyhledávání,
- shell příkazy,
- běžící procesy,
- výstup procesů,
- PDF a některé další formáty.

Na Ubuntu Serveru v NUC může běžet bez grafického prostředí. Praktické možnosti instalace jsou:

- Node.js a `npx`,
- Docker,
- vzdálený MCP launcher.

Pro server je vhodné:

- běžet pod běžným uživatelem, ne pod rootem,
- omezit přístup jen na potřebné složky,
- připojovat se přes SSH nebo VPN,
- nevystavovat MCP službu přímo do internetu,
- preferovat Docker, pokud je důležitá izolace.

Desktop Commander není vzdálená plocha. Umožňuje práci se soubory, příkazy a procesy, ne plnohodnotné klikání v grafickém prostředí.

## Pracovní kopie a před commitem

Pracovní strom obsahuje změny, které ještě nejsou čistě commitnuté. Před commitem je potřeba:

- projít změny proti aktuálnímu zadání,
- ověřit PHP syntax,
- projít routy a chybové stavy,
- otestovat platný i neplatný POST přihlášky,
- zkontrolovat obsah `storage/`,
- odstranit testovací data,
- odstranit nebo vyřešit nechtěné soubory jako `nul` a screenshot,
- zkontrolovat nové partnerské assety.

## Doporučené pořadí další práce

1. Rozhodnout a implementovat hromadnou přihlášku bez účtu.
2. Odstranit odkaz na `/oddil/registrace`.
3. Zapojit rate limiter a bezpečnostní log.
4. Přidat jednoduché hledání ve veřejných startovních listinách a výsledcích.
5. Doplnit mobilní akce a sjednotit hlavičku.
6. Teprve potom řešit e-mail, QR, lokální Tailwind a další rozšíření.

