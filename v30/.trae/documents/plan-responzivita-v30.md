# Plán: audit a dotažení responzivity webu Vodácká 30

## Shrnutí

Projekt [v30](file:///c:/Users/onvin/Documents/WEB/v30/v30) je celý postavený na Tailwind CDN bez vlastního CSS, takže responzivita stojí a padá na správně zvolených breakpointech v šablonách. Prošel jsem všech 13 veřejných šablon + [layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php), administraci a sdílené partialy.

**Celkově je základ dobrý** – mobilní navigace s Alpine.js funguje, `viewport` meta je správně, jsou řešené focus stavy, skip link, `prefers-reduced-motion` i scroll-reveal s fallbackem pro vypnutý JavaScript. Nejde tedy o přepis, ale o **cílené dotažení**.

Zásadní slabina je jedna a opakuje se na třech stránkách: **tabulky**. Pod 720 px se rozpadají do horizontálního scrollu, u kterého uživatel nemá jak poznat, že existuje, a nejdůležitější sloupec („Ztráta") je mimo obrazovku.

Druhá polovina nálezů jsou mobile-first detaily: chybějící `dvh` jednotka, chybějící `srcset` (mobil stahuje 1920px hero), chybějící `autocomplete` u formuláře a několik míst, kde dlouhý nezlomitelný text (e-mail) může rozbít šířku stránky.

## Metodika

Ověřováno staticky nad zdrojovým kódem, protože v tomto prostředí nelze spustit PHP server ani prohlížeč. Ke každému nálezu je uveden konkrétní řádek a **vypočítaná dostupná šířka** kontejneru (kontejner `max-w-7xl` s `px-4` / `sm:px-6` / `lg:px-8`).

Testovací breakpointy, na kterých se nálezy projevují: `320`, `360`, `390` (iPhone 14), `414`, `640` (sm), `768` (md), `1024` (lg), `1280` (xl), `1440`, `1920` + landscape telefon + zoom 200 %.

## Co je v pořádku (neměnit)

- Mobilní navigace v [layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php#L184-L262) – `x-cloak`, `aria-expanded`, `aria-controls`, zavírání kliknutím mimo, aktivní položka.
- Scroll-reveal s pojistkou: třída `.js` se přidává inline v hlavě, takže bez JavaScriptu zůstane obsah viditelný; navíc `window.load` + 1200 ms fallback ([layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php#L110-L120)).
- `prefers-reduced-motion` vypíná hero animace i reveal ([layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php#L168-L173)).
- Obrázky v aspect-ratio kontejnerech nebo `absolute` → prakticky nulový CLS.
- Skip link, `:focus-visible`, `theme-color`, `lang="cs"`.
- Galerie mozaika, album gridy a `grid-cols-1 → sm:2 → lg:4` v patičce jsou nastavené správně.

## Nalezené chyby

### P0 – rozbité / rušivé na mobilu

**P0-1: Tabulky výsledků a propozic přetékají až do 768 px a scroll není vidět**

- [results.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/results.php#L48-L49) – `min-w-[42rem]` = 672 px.
- [propositions.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/propositions.php#L72-L73) – `min-w-[40rem]` = 640 px.
- [start_list.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/start_list.php#L49-L50) – `min-w-[34rem]` = 544 px (těsně se vejde od `sm`, viz níže).

Výpočet dostupné šířky (`sm:px-6` = 24 px na stranu):

| breakpoint | dostupná šířka | výsledky (672) | propozice (640) | startovka (544) |
|---|---|---|---|---|
| 360 px | 328 px | ✗ přetéká o 344 px | ✗ | ✗ |
| 640 px | 592 px | ✗ přetéká o 80 px | ✗ přetéká o 48 px | ✓ |
| 768 px | 720 px | ✓ | ✓ | ✓ |

Na 360px telefonu je sloupec „Ztráta" **úplně mimo obrazovku** a nic nesignalizuje, že se dá posouvat. Řešení: pod `md:` vykreslit tabulku jako „záznamový seznam" (stacked) a tabulku zapnout až od `md:`. Tabulky jsou v `<div class="overflow-x-auto">` bez `tabindex` → v Safari (macOS/iOS) je nelze posunout klávesnicí.

**P0-2: Filtr kategorií ve výsledcích má vždy viditelný horizontální scrollbar**

[results.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/results.php#L15) – `-mb-px flex gap-7 overflow-x-auto`. Kategorií je ~10, řada je širší než i 1280px kontejner, takže se scrollbar vykresluje **i na desktopu** uprostřed designu, a navíc leží na spodní hranici (`-mb-px`), takže vytváří dvojitou linku. Stejný vzorec je v [start_list.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/start_list.php#L20).

Chybí `-mx-4 px-4`, takže poslední položka je při odscrollování nalepená na hranu kontejneru.

**P0-3: `min-h-screen` na `<body>` – problém `100vh` na mobilních prohlížečích**

[layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php#L176) – `min-h-screen flex flex-col`. Na iOS Safari / Chrome pro Android je `100vh` vyšší než skutečně viditelná plocha (adresní lišta), takže patička „odskakuje" a stránka se dá posunout o pár desítek px za obsah. Správně `min-h-dvh` (Tailwind 3.4+, CDN ji má).

**P0-4: Riziko horizontálního přetečení stránky z dlouhých textů v `justify-between` řádcích**

Vzor `<div class="flex items-baseline justify-between gap-4">` s `dt` (label) a `dd` (hodnota) se vyskytuje na:

- [registration_confirmation.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/registration_confirmation.php#L26-L57) – `dd` obsahuje e-mail a „Poznámka pro příjemce".
- [registration.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/registration.php#L149-L162) – startovné.
- [propositions.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/propositions.php#L30-L37) – pořadatelé.
- [contact.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/contact.php#L23-L38) – jméno + telefon.

Flexbox děti mají `min-width: auto`, takže dlouhý nezlomitelný řetězec (e-mail typu `petr.novak@seznam.cz`) **neroztlačí kontejner, ale přeteče ven** a vyvolá horizontální scroll celé stránky. Chybí `min-w-0` / `break-words`. Projevuje se až s reálnými daty – na 320–360 px.

### P1 – zásadní kvalita a mobilní výkon

**P1-1: Mobil stahuje 1920px hero.** [config/images.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/config/images.php#L23) definuje jednu velikost na klíč. Zonerama servíruje libovolný rozměr vzorem `{ID}_{W}x{H}_2.jpg`, takže `srcset`/`sizes` je otázka pár řádků – na telefonu se z 1920×1280 stane ~960×640. Týká se hero, záhlaví stránek, náhledů alb i mozaiky.

**P1-2: Hero nemá `fetchpriority="high"`** a na `<head>` chybí `preconnect` na `eu.zonerama.com`, přitom na Google Fonts `preconnect` je. Největší prvek LCP na mobilu tak startuje později.

**P1-3: Rozpor v breakpointech hlavičky.** Sticky chování se vypíná na `768px` ([layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php#L161-L166)), ale hamburger mizí až na `1280px` ([layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php#L205-L228)). Na tabletu (768–1279 px) tedy uživatel vidí hamburger, ale hlavička se mu při scrollu ztratí. Breakpoint držený na dvou místech + potřetí v JS (`window.innerWidth < 768` na [L188](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php#L184-L193)) → tři zdroje pravdy, které se rozjedou.

**P1-4: Formulář přihlášky nemá atributy pro mobilní klávesnici a autofill.** [registration.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/registration.php#L52-L99) – chybí `autocomplete="organization"` (oddíl), `given-name`, `family-name`, `email`, dále `enterkeyhint`. `inputmode="numeric"` u ročníku narození je správně.

**P1-5: Mozaika v galerii bez `grid-flow-dense`.** [gallery.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/gallery.php#L78) – mřížka `grid-cols-2 auto-rows-[9rem]` s prvním dlaždicí `col-span-2 row-span-2`. Při 9 fotkách sedí přesně, ale [GalleryController](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/GalleryController.php#L56-L59) fotky **filtruje podle `has_img`**, takže při jiném počtu vzniknou v mřížce díry.

**P1-6: Tailwind CDN.** Bez buildu vzniká probliknutí nenastylované stránky (na mobilní síti výrazné), `hover:` stavy zůstávají „zaseknuté" po dotyku (chybí `@media (hover: hover)` guard) a CDN vypisuje varování, že se nemá používat v produkci. Volitelné řešení: standalone Tailwind CLI bez Node.js (jeden binární soubor) → statické CSS.

### P2 – polish

- **P2-1** Eyebrow popisky `text-xs uppercase tracking-[0.3em]` ([page_header.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/page_header.php#L56), [about.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/about.php#L18)) – „Vodácké mistrovství ČR v běhu na lyžích" se na 360 px láme ošklivě; na mobilu zúžit na `tracking-[0.18em]`.
- **P2-2** Mezery mezi bloky: `space-y-16` ([results.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/results.php#L39)) a `py-20` v hero ([home.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/home.php#L16)) jsou na mobilu zbytečně velkorysé.
- **P2-3** CTA tlačítka v hero a na konci stránek – na 320 px se lámou do dvou nerovnoměrných řádků; `w-full sm:w-auto` s `justify-center` působí klidněji.
- **P2-4** Mobilní navigační odkazy mají `py-2.5` (~40 px) – pod doporučených 44 px pro dotyk.
- **P2-5** Sticky iframe s mapou v [propositions.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/propositions.php#L194-L202) (`h-80`) je na dotyku past na scroll; pod `lg:` zvážit jen odkaz „Otevřít mapu".
- **P2-6** `<main id="obsah">` není fokusovatelný → skip link nepřesune fokus. Přidat `tabindex="-1"`.
- **P2-7** Ve webrootu leží `v30.zip` (kompletní zdrojový archiv) a `nahled-fotek.html`. `.htaccess` blokuje jen `app|config|storage` a skryté soubory, takže **oba jsou veřejně dostupné na `http://v30.test/v30.zip`**. Pomocník na výběr fotek je potřeba – přesunout mimo webroot (nebo přidat do blokace), archiv smazat.

## Navrhované změny

### 1. Tabulky → záznamový seznam pod `md:`

Vzor pro [results.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/results.php#L39-L84) (obdobně startovka a propozice). Tabulka se schová pod `md:`, místo ní se vykreslí seznam; obojí z jednoho pole, takže se data neopakují:

```php
<!-- Mobil: záznamový seznam -->
<ul class="mt-6 divide-y divide-slate-200 border-y border-slate-200 md:hidden">
    <?php foreach ($skupina['vysledky'] as $radek): ?>
        <li class="py-4">
            <div class="flex items-baseline justify-between gap-4">
                <span class="flex min-w-0 items-baseline gap-3">
                    <span class="font-display text-base font-black tabular-nums <?= $barvaPorady ?>"><?= e($radek['poradi']) ?></span>
                    <span class="min-w-0 font-semibold text-slate-900"><?= e($radek['jmeno']) ?></span>
                </span>
                <span class="shrink-0 font-display text-base font-bold tabular-nums"><?= e($radek['cas']) ?></span>
            </div>
            <p class="mt-1 pl-8 text-sm text-slate-500">
                <?= e($radek['oddil']) ?> &middot; start <?= e($radek['cislo']) ?> &middot; ztráta <?= e($radek['ztrata']) ?>
            </p>
        </li>
    <?php endforeach; ?>
</ul>

<!-- Tablet a výš: tabulka -->
<div class="mt-6 hidden overflow-x-auto md:block" tabindex="0" role="region"
     aria-label="Výsledky – <?= e($skupina['nazev']) ?>">
    <table class="w-full min-w-[42rem] text-sm"> …
```

### 2. Filtr kategorií bez scrollbaru

Přidat do `<style>` v [layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php#L122-L174):

```css
.no-scrollbar { scrollbar-width: none; -ms-overflow-style: none; }
.no-scrollbar::-webkit-scrollbar { display: none; }
```

a na obě lišty ([results.php#L15](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/results.php#L15), [start_list.php#L20](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/start_list.php#L20)) doplnit `no-scrollbar -mx-4 px-4 sm:mx-0 sm:px-0`.

### 3. Mobilní jednotka a breakpoint hlavičky

- [layout.php#L176](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php#L176): `min-h-screen` → `min-h-dvh`.
- Hlavička: sjednotit na jeden breakpoint (`lg`) – sticky vypínat na `1024px` a hamburger skrývat na `lg:hidden`, navigaci zobrazit na `lg:flex`. JS podmínku nahradit `window.matchMedia('(max-width: 1023px)')`.

### 4. Obrázky – `srcset` a priority

```php
<?php
$heroId  = '510724217';
$heroSet = [];
foreach ([960 => '960x640', 1440 => '1440x960', 1920 => '1920x1280'] as $w => $size) {
    $heroSet[] = 'https://eu.zonerama.com/photos/' . $heroId . '_' . $size . '_2.jpg ' . $w . 'w';
}
?>
<img src="…" srcset="<?= e(implode(', ', $heroSet)) ?>"
     sizes="100vw" fetchpriority="high" decoding="async" …>
```

Vzhledem k tomu, že URL se skládají z ID a rozměru, je čistší rozšířit `config/images.php` na strukturu s ID + poměrem a `img_url()` nechat generovat varianty, než vypisovat URL ručně.

### 5. Prevence přetečení (P0-4)

Globální pojistka do `<style>` v [layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/layout.php#L122-L174):

```css
dd, dt, p, li, h1, h2, h3 { overflow-wrap: break-word; }
```

a u rizikových `dd` (e-mail, poznámka, variabilní symbol) doplnit `min-w-0`.

## Plán po etapách

### Etapa 1 – P0, rozbité na mobilu
1. Záznamové seznamy pro výsledky a propozice, tabulka od `md:`; totéž pro startovku kvůli konzistenci.
2. `tabindex="0" role="region" aria-label` na scroll kontejnery tabulek.
3. `no-scrollbar` + okrajové odsazení u obou filtrů.
4. `min-h-dvh` na `<body>`.
5. `min-w-0` + `overflow-wrap` u `justify-between` řádků.

### Etapa 2 – P1, kvalita a výkon
6. `srcset`/`sizes` pro hero, záhlaví a náhledy alb.
7. `fetchpriority="high"` u hero + `preconnect` na `eu.zonerama.com`.
8. Sjednocení breakpointu hlavičky na `lg` + `matchMedia`.
9. `autocomplete`/`enterkeyhint` ve formuláři přihlášky.
10. `grid-flow-dense` v mozaice galerie.

### Etapa 3 – P2 polish
11. Tracking eyebrow popisků, mezery sekcí, CTA tlačítka, výška odkazů v mobilním menu, `tabindex="-1"` na `main`.
12. Přesunout `nahled-fotek.html` mimo webroot, smazat `v30.zip`.
13. Volitelně: build Tailwindu přes standalone CLI.

## Ověření

Bez prohlížeče v tomto prostředí je potřeba ověřit ručně v DevTools (device toolbar) na šířkách **320 / 360 / 390 / 414 / 640 / 768 / 1024 / 1280 / 1920**, v landscape telefonu a při zoomu 200 %.

Kontrolní seznam pro každou stránku (`/`, `/zavod`, `/propozice`, `/prihlaska`, `/startovni-listina`, `/vysledky`, `/galerie`, `/kontakt`, 404, 405):

- [ ] Nic nevyvolává horizontální scroll (`document.documentElement.scrollWidth <= window.innerWidth`).
- [ ] Hlavička: sticky na mobilu, navigace dostupná na tabletu.
- [ ] Tabulky: čitelný zápis pod `md:`, tabulka nad `md:`.
- [ ] Filtr kategorií: bez viditelného scrollbaru, poslední položka nelepí na hranu.
- [ ] Ankory kotvy (kategorie, skip link) necouvnou pod hlavičku.
- [ ] Formulář přihlášky: klávesnice numerická u ročníku, autofill funguje, chyby se vejdou na řádek.
- [ ] Dlouhá data (e-mail, název oddílu) nerozbijí layout – testovací vložení přes 40 znaků.

Volitelné a doporučené: založit jednoduchý Playwright skript se seznamem URL a viewportů, který kontroluje `scrollWidth` a ukládá screenshoty. Zachytí přesně tuto třídu regresí i do budoucna.
