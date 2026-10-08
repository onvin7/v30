# Plán projektu: nový web Vodácké 30

**Stav dokumentu:** návrh k upřesnění s pořadateli  
**Web, který byl podkladem:** [vodacka30.cz](https://vodacka30.cz/)  
**Prohlídka webu:** 7. října 2026

## 1. Záměr projektu

Cílem je nahradit současný web přehlednou, důvěryhodnou a snadno spravovatelnou aplikací v PHP s architekturou MVC. Hlavní akcí webu je běžkařský závod na 30 km v Orlických horách. Název Vodácká 30 vychází z vodáckých pořadatelů; neznamená, že hlavní závod je na lodích. Nový web má tuto identitu zachovat, působit současně, dobře fungovat na mobilu a pomoci návštěvníkům rychle najít to nejdůležitější: kdy se závod koná, kde se přihlásit, jaká platí pravidla, kudy vede trať a kde jsou výsledky.

Součástí nemá být jen nová úvodní stránka. Potřebujeme celé provozní zázemí: správu ročníků, propozic, registrací, startovní listiny, výsledků, aktualit, dokumentů, fotogalerií, kontaktů a partnerů. Pořadatel by měl běžné změny zvládnout přes administraci, bez úprav PHP nebo ručního přepisování HTML.

Hlavní zásada projektu: obsah a pravidla se budou vztahovat ke konkrétnímu ročníku závodu. Údaj o kategorii, termínu, ceně nebo trati se nebude kopírovat mezi stránkami ručně. Bude uložen na jednom místě a web ho zobrazí v příslušném kontextu.

## 2. Co je na současném webu

Prohlédnutý web obsahuje několik důležitých částí, které je nutné zachovat nebo nahradit lepším řešením:

- **Úvodní stránka a aktuality:** stručné organizační zprávy, oznámení o dalším ročníku a odkazy na registraci, startovní listinu či trasu. Na aktuální stránce je uveden příští termín 16. ledna 2027.
- **Propozice:** pořadatel, ředitel závodu, hlavní rozhodčí, místo startu a cíle, časový program, tratě, kategorie, startovné, ubytování, časový limit a bezpečnostní ustanovení. Prohlédnuté propozice jsou pro závod 17. ledna 2026 a popisují běh na lyžích v Orlických horách, dětské tratě 3 a 7 km a dospělý závod na 30 km.
- **Výsledky:** stránka s výběrem ročníků 2015–2026, rozdělením na kategorie a tabulkami. Tabulka uvádí pořadí, startovní číslo, závodníka, oddíl, čas a ztráty. K ročníku je dostupné také PDF ke stažení.
- **Přihlášky:** formulář pro výběr kategorie, oddíl, účastníky, ročník narození, e-mail a poznámku.
- **Fotogalerie:** archiv fotografií podle ročníku; novější fotografie jsou také odkazovány do externí galerie.
- **Kontakt:** organizační výbor, ředitel závodu, hlavní rozhodčí a kontaktní formulář.
- **Další obsah:** archiv aktualit, odkazy na mapu a ubytování, loga sponzorů a partnerské weby.

Současné stránky přenášejí část informací přes staré číselné parametry v adrese, například `menu=2`. Názvy hlavních položek nejsou v přístupném obsahu zřetelné a některé seznamy používají pevné HTML tabulky. Při otevření stránek prohlížeč také zaznamenal chyby starého JavaScriptu a chybějících prostředků. Při redesignu proto nemá smysl pouze překreslit současnou šablonu; je potřeba znovu uspořádat navigaci, obsah i jeho správu.

### Důležitý rozpor k vyřešení

Při kontrole webu jsem našel nesoulad mezi propozicemi a přihlašovacím formulářem:

- Propozice a výsledky popisují hlavní běžkařský závod v Orlických horách, včetně dospělé tratě na 30 km.
- Formulář na adrese `?menu=6` nabízí kategorie lodí C2, K1 a K2 na 60 nebo 12 km. Ty přesně odpovídají letnímu závodu **Východočeský vodácký maraton**, nikoli běžkařské Vodácké 30.
- Současný web tedy zřejmě v minulosti smíchal nebo přepsal přihlášky pro dvě různé události pořádané stejným klubem, TJ KVT Pardubice: zimní běžkařskou Vodáckou 30 a letní Východočeský vodácký maraton. Jejich kategorie, přihlášky, výsledky i ročníky musí být v novém systému oddělené.
- Úvodní stránka uvádí další běžkařský ročník v lednu 2027; přesné datum a aktuální propozice se potvrdí před zveřejněním.

Formulář pro běžkařskou Vodáckou 30 se musí sestavit z potvrzených běžkařských kategorií; stávající formulář `?menu=6` nelze bez kontroly převzít, protože jeho nabídka patří k Východočeskému vodáckému maratonu. Před migrací se ověří, zda pořadatelé chtějí na novém webu spravovat obě události a zda je potřeba převést historické přihlášky či výsledky maratonu.

## 3. Cíle a zásady

### Cíle

1. Návštěvník najde termín, místo, přihlášku a výsledky bez hledání v dlouhých aktualitách.
2. Pořadatel zvládne připravit nový ročník, upravit propozice a zveřejnit výsledky v administraci.
3. Výsledky budou použitelné i na telefonu, půjde v nich vyhledávat a stáhnout oficiální export.
4. Informace o účastnících a dětech budou zpracovávány bezpečně a nebudou omylem zveřejněny.
5. Archiv ročníků zůstane dohledatelný a půjde ho postupně doplnit i o starší výsledky a fotografie.
6. Web bude přístupný, rychlý a indexovatelný vyhledávači.

### Zásady návrhu

- Nejdříve mobilní použití: návštěvníci často hledají pokyny na cestě nebo přímo v místě závodu.
- Každá stránka má mít jednoznačný účel a viditelný nadpis.
- Úřední informace, výsledky a registrace mají přednost před dekorací.
- Ročník závodu je samostatný celek se svým datem, stavem, propozicemi a daty.
- Veřejná prezentace účastníků bude řízena výslovnou volbou a pravidly pořadatele.
- Funkce pro živé měření času nebudou slibovány, dokud pořadatel nepotvrdí měřicí systém a zdroj dat.

## 4. Návrh obsahu a navigace

Hlavní navigace by měla být krátká a srozumitelná:

1. **Úvod**
2. **O závodu**
3. **Propozice a trať**
4. **Přihlášky**
5. **Startovní listina**
6. **Výsledky a archiv**
7. **Galerie**
8. **Kontakt**

Na mobilu se navigace zjednoduší do přístupného menu; tlačítko **Přihlásit se** zůstane snadno dostupné. Do zápatí patří pořadatel, kontakt, ochrana osobních údajů, partneři a odkaz na archiv.

### Úvodní stránka

Úvodní stránka bude sloužit jako závodní rozcestník, ne jako dlouhý článek. V první části zobrazí název a fotografii skutečného závodu, aktuální ročník a jeho termín. Hned vedle nebo pod tím budou hlavní akce: přihláška, propozice, startovní listina a poslední výsledky.

Stránka má podle fáze ročníku automaticky zvýraznit vhodné informace:

- **Příprava:** datum a místo, otevření registrace, startovné, propozice.
- **Před závodem:** uzávěrka, důležité organizační zprávy, pokyny k dopravě a aktuální stav trati.
- **Den závodu:** časový program, startovní listina, změny, mapy a podle možností průběžné informace.
- **Po závodu:** výsledky, poděkování, galerie a termín příštího ročníku.

Pod hlavním přehledem budou poslední aktuality, krátký přehled místa a trati, hlavní partneři a odkaz do archivu. Nouzové či zásadní oznámení, například změna času nebo zrušení závodu, musí jít připnout a zobrazit výrazně na všech relevantních stránkách.

### Propozice, ročník a trať

Propozice budou strukturované a čitelné, ne pouze nahraný dokument. Jednotlivé oddíly: základní údaje, pořadatelé, program, kategorie, délky a styly tratí, podmínky účasti, startovné, přihlášení, platba, časový limit, bezpečnost, ubytování a kontakty. Návštěvník si zároveň stáhne oficiální PDF.

U trati bude mapa, stručný popis, délka a převýšení, kontrolní body, občerstvení, případně soubor GPX a externí mapový odkaz. Každá informace bude přiřazena ke konkrétnímu ročníku, protože vedení trati a podmínky se mohou měnit. Pořadatel bude moci zveřejnit stav trati či upozornění bez úpravy zdrojového kódu.

### Přihláška a startovní listina

Přihláška začne výběrem události, ročníku a kategorie. Formulář se přizpůsobí zvolené kategorii a bude validovat povinné údaje, e-mail a případné týmové složení. Po odeslání obdrží účastník potvrzení s rekapitulací, platebními instrukcemi a kontaktem pro opravy. Pořadatel dostane oznámení o nové přihlášce.

Administrace musí umožnit otevřít a uzavřít registraci, upravit kapacitu, kontrolovat platbu, opravit záznam, změnit kategorii a exportovat přihlášené. Stav přihlášky může být například **nová**, **čeká na platbu**, **potvrzená**, **stornovaná**. U změn se zaznamená kdo a kdy je provedl.

Pro platbu převodem systém vygeneruje unikátní variabilní symbol a QR kód ve formátu QR Platba s účtem pořadatele, částkou, měnou a variabilním symbolem. QR kód se zobrazí v potvrzené rekapitulaci a přiloží do e-mailu. Částka i účet musí vycházet z konkrétního ročníku a kategorie. QR kód pouze předvyplní příkaz k úhradě; sám nepotvrzuje, že peníze dorazily. Dokud nebude napojeno bankovní párování, stav úhrady mění oprávněný pořadatel ručně.

Veřejná startovní listina bude zobrazovat pouze povolené údaje, typicky jméno a příjmení, oddíl nebo tým, kategorii a startovní číslo. Datum narození, e-mail, telefon, poznámka ani platební stav se veřejně nezobrazí. U nezletilých je potřeba zvlášť ověřit právní titul a nastavení souhlasů. Účastník dostane jasnou informaci, jak se jeho údaje používají a jak může požádat o opravu.

### Výsledky a archiv

Výsledková stránka nabídne výběr ročníku a filtr kategorie. Tabulka zachová význam údajů známých ze současných výsledků: pořadí, startovní číslo, závodník, oddíl/tým, cílový čas a ztráty. Na mobilu bude možné tabulku číst bez rozbití rozvržení. Vyhledávání podle jména nebo oddílu a řazení podle dostupných sloupců zrychlí práci s delšími výsledky.

Každý ročník bude mít stránku s výsledky v HTML a odkaz na oficiální PDF. Doporučený je také export CSV pro další zpracování. Import výsledků z CSV bude mít náhled, validaci duplicit a chybějících časů a možnost opravy před zveřejněním. Výsledky se nejprve uloží jako koncept; publikace bude samostatný krok s potvrzením. Automatické výpočty pořadí se přidají jen tehdy, když pořadatel potvrdí pravidla pro shodné časy, nedokončení a diskvalifikace.

#### Napojení na časomíru přes API

Vedle ručního importu CSV se navrhne verzované REST API pro případnou budoucí čipovou časomíru. API bude přijímat pouze data pro výslovně určenou událost a ročník; nebude poskytovat přímý přístup k databázi. Dodavatel časomíry dostane samostatné přihlašovací údaje s omezeným oprávněním pouze pro zápis časů. Přenos poběží výhradně přes HTTPS, požadavky budou ověřované podepsaným tokenem nebo HMAC klíčem, omezené limitem a zaznamenané v auditním logu.

API musí ověřit formát, existenci ročníku, kategorii a startovní číslo. Opakované doručení stejného času nesmí vytvořit duplicitní výsledek; použije se idempotency klíč nebo jednoznačný identifikátor průchodu. Přijaté časy se nejprve označí jako průběžné/neověřené a zveřejní se pouze po výslovném nastavení pořadatelem. CSV import zůstane záložní cestou. Konkrétní datový kontrakt a způsob autentizace se dohodnou s dodavatelem časomíry před napojením.

Archiv bude filtrovatelný podle ročníku a typu obsahu. Záznam ročníku může obsahovat aktuality, propozice, výsledky, PDF, trasu, fotografie a případně startovní listinu. Odkazy ze starého webu se při migraci přesměrují na nové adresy, kde to bude technicky možné.

### Aktuality, fotografie, partneři a kontakt

Aktuality budou mít titulek, datum publikace, obsah, případný obrázek, kategorii a příznak důležitosti. Starší příspěvky se budou prohledávat v archivu. Editor bude umět připravit koncept, naplánovat publikaci a skrýt neaktuální sdělení.

Fotogalerie bude členěna podle ročníků a alb. U fotografií se doplní popisek a autor, bude-li znám. Fotografie mohou zůstat u externí galerie; web pak nabídne přehled alb s jasným odkazem. Pokud se budou nahrávat lokálně, bude potřeba stanovit pravidla pro souhlasy, děti, velikosti a dobu uchování.

Partneři budou spravovatelní v administraci včetně názvu, loga, odkazu a pořadí/zobrazené úrovně. Kontakty budou mít role a aktuální údaje pro daný ročník. Formulář „Napište nám“ musí chránit proti spamu, validovat vstup, bezpečně doručovat zprávy a zobrazit srozumitelný stav odeslání.

## 5. Administrace pořadatele

Administrace má být oddělena od veřejné části, například pod `/admin`, s přihlášením a oprávněními podle role. Doporučené role:

- **Administrátor:** uživatelé, konfigurace a všechna data.
- **Pořadatel/editor:** ročníky, propozice, aktuality, dokumenty, galerie a partneři.
- **Registrace:** přihlášky, platby a startovní listina bez přístupu k nepotřebným systémovým údajům.
- **Výsledky:** import a správa výsledků s možností konceptu a publikace.

Úvodní přehled administrace má ukázat nejbližší ročník, počet přihlášek, nepotvrzené platby, nové zprávy a stav publikace výsledků. Rizikové operace, jako hromadný import, smazání nebo zveřejnění výsledků, budou vyžadovat potvrzení a budou zaznamenány v auditním protokolu.

## 6. Technické řešení: PHP MVC

### Doporučený stack

- Aktuální podporovaná verze PHP dostupná na cílovém hostingu.
- Stabilní podporovaná verze frameworku Laravel nebo obdobného PHP MVC frameworku; přesnou verzi určit při zahájení podle podpory hostingu.
- MySQL/MariaDB nebo PostgreSQL podle prostředí správce hostingu.
- Redis jako sdílená cache a případně fronta úloh; Memcached je alternativou pro cache, pokud nebude potřeba Redis pro fronty.
- Blade šablony pro serverově vykreslené stránky; JavaScript jen pro vylepšení interakcí.
- Vite pro sestavení CSS a JavaScriptu, Composer pro PHP závislosti.
- Transakční e-maily přes specializovaného poskytovatele, například Mailgun, SendGrid nebo Resend; ukládání veřejných dokumentů a fotografií do řízeného úložiště.

Pro tento projekt doporučuji serverově vykreslenou aplikaci v Laravelu, ne samostatný frontend v Reactu. MVC zůstane přehledné, veřejné stránky budou rychlé a dobře indexovatelné a administrace získá hotové stavební prvky pro validaci, autentizaci, oprávnění, e-maily a databázové migrace.

### Provozní infrastruktura, cache a nasazení

Počítá se s VPS nebo managed cloud prostředím, nikoli s běžným levným sdíleným hostingem. Produkční a testovací prostředí musí poskytovat podporované PHP CLI, Composer, přístup k terminálu/SSH, databázi, HTTPS, plánované úlohy a možnost spouštět dlouhodobé workery pro fronty. Protože Vite vytváří produkční assety pomocí Node.js, sestaví se v CI pipeline nebo v odděleném build prostředí; server nemusí mít Node.js, pokud se na něj nasazují již sestavené assety. Hosting musí umožnit bezpečně spravovat tajné proměnné a obnovovat databázi i soubory ze záloh.

CI/CD pipeline při každé změně spustí automatické testy a statickou kontrolu, nainstaluje závislosti z lock souborů, sestaví Vite assety a připraví nasaditelný artefakt. Nasazení nejprve proběhne do stagingu; produkční vydání bude mít řízený krok, zálohu, bezpečné spuštění databázových migrací a popsaný postup návratu na předchozí verzi. Produkční tajné údaje se nikdy nevkládají do Git repozitáře. Pro kritické registrace se plánuje údržba a nasazení mimo špičku.

Redis cache sníží opakované čtení z databáze zejména u veřejných propozic, archivů, seznamů kategorií, aktualit a výsledků při vysoké návštěvnosti. Cache se bude invalidovat při změně obsahu nebo publikaci výsledků; případné TTL bude bezpečnostní pojistka, ne jediný způsob aktualizace. Aktuální registrace, neveřejné osobní údaje a stav platby se nesmí podávat ze zastaralé veřejné cache. Výsledky v den závodu mohou používat krátkou TTL nebo cílenou invalidaci podle možností časomíry. Před spuštěním proběhne zátěžový test simulující souběžné otevření registrace a nárazové čtení výsledků; cílová kapacita se stanoví z odhadu pořadatele.

### Doručování transakčních e-mailů

Potvrzení registrace, platební instrukce, změny stavu a případná upozornění se budou odesílat přes transakční e-mailovou službu (například Mailgun, SendGrid nebo Resend), ne přes nespolehlivý PHP mail na sdíleném serveru. Odesílání poběží přes frontu, aby pomalá služba nezdržovala registraci; neúspěšné zprávy se bezpečně opakují a evidují bez zbytečného ukládání obsahu osobních údajů. Příjemce se nesmí kvůli opakovanému pokusu dozvědět potvrzení vícekrát.

Pro doménu se nastaví a ověří SPF, DKIM a DMARC. Před ostrým provozem se otestuje doručení do hlavních e-mailových služeb, zpracování nedoručitelných adres a přehled chyb. API klíče poskytovatele budou pouze v tajné konfiguraci prostředí.

### Rozdělení odpovědností

- **Modely** reprezentují ročníky, kategorie, registrace, závodníky, výsledky, aktuality a další uložená data.
- **Kontrolery** přijímají HTTP požadavky a volají potřebné aplikační služby; nemají obsahovat složitá pravidla závodu.
- **Request objekty** validují a normalizují vstupy formulářů.
- **Služby/doménová logika** zpracovávají import výsledků, změny stavu registrace a publikaci.
- **Integrace** oddělují externí služby od doménové logiky: transakční e-mail, QR platby a případné API časomíry.
- **Policies a middleware** vynucují oprávnění a přístup do administrace.
- **Cache a fronty** obsluhují opakovaně čtený veřejný obsah a pomalé úlohy, například e-maily a importy; změny cache invalidují podle událostí.
- **Blade šablony** zobrazují veřejné stránky a formuláře; komponenty sdílejí navigaci, upozornění, tabulky a formulářová pole.
- **Databázové migrace a seedery** udržují strukturu databáze a bezpečná vývojová data.

Příklady veřejných adres: `/`, `/akce/vodacka-30/rocnik/2027`, `/akce/vodacka-30/rocnik/2027/propozice`, `/akce/vodacka-30/rocnik/2027/trat`, `/akce/vodacka-30/rocnik/2027/prihlaska`, `/akce/vodacka-30/rocnik/2027/startovni-listina`, `/akce/vodacka-30/vysledky/2026`, `/akce/vodacka-30/galerie/2026` a `/aktuality`. Pokud pořadatelé zahrnou také Východočeský vodácký maraton, dostane vlastní identifikátor a oddělené URL, například `/akce/vychodocesky-vodacky-maraton/rocnik/2027`. Konkrétní adresy se doladí s ohledem na zachování starých odkazů a SEO.

### Základní datový model

- **Event (událost):** nadřazená akce a její identita, například **Vodácká 30** nebo **Východočeský vodácký maraton**; obsahuje název, slug, popis, výchozí kontakty a stav publikace.
- **RaceEdition (ročník):** patří právě k jednomu `Event` a obsahuje rok/ročník, datum, místo, stav, registrační termíny a veřejné oznámení.
- **Category (kategorie):** patří ke konkrétnímu ročníku a obsahuje název, popis, trať, pravidla, kapacitu, cenu a pořadí.
- **Participant (účastník):** osobní údaje potřebné pro závod a neveřejné údaje oddělené od veřejného profilu.
- **Registration (přihláška):** odkazuje na ročník i kategorii stejné události; obsahuje stav, datum vytvoření, platbu, souhlasy, unikátní variabilní symbol a referenční identifikátor.
- **RegistrationParticipant:** vazba přihlášky na jednoho či více účastníků nebo členů posádky.
- **Result (výsledek):** ročník a kategorie stejné události, závodník/tým, startovní číslo, pořadí, čas, zdroj/import, ztráty a stav výsledku.
- **ConsentRecord:** přihláška, osoba udělující souhlas, účel, verze a přesné znění podmínek platné při odeslání, časové razítko v UTC a případně technický záznam potřebný k doložení udělení.
- **CookieConsent:** volby kategorií cookies, verze textu/banneru, čas a záznam o změně či odvolání souhlasu.
- **Document:** propozice, výsledkové PDF, mapy a ostatní soubory navázané na ročník.
- **NewsArticle, GalleryAlbum, GalleryImage, Sponsor, Contact, User, AuditLog:** obsah webu, partneři, uživatelé a evidence změn.

Každá kategorie, přihláška a výsledek musí být přiřazeny ročníku, jehož `Event` odpovídá jejich události. Databázové vazby, unikátní omezení a validace služby musí zabránit tomu, aby se kategorie Východočeského vodáckého maratonu připojila k přihlášce Vodácké 30 nebo aby API časomíry zapsalo čas do jiného závodu. Identifikace účastníka ve výsledcích se bude řešit opatrně: historický výsledek musí zůstat zachován i tehdy, když se účastník později přihlásí pod jiným oddílem nebo změní kontaktní údaje.

### API pro externí časomíru

Veřejná část aplikace zůstane primárně serverově vykreslená. Vedle ní bude samostatná verzovaná API vrstva, například `/api/v1/events/{event}/editions/{edition}/timings`, pro systém časomíry. API kontroler předá ověřený požadavek aplikační službě; ta ověří oprávnění, rozsah události/ročníku, datový kontrakt a idempotenci, zapíše auditní stopu a případné další zpracování předá do fronty. API klíč bude možné jednotlivě zneplatnit a rotovat. Tato integrace nesmí obcházet běžný proces kontroly a publikace výsledků.

## 7. Bezpečnost, soukromí a dostupnost

Registrace zpracovává osobní údaje a může obsahovat data dětí. Už při návrhu je třeba určit, které údaje jsou nezbytné, kdo k nim má přístup, jak dlouho se uchovávají a které se zveřejňují. Veřejné výsledky a startovní listina nesmí omylem odhalit e-mail, telefon, poznámky, datum narození ani platební údaje.

U každého potřebného souhlasu se neuloží pouze příznak „souhlasím“. Záznam musí obsahovat konkrétní účel, verzi a přesné znění podmínek zobrazené uživateli v okamžiku odeslání přihlášky, časové razítko v UTC a vazbu na registraci a osobu, která souhlas udělila (u dítěte zákonný zástupce, pokud je to relevantní). Znění musí být po udělení neměnné; změna podmínek vytvoří novou verzi. Souhlasy se nesmí slučovat s potvrzením seznámení nebo s jiným právním titulem. Konkrétní právní základ, dobu uchování a podobu souhlasů ověří pořadatel s odborníkem na ochranu osobních údajů.

#### Cookies a analytika

Web bude mít správu souhlasů s cookies s jasnými kategoriemi: nezbytné, analytické a případně marketingové. Nezbytné cookies fungují bez souhlasu pouze v nutném rozsahu. Analytické skripty, například Google Analytics, se před aktivním souhlasem vůbec nenačtou ani nesmějí ukládat analytické identifikátory. Volby uživatele se zaznamenají včetně verze textu a času, uživatel je může kdykoli změnit nebo odvolat přes odkaz „Nastavení cookies“ v zápatí. Odvolání musí zastavit další měření a předat odpovídající signál již načteným integracím. Před spuštěním se ověří také cookies a externí skripty vložené mapami, videi či jinými nástroji.

Technické minimum: HTTPS, ochrana CSRF, serverová validace, escapování výstupu, bezpečná správa hesel, omezení pokusů o přihlášení, ochrana formulářů proti spamu, role a oprávnění, bezpečné ukládání uploadů, aktualizace závislostí, pravidelné zálohy databáze i souborů a ověřený postup obnovy. Produkční přístup nebude sdílený účet. Administrátoři by měli používat silné unikátní heslo a pokud to zvolený způsob nasazení umožní, vícefaktorové ověření.

Web bude používat sémantické HTML, správné popisky formulářů, ovládání klávesnicí, čitelný kontrast a stavové zprávy přístupné čtečkám. Výsledkové tabulky dostanou popsané sloupce a na úzké obrazovce použitelný způsob prohlížení. Cílem je splnit WCAG 2.2 AA v rozsahu odpovídajícím veřejnému webu.

## 8. Obsahová migrace a zachování adres

Před migrací vytvoříme inventář všech současných stránek, souborů, výsledkových PDF, ročníků, aktualit, fotogalerií, sponzorů a externích odkazů. Každá položka dostane rozhodnutí: převést, archivovat, nahradit aktuálním údajem, nebo vyřadit po odsouhlasení pořadatelem.

Výsledky a oficiální PDF se budou porovnávat proti původním dokumentům. Automatický import z neznámé struktury nebo prosté kopírování formuláře se nepovažuje za ověření správnosti. Nejprve se vytvoří malý zkušební import jednoho ročníku, pořadatel ho zkontroluje a teprve potom se převedou další ročníky.

Staré adresy jako `index.php?menu=2`, `index.php?menu=3` a `?menu=6` se namapují na odpovídající nové stránky, například přes přesměrování. Zachovají se odkazy na dokumenty tam, kde to hosting dovolí. Po spuštění se zkontrolují nefunkční odkazy, indexace a chybové stránky 404.

## 9. Postup realizace

### Fáze 1: Ověření zadání a dat

Potvrdit termín a oficiální kategorie běžkařské Vodácké 30, kdo spravuje web, kde vznikají výsledky a jak se evidují přihlášky a platby. Samostatně potvrdit, zda má nový web zahrnovat také Východočeský vodácký maraton a zda je potřeba zachovat jeho historické registrace a výsledky. Projít stávající přihlášky a výsledková PDF; kategorie maratonu nepřenášet do běžkařské registrace. Výstupem bude potvrzený seznam událostí, ročníků a dat k migraci.

### Fáze 2: Obsah, struktura a návrh

Schválit navigaci, seznam stránek, podobu úvodní stránky, registraci a výsledkovou tabulku. Připravit mobilní a desktopové návrhy hlavních stavů: před registrací, registrace otevřená, registrace uzavřená, den závodu a zveřejněné výsledky. Vybrat fotografie, logo, barvy a písmo podle skutečné identity závodu.

### Fáze 3: Základ aplikace

Založit PHP MVC projekt, vývojové/stagingové a produkční prostředí na VPS nebo managed cloudu, databázi, Redis cache/frontu, šablonu, navigaci, přihlášení do administrace, role, logování, zálohování a chybové stránky. Zavést CI pipeline pro testy a build Vite assetů, zabezpečené nasazení a tajné údaje mimo repozitář. Ověřit dostupnost PHP CLI, Composeru, plánovače úloh a workerů.

### Fáze 4: Ročníky a veřejný obsah

Implementovat správu ročníků, propozic, trati, dokumentů, aktualit, kontaktů, sponzorů a fotogalerií. Zprovoznit úvodní stránku a archiv. Pořadatel ověří první kompletní ročník na neveřejném testovacím prostředí.

### Fáze 5: Přihlášky a provozní administrace

Implementovat formulář podle potvrzených kategorií, záznam verzovaných souhlasů, transakční e-mailová potvrzení, unikátní variabilní symbol, generování a zobrazení QR Platby, stavy přihlášek, platební evidenci, export a startovní listinu s oddělenými veřejnými a neveřejnými poli. Otestovat doručitelnost e-mailů, QR kód v rekapitulaci i zprávě a přihlášky jednotlivců i týmů, pokud je závod používá. Ověřit celý proces včetně opravy chybné registrace.

### Fáze 6: Výsledky a historický archiv

Implementovat import výsledků přes CSV, kontrolní náhled, opravy, publikaci, filtrování a export. Připravit verzovaný a zabezpečený API kontrakt pro časomíru a otestovat jej se simulovanými požadavky; ostré napojení provést až po výběru dodavatele. Převést vybraný pilotní ročník, ověřit shodu s PDF a po schválení doplnit další ročníky.

### Fáze 7: Testy a spuštění

Otestovat mobilní a desktopové zobrazení, registraci, QR Platbu, zaznamenávání souhlasů, blokování analytických skriptů před souhlasem, oprávnění, API časomíry, výsledky, migrace, cache invalidaci, zátěž, zálohy a obnovu. Zkontrolovat SEO metadata, přesměrování starých URL, doručitelnost e-mailů a chování při chybě. Spuštění načasovat mimo kritickou dobu registrací; před přepnutím DNS provést úplnou zálohu starého webu a databáze.

## 10. Ověření kvality a kritéria dokončení

Projekt je připraven ke spuštění, když:

- pořadatel schválil závodní identitu, ročník, datum, kategorie a znění propozic;
- hlavní úkoly fungují na telefonu i počítači a navigace má srozumitelné názvy;
- přihlášku lze bezpečně odeslat, potvrdit e-mailem, najít v administraci a exportovat;
- QR Platba obsahuje správný účet, částku a unikátní variabilní symbol a nezobrazuje úhradu jako potvrzenou bez ověření platby;
- databáze dokládá přesné znění, verzi a čas udělení každého souhlasu;
- analytické a marketingové skripty se nespouštějí před aktivním souhlasem a volbu lze později odvolat;
- veřejná startovní listina neukazuje neveřejné osobní údaje;
- výsledky odpovídají schválenému zdroji, PDF lze stáhnout a tabulku lze filtrovat;
- registrace při zátěžovém testu zvládne schválený počet souběžných návštěvníků a veřejná cache se po změně obsahu či výsledků správně obnoví;
- testovací API přijímá pouze ověřené, správně scopeované a idempotentní časy; neautorizovaný zápis je odmítnut;
- aplikaci lze nasadit pipeline na staging i produkci a obnovit ze zálohy;
- transakční e-maily mají ověřené SPF/DKIM/DMARC a výsledek doručení je provozně dohledatelný;
- běžný editor upraví aktualitu a připraví další ročník bez zásahu vývojáře;
- formuláře, oprávnění, importy, přístupnost, chybové stránky, zálohy a obnova byly ověřeny;
- důležité staré odkazy se přesměrují nebo mají jasnou náhradní stránku;
- existuje návod pro pořadatele a konkrétní osoba odpovědná za správu webu.

## 11. Odhad a závislosti

Pro jednoho vývojáře je rozumný počáteční rámec přibližně **6–10 týdnů práce**, pokud pořadatel dodá včas potvrzená data, texty a vizuální podklady. Jde o orientační odhad: největší nejistota je skutečný rozsah závodu, kvalita historických výsledků, složitost přihlášek a případná návaznost na externí časomíru nebo platby. VPS/managed cloud, cache, CI/CD, transakční e-mailová služba a správa souhlasů přidávají náklady na nastavení a průběžný provoz. Ostré napojení čipové časomíry, online bankovní párování, více závodů či rozsáhlá obnova archivu mohou rozsah navýšit.

Pro první spuštění doporučuji držet rozsah při zemi: kvalitní prezentace jednoho potvrzeného ročníku, přihlášky, správa startovní listiny, výsledky, dokumenty a základní administrace. Online platby a živé měření času přidat až po ověření potřeby, poskytovatele a provozních nákladů.

## 12. Otázky k rozhodnutí s pořadateli

1. Jaké jsou oficiální běžkařské kategorie, tratě a podmínky přihlášení pro příští ročník?
2. Chcete na novém webu v rámci oddělených událostí spravovat i váš letní Východočeský vodácký maraton, nebo ten má/bude mít svůj vlastní web?
3. Jsou ve formuláři `?menu=6` nějaké platné registrace či historické výsledky Východočeského vodáckého maratonu, které je potřeba zachovat?
4. Je termín dalšího běžkařského ročníku opravdu 16. ledna 2027 a kdo ho bude potvrzovat či měnit?
5. Kdo dodá finální propozice, ceník, mapu, fotografie, loga a podklady pro výsledky?
6. Jak se dnes potvrzují platby a kdo smí přihlášky opravovat?
7. Mají být jména účastníků a výsledky veřejné? Jaká pravidla platí pro děti?
8. Kdo zadává výsledky a v jakém formátu je získává od časomíry?
9. Zůstane galerie u externí služby, nebo se budou fotografie nahrávat na nový web?
10. Kdo bude web dlouhodobě spravovat a na jakém hostingu bude aplikace provozována?

## Shrnutí

Nový web má spojit moderní prezentaci s praktickým nástrojem pro organizaci Vodácké 30 a případně také Východočeského vodáckého maratonu. Každá událost bude mít vlastní ročníky, kategorie, přihlášky a výsledky. Stávající formulář `?menu=6` odpovídá letnímu maratonu, proto se jeho lodní kategorie nepřevezmou do běžkařské přihlášky. Dalším krokem je potvrdit, zda bude maraton součástí nového webu, nebo bude mít vlastní stránky.