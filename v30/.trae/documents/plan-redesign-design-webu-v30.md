# Plán: Redesign webu Vodácká 30 (zimní expedice / editorial, modrá)

## Shrnutí

Cílem je nahradit současný generický vzhled („AI slop“: bílá karta s `border border-slate-200 rounded-lg`, oranžový akcent, na každé stránce stejná bílá hlavičková lišta) jednotným, záměrným vizuálním jazykem pro celý veřejný web v projektu [v30](file:///c:/Users/onvin/Documents/WEB/v30/v30).

Potvrzená rozhodnutí uživatele:
- **Rozsah:** celý web – [layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php) + všech 9 šablon + 404/405.
- **Barvy:** modrá (oranžovou zcela nahradit). `designov_mantinely.md` se **neřídí** (uživatel výslovně uvedl, že neexistuje / neplatí). Tím padá i zákaz gradientů, vlastních CSS tříd a stínů – používáme je však střídmě a účelně.
- **Vizuální směr:** „Zimní expedice / editorial“ – hluboká noční modrá, velké full-bleed fotografie hor a trati, úsporný text, klidná typografie, jemný pohyb.
- Obsah a osobní údaje zůstávají beze změny (data dál žijí v kontrolerech dle `AGENTS.md`, žádná DB).

## Analýza současného stavu

Přečtené klíčové soubory: [layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php), [home.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/home.php), [about.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/about.php), [propositions.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/propositions.php), [results.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/results.php), [start_list.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/start_list.php), [gallery.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/gallery.php), [contact.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/contact.php), [registration.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/registration.php), [registration_confirmation.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/registration_confirmation.php), [404.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/404.php), [405.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/405.php), [HomeController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/HomeController.php), [ResultsController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/ResultsController.php), [GalleryController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/GalleryController.php), [RaceController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/RaceController.php), [web/index.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/web/index.php), [helpers.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/config/helpers.php).

Technické mantinely, které redesign musí respektovat:
- Čisté PHP MVC, front controller [web/index.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/web/index.php), šablony se vkládají přes `require $view` v [layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php#L121). **Žádný Composer, žádný Node/build** – veškeré CSS musí být přes Tailwind CDN + inline `<style>`.
- K dispozici je Alpine.js 3 (CDN) – používá se pro mobilní menu. Fonty Archivo (display) + Inter (body) přes Google Fonts.
- Obrázky se negenerují do repozitáře; šablony i kontrolery používají generátor `https://coresg-normal.trae.ai/api/ide/v1/text_to_image` (viz [GalleryController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/GalleryController.php#L7-L15)).

Konkrétní problémy současného designu:
1. **Kartová polévka.** Téměř každý blok je `bg-white border border-slate-200 rounded-lg` (`propositions.php`, `results.php`, `about.php`, `contact.php`) → design působí jako šablona, ne jako závod.
2. **Identická hlavička stránky.** Každá podstránka začíná stejným bílým pruhem: malý verzálkový „eyebrow“ + obří Archivo h1 + šedý perex + `border-b` (např. [results.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/results.php#L1-L11)).
3. **Oranžová na zimním/vodáckém webu** neodpovídá brandu a je jediným akcentem.
4. **Hero** je generická fotka s tmavým overlayem + dvě tlačítka; chybí typografická hierarchie značky a pohyb.
5. **Chybí nosná myšlenka.** Homepage má jen 2 sekce (hero + trať) – [home.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/home.php).
6. **Tabulky** jsou funkční, ale bez „závodní“ identity (pořadí, čísla, časy jako leaderboard).
7. **Žádný pohyb** kromě `transition-colors`.

## Designový záměr (psáno před implementací)

**Vizuální teze:** „Zimní expedice za svítání“ – hluboká noční modrá jako tělo značky, sněhová bílá jako světlý povrch, ledově modrý akcent jako jediný zdroj energie; velké fotografie hor a stopy, hodně prostoru, klidná a sebevědomá typografie.

**Obsahový plán (homepage):** Hero (značka + termín + CTA) → Trať a místo (fotografie + fakta) → Ročník v číslech → Den závodu (timeline) → Poslední výsledky (podium) → Závěrečné CTA.

**Interakční teze (2–3 záměrné pohyby):**
1. Vstupní sekvence v hero (postupné fade/rise značky, titulku a CTA) + jemné zvětšení/parallax fotky při scrollu.
2. Scroll-reveal sekcí (fade + posun nahoru) přes Alpine Intersect plugin.
3. Přechod hlavičky z průhledné (nad hero) na tmavou se `backdrop-blur` a hover stavy (zoom fotky, podtržení odkazu).

**Designové tokeny (Tailwind CDN `tailwind.config.theme.extend`):**

| Token | Hodnota | Použití |
|---|---|---|
| `brand-50..950` | modrá škála (`#EFF7FD` → `#071F34`; 600 = `#1565A1`) | primární akcent, tlačítka, aktivní stavy, focus ring |
| `night` | `#06152A` | tmavé plochy, hlavička, hero overlay, patička |
| `deep` | `#0B2A4A` | tmavé panely / hlavička stránky |
| `snow` | `#F5F9FC` | světlé pozadí stránky (místo `bg-white`) |
| `ice` | `#7DD3FC` | akcent na tmavém (eyebrow, podtržení, hover linky) |

- **Typografie:** 2 fonty – Archivo (display, 700–900, `uppercase` + `tracking-tight` pro nadpisy), Inter (text). Čísla/časy/startovní čísla vždy `tabular-nums`.
- **Tvary:** `rounded-md`/`rounded-lg` max, žádné velké stíny; oddělení primárně přes hairline (`border-slate-200`) a prostor, ne přes karty.
- **Přístupnost:** skip-link, `aria-current`, kontrast textu na overlayi, `:focus-visible` ring v `brand-400`, respekt k `prefers-reduced-motion`.

## Navrhované změny

### 1. `app/Views/layout.php` (přepis vizuálního obalu)

**Co:** kompletní přepis `<head>` a obalu stránky (hlavička, `main`, patička) s novým systémem.
**Proč:** obal definuje 90 % vjemu „AI slop“; musí nést nový jazyk pro všechny stránky.

Jak:
- `tailwind.config`: rozšířit o `colors` (brand/night/deep/snow/ice dle tabulky), `fontFamily` (Archivo + Inter) a `keyframes/animation` pro hero (`heroRise`).
- Načíst navíc Alpine Intersect plugin (`https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3/dist/cdn.min.js`) vedle stávajícího Alpine CDN.
- Inline `<style>`: `[x-cloak]`, `scroll-smooth`, `.reveal { opacity:0; transform:translateY(16px); transition: … } .reveal.is-visible {…}`, `::selection`, gradient helpery, a `@media (prefers-reduced-motion: reduce)` blok, který pohyby vypne.
- **Hlavička:** `fixed inset-x-0 top-0 z-50`; `x-data="{ scrolled:false, open:false }" @scroll.window="scrolled = window.scrollY > 40"`. Na `/` startuje průhledná (jen bílý text nad hero), po odscrollování přejde na `bg-night/85 backdrop-blur border-b border-white/10`. Na ostatních cestách je vždy tmavá (vypočteno z `$currentPath`). Navigace = bílý text `text-white/75 hover:text-white`, aktivní odkaz = `text-white` + `after:` podtržení v `ice` (místo dosavadního šedého „chipu“).
- **Mobilní menu:** panel `bg-night`, stejná struktura jako desktop; CTA „Přihlásit se“ v `brand-500`.
- **Flash zprávy:** jemný banner s horním odsazením (kvůli fixní hlavičce), success = emerald, error = red, obojí ve světlém provedení.
- **Patička:** `bg-night`, velké brandové logo, tři sloupce (Navigace / Závod / Pořadatel), spodní řádek s copyrightem (TJ KVT Pardubice · Bedřichovka, Orlické hory). Zachovat všechny stávající odkazy a texty.
- **`main`:** `flex-1` + horní odsazení pro podstránky (homepage si hero řeší sama přes `100svh`).
- Zachovat: `$currentPath` výpočet, `$navItems` pole, `flash_get`, `require $view`.

### 2. `app/Views/partials/page_header.php` (nový soubor)

**Co:** sdílená tmavá hlavička podstránky.
**Proč:** dnes se stejný bílý blok ručně opakuje na 8 stránkách; jeden partial zajistí konzistenci a méně duplikace.

Jak:
- Vstupní proměnná `$pageHeader = ['eyebrow' => string, 'title' => string, 'lead' => string, 'breadcrumb' => ?array, 'image' => ?string]`.
- Vykreslí: volitelnou full-bleed fotku + gradient overlay do `night`, drobečkovou navigaci, eyebrow v `ice`, `<h1>` v Archivo `uppercase` (`text-4xl sm:text-5xl`), perex v `slate-300`. Odsazení `pt-32 pb-14` kvůli fixní hlavičce.
- Každá šablona si proměnnou nastaví lokálně a partial `require`-ne (kontrolery se nemění).

### 3. `app/Views/home.php` (přepis) + `app/Controllers/HomeController.php` (doplnění dat)

**Co:** homepage jako editorialový narativ o 6 sekcích.
**Proč:** dnes má jen 2 sekce a je to první dojem.

Jak:
- **Hero:** `min-h-[100svh]` full-bleed fotka (zimní hřeben / běžecká stopa za svítání), gradient overlay do `night` zdola, značka „Vodácká 30“ jako nejhlasitější text, eyebrow „Závod vodáků na běžkách“, termín + místo, dvě CTA (primární `brand` „Registrovat“, sekundární ghost „Startovní listina“). Obsah ukotven do klidné levé/spodní části fotky. Animace: vstupní sekvence + pomalý zoom fotky při scrollu.
- **Trať a místo:** 2sloupcové editorialové rozložení (text + fakta jako `dl` v řádku/seznamu s hairline, ne karta) vedle velké fotky chaty Bedřichovka.
- **Ročník 2026 v číslech:** pás metrik (30 km, tratě 3 a 7 km, 26. ročník, pořadatel TJ KVT Pardubice) – pouze jako podpůrná sekce, ne v hero.
- **Den závodu:** vertikální timeline z programu ([RaceController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/RaceController.php#L17-L30)) – jen klíčové body (start dětí, start 30 km, vyhlášení). Odkaz na `/propozice`.
- **Poslední výsledky:** kompaktní podium (top 3 Muži PRO z [ResultsController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/ResultsController.php#L74-L97)) s odkazem na `/vysledky`.
- **Závěrečné CTA:** tmavý pás (`night`/`deep`) s výzvou k přihlášce a kontaktem.
- **HomeController:** doplnit `$rocnik` (metriky), `$program` (klíčové body), `$podium` (3 řádky). Zachovat `$hero`, `$trat`, `$title`, `$view`.

### 4. `app/Views/about.php` (`/zavod`)

- Nahradit bílý header partialem `page_header.php` (eyebrow „Běžecké lyžování · Orlické hory“, doplnit úvodní foto).
- Místo dvou karet: 2sloupcový editorialový blok (vyprávění + `dl` faktů ročníku 2026 s hairline oddělovači) a CTA řádek (Propozice / Výsledky 2026). Texty beze změny.

### 5. `app/Views/propositions.php` (`/propozice`)

- `page_header.php`.
- Upozornění na platnost údajů: tenký banner (`bg-brand-50`, levý `border-brand-600`), ne karta.
- Sekce s jasnými nadpisy a hairline oddělovači: Pořadatelé, **Časový program jako vertikální timeline** (čas vlevo, tečka + linka, položka vpravo), Kategorie – čistá tabulka v jednom panelu, Startovné a Platba (2 sloupce), Trať / Bezpečnost / Ubytování (3 sloupce bez karet), Trasy a mapy (iframe v panelu + externí odkaz), Propozice 2026 (odkaz).
- Zachovat všechny datové klíče (`$poradatele`, `$program`, `$kategorie`, `$trasy`, `$startovne`) i vnější odkazy.

### 6. `app/Views/registration.php` (`/prihlaska`)

- `page_header.php` + úzký kontejner (`max-w-3xl`).
- Tenký banner „Zkušební režim“.
- Formulář v jednom světlém panelu: inputy `rounded-md ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-brand-600`, labely dle systému, chyby `text-red-600 text-sm`.
- Primární tlačítko „Odeslat přihlášku“ v `brand-600`.
- **Beze změny funkčnosti:** `csrf_field()`, honeypot `website`, `old_get()`, `$errors` (klíče `kategorie`, `oddil`, `jmeno`, `prijmeni`, `rocnik_narozeni`, `email`, `souhlas`).

### 7. `app/Views/registration_confirmation.php`

- `page_header.php` + panel rekapitulace + panel platebních instrukcí + CTA řádek. Zachovat všechna pole (`id`, jméno, oddíl, kategorie, ročník, e-mail, poznámka, `vytvoreno`, `ucet`, `castka`, `vs`, `poznamka`).

### 8. `app/Views/start_list.php` (`/startovni-listina`)

- `page_header.php`.
- Rychlé kotvy na kategorie (chipy odkazující na `#klic` sekcí) – bez JS.
- Každá kategorie = leaderboard panel s tabulkou: startovní číslo `tabular-nums` + tučně, jméno, oddíl vpravo. Hlavička tabulky sticky v rámci scrollu.
- Poznámka k ochraně údajů jako tenký banner (obsah ponechat).

### 9. `app/Views/results.php` (`/vysledky`) – hlavní „showpiece“

- `page_header.php`.
- Filtr kategorií jako segmentovaný pás (aktivní `bg-brand-600 text-white`, ostatní ghost) – beze změny logiky `?kategorie=`.
- Výsledky jako leaderboard: pořadí zvýrazněno (1./2./3. jemný akcent v `brand`/`ice`), časy a ztráty vpravo `tabular-nums`, `DNS` řádek utlumený.
- Sekce „Poslední dostupný ročník“ (odkazy na listinu a PDF) a „Archiv ročníků“ jako mřížka let (ověřené = odkaz, neověřené = utlumené, zachovat `title` a `sr-only` texty).

### 10. `app/Views/gallery.php` (`/galerie`)

- `page_header.php`.
- Alba: první album jako velký featured blok, ostatní v mřížce; hover = zoom náhledu + overlay s „Otevřít album ↗“.
- Fotky 2026: mřížka (`columns-2 md:columns-3`, `break-inside-avoid`) s `rounded-lg`, `loading="lazy"`.
- Poznámka k archivu jako tenký banner (text ponechat).

### 11. `app/Views/contact.php` (`/kontakt`)

- `page_header.php`.
- 2 sloupce: kontakty organizačního výboru jako `dl` s hairline oddělovači (bez těžké karty) + panel „Další informace“ s odkazy. Zachovat všechny kontakty a vnější odkaz na pořadatele.

### 12. `app/Views/404.php` a `app/Views/405.php`

- Přepis do nového jazyka: tmavá sekce `night`, velký kód (`404`/`405`) v Archivo, krátký text, primární CTA „Zpět na úvod“ v `brand-600`.

### 13. Co se needituje

- `designov_mantinely.md` – uživatel potvrdil, že se neřídí, **neměníme**.
- `AGENTS.md`, `web/index.php`, `config/helpers.php`, `config/autoloader.php`, `.htaccess`, `index.php` – beze změny.
- Ostatní kontrolery (`About`, `Race`, `StartList`, `Results`, `Gallery`, `Contact`, `Registration`) – beze změny; obsah zůstává.

### Souhrn souborů

**Nové:** `app/Views/partials/page_header.php`.

**Přepsané/upravené:** `app/Views/layout.php`, `app/Views/home.php`, `app/Views/about.php`, `app/Views/propositions.php`, `app/Views/results.php`, `app/Views/start_list.php`, `app/Views/gallery.php`, `app/Views/contact.php`, `app/Views/registration.php`, `app/Views/registration_confirmation.php`, `app/Views/404.php`, `app/Views/405.php`, `app/Controllers/HomeController.php` (doplnění dat).

## Předpoklady a rozhodnutí

- Barva: modrá (`brand` škála) je jediný akcent; oranžová `orange-600/700` se v celém projektu nahradí (`brand-600`/`brand-700`).
- Zůstává Tailwind CDN + inline `<style>` (bez buildu), Alpine.js + nově Alpine Intersect plugin (opět CDN).
- Zůstávají 2 fonty (Archivo + Inter); přidání monospace se **nezavádí** (číselné údaje řeší `tabular-nums`).
- Vlastní CSS třídy jsou povoleny (`.reveal`, hero gradient), ale jen minimálně a bez frameworku komponent; primárně se držíme Tailwind utilit.
- Fotky se generují přes stávající `text_to_image` API; pro hero a sekce volíme realistické zimní outdoor scény s klidnou tonální plochou pro text (např. hero prompt: *„dramatic winter sunrise over a snowy mountain ridge in Orlicke hory, a freshly groomed cross-country ski trail curving into a dark spruce forest, deep blue morning light, cinematic wide shot, professional sports photography“*).
- Obsah, texty a osobní údaje se nemění; mění se pouze prezentace. Žádné zápisy mimo šablony a jeden kontroler.
- `100svh` u hero + fixní hlavička: hero si horní prostor řeší sám (obsah odsazen), aby se vešel do prvního viewportu.

## Pořadí implementace

1. **Systém** – `layout.php` (paleta, fonty, hlavička, patička, flash, motion CSS, Intersect plugin) + `partials/page_header.php`. Tím se vizuálně změní všechny stránky naráz.
2. **Homepage** – `HomeController.php` (doplnění dat) + `home.php`.
3. **Datové stránky** – `results.php`, `start_list.php`, `propositions.php`.
4. **Obsahové stránky** – `about.php`, `gallery.php`, `contact.php`.
5. **Formuláře** – `registration.php`, `registration_confirmation.php`.
6. **Chybové stránky** – `404.php`, `405.php`.
7. **Kontrola** – konzistence akcentu (nikde oranžová), responzivita, kontrast, reduced-motion, průchod všech rout.

## Ověření

- **Syntaxe PHP:** `C:\xampp\php\php.exe -l` na každý nový i upravený PHP soubor (fallback `php -l`).
- **HTTP (Apache na `http://v30.test`):** `GET /`, `/zavod`, `/propozice`, `/prihlaska`, `/startovni-listina`, `/vysledky`, `/galerie`, `/kontakt` → 200 a očekávaný nadpis; `GET /vysledky?kategorie=muzi-pro` → 200 a filtrovaná tabulka; neexistující cesta → 404; `POST` na neexistující → 405.
- **Přihláška (regrese):** validní POST → 302 na `/prihlaska/potvrzeni`; nevalidní vstup → zpět na `/prihlaska` s chybou a zachovanými hodnotami; neplatný CSRF/honeypot → odmítnuto.
- **Vizuální kontrola:** desktop i mobil (hero se vejde do prvního viewportu včetně hlavičky), kontrast textu na fotkách, focus-visible stavy, `prefers-reduced-motion` vypne animace, v celém webu se nevyskytuje `orange-`.
- **Bez datových změn:** žádné úpravy obsahu/osobních údajů, žádné zápisy do DB ani souborů mimo uvedené šablony a `HomeController.php`.
