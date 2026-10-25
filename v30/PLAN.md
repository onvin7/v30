# v30

Mapa systému, ne seznam fází. Zdroj pravdy je kód – každý `[x]` má na řádku `tech:` cestu,
podle které se dá dohledat. Co se ověřit nepodařilo, je popsané v `## decisions` dole.

## Provozovat veřejný web závodu {#public-site}
tech: čisté PHP MVC bez frameworku – vlastní router v `web/index.php`, kontrolery v `app/Controllers`, šablony přes `require` z `layout.php`
links: [race-data, entries, hosting]
files: [web/index.php, app/Controllers/*.php, app/Views/**, assets/**]
- [x] Obsloužit devět veřejných stránek {#public-routes}
  tech: web/index.php – mapa rout GET: /, /zavod, /propozice, /prihlaska, /prihlaska/potvrzeni, /startovni-listina, /vysledky, /galerie, /kontakt
- [x] Vykreslit zimní expediční design {#public-design}
  tech: app/Views/layout.php – Tailwind CDN s paletou brand/night/deep/snow/ice, Alpine.js, scroll-reveal (`.reveal` → `.is-visible`)
- [x] Odpovědět 404 a 405 na neznámé adresy {#public-errors}
  tech: web/index.php (větev 405 posílá hlavičku `Allow`) + app/Views/404.php, app/Views/405.php
- [x] Poslat v hlavičce `Allow` jen metody platné pro danou cestu {#public-allow}
  by: trae
  tech: web/index.php – seznam metod se sestaví z rout, které na cestu opravdu sedí (dřív se vypsaly všechny metody routeru)
- [x] Vzít termín, ročník a počet kategorií na úvod z configu {#public-hero-config}
  by: trae
  tech: app/Controllers/HomeController.php + app/Views/home.php – hero vypisuje 27. ročník a 16. ledna 2027 přes `zavod()`, počet kategorií je `count($zavod['kategorie'])`, archivní sekce se popisují z `archiv`
- [x] Sjednotit tlačítka na „Přihlásit se“ {#public-cta}
  by: trae
  tech: app/Views/home.php (hero i závěrečné CTA), app/Views/layout.php
- [x] Zvýšit dotykové cíle v patičce na 44 px {#public-footer-touch}
  by: trae
  tech: app/Views/layout.php – odkazy v patičce mají `inline-flex min-h-[44px] items-center`
- [x] Dát na mobilu najevo, že lišta filtrů pokračuje do strany {#public-scroll-fade}
  by: trae
  tech: app/Views/layout.php (`.scroll-fade` maskuje pravý okraj pod 768 px) + app/Views/results.php, app/Views/start_list.php
- [x] Ztlumit cenu v mobilním rozpisu kategorií {#public-price-muted}
  by: trae
  tech: app/Views/propositions.php:72-86 – název kategorie je dominantní řádek, cena spadla do vedlejšího řádku k trati a stylu
- [x] Ukončit propozice přihláškou a zkrátit opakování ročníku {#public-propositions}
  by: trae
  tech: app/Views/propositions.php – ročník 2026 zůstal jen v jednom úvodním bloku, poslední sekce vede na /prihlaska a nese 16. ledna 2027
- [x] Zobrazit startovní listinu a výsledky s filtrem podle kategorie {#public-results}
  tech: app/Controllers/StartListController.php, app/Controllers/ResultsController.php
- [x] Nahradit široké tabulky seznamem na mobilu {#public-record-lists}
  tech: app/Views/results.php:61, app/Views/propositions.php:72, app/Views/start_list.php:50 – varianty s `md:hidden`
- [x] Zobrazit galerii z lokálně hostovaných fotek {#public-gallery}
  tech: app/Controllers/GalleryController.php + config/images.php
- [ ] Zveřejnit sekci Novinky na úvodu {#public-news}
  from: agent
  tech: app/Views/home.php sekci vykresluje, ale HomeController::index `$novinky` nikdy nenastaví – je mrtvá
- [ ] Sjednotit breakpoint hlavičky {#public-header}
  from: roadmap
  tech: sticky se vypíná na 768 px, hamburger mizí až na 1280 px – app/Views/layout.php
- [ ] Nabuildit Tailwind lokálně místo CDN {#public-tailwind}
  from: roadmap
  tech: dnes CDN → na mobilní síti problikne nenastylovaná stránka

## Držet údaje o závodě na jednom místě {#race-data}
tech: jediný zdroj pravdy `config/zavod.php`, čtený přes helper `zavod()`; meta popisky a JSON-LD se z něj odvozují
links: [public-site, entries, hosting]
files: [config/zavod.php, config/seo.php, config/images.php, config/helpers.php, app/Helpers/Seo.php, app/Helpers/Text.php]
- [x] Držet ročník, termín, místo, kategorie a ceny v jednom souboru {#race-config}
  tech: config/zavod.php – ročník 27/2027, 11 kategorií s tratí, stylem a cenou
- [x] Odvodit meta popisky, Open Graph a JSON-LD z configu {#race-seo}
  tech: app/Helpers/Seo.php (structuredData, sitemapUrls) + app/Views/layout.php
- [x] Předat strojový (ISO) termín do strukturovaných dat {#race-iso}
  by: trae
  tech: config/zavod.php `rocnik.datum_iso` → app/Helpers/Seo.php::structuredData; config/seo.php drží už jen záložní hodnotu
- [x] Servírovat fotky z lokálních souborů {#race-images}
  tech: config/images.php → assets/img/*.jpg (63 souborů, commit e0721cc)
- [ ] Doplnit číslo účtu pořadatele {#race-ucet}
  from: roadmap
  tech: config/zavod.php `'ucet' => ''`; app/Views/registration_confirmation.php:72 na prázdnou hodnotu upozorňuje
- [ ] Napojit potvrzená data ročníku 2027 {#race-2027}
  from: roadmap
  tech: app/Controllers/StartListController.php a ResultsController.php mají ročník 2026 natvrdo
- [ ] Doplnit PDF propozic k aktuálnímu ročníku {#race-pdf}
  from: roadmap

## Přijmout a uložit přihlášku jednotlivce {#entries}
tech: POST /prihlaska → RegistrationRepository (`flock(LOCK_EX)` + dočasný soubor + `rename`) → storage/prihlasky.json
needs: [race-data]
links: [public-site, clubs]
files: [app/Controllers/RegistrationController.php, app/Models/RegistrationRepository.php, app/Views/registration.php, app/Views/registration_confirmation.php]
- [x] Zvalidovat a uložit formulář {#entries-store}
  tech: app/Controllers/RegistrationController.php:34-142 – CSRF, honeypot `website`, allowlist kategorií, FILTER_VALIDATE_EMAIL
- [x] Přidělit identifikátor V30-… a variabilní symbol {#entries-vs}
  tech: app/Models/RegistrationRepository.php (`PRVNI_VS 1000000001`)
- [x] Zobrazit rekapitulaci s platebními instrukcemi {#entries-confirm}
  tech: app/Views/registration_confirmation.php:72-92
- [x] Zahodit předvyplněné hodnoty při prvním vykreslení formuláře {#entries-old}
  by: trae
  tech: config/helpers.php::old_get – hodnoty načte ze session jednou a hned smaže, aby formulář nezůstal vyplněný napořád
- [x] Zobrazit souhrn chyb nad formulářem {#entries-errors}
  by: trae
  tech: app/Views/registration.php – `role="alert"` se seznamem chyb a odkazy na pole
- [x] Svázat chyby s poli přes `aria-invalid` a `aria-describedby` {#entries-aria}
  by: trae
  tech: app/Views/registration.php – chybující pole nesou `aria-invalid`/`aria-describedby`, chybové zprávy mají `id="<pole>-error"`, souhrn nad formulářem má `role="alert"`
- [ ] Zapojit limit pokusů a bezpečnostní log do přihlášky {#entries-ratelimit}
  from: agent
  tech: app/Helpers/RateLimit.php a app/Helpers/Log.php existují, ale na `main` je nikdo nevolá (grep: 0 volání)
- [ ] Posílat potvrzení e-mailem {#entries-email}
  from: roadmap
  tech: dnes se text vykreslí přímo na stránce – app/Views/registration_confirmation.php

## Postavit zónu oddílu {#clubs}
> Hotové na větvi `feature/ucty-oddilu` (commity 9a6a4f7, c02a5eb), do `main` nesloučené – routy proto na produkci neexistují.
tech: middleware OddilAuth + OddilRepository + šablony app/Views/oddil/**
needs: [entries]
links: [admin, merge]
files: [app/Controllers/Oddil*.php, app/Middleware/OddilAuth.php, app/Views/oddil/**, app/Models/OddilRepository.php, config/oddily-2026.php]
- [x] Zaregistrovat oddíl se schválením pořadatelem {#clubs-register}
  tech: feature/ucty-oddilu:app/Controllers/OddilAuthController.php (showRegister, register, oznameni)
- [x] Vést soupisku závodníků {#clubs-roster}
  tech: feature/ucty-oddilu:app/Controllers/OddilController.php (pridejZavodnika, upravZavodnika, smazZavodnika)
- [x] Odeslat hromadnou přihlášku za oddíl {#clubs-bulk}
  tech: feature/ucty-oddilu:app/Controllers/OddilRegistrationController.php (create, store, confirmation, show)
- [x] Nahradit e-mail stránkou s textem oznámení {#clubs-notify}
  tech: feature/ucty-oddilu:app/Views/oddil/oznameni.php
- [x] Naplnit výchozí seznam oddílů ročníku 2026 {#clubs-seed}
  tech: feature/ucty-oddilu:config/oddily-2026.php

## Postavit pořadatelskou administraci {#admin}
> Hotové na větvi `feature/administrace` (commity acdc4a9, eb6e549), do `main` nesloučené – routy proto na produkci neexistují.
tech: middleware AdminAuth, účty v config/admin.php, šablony app/Views/admin/**
links: [clubs, entries, merge]
files: [app/Controllers/Admin/**, app/Middleware/AdminAuth.php, app/Views/admin/**, config/admin.php, app/Models/PostRepository.php]
- [x] Přihlásit pořadatele {#admin-login}
  tech: feature/administrace:app/Controllers/Admin/AuthAdminController.php + app/Middleware/AdminAuth.php
- [x] Spravovat příspěvky na úvod {#admin-posts}
  tech: feature/administrace:app/Controllers/Admin/PostAdminController.php + app/Models/PostRepository.php
- [x] Schvalovat a importovat oddíly {#admin-clubs}
  tech: feature/administrace:app/Controllers/Admin/OddilAdminController.php (schvalit, zamitnout, resetHeslo, smazat, import)
- [x] Prohlížet přihlášky a exportovat je do CSV {#admin-export}
  tech: feature/administrace:app/Controllers/Admin/RegistrationAdminController.php (index, setStav, export)

## Sloučit větve do main {#merge}
tech: `git merge feature/administrace` + `feature/ucty-oddilu`; obě větve sáhnou do mapy rout v `web/index.php` a obě forknou před commitem e0721cc
needs: [clubs, admin]
links: [public-site, hosting]
files: [web/index.php, .gitignore]
- [ ] Sloučit obě větve a srovnat mapu rout {#merge-routes}
  from: roadmap
  tech: PROJEKT.md „Co zbývá“ bod 1; obě větve rozšiřují `$routes` v web/index.php
- [ ] Zachovat lokální fotky při sloučení {#merge-photos}
  from: agent
  tech: obě větve proti `main` mažou všech 63 souborů assets/img/*.jpg – vznikly před commitem e0721cc
- [ ] Opravit mrtvý odkaz /oddil/registrace na main {#merge-404}
  from: roadmap
  tech: odkaz je na stránce přihlášky, cíl existuje jen na větvi

## Udržet web v bezpečí a hosting v souladu {#hosting}
tech: `.htaccess` (HTTPS redirect, blokace, hlavičky, gzip, cache), hardening session v `index.php`, sitemap/robots z routeru
links: [public-site, race-data]
files: [.htaccess, index.php, config/autoloader.php, app/Helpers/Client.php, app/Helpers/Log.php, app/Helpers/RateLimit.php]
- [x] Zpevnit session cookie {#hosting-session}
  tech: index.php – httponly, samesite Lax, use_strict_mode, secure jen na HTTPS
- [x] Zablokovat app, config, storage, archivy a skryté soubory {#hosting-blocks}
  tech: .htaccess:15-22 + bezpečnostní hlavičky .htaccess:31-37
- [x] Generovat sitemap.xml a robots.txt z routeru {#hosting-seo}
  tech: web/index.php + app/Helpers/Seo.php::sitemapUrls
- [x] Přeskočit HTTPS redirect na localhostu a na Webzdarma {#hosting-https}
  tech: .htaccess:6-12
- [ ] Volat rate limiter a bezpečnostní log při přihlašování {#hosting-logging}
  from: agent
  tech: app/Helpers/RateLimit.php a app/Helpers/Log.php jsou hotové, ale na `main` je nikdo nevolá (grep: 0 volání)

## decisions
- 2026-10-23 – Sepsal jsem do dokumentů práci, která byla hotová jen v kódu, aby plán i kořenové dokumenty seděly se skutečností:
  - **Opravy z auditu (`.trae/documents/audit-webu-v30.md`) jsou v PLAN.md jako `[x]`.** Termín a ročník v hero z `config/zavod.php`, ISO termín pro JSON-LD, počet kategorií z configu, mazání `old` hodnot, hlavička `Allow` u 405, souhrn chyb v přihlášce a sjednocená CTA. Vše je zatím **v pracovní kopii, ne v commitu** – `[x]` znamená hotové v kódu, ne nasazené.
  - **Chybové stavy přihlášky jsou hotové celé.** Z auditu (nález M9) je hotová viditelná část (souhrn chyb nad formulářem) i strojová část – pole nesou `aria-invalid` a `aria-describedby`, chybové zprávy mají `id="<pole>-error"`, proto je `{#entries-aria}` `[x]`.
  - **Dokumentace se srovnala s kódem.** `README.md`, `PROJEKT.md` a `TECHNICKA_DOKUMENTACE.md` přestaly tvrdit, že fotky v repu nejsou (jsou v `assets/img/`, commit `e0721cc`), že přihláška má hotový „limit pokusů“ (`RateLimit` i `Log` na `main` nikdo nevolá) a že `RegistrationRepository` je jediný repozitář (na `main` leží i `OddilRepository` a `PostRepository`, bez volajícího). Rozpory níže jsou proto přeškrtnuté.
  - **Nové soubory jsem do dokumentace dovedl odkazy.** Živý plán `v30/PLAN.md`, audit `v30/.trae/documents/audit-webu-v30.md` a zásobník promptů `v30/TODO.md` na sebe odkazují z `README.md`.
- 2026-10-23 – Přepsal jsem `PLAN.md` ze stubu z `agenttrail init` na skutečnou mapu kódu. Rozhodnutí, která u toho padla:
  - **Stav jsem ověřoval v kódu, ne v dokumentaci.** Každý `[x]` má na řádku `tech:` cestu k souboru (u větví i jméno větve), aby se dal dohledat.
  - **Větve `feature/administrace` a `feature/ucty-oddilu` mají `[x]`, ale karta to říká nahlas.** Kód existuje a je ověřený (`git show <větev>:v30/web/index.php`, signatury metod kontrolerů), jenže na `main` ty routy nejsou – proto je nad kartami `{#clubs}` a `{#admin}` věta „nesloučené“. Sloučení samo je samostatná karta `{#merge}`, aby se „postaveno“ nepletlo s „nasazeno“.
  - **Nic jsem neoznačil `[~]`.** Během téhle session se na projektu nic nevyvíjí, takže zelená „právě běží“ by lhala.
  - **`by:` u hotových úkolů chybí.** Nemůžu je poctivě připsat – dokončení předchází tuhle session; kdo co udělal, drží git log. (Úkoly přidané 2026-10-23 už `by:` mají.)
  - **Vzniklo sedm karet místo původní jedné `{#setup}`.** Kopírují hranice kódu (veřejný web, data závodu, přihláška, oddíly, administrace, sloučení větví, bezpečnost/hosting), každá má vlastní „hotovo“ a aspoň jednu hranu.
  - **Kartu pro dokumentaci jsem záměrně nezaložil.** Nic na ní nezávisí a její smazání by nezměnilo, co produkt dělá – test durability neprojde. Úklid dokumentů proto zůstává úkolem uvnitř karet, které ta fakta vlastní.
  - **Roadmapu jsem nedělal jako jednu kartu.** Body z `PROJEKT.md` „Co zbývá“ jsem rozdal do karet podle souborů, kterých se týkají, a označil `from: roadmap`. Vlastní objevy z auditu kódu (`from: agent`) jsou čtyři: mrtvá sekce Novinky, nezapojený rate limiter a log u přihlášky a u přihlašování, a mazání fotek ve větvích.

- **Rozpory mezi dokumentací a kódem** (2026-10-23): dokumentace byla v těchto bodech zvetšelá, proto se jí nedalo věřit. První tři se při téhle revizi srovnaly s kódem, čtvrtý je věc kódu, ne dokumentace:
  - ~~fotky v repozitáři~~ → opraveno: `README.md` i `TECHNICKA_DOKUMENTACE.md` teď popisují 63 lokálních souborů v `assets/img/` (commit `e0721cc`), ne hotlinking na Zoneramu.
  - ~~„limit pokusů“ u přihlášky~~ → opraveno: `PROJEKT.md` a `TECHNICKA_DOKUMENTACE.md` už limit neuvádějí jako hotový. `RegistrationController::store` žádný limit nevolá a `App\Helpers\RateLimit` (ani `Log`) nemá na `main` jediného volajícího.
  - ~~jediný repozitář~~ → opraveno: `TECHNICKA_DOKUMENTACE.md` už zmiňuje i `OddilRepository` a `PostRepository`, které na `main` leží bez volajícího.
  - Sekce „Novinky“ na úvodu je mrtvá: `app/Views/home.php` ji vykresluje, ale `HomeController::index` `$novinky` nikdy nenastaví. Zůstává otevřený úkol `{#public-news}`.

- **Co se ověřit nepodařilo** (proto bez `[x]`, i když to dokumentace tvrdí): že by každá z dětských tratí měla vlastní mapu. V `RaceController::propositions` tři trasy s odkazy na `mapy.com` jsou, ale nespároval jsem je spolehlivě s kategoriemi. Když to někdo ověří, ať kartě `{#race-data}` přidá úkol.
