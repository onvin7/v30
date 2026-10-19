# Designové mantinely: Vodácká 30

**Kontext:** Web tradičního **běžkařského** závodu Vodácká 30 v Orlických horách. Hlavní uživatelé jsou outdooroví sportovci, pořadatelé a diváci.
**Tech stack:** **čisté PHP MVC** (šablony v `app/Views` vykreslované přes `layout.php`), **Tailwind CSS přes CDN**, **Alpine.js přes CDN** (jen mobilní menu), fonty Inter a Archivo z Google Fonts. Bez buildování (žádný Node.js, žádné sestavování assetů).

## 1. Vzhled a dojem

- **Téma:** "Winter Outdoor Utility". Design má působit chladně, jasně a profesionálně.
- **Inspirace:** profesionální sportovní a datové aplikace (Strava, Garmin Connect, Salomon).
- **Cíl:** vysoká hustota dat (výsledky), extrémní čitelnost a funkční minimalismus.

## 2. Striktní pravidla (anti-slop)

Nepoužívej obecné AI designové trendy. Dodržuj tato omezení:

- **Žádné velké/blur stíny:** nepoužívej `shadow-xl`, `shadow-2xl` ani barevné stíny. Použij `shadow-sm` nebo jen 1px ohraničení.
- **Žádné extrémní radiusy:** nepoužívej `rounded-2xl`, `rounded-3xl` ani `rounded-full` pro kontejnery. Karty `rounded-lg`, tlačítka a inputy `rounded-md` (maximum).
- **Žádné gradienty:** místo `bg-gradient-to-r` používej plné barvy.
- **Žádný nízký kontrast:** nikdy nepoužívej světle šedý text na bílém pozadí pro důležitá data.
- **Žádné vlastní CSS třídy:** používej výhradně Tailwind utility. Nevytvářej třídy jako `.btn-primary` nebo `.card`.

## 3. Barevná paleta

Drž se definované palety (ověřeno v `layout.php`, `home.php`, `results.php` a `registration.php`):

- **Pozadí:**
  - Základ stránky: layout používá `bg-white`.
  - Karty/kontejnery: `bg-white`.
  - Hlavička tabulek: `bg-slate-50`.
  - Patička: `bg-slate-900`.
- **Typografie:**
  - Nadpisy a primární data: `text-slate-900`.
  - Běžný text a sekundární data: `text-slate-600`.
  - Utlumený/meta text (data, malé popisky): `text-slate-500`.
- **Primární akcent (výrazná oranžová):**
  - Tlačítka/aktivní stavy: `bg-orange-600`.
  - Hover: `hover:bg-orange-700`.
  - Focus ring: `focus:ring-orange-600`.
- **Ohraničení a oddělovače:**
  - `border-slate-200` pro jemné oddělení.
- **Chybové zprávy formulářů:** `text-red-600`.

## 4. Typografie a spacing

- **Fonty:** nadpisy používají `font-display` (Archivo), tělo `font-sans` (Inter). Oba se načítají z Google Fonts v `layout.php`.
- **Spacing:** drž 8pt grid (`4`, `8`, `12`, `16`, `24`, `32`). Nepoužívej libovolné hodnoty jako `-[17px]`.
- **Whitespace:** velkorysé svislé odsazení hlavních sekcí (`py-16`, u hero `py-24` a více).

## 5. Klíčové vzory komponent

### Karty

Křehké ohraničení a velmi jemný stín.

- *Základ:* `bg-white border border-slate-200 rounded-lg`.

### Tlačítka

Ostrá a plná.

- *Primární:* `inline-flex items-center rounded-md bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold px-5 py-3 transition-colors`.
- *Sekundární:* `inline-flex items-center rounded-md bg-white border border-slate-300 hover:bg-slate-50 text-slate-900 text-sm font-semibold px-5 py-3 transition-colors`.

### Datové tabulky (výsledky, startovní listina)

Musí být velmi čitelné na mobilu i na počítači.

- *Hlavička:* `bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500 border-b border-slate-200`.
- *Řádky:* `border-b border-slate-200 hover:bg-slate-50 transition-colors`.
- *Zarovnání:* jména a oddíly vlevo; čísla (časy, startovní čísla, pořadí) vpravo (`text-right`). Obal tabulky `overflow-x-auto` a `caption` s `sr-only`.

### Formuláře (přihláška, kontakt)

Čisté vstupy s výrazným focusem.

- *Inputy:* `block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6`.
- *Popisky:* `block text-sm font-medium leading-6 text-slate-900`.
- *Chyby u polí:* `text-red-600 text-sm mt-1`.

## 6. Poznámky k implementaci v PHP šablonách

- Veškeré stránky se vykreslují přes společný `layout.php`, který drží hlavičku, navigaci, flash zprávy a patičku a na konci `require` dané view.
- Opakující se prvky (hlavička, patička, navigace) zůstávají v `layout.php`; nevytvářej nové abstrakce pro jednorázové použití.
- Výstup escapuj přes `e()` (v `config/helpers.php`); v šablonách, které `e()` ještě nepoužívají, se používá `htmlspecialchars`.
- Formuláře vkládají CSRF pole přes `csrf_field()` a chrání se honeypot políčkem.
- Chybové zprávy u polí vykresluj malým červeným textem (`text-red-600 text-sm mt-1`).
