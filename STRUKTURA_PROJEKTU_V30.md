# Rozvržení projektu a struktura složek (Laravel)

Tento dokument doplňuje [hlavní plán projektu](PLAN_PROJEKTU_V30.md). Popisuje, jak uspořádat zdrojový kód nového webu Vodácké 30 v Laravelu, jak pojmenovávat jeho části a jak rozdělit veřejný web, administraci a případné napojení na časomíru.

Aplikace může v budoucnu spravovat více událostí pořádaných TJ KVT Pardubice. Datově proto rozlišuje `Event` a jeho jednotlivé `RaceEdition`. Vodácká 30 (běžkařský závod) a Východočeský vodácký maraton (letní lodní závod) jsou samostatné události. Přihlášky, kategorie a výsledky se vždy vážou ke správné události a ročníku; jejich formuláře ani data se nesmějí míchat.

## Zásady struktury

- Zachovat standardní strukturu Laravelu. Vlastní složky přidávat jen pro skutečně oddělené odpovědnosti.
- Kontrolery zpracují HTTP požadavek a vrátí odpověď. Pravidla registrace, plateb a výsledků patří do aplikačních služeb.
- Vstupy validovat ve `FormRequest`; ověřit oprávnění pomocí policies a middleware.
- Každá veřejná a administrátorská operace musí respektovat rozsah `Event` a `RaceEdition`.
- Veřejné čtení publikovaného obsahu oddělit od neveřejných přihlášek, kontaktních údajů a plateb.
- Externí systémy (časomíra, transakční e-mail) integrovat přes služby a rozhraní, ne přímými voláními z kontrolerů.
- Používat podporovanou verzi Laravelu a její konvence. Konkrétní verze se vybere podle data zahájení a možností cílového hostingu.

## Doporučený strom projektu

Níže je zamýšlená struktura relevantních částí aplikace. Laravel při založení vytvoří i další standardní soubory, které není potřeba přesouvat.

