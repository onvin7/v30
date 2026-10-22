> **ARCHIV – NEPLATÍ.** Redesign je hotový a vizuální pravidla platí v podobě, kterou popisuje `DESIGN.md` v kořeni repozitáře.

# Plán: Redesign webu Vodácká 30 (modrá, „zimní expedice“)

## Shrnutí

Nahradit současný generický vzhled („AI slop“: bílá karta s `border border-slate-200 rounded-lg`, oranžový akcent, na každé stránce stejná bílá hlavičková lišta) jednotným, záměrným vizuálním jazykem pro celý veřejný web v projektu [v30](file:///c:/Users/onvin/Documents/WEB/v30/v30).

**Potvrzená rozhodnutí uživatele:**
- **Rozsah:** celý web – [layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php) + všech 9 šablon + 404/405.
- **Barvy:** modrá; oranžovou zcela nahradit. `designov_mantinely.md` se **neřídí** (uživatel potvrdil, že neplatí).
- **Směr:** „Zimní expedice / editorial“ – kompozice schválená v konceptu 1 (tmavá značka nahoře, velká typografie, světlý obsah).
- **Žádné AI fotografie.** Používají se reálné fotky od pořadatele; kombinace – teď hotlink z alb, později nahraditelné lokálními soubory (jedno centrální místo pro cesty k obrázkům). Kde reálná fotka není, použije se grafický/typografický blok, **ne** vygenerovaná „fotka“.
- **Navigace:** sticky lišta **jen na mobilu** – objeví se, když uživatel na stránce scrolluje nahoru; jinak je jen nahoře. Na desktopu je lišta statická nahoře a odscrolluje se.
- **Žádná plovoucí průhledná lišta přes fotku** (to bylo označeno jako „klasik AI slop“).
- **Vizuál odsouhlasen** – desktopová kompozice konceptu je schválená. Dvě výtky k dopracování: (1) **mobil je celkově příliš velký** – hero i jeho odsazení zmenšit o stupeň; (2) **typografie běžného textu pod hero sekcí** se doladí později (uživatel výslovně odložil – obsah sekce je zatím jedno).

## Analýza současného stavu

Přečtené soubory: [layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php), [home.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/home.php), [about.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/about.php), [propositions.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/propositions.php), [results.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/results.php), [start_list.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/start_list.php), [gallery.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/gallery.php), [contact.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/contact.php), [registration.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/registration.php), [registration_confirmation.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/registration_confirmation.php), [404.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/404.php), [405.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/405.php), [HomeController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/HomeController.php), [ResultsController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/ResultsController.php), [GalleryController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/GalleryController.php), [RaceController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/RaceController.php), [web/index.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/web/index.php), [helpers.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/config/helpers.php).

Technické mantinely, které redesign respektuje:
- Čisté PHP MVC, front controller [web/index.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/web/index.php), šablony se vkládají přes `require $view` v [layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php#L121). **Žádný Composer, žádný Node/build** – CSS jen přes Tailwind CDN + inline `<style>`.
- Alpine.js 3 (CDN) – dnes pro mobilní menu, nově i pro chování hlavičky a scroll-reveal.
- Fonty Archivo (display) + Inter (body) přes Google Fonts.
- **Fotky se dnes generují AI** přes `https://coresg-normal.trae.ai/api/ide/v1/text_to_image` ([GalleryController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/GalleryController.php#L7-L15), [home.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/home.php#L3-L6)). To se ruší.

Problémy současného designu:
1. **Kartová polévka** – téměř každý blok `bg-white border border-slate-200 rounded-lg`.
2. **Identický bílý header** na každé podstránce (eyebrow + obří Archivo h1 + šedý perex + `border-b`).
3. **Oranžová** jako jediný akcent neodpovídá brandu.
4. **Generický hero** s AI fotkou a tmavým overlayem.
5. **Homepage má jen 2 sekce** ([home.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/home.php)).
6. **Tabulky bez závodní identity** (žádný leaderboard pocit).
7. **Pohyb** jen `transition-colors`.

## Designový záměr

**Vizuální teze:** „Zimní expedice za svítání“ – hluboká noční modrá jako tělo značky, sněhová bílá jako světlý povrch, ledově modrý akcent jako jediný zdroj energie; velká typografie a reálné fotky závodu, hodně prostoru, klid.

**Obsahový plán homepage:** Hero (značka + termín + CTA) → Trať a místo → Ročník v číslech → Den závodu (timeline) → Poslední výsledky (podium) → Závěrečné CTA.

**Interakční teze (záměrné pohyby):**
1. **Hlavička na mobilu** – při scrollu dolů se posune pryč, při scrollu nahoru se vrátí (Alpine). Na desktopu statická, žádný pohyb.
2. **Vstupní sekvence hero** – postupné fade/rise značky, titulku a CTA (CSS animace).
3. **Scroll-reveal sekcí** – fade + posun nahoru (Alpine Intersect plugin).
4. **Hover stavy** – podtržení odkazu, jemný zoom náhledu v galerii.

Vše vypnuté při `prefers-reduced-motion: reduce`.

**Designové tokeny (Tailwind CDN `tailwind.config.theme.extend`):**

| Token | Hodnota | Použití |
|---|---|---|
| `brand-50..950` | modrá škála (`#EFF7FD` → `#071F34`; 600 = `#1565A1`) | primární akcent, tlačítka, aktivní stavy, focus ring |
| `night` | `#06152A` | tmavé plochy, hlavička, patička, overlay |
| `deep` | `#0B2A4A` | tmavé panely / záhlaví podstránek |
| `snow` | `#F5F9FC` | světlé pozadí stránky (místo `bg-white`) |
| `ice` | `#7DD3FC` | akcent na tmavém (eyebrow, podtržení, hover linky) |

- **Typografie:** 2 fonty – Archivo (display, 700–900, `uppercase` + `tracking-tight`), Inter (text). Čísla/časy/startovní čísla vždy `tabular-nums`.
- **Typografická škála (mobil menší než desktop):** hero nadpis `text-4xl sm:text-6xl lg:text-8xl` (na mobilu tedy výrazně menší než v konceptu), hero odsazení `py-16 sm:py-24`; nadpisy sekcí `text-2xl sm:text-4xl`; perex `text-base sm:text-lg`. Cíl: na mobilu působí vše o stupeň menší a kompaktnější.
- **Běžný text (odloženo):** vizuální podobu textu v sekcích pod hero doladíme v samostatném kroku během implementace (řádkování, šířka `max-w-prose`, barvy `slate-600/500`).
- **Tvary:** `rounded-md`/`rounded-lg` max, bez velkých stínů; oddělení přes hairline (`border-slate-200`) a prostor, ne přes karty.
- **Přístupnost:** skip-link, `aria-current`, kontrast textu na overlayi, `:focus-visible` ring v `brand-400`, respekt k `prefers-reduced-motion`.

## Fotografie

Zdroj: reálné fotky závodu (Zonerama). Nalezené a ověřené album pro ročník 2026:
- **Vodácká 30tka 2026** – `https://eu.zonerama.com/milvin/Album/14562205` (17. 1. 2026, 52 fotek).
- **Jakub Šilar – ročník 2026** – `https://eu.zonerama.com/JakubSilar/Album/14557825` (už používáno).

Uživatel uvádí tři alba závodu za poslední tři ročníky; druhá dvě (starší) budou doplněna – na profilu `eu.zonerama.com/milvin` je veřejně vidět jen album 2026, starší ročníky dodá pořadatel nebo jsou na `vodacka30.cz`. **Otevřená položka k potvrzení při implementaci.**

Způsob zapojení (dle volby „kombinace“):
- Nový soubor `config/images.php` vrací mapu `klíč => URL` pro všechny obrázky webu (hero, chata, trať, náhledy alb, fotky v galerii).
- Nová pomocná funkce `img_url(string $key): string` v [helpers.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/config/helpers.php) mapu načte; při prázdné hodnotě vrátí `''`.
- Nová pomocná funkce `has_img(string $key): bool` pro podmínky v šablonách.
- Šablony: pokud je URL k dispozici → `<img>`; jinak → **grafický blok** (tmavá plocha + jemný motiv vrstevnice/linky trati + popisek), nikdy AI fotka.
- Nahrazení lokálními soubory později = změna hodnot v `config/images.php` (jedno místo), logika šablon se nemění.
- Poznámka k riziku: CDN URL Zoneramy bývají podepsané (token v query) a mohou expirovat. Pokud se hotlink ukáže jako nestabilní, hodnoty v `config/images.php` zůstanou prázné a použije se grafický blok, dokud pořadatel nedodá originální soubory.

## Navrhované změny

### 1. `app/Views/layout.php` (přepis vizuálního obalu)

**Co:** kompletní přepis `<head>` a obalu (hlavička, `main`, patička).
**Proč:** obal definuje 90 % vjemu „AI slop“ a musí nést nový jazyk pro všechny stránky.

Jak:
- `tailwind.config`: rozšířit o `colors` (brand/night/deep/snow/ice) a `fontFamily` (Archivo + Inter).
- Načíst Alpine Intersect plugin (`https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3/dist/cdn.min.js`) vedle stávajícího Alpine CDN.
- Inline `<style>`: `[x-cloak]`, `scroll-smooth`, `.reveal`/`.is-visible` (fade + posun), `::selection`, `@media (prefers-reduced-motion: reduce)` (vypne animace i transform hlavičky), a desktop override hlavičky:
  `@media (min-width: 768px) { .site-header { position: relative !important; transform: none !important; } }`
- **Hlavička** (`class="site-header sticky top-0 z-50 bg-night …"`):
  - Základ: `bg-night`, logo vlevo (značka + malý modrý akcentní prvek), odkazy vpravo (`text-white/75 hover:text-white`, aktivní = `text-white` + `after:` podtržení v `ice`), CTA „Přihlásit se“ v `brand-500`. Žádná průhlednost, žádný overlay přes fotku.
  - Mobil: `x-data="{ open:false, hidden:false, lastY:0 }" @scroll.window="const y = window.scrollY; hidden = (window.innerWidth < 768) && y > lastY && y > 120; lastY = y;"` + `:class="{ '-translate-y-full': hidden }"` a `transition-transform`. Tedy: při scrollu dolů se lišta schová, při scrollu nahoru se vrátí.
  - Desktop: přes media query v `<style>` vždy `position: relative` bez transformu → lišta je jen nahoře a odscrolluje se.
  - Mobilní menu: dropdown panel `bg-night` pod lištou (stejná data z `$navItems`), CTA v `brand-500`.
- **Flash zprávy:** jemný banner (success = emerald, error = red, světlé provedení).
- **Patička:** `bg-night`, velké brandové logo, tři sloupce (Navigace / Závod / Pořadatel), spodní řádek s copyrightem (TJ KVT Pardubice · Bedřichovka, Orlické hory). Zachovat všechny stávající odkazy a texty.
- **`main`:** `flex-1`.
- Zachovat: `$currentPath` výpočet, `$navItems` pole, `flash_get`, `require $view`.
- Nahradit všechny výskyty `orange-600/700` za `brand-600/700`.

### 2. `app/Views/partials/page_header.php` (nový soubor)

**Co:** sdílené tmavé záhlaví podstránky.
**Proč:** dnes se stejný bílý blok ručně opakuje na 8 stránkách.

Jak:
- Vstup: `$pageHeader = ['eyebrow', 'title', 'lead', 'breadcrumb' => ?array, 'image' => ?string]`.
- Vykreslí: tmavý pás (`night`/`deep`); pokud je `image` dostupný → full-bleed reálná fotka + gradient overlay do `night`, jinak **grafický blok** s motivem vrstevnice. Dále drobečková navigace, eyebrow v `ice`, `<h1>` Archivo `uppercase` (`text-4xl sm:text-5xl`), perex v `slate-300`.
- Šablony si proměnnou nastaví lokálně a partial `require`-nou (kontrolery se nemění).

### 3. `app/Views/home.php` (přepis) + `app/Controllers/HomeController.php` (doplnění dat)

Jak:
- **Hero:** full-bleed reálná fotka (klíč `hero`) s gradient overlayem do `night`; když fotka není, grafický blok s vrstevnicemi. Značka „Vodácká 30“ jako nejhlasitější text, eyebrow „Závod vodáků na běžkách“, termín + místo, dvě CTA (plná `brand` + obrysová). Vstupní animace (CSS).
- **Trať a místo:** 2sloupcový editorialový blok – text + fakta jako `dl` s hairline oddělovači (ne karta) vedle reálné fotky chaty (`chalet`, jinak grafický blok).
- **Ročník 2026 v číslech:** pás metrik (30 km, tratě 3 a 7 km, 26. ročník, pořadatel TJ KVT Pardubice) – podpůrná sekce, ne v hero.
- **Den závodu:** vertikální timeline z programu ([RaceController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/RaceController.php#L17-L30)) – jen klíčové body. Odkaz na `/propozice`.
- **Poslední výsledky:** kompaktní podium (top 3 Muži PRO z [ResultsController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/ResultsController.php#L74-L97)) s odkazem na `/vysledky`.
- **Závěrečné CTA:** tmavý pás s výzvou k přihlášce + kontakt.
- **HomeController:** doplnit `$rocnik` (metriky), `$program` (klíčové body), `$podium` (3 řádky); zachovat `$hero`, `$trat`, `$title`, `$view`.

### 4. `app/Views/about.php` (`/zavod`)

- `page_header.php` (eyebrow „Běžecké lyžování · Orlické hory“, reálná fotka nebo grafický blok).
- Místo dvou karet: 2sloupcový editorialový blok (vyprávění + `dl` faktů 2026 s hairline oddělovači) + CTA řádek (Propozice / Výsledky 2026). Texty beze změny.

### 5. `app/Views/propositions.php` (`/propozice`)

- `page_header.php`.
- Upozornění na platnost údajů: tenký banner (`bg-brand-50`, levý `border-brand-600`), ne karta.
- Sekce s hairline oddělovači: Pořadatelé, **Časový program jako vertikální timeline**, Kategorie – čistá tabulka v panelu, Startovné a Platba (2 sloupce), Trať / Bezpečnost / Ubytování (3 sloupce bez karet), Trasy a mapy (iframe v panelu + odkaz), Propozice 2026 (odkaz).
- Zachovat všechny datové klíče (`$poradatele`, `$program`, `$kategorie`, `$trasy`, `$startovne`) i vnější odkazy.

### 6. `app/Views/registration.php` (`/prihlaska`)

- `page_header.php` + `max-w-3xl`; tenký banner „Zkušební režim“.
- Formulář v panelu: inputy `rounded-md ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-brand-600`, labely dle systému, chyby `text-red-600 text-sm`. Primární tlačítko `brand-600`.
- **Beze změny:** `csrf_field()`, honeypot `website`, `old_get()`, `$errors` (klíče `kategorie`, `oddil`, `jmeno`, `prijmeni`, `rocnik_narozeni`, `email`, `souhlas`).

### 7. `app/Views/registration_confirmation.php`

- `page_header.php` + panel rekapitulace + panel platebních instrukcí + CTA řádek; zachovat všechna pole (`id`, jméno, oddíl, kategorie, ročník, e-mail, poznámka, `vytvoreno`, `ucet`, `castka`, `vs`, `poznamka`).

### 8. `app/Views/start_list.php` (`/startovni-listina`)

- `page_header.php`; rychlé kotvy na kategorie (chipy odkazující na `#klic`, bez JS).
- Každá kategorie = leaderboard panel: startovní číslo `tabular-nums` + tučně, jméno, oddíl vpravo, sticky hlavička tabulky. Poznámka k ochraně údajů jako tenký banner (obsah ponechat).

### 9. `app/Views/results.php` (`/vysledky`) – hlavní „showpiece“

- `page_header.php`; filtr kategorií jako segmentovaný pás (aktivní `bg-brand-600 text-white`) – logika `?kategorie=` beze změny.
- Leaderboard: pořadí zvýrazněno (1./2./3. jemný akcent v `brand`/`ice`), časy a ztráty vpravo `tabular-nums`, `DNS` řádek utlumený.
- „Poslední dostupný ročník“ (listina + PDF) a „Archiv ročníků“ jako mřížka let (ověřené = odkaz, neověřené = utlumené, zachovat `title` a `sr-only` texty).

### 10. `app/Views/gallery.php` (`/galerie`) + `app/Controllers/GalleryController.php`

- `page_header.php`.
- Alba: první jako velký featured blok, ostatní v mřížce; hover = zoom + overlay s „Otevřít album ↗“. Alba odkazují na reálné Zonerama albumech (milvin 2026 + Jakub Šilar 2026, starší po doplnění).
- Fotky v albu: mřížka (`columns-2 md:columns-3`) s `rounded-lg`, `loading="lazy"`, `alt` popisky. Když nejsou URL, místo fotek grafické bloky (žádná AI fotka).
- **GalleryController:** nahradit generátor `img()` zdroji z `config/images.php` (odstranit `IMG_BASE` a AI prompty); zachovat `$alba`, `$fotky`, `$title`, `$view`.

### 11. `app/Views/contact.php` (`/kontakt`)

- `page_header.php`; 2 sloupce – kontakty jako `dl` s hairline oddělovači + panel „Další informace“ s odkazy. Zachovat všechny kontakty a vnější odkaz na pořadatele.

### 12. `app/Views/404.php` a `app/Views/405.php`

- Přepis do nového jazyka: tmavá sekce `night`, velký kód v Archivo, krátký text, primární CTA „Zpět na úvod“ v `brand-600`.

### 13. `config/images.php` (nový) + `config/helpers.php` (doplnění)

- `config/images.php` vrací mapu `klíč => URL`.
- `img_url(string $key): string` a `has_img(string $key): bool` v [helpers.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/config/helpers.php) (načtení mapy s `require`, ošetření prázdných hodnot).

### 14. Co se needituje

- `designov_mantinely.md` – neplatí, neměníme.
- `AGENTS.md`, `web/index.php`, `config/autoloader.php`, `.htaccess`, `index.php` – beze změny.
- Ostatní kontrolery (`About`, `Race`, `StartList`, `Results`, `Contact`, `Registration`) – beze změny.

### Souhrn souborů

**Nové:** `config/images.php`, `app/Views/partials/page_header.php`.

**Upravené:** `app/Views/layout.php`, `app/Views/home.php`, `app/Views/about.php`, `app/Views/propositions.php`, `app/Views/results.php`, `app/Views/start_list.php`, `app/Views/gallery.php`, `app/Views/contact.php`, `app/Views/registration.php`, `app/Views/registration_confirmation.php`, `app/Views/404.php`, `app/Views/405.php`, `config/helpers.php`, `app/Controllers/HomeController.php`, `app/Controllers/GalleryController.php`.

## Předpoklady a rozhodnutí

- Modrá (`brand` škála) je jediný akcent; oranžová se v projektu nevyskytuje.
- Zůstává Tailwind CDN + inline `<style>` (bez buildu), Alpine.js + Alpine Intersect plugin (CDN).
- Zůstávají 2 fonty (Archivo + Inter); mono se nezavádí, čísla řeší `tabular-nums`.
- Minimální vlastní CSS je povoleno (`.reveal`, `.site-header`, hero gradient) – uživatel explicitně odmítl řídit se `designov_mantinely.md`.
- **Nepoužívá se žádný AI generátor obrázků.** Fotky = reálné (hotlink z alb) nebo grafický blok.
- Hlavička: mobil sticky s reveal při scrollu nahoru; desktop statická nahoře.
- Obsah, texty a osobní údaje se nemění; mění se pouze prezentace.
- Zdroj fotek pro ročníky 2024 a 2025 je otevřená položka – do potvrzení se u nich použije grafický blok.

## Pořadí implementace

1. **Systém** – `config/images.php`, `helpers.php` (`img_url`/`has_img`), `layout.php` (paleta, hlavička včetně mobilního chování, patička, flash, motion CSS, Intersect plugin), `partials/page_header.php`.
2. **Homepage** – `HomeController.php` + `home.php`.
3. **Datové stránky** – `results.php`, `start_list.php`, `propositions.php`.
4. **Obsahové stránky** – `about.php`, `gallery.php` (+ `GalleryController.php`), `contact.php`.
5. **Formuláře** – `registration.php`, `registration_confirmation.php`.
6. **Chybové stránky** – `404.php`, `405.php`.
7. **Kontrola** – konzistence akcentu (nikde `orange-` ani AI obrázky), responzivita, kontrast, reduced-motion, chování hlavičky na mobilu, průchod všech rout.

## Ověření

- **Syntaxe PHP:** `C:\xampp\php\php.exe -l` na každý nový i upravený PHP soubor (fallback `php -l`).
- **HTTP (Apache na `http://v30.test`):** `GET /`, `/zavod`, `/propozice`, `/prihlaska`, `/startovni-listina`, `/vysledky`, `/galerie`, `/kontakt` → 200 a očekávaný nadpis; `GET /vysledky?kategorie=muzi-pro` → 200 a filtrovaná tabulka; neexistující cesta → 404; `POST` na neexistující → 405.
- **Přihláška (regrese):** validní POST → 302 na `/prihlaska/potvrzeni`; nevalidní vstup → zpět na `/prihlaska` s chybou a zachovanými hodnotami; neplatný CSRF/honeypot → odmítnuto.
- **Hlavička:** na mobilu se při scrollu dolů schová a při scrollu nahoru vrátí; na desktopu je statická nahoře a odscrolluje se.
- **Vizuální kontrola:** hero se vejde do prvního viewportu, kontrast textu na fotkách, focus-visible stavy, `prefers-reduced-motion` vypne animace, v celém webu se nevyskytuje `orange-` ani `text_to_image`.
- **Bez datových změn:** žádné úpravy obsahu/osobních údajů, žádné zápisy mimo uvedené soubory.
