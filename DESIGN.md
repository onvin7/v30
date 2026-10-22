# Designová pravidla

Vizuální jazyk webu Vodácké 30. Drž se ho, když saháš do šablon – kontext projektu je v [PROJEKT.md](PROJEKT.md), technické řešení v [TECHNICKA_DOKUMENTACE.md](TECHNICKA_DOKUMENTACE.md).

**Téma:** „Zimní expedice / editorial“ – chladná modrá paleta, výrazná typografie, klidný profesionální dojem. **Uživatelé:** outdooroví sportovci, pořadatelé a diváci. **Cíl:** vysoká hustota dat (výsledky, startovní listiny), extrémní čitelnost a funkční minimalismus.

Web nemá build krok: stylování jde přes Tailwind CDN a vlastní paletu v `tailwind.config` uvnitř `app/Views/layout.php`, interaktivitu řeší Alpine.js. Nepřidávej vlastní CSS třídy ani build pipeline.

## 1. Co se záměrně nepoužívá

Web nemá vypadat jako generický výstup AI šablon. Platí tato omezení:

- **Žádné velké nebo barevné stíny.** Nepoužívej `shadow-xl`, `shadow-2xl` ani barevné stíny; stačí `shadow-sm` nebo 1px ohraničení.
- **Žádné velké radiusy.** Maximum pro karty, tlačítka a inputy je `rounded-md`, `rounded-full` jen pro drobné ikonové prvky. `rounded-xl` a větší se nepoužívají.
- **Žádné gradienty.** Místo `bg-gradient-to-*` používej plné barvy. Jediná výjimka je tmavý přechod (scrim) přes hero fotografii na úvodu, který drží čitelnost bílého textu.
- **Žádný nízký kontrast.** Světle šedý text na bílém pozadí nikdy u důležitých dat.
- **Žádné teplé akcenty.** Oranžová ani jiná teplá barva se nepoužívá; akcent je modrá `brand` a světle modrá `ice` na tmavých plochách.
- **Žádné nové vlastní CSS třídy.** Používej Tailwind utility. Výjimkou je stávající scroll-reveal, hero animace a `.no-scrollbar` v `layout.php`.

## 2. Paleta

Definovaná v `tailwind.config` v `layout.php`:

- `brand` – škála 50–950 (`brand-500 #2689CC`, `brand-600 #1565A1`, `brand-700 #0F5082`).
- `night #06152A` – tmavé plochy: hlavička, patička, hero.
- `deep #0B2A4A` – tmavé bloky uvnitř obsahu.
- `snow #F5F9FC` – základní pozadí stránky, hlavičky tabulek.
- `ice #7DD3FC` – akcent na tmavém pozadí (eyebrow popisky, linky).

Použití:

- **Pozadí:** základ stránky `bg-snow`, karty a kontejnery `bg-white`, jemné sekce a hlavičky tabulek `bg-snow`, hlavička, patička a hero `bg-night` s `text-white`, vnořené tmavé bloky `bg-deep`.
- **Text:** nadpisy a primární data `text-slate-900`, běžný text `text-slate-600`, meta text `text-slate-500`, na tmavém pozadí `text-white`, `text-white/75`, `text-white/60`.
- **Akcent:** tlačítka `bg-brand-500` s `hover:bg-brand-400`, odkazy `text-brand-600` až `text-brand-700`, focus `focus:ring-brand-600`, dekorativní linka na tmavém pozadí `bg-brand-400` + `text-ice`.
- **Ohraničení:** světlé plochy `border-slate-200` (`divide-slate-200` v tabulkách), tmavé plochy `border-white/10`.
- **Chyby formulářů:** `text-red-600`.

## 3. Typografie a rozestupy

- **Fonty:** nadpisy `font-display` (Archivo), tělo `font-sans` (Inter); oba z Google Fonts v `layout.php`.
- **Rozestupy:** drž 8pt grid (`4`, `8`, `12`, `16`, `24`, `32`), žádné hodnoty na míru jako `-[17px]`.
- **Whitespace:** velkorysé svislé odsazení sekcí (`py-16`, hero `py-24` a víc).
- **Celoobrazovkové sekce:** `min-h-dvh` (ne `min-h-screen`), aby výška odpovídala i mobilním prohlížečům s adresní lištou.
- **Pohyb:** scroll-reveal je vázaný na třídu `.js` na `<html>`; bez JavaScriptu musí být obsah viditelný. Respektuj `prefers-reduced-motion`.

## 4. Komponenty

### Karty

- Základ: `rounded-md border border-slate-200 bg-white p-6 sm:p-8`.
- Tmavá varianta: `rounded-md bg-deep p-6` s `text-white`.

### Tlačítka

Dotyková plocha má na mobilu aspoň 44 px (`min-h-[44px]`).

- Primární: `inline-flex items-center justify-center rounded-md bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-brand-400`.
- Sekundární: `inline-flex items-center rounded-md bg-white border border-slate-300 hover:bg-slate-50 text-slate-900 text-sm font-semibold px-5 py-3 transition-colors`.

### Datové tabulky (výsledky, startovní listina)

- Hlavička: `text-xs font-medium uppercase tracking-wider text-slate-500` s `border-b border-slate-300`.
- Řádky: `border-b border-slate-200 hover:bg-snow transition-colors` (tělo `divide-y divide-slate-200`).
- Zarovnání: jména a oddíly vlevo, čísla (časy, startovní čísla, pořadí) vpravo (`text-right`).
- **Na mobilu se místo široké tabulky vykresluje záznamový seznam** (od `md:` nahoru je tabulka). Obal tabulky má `overflow-x-auto` a `caption` s `sr-only`.

### Formuláře

- Inputy: `mt-2 block w-full rounded-md border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-brand-600 sm:text-sm`.
- Popisky: `block text-sm font-medium leading-6 text-slate-900`.
- Chyby u polí: `text-red-600 text-sm mt-1`.

### Grafické bloky

Chybějící fotografii nahrazuje grafický blok s vrstevnicemi (`app/Views/partials/contours.php`) na tmavém pozadí – nikdy negenerovaná fotka ani prázdné šedé pole.

## 5. Zásady pro šablony

- Všechny stránky se vykreslují přes společný `layout.php`, který drží hlavičku, navigaci, SEO metadata, flash zprávy a patičku a na konci `require` dané view.
- Opakující se prvky zůstávají v `layout.php`; nevytvářej nové abstrakce pro jednorázové použití.
- Obrázky vkládej přes `img_url()` a `img_srcset()` z `config/helpers.php`, s `loading="lazy"` a rozměry kvůli stabilitě layoutu. Hero obrázek má `fetchpriority="high"` a `sizes="100vw"`.
- U flex položek s textem používej `min-w-0`, aby se dlouhý obsah (e-mail, název oddílu) nepřelil mimo kartu.
- Formuláře vkládají CSRF pole přes `csrf_field()` a chrání se honeypot políčkem.
