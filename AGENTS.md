# Pokyny pro AI: projekt Vodácká 30

## Účel
V tomto workspace se připravuje nový web a administrační systém pro běžkařský závod Vodácká 30 v Orlických horách. Cílem je produkční MVP použitelné na začátku prosince 2026 a připravené pro závodní ročník 2027. Návštěvník musí najít informace a bezpečně se přihlásit; pořadatel musí zvládnout spravovat ročník, registrace, obsah a výsledky bez zásahu vývojáře.

## Potvrzený rozsah
- První verze je pouze pro běžkařskou Vodáckou 30. Východočeský vodácký maraton není součástí webu, registrací ani administrace.
- Datový model přesto odděluje `Event`, `RaceEdition` a `Category`; kategorie, registrace, výsledky a oprávnění musí být vždy svázané se správnou událostí a ročníkem.
- Založit archivní ročníky a převést dostupné historické výsledky, propozice, aktuality, dokumenty, fotografie/externí galerie a další obsah. Rozsah jednotlivých let potvrdit inventářem a pořadatelem; chybějící či neověřený obsah nevydávat za kompletní ani oficiální.
- Přihlášky pro aktivní ročník, ruční evidence plateb a bankovní instrukce. QR platba, platební brána, automatické párování převodů, účty závodníků, živé výsledky a API časomíry jsou mimo MVP.
- Veřejné údaje o závodnících omezit na výslovně schválený seznam polí. E-mail, telefon, datum narození, poznámka a stav platby nesmí unikat do veřejných stránek, odpovědí, filtrů ani exportů. Politiku pro nezletilé musí schválit pořadatel.
- Historická osobní data nepřenášet do nové databáze ani stagingu bez ověření účelu, právního titulu, retence a přístupových práv. Ve vývoji používat fiktivní/anonymizovaná data.
- Stávající formulář `?menu=6` není zdrojem kategorií Vodácké 30. Aktuální kategorie, ceny, kapacity a termíny musí potvrdit pořadatelé.

## Zdroje pravdy
- [Plán projektu](PLAN_PROJEKTU_V30.md) popisuje cíle, stávající web, migraci, provoz a širší produktový kontext.
- [Specifikace MVP](SPECIFIKACE_MVP_V30.md) obsahuje požadované scénáře, bezpečnostní a akceptační kritéria. U platby platí potvrzené rozhodnutí: bankovní instrukce bez QR.
- [Struktura Laravel aplikace](STRUKTURA_PROJEKTU_V30.md) je vodítko pro modely, služby, requests, policies, fronty, soukromí a testy; držet konvence skutečně zvolené verze Laravelu.
- Veřejný web `https://vodacka30.cz/` je pouze zdroj pro obsahový inventář, ne důvěryhodný zdroj DB schématu, přístupových práv ani kompletního archivu. Při kontrole uváděl termín 16. 1. 2027 a obsah ročníku 2026; datum 2027 je nutné potvrdit pořadatelem.

## Povinná první technická brána
Než se připojí nebo změní jakákoli databáze:
1. Zjistit od uživatele/pořadatele skutečný hosting, databázový engine a verzi, umístění aplikace a oprávnění přístupového účtu. Nepředpokládat, že známá databáze je databází starého webu nebo že je kompatibilní s Laravel aplikací.
2. Přístupy k tajemstvím získávat mimo chat/model; nikdy nevypisovat, zapisovat do repozitáře ani ukládat hesla, API klíče nebo produkční `.env`.
3. Nejprve provést pouze read-only kontrolu. Před jakýmkoli zápisem vytvořit zálohu a ověřit obnovu ve stagingu.
4. Rozhodnout s uživatelem, zda se existující DB použije přímo, zda vznikne nové Laravel schéma, nebo se legacy DB použije pouze pro řízený export/migraci. Do DB zatím nepřidávat tabulky, seed data ani migrace.
5. Neprovádět produkční změny, DNS přepnutí ani hromadnou migraci bez schválení a rollback plánu.

