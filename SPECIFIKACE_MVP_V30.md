# Specifikace MVP: web Vodácké 30

**Verze:** pracovní návrh  
**Stav:** k potvrzení pořadateli  
**Rozsah:** první produkční verze webu a administračního systému

Tento dokument převádí [hlavní plán projektu](PLAN_PROJEKTU_V30.md) a [návrh struktury Laravel aplikace](STRUKTURA_PROJEKTU_V30.md) na konkrétní rozsah první verze, chování důležitých procesů a ověřitelná kritéria dokončení.

## 1. Účel MVP

První verze má umožnit pořadatelům připravit a provozovat další ročník běžkařského závodu Vodácká 30, bezpečně přijímat registrace, zveřejnit startovní listinu a výsledky a spravovat základní obsah webu bez zásahu vývojáře.

MVP je dokončené, když návštěvník zvládne hlavní úkoly z telefonu i počítače a pořadatel dokáže závodní ročník spravovat v administraci, aniž by musel upravovat databázi nebo zdrojový kód.

## 2. Rozsah událostí

### Výchozí rozsah

MVP se soustředí na **běžkařskou Vodáckou 30 v Orlických horách**. Ve formuláři starého webu `?menu=6` jsou kategorie C2, K1 a K2, které patří k letnímu **Východočeskému vodáckému maratonu**; do běžkařské registrace se nepřevezmou.

Datový model od začátku odděluje `Event` a `RaceEdition`. Produkční MVP může obsahovat pouze událost Vodácká 30. Pokud pořadatelé potvrdí, že chtějí na novém webu spravovat také Východočeský vodácký maraton, musí se do rozsahu výslovně přidat samostatná událost, ročníky, kategorie, formulář, výsledky a obsah. Žádná data maratonu se nesmí zobrazit ani přijmout v kontextu Vodácké 30.

### Rozhodnutí před implementací

- Potvrdit datum dalšího ročníku; stávající web uvádí 16. ledna 2027.
- Potvrdit oficiální kategorie, délky a styly tratí, kapacity, ceny a termíny přihlášek.
- Rozhodnout, zda bude Východočeský vodácký maraton součástí stejné instalace, nebo bude mít vlastní web.
- Potvrdit majitele účtu pro platby, bankovní spojení a pravidla pro variabilní symbol.
- Potvrdit, jaké údaje se smějí zveřejnit u dospělých a nezletilých účastníků.
- Určit, kdo schvaluje propozice, registrace, import a zveřejnění výsledků.

Neověřená data ze starého formuláře se nepovažují za pravdivý zdroj kategorií Vodácké 30.

## 3. Uživatelé a oprávnění

### Návštěvník

Může číst veřejné stránky, propozice, trať, aktuality, veřejnou startovní listinu a publikované výsledky. Může odeslat registraci a kontaktní zprávu. Nemá přístup k administračním údajům, platbám ani soukromým datům ostatních účastníků.

### Pořadatel / editor

Spravuje konkrétní ročník, propozice, tratě, aktuality, dokumenty, fotografie, partnery a důležitá oznámení. Může připravit obsah jako koncept a zveřejnit jej podle přidělené role.

### Správce registrací

Vyhledává a opravuje přihlášky, mění jejich stavy, eviduje ručně ověřené platby a exportuje potřebné seznamy. Vidí pouze osobní údaje nutné pro tuto práci.

### Správce výsledků

Importuje soubory, kontroluje náhled, opravuje chyby a připravuje výsledky ke zveřejnění. Zveřejnění může být omezeno na pořadatele nebo administrátora.

### Administrátor

Spravuje uživatele, nastavení a oprávnění. Oprávnění se kontrolují nejen podle role, ale také podle události a ročníku. Přístup k Vodácké 30 automaticky neznamená přístup k Východočeskému vodáckému maratonu.

## 4. Funkce MVP

### Must have: nutné pro první spuštění