```text
v30/
|-- app/
|   |-- Enums/
|   |-- Events/
|   |-- Http/
|   |   |-- Controllers/
|   |   |   |-- Admin/
|   |   |   |-- Api/V1/
|   |   |   `-- PublicSite/
|   |   |-- Middleware/
|   |   |-- Requests/
|   |   |   |-- Admin/
|   |   |   |-- Api/V1/
|   |   |   `-- PublicSite/
|   |   `-- Resources/Api/V1/
|   |-- Jobs/
|   |-- Listeners/
|   |-- Mail/
|   |-- Models/
|   |-- Policies/
|   |-- Services/
|   |-- Support/
|   `-- View/Components/
|-- bootstrap/
|-- config/
|-- database/
|   |-- factories/
|   |-- migrations/
|   `-- seeders/
|-- docs/
|-- public/
|-- resources/
|   |-- css/
|   |-- js/
|   `-- views/
|       |-- admin/
|       |-- components/
|       |-- emails/
|       |-- layouts/
|       `-- public/
|-- routes/
|-- storage/
|-- tests/
|   |-- Feature/
|   |-- Performance/
|   `-- Unit/
|-- .env.example
|-- composer.json
|-- package.json
`-- vite.config.js
```

Konkrétní názvy Laravel bootstrap souborů a způsob registrace tras se mohou mezi hlavními verzemi frameworku lišit. Strom je cílová organizace aplikace, ne pokyn kopírovat konfiguraci z jiné verze.

## Aplikační vrstva (`app/`)

### 1. Modely (`app/Models/`)

Modely jsou Eloquent reprezentace uložených dat. Definují relace, povolené casty, jednoduché lokální scopes a případně malé invarianty. Nemají samy odesílat e-maily, importovat soubory ani obsahovat dlouhé procesy.

- `Event.php` – dlouhodobá identita události, například Vodácká 30 nebo Východočeský vodácký maraton.
- `RaceEdition.php` – konkrétní ročník jedné události, jeho termín, místo, stav a nastavení registrace.
- `Category.php` – kategorie v konkrétním ročníku včetně tratě, kapacity, ceny a pravidel.
- `Participant.php` – fyzická osoba a její osobní údaje; neveřejná data nesmí být automaticky serializována do veřejných odpovědí.
- `Registration.php` – přihláška účastníka nebo týmu do jedné kategorie konkrétního ročníku.
- `RegistrationParticipant.php` – spojovací model pro přihlášku s jedním nebo více účastníky, pokud formát závodu vyžaduje tým.
- `Result.php` – výsledek závodníka/týmu v jedné kategorii a ročníku včetně času, pořadí a stavu.
- `TimingPassage.php` – přijatý průchod nebo čas z externí časomíry, pokud bude tento zdroj používán.
- `ConsentRecord.php` – neměnný záznam konkrétního souhlasu včetně účelu, verze, přesného textu a času.
- `CookieConsent.php` – záznam volby cookies, pouze pokud se rozhodne ukládat volby i serverově; jinak může být volba vedena v prohlížeči.
- `Payment.php` – platební reference a stav platby. Neukládá údaje platební karty; při běžném převodu eviduje variabilní symbol, částku, měnu a stav ověření.
- `NewsArticle.php`, `Document.php`, `GalleryAlbum.php`, `GalleryImage.php`, `Sponsor.php`, `Contact.php` – spravovaný obsah webu.
- `User.php`, `AuditLog.php` – uživatelské účty, role a důležité změny provedené v administraci.

Preferované relace: `Event hasMany RaceEdition`; `RaceEdition belongsTo Event` a `hasMany Category`; `Category belongsTo RaceEdition`; `Registration belongsTo RaceEdition` a `Category`; `Registration belongsToMany Participant` přes spojovací tabulku; `Result belongsTo RaceEdition` a `Category`.

**Datový invariant:** kategorie přihlášky i výsledku musí patřit ke stejnému ročníku, ke kterému patří samotná přihláška/výsledek. Tím se například zabrání přihlášení do kategorie Východočeského vodáckého maratonu přes ročník Vodácké 30. Kontrolu provede služba a databáze; tam, kde to zvolený databázový engine dovolí, také složený cizí klíč nebo odpovídající unikátní omezení.

### 2. HTTP vrstva (`app/Http/`)

HTTP vrstva převádí požadavky na aplikační operace a jejich výsledek na HTML nebo JSON. Nemá obsahovat pravidla pro přidělování variabilních symbolů, výpočet výsledků ani správu souhlasů.

#### Kontrolery (`app/Http/Controllers/`)

Kontrolery seskupujeme podle publika a rozhraní:

- `PublicSite/`
  - `HomeController` – úvodní stránka a aktuální oznámení.
  - `EventController` – přehled událostí a detail konkrétní události.
  - `RaceEditionController` – ročník, propozice a trať.
  - `RegistrationController` – formulář, vytvoření přihlášky a bezpečná rekapitulace.
  - `ResultController` – publikované výsledky a archiv.
  - `GalleryController`, `NewsController`, `ContactController` – galerie, aktuality a kontaktní formulář.
- `Admin/`
  - `EventController`, `RaceEditionController`, `CategoryController` – správa oddělených událostí, ročníků a kategorií.
  - `RegistrationController`, `ParticipantController` – správa přihlášek a účastníků podle oprávnění.
  - `ResultImportController` – nahrání souboru a náhled výsledků před importem.
  - `ContentController`, `SponsorController`, `DocumentController` – spravovaný obsah.
- `Api/V1/`
  - `TimingApiController` – ověřený příjem dat od časomíry přes verzované REST API.

Kontroler by měl být krátký: přijme validovaný `FormRequest`, autorizuje operaci, předá práci službě a zvolí odpověď. Veřejné endpointy nesmějí vracet celý Eloquent model s neveřejnými údaji; pro API se používají explicitní Resources.

#### Validace (`app/Http/Requests/`)

Každý formulář nebo endpoint má vlastní `FormRequest`, ve kterém jsou vstupní pravidla a autorizace požadavku.

- `PublicSite/StoreRegistrationRequest.php` – údaje závodníků, událost, ročník, kategorie a potvrzené volby souhlasu.
- `Admin/UpdateRaceEditionRequest.php` – změna ročníku, termínu, stavu nebo nastavení registrace.
- `Admin/ImportResultsRequest.php` – povolený typ a velikost CSV, ročník a režim importu.
- `Api/V1/StoreTimingRequest.php` – schéma průchodu časomíry, identifikátory závodu a idempotency klíč.
- `PublicSite/StoreContactMessageRequest.php` – validace kontaktního formuláře a ochrana proti spamu.

Validace vstupu nenahrazuje kontrolu oprávnění ani kontrolu, že kategorie patří do právě vybraného ročníku.

#### API Resources a Middleware

`app/Http/Resources/Api/V1/` obsahuje explicitní JSON reprezentace, například `TimingPassageResource`. Odpovědi obsahují pouze pole potřebná externímu systému.

`app/Http/Middleware/` obsahuje pouze vlastní middleware, například kontrolu rozsahu API tokenu nebo ověřeného provozního režimu. Standardní autentizaci, CSRF ochranu a rate limiting použijeme z Laravelu, pokud není konkrétní důvod pro vlastní řešení.

### 3. Služby a doménové operace (`app/Services/`)

Služby zapouzdřují operace používané z kontrolerů, jobs nebo konzolových příkazů. Každá má jasný vstup a výstup a lze ji testovat bez HTTP vrstvy.

- `RegistrationService.php` – v databázové transakci ověří událost, ročník a kategorii, vytvoří přihlášku a její účastníky, rezervuje unikátní variabilní symbol a zaznamená souhlasy.
- `PaymentQrService.php` – sestaví platební údaje ve formátu QR Platba z částky a účtu příslušného ročníku; nesmí označit platbu jako přijatou.
- `ConsentService.php` – uloží neměnnou verzi přesného textu souhlasu, účel, osobu a časové razítko v UTC.
- `CookieConsentService.php` – spravuje verzi a záznam voleb cookies, pokud se rozhodne o serverové evidenci.
- `ResultImportService.php` – bezpečně parsuje CSV, ověří strukturu, kategorie a závodníky; nejprve vytvoří náhled a chyby, teprve po schválení zapíše výsledky.
- `TimingApiService.php` – ověří čas, ročník, kategorii a startovní číslo, uplatní idempotenci a uloží auditní stopu.
- `ResultPublicationService.php` – publikuje schválený soubor výsledků a spustí invalidaci příslušné cache.
- `EventEditionResolver.php` – centralizuje bezpečné dohledání ročníku v rozsahu události; nepřijímá jen neověřené ID od klienta.

Služby mohou používat menší hodnotové objekty nebo enumy, pokud tím zlepší čitelnost. Nevytváříme obecnou vrstvu repository nad každým Eloquent modelem bez konkrétní potřeby.

### 4. Události, fronty a e-maily

#### Aplikační události (`app/Events/`) a listenery (`app/Listeners/`)

Události umožní oddělit dokončení operace od následků, například `RegistrationCreated` spustí odeslání potvrzení nebo `ResultsPublished` invalidaci cache. Listener nesmí znovu vytvořit přihlášku při opakovaném doručení události.

#### Úlohy na pozadí (`app/Jobs/`)

Činnosti, které nemají blokovat odpověď prohlížeče, posíláme do fronty:

- `SendRegistrationConfirmationJob.php` – odešle transakční potvrzení s QR kódem a platebními údaji.
- `SendRegistrationStatusUpdateJob.php` – oznámí změnu stavu přihlášky.
- `ImportResultsJob.php` – zpracuje schválený import většího souboru.
- `RebuildPublishedResultsJob.php` – připraví export/PDF nebo odvozený výstup, pokud to provoz vyžaduje.

Jobs musí mít nastavený počet pokusů, prodlevu, timeout a chování při trvalém selhání. Opakované provedení nesmí odeslat potvrzení vícekrát ani vytvořit duplicitní výsledky. Chyby se logují bez zbytečných osobních údajů.

#### E-mailové třídy (`app/Mail/`) a šablony (`resources/views/emails/`)

`RegistrationConfirmationMail` předá Blade šabloně rekapitulaci přihlášky a QR platbu. Odeslání běží přes frontu a specializovaného transakčního poskytovatele. Konfigurace připojení je v proměnných prostředí; SPF, DKIM a DMARC se nastaví pro odesílací doménu. E-mail je idempotentní a neobsahuje neveřejné údaje, které nejsou pro účastníka nutné.

### 5. Oprávnění (`app/Policies/`)

Policies rozhodují o přístupu k jednotlivým zdrojům:

- `EventPolicy`, `RaceEditionPolicy` – kdo může upravovat konkrétní událost a ročník.
- `RegistrationPolicy`, `ParticipantPolicy` – kdo může číst, měnit nebo exportovat osobní data.
- `ResultPolicy` – kdo může importovat a publikovat výsledky.
- `DocumentPolicy` – kdo může nahrávat a mazat soubory.

Role mohou být například administrátor, pořadatel/editor, správce registrací a správce výsledků. Kontrola role sama nestačí: uživatel s oprávněním ke správě Vodácké 30 nemá automaticky přístup k registracím maratonu, pokud mu nebyl přidělen odpovídající rozsah.

## Databáze (`database/`)

### Migrace (`database/migrations/`)

Tabulky vytvářet v pořadí odpovídajícím relacím. Minimální skupiny:

1. uživatelé, role a oprávnění;
2. `events`, `race_editions`, `categories`;
3. `participants`, `registrations`, `registration_participants`, `payments`;
4. `results`, případně `timing_passages` a záznamy idempotence;
5. `consent_records`, případně `cookie_consents`;
6. aktuality, dokumenty, galerie, partneři, kontakty a auditní log.

Používat cizí klíče, unikátní indexy pro variabilní symbol v určeném rozsahu a vhodné indexy pro veřejné filtry výsledků. Mazání má odpovídat významu dat: ročník s přihláškami či výsledky se běžně nesmí smazat (`restrictOnDelete`); spojovací záznamy lze mazat pouze v rámci řízené operace; auditní a souhlasové záznamy mají být neměnné. Osobní data se při oprávněném požadavku řeší anonymizací nebo retenční politikou, ne nekontrolovaným mazáním historických výsledků.

Každá migrace musí být bezpečně spustitelná v CI a stagingu. Změny schématu při produkčním nasazení mají být zpětně kompatibilní alespoň po dobu přechodu mezi verzemi aplikace.

### Továrny a seedery (`database/factories/`, `database/seeders/`)

Factories vytvářejí realistická, ale výhradně fiktivní data. `DatabaseSeeder` pro lokální vývoj může vytvořit dvě události (Vodácká 30 a Východočeský vodácký maraton), několik ročníků, odlišné kategorie, fiktivní účastníky, testovací přihlášky a výsledky. Seedované kategorie musí zřetelně patřit pouze ke své události.

V CI a vývoji se nesmí posílat skutečné e-maily ani používat produkční klíče. Zátěžová data se generují odděleným příkazem nebo seederem, aby omylem nezatížila běžnou lokální databázi. Produkční seeder vytváří jen minimální bezpečný obsah a nezakládá veřejné testovací účty.

## Frontend a šablony (`resources/`, `public/`)

### Blade šablony (`resources/views/`)

```text
resources/views/
|-- layouts/
|   |-- public.blade.php
|   |-- admin.blade.php
|   `-- email.blade.php
|-- public/
|   |-- home.blade.php
|   |-- events/index.blade.php
|   |-- events/show.blade.php
|   |-- editions/show.blade.php
|   |-- registrations/create.blade.php
|   |-- registrations/confirmation.blade.php
|   |-- results/index.blade.php
|   |-- results/show.blade.php
|   |-- news/
|   |-- gallery/
|   `-- contact.blade.php
|-- admin/
|   |-- events/
|   |-- editions/
|   |-- registrations/
|   |-- results/
|   `-- content/
|-- emails/
|   |-- registration-confirmation.blade.php
|   `-- registration-status.blade.php
`-- components/
    |-- alerts/
    |-- buttons/
    |-- forms/
    |-- navigation/
    `-- results/
