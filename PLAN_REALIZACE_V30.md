# Realizační plán: Vodácká 30

**Cíl:** produkční MVP na začátku prosince 2026, připravené pro závodní ročník 2027. Tento plán doplňuje [hlavní plán](PLAN_PROJEKTU_V30.md), [specifikaci MVP](SPECIFIKACE_MVP_V30.md) a [strukturu Laravel aplikace](STRUKTURA_PROJEKTU_V30.md).

## Potvrzená rozhodnutí

- První vydání je pouze běžkařská Vodácká 30. Východočeský vodácký maraton není součástí veřejného webu, registrací ani administrace.
- Archiv bude obsahovat ročníky Vodácké 30 a co nejúplnější ověřený obsah. Rozsah materiálů každého roku závisí na dostupnosti, právech a schválení pořadatelem; neověřená data se nesmějí vydávat za oficiální ani kompletní.
- MVP obsahuje registraci, ruční evidenci plateb a bankovní instrukce bez QR. Neobsahuje platební bránu, automatické párování, účty závodníků, živé výsledky ani integraci časomíry.
- Produkční datum závodu 2027, kategorie, ceny, kapacity, uzávěrky, platební instrukce a pravidla zveřejnění jsou otevřené pořadatelské vstupy. Veřejný web aktuálně uvádí 16. 1. 2027, datum musí potvrdit pořadatel.
- Zachovat v datovém modelu oddělení `Event`, `RaceEdition` a `Category`, ale nevytvářet funkce pro druhou událost v MVP.
- Laravel aplikace je umístěna v podsložce `application/`, dokumenty zůstávají v kořeni workspace. Lokální URL je zamýšlená `http://v30.test`.

## Stav k 12. 10. 2026

- V kořeni je trvalý [AGENTS.md](AGENTS.md) s pravidly pro další AI a bezpečnostními branami.
- V `application/` je Laravel Framework 13.35.0 vytvořený přes Laragon PHP 8.3.33; Composer závislosti jsou nainstalované a `artisan --version` úspěšně běží.
- Laravel Boost je přidán jako vývojová závislost a jeho guidelines/skills jsou vygenerované.
- Veřejná česká homepage je v `application/resources/views/public/home.blade.php`; route `/` má název `home` a čte Event, aktuální RaceEdition a historické Category z Eloquentu. Výsledky/fotogalerii odkazuje na původní web a nepotvrzený termín 2027 jasně označuje.
- Veřejná navigace má pojmenované stránky `/zavod`, `/propozice`, `/vysledky`, `/prihlasky` a `/kontakt` se sdíleným Blade layoutem. Propozice 2027 zůstávají výslovně nepotvrzené; výsledky 2026 odkazují na původní HTML/PDF. Kontaktní a výsledkové osobní údaje se zatím nekopírují do nové DB.
- Lokální `application/database/database.sqlite` je izolovaná SQLite 3.53.2, ignorovaná Gitem. Obsahuje jednu událost V30, archivní edici 2026 s 11 veřejně uvedenými kategoriemi a plánovanou edici 2027 s datem z původního webu označeným jako nepotvrzené; 2027 zatím nemá kategorie. Neseedují se účastníci, registrace ani uživatelské účty.
- PHPUnit suite prošel (12 testů, 36 assertions), Vite build je úspěšný. Mobilní kontrola na 390 px nezjistila horizontální přetékání; veřejné stránky i DB data byly ověřeny v browseru.
- `http://v30.test` je dostupné přes Laragon Apache vhost; Laravel dev server na `http://127.0.0.1:8000` zůstává alternativou.
- Lokální `application/.env` má APP_URL `http://v30.test`, vygenerovaný lokální APP_KEY, souborové sessions/cache a synchronní queue. `.env` je ignorovaný Git-em.
- Laravel migrace a seed byly spuštěny pouze explicitně nad novou lokální SQLite (`--database=sqlite`). Běžící Laragon MySQL 8.4.3 na portu 3306 ani jakákoli legacy/produkční DB nebyla otevřena ani změněna; jejich účel, přístupy, zálohy a hosting zůstávají neověřené.
- PHP ZIP modul je v Laragonu přítomný, ale vypnutý. Composer operace byly provedeny s dočasným `-d extension=zip`; globální `php.ini` se neměnil.
- Laragon Apache poslouchá na portech 80 a 443. Vhost i hosts mapování `v30.test` jsou nakonfigurované a ověřené.