- Responzivní veřejný web v češtině s přístupnou navigací a jasným tlačítkem k přihlášce.
- Úvodní stránka se stavem aktuálního ročníku, datem, důležitými odkazy a připnutým provozním oznámením.
- Strukturované propozice, informace o trati a mapě, místo startu/cíle, harmonogram, kontakt a možnost stáhnout oficiální dokument.
- Správa ročníků, kategorií, propozic, aktualit, dokumentů a partnerů v administraci.
- Online přihláška podle schválených běžkařských kategorií, s validací a potvrzovacím e-mailem.
- Jedinečný variabilní symbol a QR Platba, pokud pořadatel potvrdí platbu převodem a dodá účet i pravidla ceny.
- Evidování platebního stavu pořadatelem; bez bankovní integrace se platba nepovažuje automaticky za přijatou.
- Veřejná startovní listina s pouze schválenými veřejnými údaji.
- Import výsledků z CSV s kontrolním náhledem, validací a samostatným potvrzením zveřejnění.
- Výsledky jako přístupná HTML tabulka, filtrování kategorií a odkaz na oficiální PDF.
- Archiv výsledků, propozic a aktualit alespoň pro ročníky, ke kterým jsou dostupné ověřené podklady.
- Bezpečné přihlášení do administrace, oprávnění, záznam důležitých změn a ochrana osobních údajů.
- Transakční e-mail přes specializovaného poskytovatele, základní monitoring, zálohy a řízené nasazení.
- Správa cookies, která nespustí analytické či marketingové skripty před příslušným souhlasem.
- Přesměrování důležitých starých adres a základní SEO metadata.

### Should have: přidat, pokud se vejde do rozpočtu a termínu

- Vyhledávání ve výsledcích podle jména a oddílu.
- Přehled alb a externích fotogalerií přiřazených k ročníku.
- Export přihlášek do CSV s omezením podle role.
- Plánované publikování aktualit a základní náhled stránky před zveřejněním.
- Automatické upozornění pořadateli na nové přihlášky a nedoručené e-maily.
- Základní zátěžový test špičky při otevření registrace a zveřejnění výsledků.

### Later: mimo rozsah prvního spuštění

- Přímé napojení na čipovou časomíru a živé průběžné výsledky.
- Automatické párování bankovních plateb nebo platební brána.
- Kompletní obnova všech historických fotografií a každé aktuality, pokud podklady nejsou dostupné nebo ověřitelné.
- Účty závodníků, samoobslužné změny registrace a online storna.
- Vícejazyčná verze, mobilní aplikace, push notifikace a personalizovaná analytika.
- Správa Východočeského vodáckého maratonu, pokud nebude výslovně schválena jako součást prvního vydání.

## 5. Uživatelské scénáře a akceptační kritéria

### US-01: Najít informace o závodě

**Jako návštěvník** chci rychle zjistit termín, místo, trať, program a podmínky, abych se mohl rozhodnout, zda se přihlásím.

**Kritéria přijetí:**

- Úvodní stránka viditelně uvádí aktivní ročník nebo sdělení, že termín zatím není zveřejněn.
- Z hlavní stránky vedou nejvýše dva kroky k propozicím, přihlášce, startovní listině a výsledkům.
- Propozice a oficiální PDF se vztahují ke stejnému ročníku.
- Pokud je zveřejněna změna času, trati nebo místa, zobrazí se jako důležité oznámení.
- Stránka je použitelná na mobilu a její odkazy a formuláře jsou ovladatelné klávesnicí.

### US-02: Odeslat přihlášku

**Jako závodník** chci vyplnit přihlášku do správné kategorie a dostat potvrzení, abych věděl, že pořadatel registraci přijal.

**Kritéria přijetí:**

- Formulář nabídne pouze kategorie patřící k vybrané Vodácké 30 a jejímu ročníku.
- Při uzavřené registraci, neexistující kategorii nebo vyčerpané kapacitě se přihláška odmítne s vysvětlením.
- Povinná pole, e-mail, souhlasy a případné složení týmu se ověří na serveru.
- Uložení přihlášky, účastníků, souhlasů a platební reference proběhne konzistentně v databázové transakci.
- Opakované kliknutí nebo opakované doručení téhož požadavku nevytvoří nechtěnou duplicitu.
- Po úspěchu se zobrazí rekapitulace s identifikátorem přihlášky a platebními instrukcemi.
- E-mailové potvrzení se odešle přes frontu. Dočasný výpadek e-mailové služby neztratí uloženou přihlášku; neúspěšné odeslání se zaznamená a bezpečně opakuje.

### US-03: Zaplatit převodem

**Jako přihlášený závodník** chci dostat přesné údaje k platbě, abych mohl zaplatit a pořadatel platbu snadno dohledal.