## Realizační pořadí
1. Ověřit DB/hosting/repozitář/zálohy a zjistit lokální verze PHP, Composeru, Node.js a dostupné služby.
2. Souběžně vytvořit inventář všech dostupných ročníků a materiálů: zdroj URL/soubor, typ, úplnost, oprávnění, stav ověření, cílová stránka a rozhodnutí o převodu. Ověřit pilotní ročník proti PDF s pořadatelem před hromadným převodem.
3. Potvrdit ročník 2027: datum, oficiální kategorie a týmové složení, ceny, kapacity, uzávěrky, platební instrukce, pravidla duplicit, veřejná pole, právní texty a formát CSV.
4. Založit Laravel SSR aplikaci (Blade/Vite), CI, staging, testovací e-mail, frontu, logování, zálohy a rollback; verzi a DB vybrat podle hostingu. Následně vytvořit model a integritní testy pro Event/RaceEdition/Category a role scoped podle události/ročníku.
5. Veřejný web a CMS: homepage, stav/oznámení ročníku, propozice, trať, harmonogram, dokumenty, aktuality, archiv, galerie/externí alba, kontakty a partneři; administrace podporuje koncept, náhled a publikaci.
6. Migrovat historii po ověřeném pilotu. Výsledky a obsah validovat po ročnících, porovnávat výsledky s oficiálním PDF a ukládat report chybějících/odmítnutých podkladů.
7. Registrace a admin: serverová validace, transakční kapacita, ochrana proti duplicitnímu odeslání, verzované souhlasy, stavové přechody, audit, oprávnění, frontované potvrzovací e-maily s bezpečným retry, ruční potvrzení platby a veřejný allowlist startovní listiny.
8. Výsledky: bezpečný CSV upload, náhled a chyby, koncept, autorizovaná publikace, audit, HTML tabulka, filtr kategorií a oficiální PDF. Systém nevymýšlí pravidla pořadí.
9. Akceptace: testy registrace, plné/uzavřené kategorie, duplicit a souběhu, výpadku e-mailu, soukromí, rolí, CSV importu/publikace, cookies bez souhlasu a po odvolání, cache, přístupnosti, mobilu, starých URL, záloh a obnovy. Staging -> schválení pořadatelem -> produkce.

## Termín a řízení rozsahu
Cíl je začátek prosince 2026, přibližně 7–8 týdnů od 8. října. Je to agresivní termín; plný archiv médií a aktualit napříč ročníky může být samostatným rizikem. Co se nedá dohledat nebo ověřit, označit jako otevřenou položku a eskalovat pořadateli, ne tiše vynechat. Při skluzu chránit bezpečné spuštění aktuálního ročníku; rozsah archivu měnit pouze po dohodě s uživatelem.

## Technické a bezpečnostní zásady
- Laravel MVC, serverově vykreslené Blade stránky; JavaScript jen jako vylepšení interakcí. Nepřidávat React SPA.
- Krátké kontrolery, validace ve FormRequest, doménová logika ve službách, oprávnění přes policies, explicitní veřejné Resources/allowlisty.
- Kategoriemi, registracemi a výsledky nejde přejít mezi ročníky nebo událostmi. Kritické invarianty testovat i na úrovni DB, kde to zvolený engine dovolí.
- Veřejná cache pouze pro publikovaný obsah; nikdy necachovat osobní údaje, registrace nebo platby jako veřejná data. Cache invalidovat po publikaci/změně.
- E-maily přes sandbox při vývoji a transakčního poskytovatele v produkci, ve frontě; žádná produkční tajemství v Git.
- Uploady validovat podle MIME/velikosti, neveřejné exporty ukládat soukromě. Auditovat změny plateb, stavů, importů a publikací.
- Dodržovat přístupnost, mobilní použitelnost, české texty, SEO, souhlasy s cookies a blokování analytiky před souhlasem.

## Ověření hotové práce
Po každé změně spustit nejužší dostupnou kontrolu (test, lint, type/build). Kritické acceptance gates jsou v `SPECIFIKACE_MVP_V30.md`, zejména US-02 až US-08 a testovací brána v oddíle 9. Před produkcí musí být ověřené zálohy/obnova, staging deployment, souhlas pořadatelů a rollback. Neprovádět nesouvisející refaktoring a nikdy nepřepisovat uživatelská data bez zálohy a výslovného souhlasu.