## Realizační postup a milníky

### 1. Dokončit lokální přístup (`application/` -> `v30.test`)

1. V Laragonu nakonfigurovat Apache VirtualHost, jehož `DocumentRoot` je `C:/Users/onvin/Documents/WEB/v30/application/public` a `ServerName` `v30.test`.
2. Přidat `127.0.0.1 v30.test` do Windows hosts souboru pomocí Laragonu spuštěného s oprávněním správce nebo ručně uživatelem. Neobcházet UAC ani neupravovat systémový soubor bez elevace.
3. Znovu načíst Apache, ověřit DNS/hosts překlad a HTTP odpověď. Do té doby lze lokální aplikaci ověřovat přes `php artisan serve --host=127.0.0.1 --port=8000`.
4. Ověřit, že `application/.env` není verzovaný; žádné produkční tajemství do něj nevkládat.

### 2. Povinný read-only audit hostingu a databáze (blokuje databázové změny)

1. Zjistit od uživatele/pořadatele skutečný hosting, databázový engine/verzi, existenci a umístění DB, umístění staré aplikace a oprávnění účtu. Přihlašovací údaje získávat mimo chat/model.
2. Potvrdit, zda dostupná DB patří starému webu Vodácké 30, či jde o jinou/novou databázi. Prohlédnout pouze read-only: schéma, metadata, tabulky, objemy a vazby; nevypisovat osobní data.
3. Zajistit zálohu DB i souborů a ověřit její obnovu ve stagingu před každým zápisem. Rozhodnout s uživatelem mezi novou Laravel DB a řízeným exportem legacy DB; nepřipojovat aplikaci na neznámé schéma.
4. Do neidentifikované, legacy nebo produkční DB zatím nepřidávat tabulky, migrace ani seed data. Výslovně schválená lokální SQLite DB s fiktivními/necitlivými daty je povolená; nepřenášet do ní historická osobní data.

**Výstup:** schválená mapa prostředí, rozhodnutí o databázi, doložený backup/restore test a rollback plán.

### 3. Obsahový inventář a ročník 2027 (1. týden, souběžně s auditem)

1. Inventarizovat všechny dostupné ročníky a materiály: aktuality, propozice, výsledky, PDF, mapy, fotogalerie, externí alba, kontakty, partnery, staré URL. Pro každou položku zaznamenat zdroj, typ, ročník, úplnost, oprávnění, stav ověření, cílovou stránku a rozhodnutí.
2. Ověřit, zda jsou dostupné historické ročníky uváděné v návrhových dokumentech (například výsledky 2015–2026). Veřejný web zaručuje pouze část obsahu; seznam zkontrolovat s pořadatelem.
3. Získat písemné potvrzení termínu 2027, běžkařských kategorií a týmů, cen, kapacit, uzávěrek, bankovních instrukcí, pravidel duplicity, veřejných polí včetně nezletilých, souhlasů/retence a rolí schvalovatelů.
4. Získat oficiální CSV formát a pravidla výsledků. Formulář `?menu=6` nepoužívat jako zdroj kategorií běžkařského závodu.
5. U galerií ověřit práva a pravidla pro fotografie dětí. Historické osobní registrace nepřenášet bez ověření účelu, právního titulu, retence a práv přístupu.

**Výstup:** schválený datový balíček ročníku 2027, inventář a seznam otevřených položek.

### 4. Aplikační základ, CI a datový model (1.–2. týden)

1. Nastavit Laravel SSR (Blade/Vite), české locale, časové pásmo, quality checks a CI pro PHPUnit, migrace na čisté lokální/staging DB, statickou kontrolu a Vite build.
2. Připravit bezpečný staging, testovací e-mail, frontu, logování, zálohy a deploy/rollback. Produkční tajemství spravovat mimo Git.
3. Navrhnout modely `Event`, `RaceEdition`, `Category`, obsahové entity, `Registration`, `Participant`, `Payment`, `ConsentRecord`, `Result`, `Document` a auditní záznamy podle zvolené verze Laravelu.
4. Přidat cizí klíče a unikátní omezení. Testy musí prokázat, že kategorie/registrace/výsledek nemohou přejít do jiného ročníku nebo události.
5. Vývoj a staging používají pouze fiktivní/anonymizovaná data.