```

Veřejné, administrační a e-mailové layouty oddělit. Veřejné stránky a administrace mohou sdílet základní komponenty, ale nemají sdílet navigaci ani oprávnění. V šablonách nevykonávat databázové dotazy ani doménové výpočty.

### CSS a JavaScript

- `resources/css/app.css` – vstupní styly a designové proměnné.
- `resources/js/app.js` – vstupní skript veřejného webu.
- `resources/js/admin.js` – případné skripty administrace odděleně od veřejné části.
- `resources/js/cookie-consent.js` – ovládání volby cookies; analytické skripty se nesmí vložit ani spustit před souhlasem.

Vite sestaví produkční CSS a JavaScript. Použít jedno zvolené řešení stylování (například Tailwind CSS nebo vlastní CSS), ne několik překrývajících se frameworků. Interakce mají fungovat i s omezeným JavaScriptem, pokud jde o odeslání formuláře nebo zobrazení důležitých informací.

### Veřejné a soukromé soubory (`public/`, `storage/`)

Do `public/` patří veřejně dostupné build assety a soubory, které mohou vidět všichni. Nahrané soubory z administrace ukládat přes Laravel filesystem. Veřejné propozice a výsledková PDF mohou být ve veřejném disku; exporty s osobními údaji, neveřejné seznamy a zálohy musí být na soukromém disku a dostupné pouze po autorizaci. Ověřovat MIME typ, velikost a název souboru; nikdy nespouštět upload jako PHP.

## Routování (`routes/`)

- `routes/web.php` – veřejné stránky, formuláře a administrace, pokud ji daná verze Laravelu registruje zde.
- `routes/admin.php` – volitelný soubor pro administraci. Je nutné ho explicitně zaregistrovat v bootstrap konfiguraci Laravelu a chránit autentizačním i autorizačním middlewarem.
- `routes/api.php` – REST API pro integrace, verzované pod `/api/v1`; chránit samostatnými tokeny/HMAC, scope oprávněními a rate limitem.
- `routes/console.php` – plánované úlohy a příkazy podle konvencí používané verze Laravelu.

Veřejné adresy by měly nést identitu akce, například `/akce/vodacka-30/rocnik/2027` a při rozhodnutí pořadatele také `/akce/vychodocesky-vodacky-maraton/rocnik/2027`. URL nesmí být jedinou bezpečnostní kontrolou. ID události a ročníku se vždy ověřují proti oprávnění uživatele a vztahu k vybrané kategorii.

## Cache, fronty a škálování

Redis bude sloužit pro cache a podle zvoleného nasazení i fronty. Cacheovat lze veřejné a často čtené údaje: publikované propozice, seznamy kategorií, aktuality, archiv a publikované výsledky. Cache klíče vždy zahrnou identifikátor události a ročníku. Při změně nebo publikaci se příslušné klíče invalidují.

Necachovat jako veřejný obsah osobní údaje, neveřejné registrace, platební stavy ani odpovědi časomíry určené administrátorům. Nárazovou registraci a čtení výsledků ověřit zátěžovým testem; v testovacím prostředí lze použít fiktivní data bez odesílání e-mailů.

## Konfigurace a tajemství

- `.env.example` obsahuje pouze názvy proměnných a bezpečné ukázkové hodnoty; nikdy neplatná produkční hesla ani API klíče.
- `.env` zůstává mimo Git a produkční tajemství spravuje hosting nebo secret manager.
- `config/v30.php` obsahuje ne-tajná nastavení aplikace, například výchozí časové pásmo nebo limity importu.
- `config/mail.php`, `config/queue.php`, `config/cache.php` a `config/filesystems.php` vycházejí z Laravel konfigurace a čtou prostředí přes standardní konfigurační vrstvu.
- Produkční prostředí musí mít nastavené `APP_DEBUG=false`, HTTPS, bezpečné session cookies, SMTP/API údaje poskytovatele a správné doménové DNS záznamy SPF/DKIM/DMARC.

## Testy a kvalita (`tests/`)

- `tests/Unit/` – pravidla variabilního symbolu, částky, validace vztahů a parseru výsledků.
- `tests/Feature/PublicSite/` – navigace, ročníky, veřejné výsledky a správné 404.
- `tests/Feature/Registration/` – vytvoření registrace, kapacita, souhlasy, QR platba a oddělení událostí.
- `tests/Feature/Admin/` – role, oprávnění, import, publikace a auditní záznamy.
- `tests/Feature/Api/V1/` – autentizace, rate limit, chybné payloady, idempotence, scope události a zákaz duplicit.
- `tests/Feature/Privacy/` – nepřítomnost analytických skriptů před souhlasem a možnost souhlas odvolat.
- `tests/Performance/` – zátěžové scénáře registrace a veřejného načítání výsledků; spouštět v odpovídajícím testovacím prostředí.

Kritické integrační testy musí ověřit, že kategorie maratonu nelze použít v přihlášce Vodácké 30 a naopak. E-mailové testy používají `Mail::fake()` nebo sandbox; nesmějí kontaktovat skutečné účastníky.

## CI/CD a provozní prostředí

Vývoj i produkce potřebují prostředí vhodné pro Laravel: VPS nebo managed cloud, PHP CLI, Composer, terminál/SSH, databázi, HTTPS, plánované úlohy a dlouho běžící queue workery. Běžný levný sdílený hosting není cílové prostředí. Vite assety lze sestavit v CI a nasadit jejich výstup; Node.js proto nemusí běžet na produkčním serveru.

CI pipeline by měla:

1. instalovat PHP a JavaScript závislosti z lock souborů;
2. spustit automatické testy a statickou analýzu;
3. ověřit migrace nad čistou databází;
4. sestavit Vite assety;
5. vytvořit artefakt a nasadit ho na staging;
6. po schválení nasadit produkční verzi s řízeným spuštěním migrací a možností rollbacku.

Po nasazení ověřit health check, queue workery, plánované úlohy, e-mailovou službu, cache a čerstvost výsledků. Před změnou schématu nebo nasazením před kritickým závodem musí být ověřená záloha i postup obnovy.

## Routinní vývoj: doporučený postup

1. **Založit aplikaci.** Vytvořit Laravel projekt v aktuálně podporované verzi, nastavit lokální `.env` a ověřit PHP CLI, Composer, databázi a Node.js pro build Vite.
2. **Připravit vývojové služby.** Zprovoznit lokální databázi a Redis; e-mail směrovat do testovacího mailboxu/sandboxu. Použít výhradně testovací API klíče.
3. **Navrhnout schéma.** Začít entitami `Event -> RaceEdition -> Category`; přidat přihlášky, účastníky, platby, souhlasy a výsledky s cizími klíči a unikátními omezeními.
4. **Doplnit modely a testovací data.** Nastavit Eloquent relace, factories a seedery pro obě oddělené události a jejich ročníky. Fiktivní data jasně oddělit od produkčních.
5. **Přidat autorizaci.** Založit účty pořadatelů, policies a role. Ověřit, že role a přístupová práva jsou omezená i podle události.
6. **Vytvořit layouty.** Zprovoznit Vite a samostatné Blade layouty pro veřejný web, administraci a e-maily.
7. **Zpřístupnit veřejný obsah.** Vytvořit přehled událostí, ročníku, propozic a tratě; ověřit, že archiv správně filtruje podle události i ročníku.
8. **Dokončit registraci.** Přidat `FormRequest`, transakční `RegistrationService`, záznam souhlasu, unikátní VS a QR platbu. Odeslání potvrzení předat do queue jobu.
9. **Přidat administraci.** Umožnit správu kategorií, přihlášek, plateb, aktualit, dokumentů a partnerů podle práv uživatele.
10. **Zprovoznit výsledky.** Nejprve import CSV s náhledem a schválením; následně přidat zabezpečené REST API pro časomíru podle dohodnutého kontraktu.
11. **Prověřit soukromí a výkon.** Otestovat souhlasy, cookie lištu, cache, zátěž, zálohování a obnovu.
12. **Nasadit řízeně.** Projít CI, staging a schválení pořadatelem, pak nasadit produkci mimo největší provozní špičku.

## Co tento dokument neurčuje

Tato struktura není hotový kód ani definitivní rozhodnutí o všech funkcích. Před implementací se s pořadateli potvrdí, zda nový web bude spravovat také Východočeský vodácký maraton, které kategorie jsou pro jednotlivé události platné, jaký je zdroj výsledků, jak dlouho se osobní údaje uchovávají a zda je zapotřebí online párování plateb nebo živé výsledky. Datový model už ale počítá s bezpečným oddělením akcí, pokud budou obě na jedné platformě.