**Kritéria přijetí:**

- Variabilní symbol je jedinečný v dohodnutém rozsahu a je uložený u přihlášky.
- Částka, měna, účet a variabilní symbol v QR Platbě odpovídají rekapitulaci a potvrzovacímu e-mailu.
- QR kód odpovídá specifikaci QR Platba a je možné jej načíst běžnou bankovní aplikací.
- Samotné vytvoření QR kódu ani odeslání e-mailu nemění stav platby na zaplaceno.
- Oprávněný pořadatel může platbu označit jako ověřenou a systém zaznamená kdo a kdy změnu provedl.

### US-04: Spravovat registrace

**Jako správce registrací** chci najít a opravit záznam účastníka a evidovat přijatou platbu, abych mohl připravit správnou startovní listinu.

**Kritéria přijetí:**

- Vyhledávání v administraci je dostupné jen přihlášené a oprávněné roli.
- Správce může upravit povolená pole, kategorii nebo platební stav; každá důležitá změna se zapíše do auditního logu.
- Veřejná stránka ani export neukazuje e-mail, telefon, poznámku, datum narození nebo platební stav.
- Oprávnění jsou omezená na událost a ročník, ke kterým má uživatel přístup.

### US-05: Zobrazit startovní listinu

**Jako účastník** chci zkontrolovat, že jsem zařazen do správné kategorie, ale nechci zveřejnit své soukromé kontaktní údaje.

**Kritéria přijetí:**

- Zobrazí se jen pořadatelem schválená pole, například jméno, oddíl/tým, kategorie a startovní číslo.
- Neveřejné údaje nelze získat ani úpravou URL, filtrem, exportem nebo JSON odpovědí.
- Nezletilé osoby se zobrazují podle schválené politiky pořadatele a právního posouzení.
- Výsledek lze na mobilu číst bez horizontálního rozbití rozvržení.

### US-06: Importovat a zveřejnit výsledky

**Jako správce výsledků** chci nahrát CSV, opravit chyby před importem a výsledky publikovat až po kontrole.

**Kritéria přijetí:**

- Systém ověří formát, velikost souboru, požadované sloupce, ročník, kategorii, startovní čísla a duplicitní řádky.
- Náhled zobrazí počet přijatelných řádků a konkrétní chyby; chybná data se automaticky nezveřejní.
- Import patří pouze do právě zvolené události a ročníku.
- Výsledky mají nejprve stav koncept; publikace je samostatná autorizovaná akce.
- Publikace aktualizuje HTML výsledky a odkaz na oficiální PDF a invaliduje příslušnou cache.
- Import a publikace jsou zaznamenány v auditním logu.

### US-07: Spravovat obsah ročníku

**Jako pořadatel** chci upravit aktuality, dokumenty a propozice, abych mohl web provozovat bez vývojáře.

**Kritéria přijetí:**

- Editor může obsah uložit jako koncept, zobrazit náhled a zveřejnit jej.
- Obsah se vždy vztahuje ke správné události a případně konkrétnímu ročníku.
- Důležité oznámení lze připnout a po skončení jeho platnosti odebrat.
- Veřejná cache se po změně publikovaného obsahu obnoví.

### US-08: Spravovat analytické cookies

**Jako návštěvník** chci si zvolit analytické cookies a později volbu změnit.

**Kritéria přijetí:**

- Nezbytné cookies jsou oddělené od analytických a marketingových kategorií.
- Analytické/marketingové skripty nejsou načtené ani aktivní před udělením příslušného souhlasu.
- Souhlas lze odmítnout, přijmout nebo nastavit po kategoriích; odmítnutí není skryté ani podmíněné.
- Volbu lze později změnit přes odkaz na nastavení cookies.
- Události a verze souhlasu se zaznamenají podle zvolené privacy architektury; právní texty před spuštěním schválí pořadatel.

## 6. Základní provozní pravidla

### Stavy registrace

Výchozí sada stavů:

- `awaiting_payment` – přihláška uložena, čeká se na ověření platby;
- `paid` – pořadatel platbu ověřil;
- `cancelled` – přihláška byla zrušena;
- `needs_review` – záznam vyžaduje ruční kontrolu, například neúplná platba.