**Výstup:** opakovatelný build/deploy a testovaný model bez skutečných historických osobních údajů.

### 5. Veřejný web a CMS (2.–4. týden)

1. Responzivní česká homepage, stav ročníku, termín po schválení, připnuté oznámení a nejvýše dva kroky k hlavním akcím.
2. Stránky ročníku, strukturované propozice, trať/mapa, harmonogram, oficiální dokumenty, aktuality, archiv, galerie/externí alba, kontakt a partneři.
3. Admin pro ročníky, kategorie a obsah: koncept, náhled, publikace, připínání a ukončení oznámení.
4. Veřejná část smí číst jen publikovaný obsah. Cache obsahuje klíč ročníku/události a invaliduje se po změně.
5. Zachovat důležité staré adresy přes schválenou mapu přesměrování; neznámé adresy mají srozumitelnou 404.

**Výstup:** pořadatel upraví a zveřejní obsah bez změny zdrojového kódu.

### 6. Pilotní a dávková migrace archivu (3.–6. týden)

1. Nejdřív převést reprezentativní ročník s výsledky a oficiálním PDF.
2. Porovnat počty záznamů a vybrané řádky s PDF; pořadatel schválí pilot.
3. Teprve potom převádět další dostupné ročníky, propozice, aktuality, dokumenty a fotografie podle inventáře.
4. U každého roku zachovat zdroj a stav ověření. Neúplný obsah označit; reportovat materiály nepřevedené nebo bez oprávnění.

**Výstup:** archiv všech potvrzených dostupných let a report výjimek.

### 7. Registrace a provozní administrace (4.–6. týden)

1. FormRequest serverová validace, uzavření registrace, transakční kontrola kapacity/lock, ochrana proti opakovanému doručení a kontrola správného ročníku/kategorie.
2. Uložit registraci, účastníky, přesnou verzi souhlasu a platební referenci konzistentně. Provozní stav `paid` smí nastavit pouze oprávněný pořadatel; změny stavů mají historii a audit.
3. E-mailové potvrzení přes frontu se sandboxem, bezpečným retry a idempotencí. Registrace zůstane uložená při výpadku e-mailu.
4. Zobrazit bankovní instrukce bez QR. Variabilní symbol použít jen po schválení pravidla a unikátnosti.
5. Admin role/policies omezit podle události a ročníku. Veřejná startovní listina používá explicitní allowlist; žádný e-mail, telefon, datum narození, poznámka či platební stav v HTML/JSON/filtrech/exportu.
6. Politika zveřejnění nezletilých musí být schválena před otevřením registrace.

**Výstup:** end-to-end cesta od registrace po administraci a bezpečnou startovní listinu.

### 8. Výsledky a publikace (5.–6. týden)

1. Bezpečný CSV upload s limitem a kontrolou MIME/struktury, sloupců, ročníku, kategorie, startovních čísel a duplicit.
2. Náhled ukáže přijímané řádky a konkrétní chyby; vadná data se nezveřejní.
3. Výsledky nejdřív koncept; samostatná autorizovaná publikace a audit. Pořadí se nepřepočítává bez schválených pravidel.
4. Přístupná HTML tabulka, filtr kategorií a odkaz na oficiální PDF; invalidovat veřejnou cache.
5. Žádná ostrá časomíra/API, živé výsledky ani automatická platební integrace v MVP.

**Výstup:** ověřený pilot importu, opravy a publikace.

### 9. Akceptace a produkční spuštění (7.–8. týden, cíl 1.–4. prosince)

