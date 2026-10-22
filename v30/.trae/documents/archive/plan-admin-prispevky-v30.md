> **ARCHIV – NEPLATÍ.** Část o bezpečnosti a SEO je hotová, administrace žije ve větvi `feature/administrace`. Aktuální stav je v `PROJEKT.md` a `TECHNICKA_DOKUMENTACE.md` v kořeni repozitáře.

# Plán: zabezpečení + SEO/meta + jednoduchá administrace (Vodácká 30)

## 1. Shrnutí

Kompletní „dorovnání" webu `v30` na úroveň referenčního projektu **Cyklistickey** (složka `c:\Users\onvin\Documents\WEB\maturita`) v rozsahu, který dává smysl pro web **bez databáze**:

1. **Bezpečnost** — session hardening, HTTPS, bezpečnostní hlavičky, gzip/cache, rate limiting, logování, IP anonymizace, User-Agent, auth middleware, blokace interních složek.
2. **SEO / meta** — meta description, canonical, robots, Open Graph, Twitter Card, strukturovaná data, `sitemap.xml`, `robots.txt`, robots meta `noindex` pro admin a chyby.
3. **Administrace** — jednoduchý admin s loginem (jméno + heslo **uložené v souboru**), CRUD nad příspěvky uloženými v souboru (bez DB).
4. **Homepage** — sekce „Novinky" s posledními 3 zveřejněnými příspěvky.