Povolené přechody se implementují centrálně v doménové službě, ne volným přepsáním textového stavu v administraci. Historie změn stavu obsahuje uživatele, čas a důvod. Pořadatel může názvy zobrazované účastníkům změnit, interní klíče zůstávají stabilní.

### Kapacita a duplicity

Kontrola kapacity musí být bezpečná i při současném odeslání více registrací. Zápis rezervace a kontrola volného místa proběhnou transakčně, případně s uzamčením daného záznamu kategorie. Pravidlo pro opakovanou registraci stejného člověka se stanoví podle oficiálních propozic; systém nemá automaticky zakázat legitimní opakované přihlášení bez potvrzeného pravidla.

### Výsledky

MVP výsledky importuje a zobrazuje, ale nevymýšlí pravidla pořadí. Pořadatel předá závazný formát CSV a pravidla pro shodné časy, nedokončení, diskvalifikaci a případné opravy. Oficiální pořadí zůstává odpovědností pořadatele/časomíry. API časomíry je mimo MVP; databázový model může obsahovat zdroj výsledku pro budoucí integraci.

## 7. Migrace ze starého webu

Před migrací vznikne tabulka přesměrování a inventář obsahu. U každé položky se zaznamená původní URL, typ akce, ročník, cílová URL, stav ověření a rozhodnutí (převést, zachovat jako externí odkaz, archivovat nebo vyřadit).

- Propozice a výsledky běžkařské Vodácké 30 se párují s ročníkem Vodácké 30.
- Formulář `?menu=6` se neimportuje jako běžkařská registrace. Jeho kategorie C2/K1/K2 se označí jako podklady k Východočeskému vodáckému maratonu.
- Přihlášky maratonu se převedou pouze tehdy, pokud pořadatelé potvrdí, že jsou platné, dostupné a že maraton bude součástí nového systému.
- Výsledkové PDF se porovnají s převedenou HTML tabulkou; pořadatel schválí pilotní ročník před hromadnou migrací.
- Staré URL se přesměrují podle schválené mapy; neznámé nebo neplatné adresy mají srozumitelnou 404 stránku.

Migrace osobních údajů musí respektovat účel, právní titul, přístupová práva a dobu uchování. Přenos do testovacího prostředí používá anonymizovaná nebo syntetická data.

## 8. Nefunkční požadavky

- **Výkon:** veřejné propozice, aktuality a výsledky lze cachovat; neveřejná registrace a platební údaje se z veřejné cache nikdy neservírují. Před spuštěním se provede zátěžový test s pořadatelem schváleným počtem souběžných požadavků.
- **Dostupnost:** hosting je VPS nebo managed cloud s podporovaným PHP, Composerem, SSH/terminálem, databází, Redisem, HTTPS a queue workery. Běžný levný sdílený hosting není cílové prostředí.
- **E-maily:** transakční poskytovatel, ověřené SPF/DKIM/DMARC, fronta a bezpečné opakování při dočasném výpadku.
- **Bezpečnost:** HTTPS, CSRF ochrana, serverová validace, rate limiting, policies, bezpečné uploady, ochrana tajemství a audit kritických změn.
- **Soukromí:** přesná verze textu souhlasu a časové razítko u registrace; veřejná rozhraní vracejí pouze výslovně povolená pole.
- **Cookies:** bez aktivního souhlasu se nenačítá analytika ani marketingové skripty; souhlas jde odvolat.
- **Přístupnost:** ovládání klávesnicí, popsané formulářové prvky, čitelný kontrast, sémantické nadpisy a tabulky.
- **Zálohy:** zálohovat databázi i soubory a před spuštěním prakticky ověřit obnovu.
- **Nasazení:** automatizované testy a build přes CI; nejdřív staging, pak schválené produkční vydání s rollback postupem.

## 9. Testovací a akceptační brána

MVP se předá pořadatelům k akceptaci až po splnění následujících bodů:

