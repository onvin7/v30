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
4. Rozhodnout s uživatelem, zda se existující DB použije přímo, zda vznikne nové Laravel schéma, nebo se legacy DB použije pouze pro řízený export/migraci. Výslovně schválená lokální SQLite DB pro vývoj a syntetická/necitlivá testovací data je povolena; nesmí se zaměnit za legacy ani produkční DB. Do neidentifikované, legacy nebo produkční DB nepřidávat tabulky, seed data ani migrace.
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

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

## Foundational Context

This application is a Laravel application running on PHP 8.3. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If a frontend change doesn't show in the UI or you get a "Unable to locate file in Vite manifest" error, run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

# Laravel Boost

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists, including path-scoped framework guidelines under `.ai/rules/boost`. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.
- Activate the `deploying-to-cloud` skill whenever deploying to Laravel Cloud, configuring Cloud environments or resources, using the Cloud CLI, or troubleshooting Cloud deployments.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== phpunit/core rules ===

# PHPUnit

- This project uses PHPUnit. Create tests with `php artisan make:test --phpunit {name}`.
- Do not include the test suite directory in `{name}`. Use `SomeFeatureTest`, not `Feature/SomeFeatureTest`.
- Read the `testing-best-practices` skill for guidance on coverage, naming, structure, dependency isolation, and review.

## Running Tests

- Run the narrowest set of tests that covers the change. Pass a file path or `--filter=testName` to `php artisan test --compact`.
- Rerun a test after each change to it.
- Run `vendor/bin/phpunit` to call the test runner directly. It accepts the same file path and `--filter=testName` arguments.

</laravel-boost-guidelines>