Respektovat `AGENTS.md` ([AGENTS.md](file:///c:/Users/onvin/Documents/WEB/v30/AGENTS.md)): čisté PHP MVC bez závislostí, bez DB, kód a texty česky, escapovat přes `e()`, formuláře přes CSRF + honeypot, PRG přes `redirect()`, nevytvářet zbytečné abstrakce. Veřejně se zobrazují jen titulní/metadata příspěvků (žádná osobní data účastníků).

## 2. Analýza: inventář Cyklistickey a co se přenáší

### 2.1 Co už `v30` má (a je na úrovni)

| Prvek | Stav | Soubor |
|---|---|---|
| CSRF token (session, `hash_equals`) | ✅ | [helpers.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/config/helpers.php#L21-L50) |
| Escapování výstupu `e()` | ✅ | [helpers.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/config/helpers.php#L13-L16) |
| Honeypot + CSRF ve formuláři | ✅ | [RegistrationController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/RegistrationController.php#L52-L55) |
| Serverová validace s allowlistem | ✅ | [RegistrationController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/RegistrationController.php#L68-L109) |
| Flash zprávy + PRG | ✅ | [helpers.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/config/helpers.php#L52-L92) |
| Vlastní router, 404/405 | ✅ | [web/index.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/web/index.php#L86-L113) |

### 2.2 Inventář vrstev Cyklistickey

| Vrstva | Cyklistickey (zdroj) | Přenášíme do v30 | Poznámka |
|---|---|---|---|
| Session hardening (cookie/HttpOnly/SameSite/strict mode) | `index.php` (`ini_set`) | ✅ | adaptace |
| HTTPS enforcement (301) | `web/.htaccess` | ✅ | s výjimkou localhost/127.0.0.1/`*.test` |
| Security headers (nosniff, SAMEORIGIN, XSS, Referrer) | `web/.htaccess` | ✅ | do kořenového `.htaccess` |
| Gzip + browser caching | `web/.htaccess` | ✅ | mod_deflate / mod_expires |
| CSRF | `app/Helpers/CsrfHelper.php` | ✅ už existuje | funkce `csrf_*` |
| Rate limiting | `app/Helpers/RateLimitHelper.php` | ✅ | adaptace, souborové úložiště |
| Logování (login/security/admin) | `app/Helpers/LogHelper.php` | ✅ | adaptace do `storage/` |
| Auth middleware | `app/Middleware/AuthMiddleware.php` | ✅ | adaptace: jeden admin, soubor |
| Login (CSRF, `password_verify`, log) | `app/Controllers/LoginController.php` | ✅ | adaptace bez DB |
| IP anonymizace (GDPR) | `app/Helpers/IPAnonymizerHelper.php` | ✅ | zkrácená verze |
| User-Agent / client IP / zařízení | `app/Helpers/UserAgentHelper.php` | ✅ | zkrácená verze (IP + detekce) |
| Redirect helper (301/302, mapy starých URL) | `app/Helpers/RedirectHelper.php` | ⚠️ částečně | v30 nemá staré URL; `redirect()` (302) už existuje, přidá se jen 301 utilita — viz rozhodnutí |
| Flash messages | `app/Helpers/FlashMessageHelper.php` | ✅ už existuje | `flash_set/get` |
| Text util (truncate, slug) | `app/Helpers/TextHelper.php` | ✅ | jen `truncate` + `slug` (pro příspěvky) |
| Time util (relativní čas) | `app/Helpers/TimeHelper.php` | ⚠️ volitelné | příspěvky mají jen datum; `relative()` pro sekci Novinky — viz rozhodnutí |
| SEO helper (meta/OG/Twitter/canonical/robots/schema) | `app/Helpers/SEOHelper.php` | ✅ | zkrácená verze pro v30 |
| SEO konfigurace | `web/config/seo_config.json` | ✅ | jako `config/seo.php` (styl projektu) |
| Sitemap (statická + obrázky + news) | `web/sitemap.php`, `sitemap-images.php`, `sitemap-news.php` | ✅ | v30 má málo obrázků → jen jeden `sitemap.xml` (statické routy) |
| robots.txt | `web/robots.txt` | ✅ | + `Disallow: /admin` |
| `ads.txt` (prodej reklamy) | `web/ads.txt` | ❌ | v30 neprodává reklamu |
| manifest.json / PWA | `web/manifest.json` | ❌ | potřeba sada ikon; v30 už má `theme-color` — viz rozhodnutí |
| Cookie consent (GDPR lišta) | `web/js/cookie-consent.js` | ❌ | v30 nemá trackovací cookies ani analytiku |
| Geo-lokace (geo-IP) | `app/Helpers/GeoLocationHelper.php` | ❌ | vázané na tracking/DB |
| AI SEO (generování metadat) | `app/Helpers/AISEOHelper.php` | ❌ | vyžaduje AI API + článkovou DB |
| Vkládání reklam | `app/Helpers/AdInsertionHelper.php` | ❌ | v30 neprodává reklamu |
| Tracking / klik-tracking | `app/Helpers/TrackingHelper.php`, `LinkTrackingHelper.php`, `app/Models/LinkClick*.php` | ❌ | vyžaduje DB + trackingové cookies |
| Články, kategorie, uživatelé, statistiky, reklamy, promo, flash news, hledání, migrace | `app/Models/*`, `app/Controllers/*`, `config/*.sql` | ❌ | **stojí na MySQL, v30 DB nemá** |

### 2.3 Zjištěné slabiny `v30`

- Kořenový [.htaccess](file:///c:/Users/onvin/Documents/WEB/v30/v30/.htaccess) přepisuje jen **neexistující** cesty (`!-f`, `!-d`) → `/app`, `/config` a budoucí `/storage` jsou přímo dostupné.
- Session cookie se zakládá bez `HttpOnly`/`SameSite`/`use_strict_mode`.
- Žádné bezpečnostní hlavičky, žádná HTTPS vynucení, žádný rate limit, žádné logování.
- Router nezachází s capture skupinami (potřeba pro edit/smazání podle ID).
- Ve webrootu leží dev artefakty `nahled-fotek.html` a `v30.zip` (nález mimo rozsah — jen doporučení odstranit).
- Hlavička `<head>` má jen `<title>` (žádný popis, canonical, OG).

### 2.4 Závěr hodnocení

Jádro (CSRF, escapování, validace, PRG) je srovnatelné. Chybí **autentizace, provozní zabezpečení (rate limit, logy, session, hlavičky, HTTPS, blokace složek) a SEO vrstva** — to vše tento plán doplňuje.

## 3. Navržené změny

### 3.A Bezpečnost

**A1. Session hardening** — do kořenového [index.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/index.php) před `require`, dle vzoru `maturita/index.php`:

```php
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', '');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_secure', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? '1' : '0');
```
(`session_start()` zůstává ve `web/index.php`.)

**A2. Kořenový [.htaccess](file:///c:/Users/onvin/Documents/WEB/v30/v30/.htaccess)** — doplnit dle `maturita/web/.htaccess`:

```apache
# HTTPS (vyjma lokálu a *.test)
RewriteCond %{HTTPS} off
RewriteCond %{HTTP_HOST} !^localhost [NC]
RewriteCond %{HTTP_HOST} !^127\.0\.0\.1 [NC]
RewriteCond %{HTTP_HOST} !\.test$ [NC]
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Blokace interních složek a dotfiles
RewriteRule ^(app|config|storage)(/|$) - [F,L]
RewriteRule (^|/)\. - [F,L]

# Bezpečnostní hlavičky
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Gzip
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/css application/javascript application/json image/svg+xml
</IfModule>

# Cache statických souborů
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png  "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType text/html "access plus 0 seconds"
</IfModule>

# Front controller (stávající)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```
(HTTPS pravidlo **musí** vyloučit `*.test`, jinak rozbije lokální `http://v30.test`.)

**A3. Nový `storage/.htaccess`** (obrana do hloubky):

```apache
<IfModule mod_authz_core.c>
    Require all denied
</IfModule>
<IfModule !mod_authz_core.c>
    Order allow,deny
    Deny from all
</IfModule>
```

**A4. Nový `app/Helpers/Log.php`** (namespace `App\Helpers`) — zápis do `storage/`:
- `write(string $soubor, string $zprava): void` (`LOCK_EX`, doplní čas + IP klienta),
- zkratky `security()`, `login()`, `admin()`.
- Konvence: `security.log` (neoprávněný přístup), `login.log` (pokusy o přihlášení), `admin.log` (změny příspěvků).

**A5. Nový `app/Helpers/RateLimit.php`** — souborový čítač `storage/rate_limit.json`:
- `check(string $klic, int $max, int $okno): bool`, `remaining()`, `resetTime()`.
- Nasazení: login `max = 5` / `okno = 900 s` na IP. Při překročení se přihlášení odmítne bez ověření hesla.

**A6. Nový `app/Helpers/Client.php`** — zkrácená verze `UserAgentHelper` + `IPAnonymizerHelper`:
- `ip(): ?string` (proxy-aware přes `X-Forwarded-For` atd.),
- `anonymizeIp(?string $ip): ?string` (IPv4 → poslední oktet 0; IPv6 → posledních 80 bit),
- `deviceType()`, `browser()`, `os()` (detekce pro logy).
- Logy zapisují **anonymizovanou** IP (GDPR).

**A7. Nový `app/Middleware/AdminAuth.php`** (namespace `App\Middleware`):
- `check(): void` — není-li `$_SESSION['admin_id']` → `redirect('/admin/prihlaseni')` + `Log::security()`; kontrola nečinnosti dle `session_timeout` (překročení → odhlásit a přesměrovat); jinak obnoví `$_SESSION['admin_last']`.
- `attempt(string $uzivatel, string $heslo): bool` — `hash_equals` na jméno, `password_verify` na heslo; úspěch → `session_regenerate_id(true)` + nastavení session.
- `logout(): void` — vyprázdnění `$_SESSION` + `session_destroy()`.

**A8. Nový `config/admin.php`** (styl `config/images.php`, vrací pole):

```php
<?php
// Přihlášení do administrace. HESLO JE HASHOVANÉ – nikdy neplaintext.
// Změna hesla: php -r "echo password_hash('nove-heslo', PASSWORD_DEFAULT);"
return [
    'uzivatel'        => 'admin',
    'heslo_hash'      => '$2y$10$…hash hesla "admin"…', // vygenerovat při implementaci
    'session_timeout' => 1800, // 30 min nečinnosti
];
```

**A9. `web/index.php`** — přidat admin routy, guard a předávání parametrů (viz 3.C).

### 3.B SEO / meta

**B1. Nový `config/seo.php`** (vrací pole, styl projektu):

```php
return [
    'url'         => 'http://v30.test',      // při nasazení změnit na produkční doménu
    'nazev'       => 'Vodácká 30',
    'popis'       => 'Tradiční běžkařský závod pro vodáky v Orlických horách. Pořádá TJ KVT Pardubice.',
    'klicova_slova' => 'Vodácká 30, běžkařský závod, Orlické hory, Bedřichovka, běžky',
    'autor'       => 'TJ KVT Pardubice',
    'og_obrazek'  => /* img_url('home-hero') */,
    'theme_color' => '#06152A',
    'socialni'    => [ /* odkazy, pokud existují */ ],
];
```

**B2. Nový `app/Helpers/Seo.php`** (zjednodušený `SEOHelper`, jen co v30 potřebuje):
- `title(?string $t): string` (doplní suffix názvu webu),
- `description(?string $text): string` (zkrácení na ~160 znaků),
- `canonical(string $path): string`,
- `robots(bool $index = true): string` (`noindex, nofollow` pro admin/404/405),
- `openGraph(array $data): array`, `twitterCard(array $data): array`,
- `structuredData()` — `Organization` (TJ KVT Pardubice), `WebSite`, `Event` (Vodácká 30, datum/místo z HomeControlleru),
- `sitemapUrls(): array` — statické routy + homepage.

**B3. [app/Views/layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php)** — do `<head>` doplnit (přes `Seo`):
meta description, canonical, robots, Open Graph (`og:title/description/type/url/image/site_name/locale`), Twitter Card, JSON-LD strukturovaná data. `<title>` nadále `$title`, ale sjednotit přes `Seo::title()` (řeší i suffix). Nové volitelné proměnné pro stránky: `$description`, `$ogImage`, `$robots` (když nejsou, použijí se výchozí z `config/seo.php`).

**B4. Kontrolery** — doplnit `$description` u klíčových stránek: home, zavod, propozice, vysledky, galerie, kontakt (krátké, české, dle obsahu).

**B5. `web/index.php`** — nové routy:
- `GET /sitemap.xml` → `Seo::sitemapUrls()` + zápis XML (`Content-Type: application/xml`),
- `GET /robots.txt` → text/plain (`Allow: /`, `Disallow: /admin`, `Sitemap: <url>/sitemap.xml`).

**B6. robots meta** — `noindex` na `/admin*` a v [404.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/404.php)/[405.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/405.php).

**B7. `app/Helpers/Text.php`** — jediná metoda `truncate(string $text, int $max): string` (zkrácení perexu pro kartu v sekci Novinky, cca 160 znaků). Slack/embeds ani `slug()` se nepřebírají (v30 nemá detail ani kotvy).

### 3.C Administrace

**Kontrolery** (namespace `App\Controllers\Admin`):
- `app/Controllers/Admin/AuthAdminController.php` — `showLogin()`, `login()`, `logout()`.
  - `login()`: rate limit (`RateLimit::check` na IP) → CSRF + honeypot → `AdminAuth::attempt()`; úspěch → `Log::login()` + flash + `redirect('/admin')`; neúspěch → `Log::login()` + flash chyba + `redirect('/admin/prihlaseni')`.
  - `logout()`: `AdminAuth::logout()` + `redirect('/admin/prihlaseni')`.
- `app/Controllers/Admin/PostAdminController.php` — `index()`, `create()`, `store()`, `edit($id)`, `update($id)`, `delete($id)`.
  - Validace: nadpis povinný (≤ 120 znaků), datum povinné (`Y-m-d`), perex ≤ 300 znaků, text volitelný, `zverejneno` 0/1.
  - Každá změna → `Log::admin()`.

**Model / úložiště**: `app/Models/PostRepository.php` (namespace `App\Models`)
- `all()`, `published(int $limit)`, `find(int $id)`, `create(array): int`, `update(int, array): bool`, `delete(int): bool`.
- Úložiště `storage/prispevky.json`: `{ "next_id": N, "posts": [ {id, nadpis, datum, perex, text, zverejneno, vytvoreno, upraveno} ] }`.
- Zápis atomicky (`tempnam` + `rename`) pod `flock(LOCK_EX)`; chybějící soubor → prázdná struktura.

**Routy** v [web/index.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/web/index.php):

| Metoda | Cesta | Akce |
|---|---|---|
| GET | `/admin` | `PostAdminController::index` |
| GET | `/admin/prihlaseni` | `AuthAdminController::showLogin` |
| POST | `/admin/prihlaseni` | `AuthAdminController::login` |
| POST | `/admin/odhlaseni` | `AuthAdminController::logout` |
| GET | `/admin/prispevky/novy` | `PostAdminController::create` |
| POST | `/admin/prispevky` | `PostAdminController::store` |
| GET | `/admin/prispevky/(\d+)/upravit` | `PostAdminController::edit` |
| POST | `/admin/prispevky/(\d+)` | `PostAdminController::update` |
| POST | `/admin/prispevky/(\d+)/smazat` | `PostAdminController::delete` |

Změny routeru: admin routy (kromě `/admin/prihlaseni`) volají před dispatch `AdminAuth::check()`; dispatch předává capture skupiny `...array_slice($matches, 1)` (stávající routy skupiny nemají → beze změny).

**Šablony** (admin, vizuálně v duchu webu — `brand/night`, Inter/Archivo, Tailwind z CDN):
- `app/Views/admin/layout.php` (vlastní layout, bez veřejné navigace),
- `app/Views/admin/login.php`,
- `app/Views/admin/posts/index.php`,
- `app/Views/admin/posts/form.php`.

### 3.D Homepage — sekce „Novinky"

- [HomeController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/HomeController.php) — načíst `(new PostRepository())->published(3)` do `$novinky`.
- [home.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/home.php) — nová sekce „Novinky" mezi „Den závodu" a „Poslední výsledky"; renderuje se jen když `$novinky` není prázdné; každý záznam: datum, nadpis, perex (přes `e()`), bez detailu.

### 3.E Přehled souborů

**Nové**
- `config/admin.php`, `config/seo.php`
- `app/Helpers/Log.php`, `app/Helpers/RateLimit.php`, `app/Helpers/Client.php`, `app/Helpers/Seo.php`, `app/Helpers/Text.php`
- `app/Middleware/AdminAuth.php`
- `app/Models/PostRepository.php`
- `app/Controllers/Admin/AuthAdminController.php`, `app/Controllers/Admin/PostAdminController.php`
- `app/Views/admin/layout.php`, `app/Views/admin/login.php`, `app/Views/admin/posts/index.php`, `app/Views/admin/posts/form.php`
- `storage/.htaccess` (+ `storage/prispevky.json` vznikne automaticky)

**Upravené**
- `index.php` (session hardening)
- `.htaccess` (HTTPS, hlavičky, gzip, cache, blokace složek)
- `web/index.php` (admin routy, guard, parametry, sitemap/robots)
- `app/Views/layout.php` (meta/OG/Twitter/JSON-LD)
- `app/Controllers/HomeController.php` (+ `$description`, `$novinky`)
- `app/Views/home.php` (+ sekce Novinky)
- `app/Views/404.php`, `app/Views/405.php` (robots noindex)
- vybrané kontrolery (doplnění `$description`)

## 4. Rozhodnutí a předpoklady

- **Přihlášení:** výchozí `admin` / `admin` (v souboru jen bcrypt hash); v `config/admin.php` výrazná poznámka „změň heslo".
- **Příspěvky** se ukládají do JSON souboru v chráněné `storage/` (dle zadání, bez DB).
- **Veřejně** se zobrazuje jen sekce na homepage s 3 nejnovějšími zveřejněnými příspěvky (bez samostatné stránky/detailu).
- Zavádí se složky `app/Helpers/`, `app/Middleware/`, `app/Models/` (parita s Cyklistickey); PSR-4 autoloader je zvládne bez úprav.
- Seznam `storage/` je jediná zapisovaná složka (příspěvky, rate limit, logy) → jeden chráněný `.htaccess`.
- **Nepřenášené DB funkce** (články, uživatelé, statistiky, tracking, reklamy, promo, flash news, hledání, migrace) — vyžadují MySQL, kterou v30 nemá.
- **Nepřenášené pomocné vrstvy Cyklistickey:** `GeoLocationHelper`, `AISEOHelper`, `AdInsertionHelper`, `TrackingHelper`/`LinkTrackingHelper` (+ modely `LinkClick*`), `ads.txt` — viz inventář v 2.2; všechny jsou vázané na DB, tracking nebo prodej reklamy, které v30 nemá.
- **`TimeHelper` (relativní čas) se nepřebírá** — příspěvky mají jen datum (ne čas); sekce Novinky zobrazí prostý formát `d. m. Y`, což je pro editorial styl vhodnější. (Lze doplnit později, pokud bude potřeba „před X dny".)
- **`RedirectHelper::permanent()` (301) se nepřebírá** — v30 nemá staré URL ani potřebu přesměrování. Kanonickou podobu URL (koncové lomítko) řeší už PHP router (`rtrim`) a `<link rel="canonical">` z B2/B3; Apache trailing-slash redirect z Cyklistickey se proto záměrně **nepřidává** (přinášel by riziko rewrite bez reálného přínosu).
- **GDPR cookie lišta se nepřidává** — v30 nemá žádné trackovací cookies ani analytiku; `Client::anonymizeIp()` řeší IP v logech. Doplnit lze později, pokud se přidá analytika.
- **manifest.json / PWA se nepřidává** — vyžaduje sadu ikon; v30 už má `theme-color`.
- HTTPS vynucení v `.htaccess` je vypnuté pro `localhost`, `127.0.0.1` a `*.test` (aby lokální `http://v30.test` fungovalo).
- Nález mimo zadání (dev artefakty `nahled-fotek.html`, `v30.zip` ve webrootu) — jen doporučení odstranit, neimplementuje se automaticky.
- SEO `config/seo.php` obsahuje `url` s vývojovou hodnotou `http://v30.test` → při nasazení změnit.

## 5. Ověření

1. **Syntaxe** všech nových/upravených PHP: `php -l <soubor>`.
2. **Session/hlavičky**: `curl -I http://v30.test/` → `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`; session cookie má `HttpOnly` + `SameSite=Lax`.
3. **Admin bez přihlášení**: `GET /admin` → 302 na `/admin/prihlaseni` + záznam v `storage/security.log`.
4. **Login**: špatné heslo → chyba + `storage/login.log`; správné `admin/admin` → 302 na `/admin`; po loginu nové `session_regenerate_id`.
5. **Rate limit**: 6. pokus o login v krátkém čase → odmítnuto bez ověření hesla.
6. **CRUD**: vytvoření/úprava/smazání příspěvku → 302, změny v `storage/prispevky.json`, záznamy v `storage/admin.log`; POST bez CSRF odmítnut; validace vrací chybu a zachová hodnoty.
7. **Homepage**: zveřejněný příspěvek se objeví v sekci „Novinky" (max 3, od nejnovějšího); nezveřejněný ne; bez příspěvků se sekce nevykreslí.
8. **Ochrana složek**: `GET /storage/prispevky.json`, `/config/admin.php`, `/config/seo.php`, `/app/Models/PostRepository.php` → 403.
9. **SEO**: `view-source` obsahuje meta description, canonical, robots, OG, Twitter, JSON-LD; `GET /sitemap.xml` → platné XML s routami; `GET /robots.txt` → text s `Disallow: /admin`; `/admin` a 404 mají `noindex`.
10. **Regrese veřejných rout**: `/`, `/zavod`, `/propozice`, `/prihlaska`, `/startovni-listina`, `/vysledky`, `/galerie`, `/kontakt` → 200; neznámá cesta 404; špatná metoda 405.
11. Testovat na Apache `http://v30.test` (kvůli `.htaccess`).