1. Pořadatelé schválili oficiální ročník, datum, kategorie, ceny, platební účet, texty a zásady zveřejnění osobních údajů.
2. Test registrace pokrývá validní i nevalidní data, uzavřenou registraci, plnou kapacitu a opakované odeslání.
3. Kategorii Východočeského vodáckého maratonu nelze zvolit při registraci do Vodácké 30; test opačného směru se provede, pokud je maraton součástí instalace.
4. Variabilní symbol je jedinečný a QR platba odpovídá rekapitulaci. Žádná registrace se neoznačí jako zaplacená jen na základě vygenerování QR.
5. Potvrzovací e-mail projde sandboxovým testem a při simulovaném výpadku poskytovatele zůstane přihláška uložená.
6. Veřejná startovní listina a výsledky neodhalí neveřejné údaje ani při přímém požadavku na export/API.
7. CSV import lze zkontrolovat, chybné řádky odmítnout, opravit a publikovat pouze oprávněným uživatelem.
8. Analytický skript je blokovaný před souhlasem a po odvolání volby se další měření zastaví.
9. Staré ověřené adresy a dokumenty fungují nebo přesměrovávají na odpovídající nový obsah.
10. Web prošel testem na mobilu, klávesnicí, zátěžovým testem a obnovou zálohy na stagingu.
11. Pořadatelé mají přístupy, krátký návod ke správě ročníku a určenou osobu pro provozní podporu.

## 10. Doporučené pořadí implementace

### První 4 kroky k zahájení projektu

1. **Potvrdit zadání a rozsah událostí**
   - potvrdit, které události patří do systému: Vodácká 30 a případně Východočeský vodácký maraton;
   - definovat ročník, datum, kategorie, ceny, platební účet a povinná veřejná pole;
   - odsouhlasit, jak se mají řešit právní texty a souhlasy s GDPR;
   - výstup: schválený dokument s rozsahem, daty a kategoriemi pro první aktivní ročník.

2. **Založit Laravel a provozní infrastrukturu**
   - vytvořit Laravel aplikaci v aktuálně podporované verzi;
   - nastavit `.env`, lokální databázi, Redis, queue worker a e-mail do sandboxu;
   - připravit CI/CD, staging prostředí, logování, zálohy a rollback plán;
   - výstup: funkční vývojové i stagingové prostředí bez produkčních tajemství.

3. **Navrhnout datový model a oddělení událostí**
   - vytvořit modely `Event`, `RaceEdition`, `Category`, `Registration`, `Participant`, `Payment`, `Result` a role uživatelů;
   - zajistit, aby kategorie a registrace byly vždy vázané na konkrétní událost, nikoli na společné globální pole;
   - přidat unikátní omezení a relace, které zabrání chybám typu „kategorie maratonu v přihlášce Vodácké 30“;
   - výstup: schéma s testy datové integrity a seedery pro fiktivní ročníky obou událostí.

4. **Zprovoznit veřejný web a základ administrace**
   - vytvořit úvodní stránku, detail ročníku, propozice, trať, aktuality a archiv;
   - připravit přihlášení pořadatelů a základní admin rozhraní pro správu ročníků a kategorií;
   - ověřit přístupnost, responzivitu a správné zobrazení na mobilech;
   - výstup: první funkční veřejný web a administrace, připravená pro rozšíření registrace a výsledků.

Následující kroky pak pokračují po této “základně” v pořadí:

5. **Postavit administraci:** přihlášení, event-scoped policies, správa ročníků, kategorií a obsahu.
6. **Dodat registraci:** validace, kapacita, záznam souhlasů, variabilní symbol, QR platba, queue e-mail a správa stavů.
7. **Dodat startovní listinu:** pouze schválená veřejná pole a testy proti úniku údajů.
8. **Dodat výsledky:** import CSV s náhledem, ruční kontrola, publikace, filtrování a PDF.
9. **Migrovat pilotní data:** jeden potvrzený ročník, přesměrování, kontrola s pořadateli.
10. **Akceptovat a spustit:** testy, zátěž, přístupnost, obnova záloh, návod a produkční nasazení mimo špičku.

## 11. Mimo MVP výslovně

MVP nezahrnuje živé výsledky ani napojení na čipovou časomíru, automatické párování bankovních převodů, platební kartu, mobilní aplikaci ani automatický výpočet oficiálního pořadí podle neodsouhlasených pravidel. Tyto funkce mají být samostatně odhadnuty až po ověření konkrétního dodavatele, datového formátu, rozpočtu a provozních odpovědností.

Zahrnutí Východočeského vodáckého maratonu je rozhodnutí o rozsahu, nikoli technická nutnost pro MVP. Pokud ho pořadatelé chtějí spravovat pod jedním webem, lze znovu použít stejnou platformu a administraci, ale každá událost bude mít samostatné kategorie, registrace, výsledky, URL i oprávnění.