1. Testy pro registraci: validní/nevalidní údaje, uzavřená a plná kategorie, opakovaný požadavek, souběh a oddělení ročníků.
2. Testovat e-mailový výpadek, retry, ruční potvrzení platby, role, audit a ochranu neveřejných údajů.
3. Testovat CSV náhled, chyby, opravu, koncept, autorizovanou publikaci a invalidaci cache.
4. Ověřit analytické/marketingové skripty před souhlasem i po odvolání, včetně externích map/videí.
5. Projít mobil, desktop, klávesnici, formuláře a tabulky. Provést zátěžovou zkoušku podle pořadatelem schváleného počtu.
6. Nastavit SPF/DKIM/DMARC a monitoring. Prakticky obnovit databázi i soubory ze zálohy na stagingu.
7. Pořadatel schválí obsah, pilot archivu, pravidla osobních údajů, provozní kontakt a go-live checklist.
8. Před produkcí záloha starého webu, staging deploy, kontrola rollbacku a teprve potom schválené přepnutí DNS. Zaškolit správce a předat stručný návod.

## Kritická cesta, rizika a řízení rozsahu

- DB/hosting audit, archivní inventář a potvrzení ročníku 2027 mohou běžet souběžně.
- Schválení kategorie, cen, platebních instrukcí a veřejných polí blokuje registraci.
- Základ modelu blokuje CMS, migraci a registrace. Pilotní migrace blokuje hromadný import.
- Začátek prosince je agresivní termín. Kompletní fotografie a aktuality všech let mohou vyžadovat víc než 7–8 týdnů kvůli dostupnosti, právům a ručnímu ověření.
- Při skluzu chránit bezpečné spuštění aktuálního ročníku a registrací. Archiv zveřejňovat po ověřených dávkách a otevřené položky předat pořadateli; rozsah měnit pouze dohodou s uživatelem.
- Veřejná stránka potvrzuje pouze viditelný obsah. Nepotvrzuje DB, její vlastníka, hosting, schéma ani přístupové údaje.

## Akceptační brány

- Žádná neověřená vazba kategorie/registrace/výsledku mezi ročníky nebo událostmi.
- Souběžné registrace nepřekročí kapacitu; retry nevytváří duplicitu.
- Výpadek e-mailu neztratí přihlášku; QR se negeneruje a samotné instrukce nikdy neoznačí platbu jako zaplacenou.
- Veřejná HTML/JSON/export rozhraní neobsahují neveřejná osobní ani platební pole.
- Výsledky jsou veřejné jen po explicitní autorizované publikaci a odpovídají schválenému zdroji.
- Každý importovaný archivní ročník má evidovaný zdroj, stav ověření a pořadatelské schválení podle dohodnutého rozsahu.
- CI projde testy, migracemi na čisté DB, statickou kontrolou a Vite buildem.
- Staging nasazení/rollback i obnova DB a souborů ze zálohy jsou prakticky ověřené před produkcí.

## Lokální vývoj

Aplikace je v `application/`; zdrojové projektové dokumenty zůstávají v kořeni. Na tomto počítači jsou Laravel/Apache přes Laragon, PHP 8.3.33, Composer 2.10.2 a Node.js 22.23.2. PHP ZIP je nutné pro Composer operace dočasně aktivovat (`php -d extension=zip ...`) nebo povolit v Laragonu.

- Ověřit aplikaci: `php artisan --version`
- Spustit dočasně bez Apache vhostu: `php artisan serve --host=127.0.0.1 --port=8000`
- App URL v lokálním `.env`: `http://v30.test`
- `application/.env` obsahuje pouze nově vygenerovaný lokální klíč, ignoruje se v Gitu. Neobsahuje produkční přístup.
- `SESSION_DRIVER=file`, `CACHE_STORE=file`, `QUEUE_CONNECTION=sync`. Lokální DB je pouze `application/database/database.sqlite`; pro její inicializaci použít `php artisan migrate --database=sqlite` a `php artisan db:seed --database=sqlite --class=Vodacka30Seeder`. Skutečnou/legacy DB nepřipojovat před auditem a rozhodnutím o cílovém schématu.
- Pro plnohodnotné `http://v30.test` je nutný Laragon Apache VirtualHost s `DocumentRoot` do `application/public` a hosts mapping `127.0.0.1 v30.test`. Aktuální agentní shell není administrátor a systémový hosts soubor z něj nelze měnit. Přidání mapování je potřeba provést v Laragonu s oprávněním správce nebo uživatelem.
