# Audit webu Vodácká 30

Podklad vznikl podle zadání v `TODO.md` (8 auditních promptů). Všechny nálezy vycházejí z reálného kódu a z reálných HTTP odpovědí, ne z obecných pouček.

## Jak se auditoval

- Projekt root: `c:\Users\onvin\Documents\WEB\v30\v30`.
- Web byl spuštěný lokálně (`php -S`, PHP 8.2.12, XAMPP) nad front controllerem `web/index.php`.
- Všech osm veřejných rout (`/`, `/zavod`, `/propozice`, `/prihlaska`, `/startovni-listina`, `/vysledky`, `/galerie`, `/kontakt`) vrací HTTP 200 a v odpovědi nejsou PHP warningy ani notices. `/neexistuje` správně vrací 404.
- **Browser testování nebylo k dispozici.** V prostředí není nástroj pro ovládání prohlížeče a Tailwind i Alpine se načítají z CDN, takže z vypsaného HTML nejde spolehlivě posoudit výsledný vzhled. Vizuální nálezy proto vycházejí z markupu a z tříd, u rizikových míst je to uvedené. Nálezy týkající se dat, textu, chování a přístupnosti jsou ověřené přímo.

## Kontext, na kterém stojí většina nálezů

`config/zavod.php` je zdroj pravdy o dvou ročnících:

- **27. ročník – 16. ledna 2027** – potvrzený termín dalšího ročníku, propozice k němu ještě nejsou.
- **26. ročník – 17. ledna 2026** – poslední odjetý ročník, ze kterého web zobrazuje výsledky, startovní listinu, kategorie, ceny a program.

Web je tedy postavený na datech z ročníku, který už proběhl, a zároveň už sbírá přihlášky a slibuje další ročník. Většina problémů níže jsou důsledky toho, že se poslední ročník a nejbližší ročník na stránkách míchají bez jasného označení.

---

## 1. Celkový audit webu napříč všemi stránkami

### Zjištění podle dopadu

**1. Homepage tvrdí, že závod se jede 17. ledna 2026.**
- Kde: `app/Controllers/HomeController.php:13` (`'datum' => '17. ledna 2026'`), vykresleno v hero na `app/Views/home.php:31`.
- Proč to vadí: datum je v minulosti, ale hned pod ním je tlačítko „Registrovat do závodu". Návštěvník nemá jak zjistit, že se přihlašuje na 27. ročník 2027. Působí to jako neaktualizovaný web — přesně ten dojem, kterému se má web vyhnout.
- Změna: v hero vypisovat `zavod()['rocnik']['datum']` (16. ledna 2027) a vedle něj ročník; archivní datum 2026 používat jen u archivních sekcí.

**2. Strukturovaná data pro vyhledávače posílají neplatný termín.**
- Kde: `config/seo.php:31-32` (`'datum_zacatku' => '2026-01-17'`), použito v `app/Helpers/Seo.php:188-203` jako `SportsEvent`.
- Proč to vadí: Google dostane od závodu `startDate` v minulosti a stav `EventScheduled`. Sportovní akce se pak může zobrazovat jako proběhlá nebo se termín vůbec nezobrazí ve výsledcích.
- Změna: doplnit do `config/zavod.php` ISO termíny (`datum_iso`) a v `Seo::structuredData()` je brát odtud, ne z druhé kopie v `seo.php`.

**3. Z homepage se nedá zjistit, kdy je nejbližší závod.**
- Kde: `app/Views/home.php` — datum 2027 se na homepage nevyskytuje nikde. Je jen na `/zavod` (`app/Views/about.php:28`) a v propozicích.
- Proč to vadí: homepage je hlavní vstup. Kdo přistane z odkazu nebo z vyhledávače, nedozví se termín a nemá důvod se posunout dál než k archivním výsledkům.
- Změna: do hero dát výrazně „27. ročník · 16. ledna 2027" a v závěrečném CTA termín zopakovat.

**4. „10 vypsaných kategorií" je fakticky špatně.**
- Kde: `app/Controllers/HomeController.php:34`. V `config/zavod.php:51-63` je kategorií jedenáct (včetně `children-pro-7-14` a `veterans`).
- Proč to vadí: číslo si návštěvník porovná s propozicemi a najde nesoulad. U závodu, kde se platí startovné, to je zbytečná ztráta důvěry.
- Změna: počítat `count(zavod()['kategorie'])`, ne psát číslo ručně.

**5. Po odeslání přihlášky se návštěvník nedozví, co bude dál.**
- Kde: `app/Views/registration_confirmation.php` celá stránka.
- Proč to vadí: potvrzení obsahuje rekapitulaci a platební instrukce, ale neříká, jestli přijde e-mail, jestli se má člověk ozvat, ani kdy pořadatel platbu ověří. U závodu bez online platby je to nejdůležitější věta.
- Změna: doplnit krátký blok „Co bude dál" (pořadatel ověří platbu, stav startovní listiny, kontakt při neshodě).

**6. Chybové stavy formuláře nejsou svázané s poli.**
- Kde: `app/Views/registration.php:46-48, 56-58, 67-69, 75-77, 87-89, 96-98, 120-122` — chyba je jen `p` pod polem, bez `aria-invalid` a `aria-describedby`. Formulář má navíc `novalidate` (řádek 24).
- Proč to vadí: odečítač obrazovky chybu nepřečte v kontextu pole, klávesnicí se k ní nedostanete. Po odeslání se navíc nezobrazí žádný souhrn a stránka se neposune k formuláři.
- Změna: přidat `aria-invalid`, `aria-describedby`, `id` u chybových textů, souhrn chyb nad formulářem se `role="alert"` a fokus na první chybné pole.

**7. Vyplněné hodnoty přežívají, i když už nemají.**
- Kde: `config/helpers.php:78-83` (`old_get()` čte `$_SESSION['_flash']['old']`), nikde se ale nevolá `flash_get('old')`, takže se klíč nikdy nemaže.
- Proč to vadí: kdo jednou odešle neplatný formulář, najde ho při další návštěvě `/prihlaska` pořád předvyplněný — i když mezitím odešel z webu a vrátil se. Působí to jako „web si pamatuje moje data".
- Změna: `old` hodnoty načíst jednou za vykreslení a hned je smazat (např. nový helper, který je přečte a vyčistí).

**8. Propozice zopakují „ročník 2026" v pěti samostatných blocích.**
- Kde: `app/Views/propositions.php:3` (eyebrow), `:19-26` (horní box), `:69`, `:199`, `:248-254`.
- Proč to vadí: čtenář si pětkrát přečte totéž a přestane vnímat, co je vlastně platné. Stránka působí nejistě, jako by se sama omlouvala.
- Změna: jeden jasný disclaimer v horním bloku, níže už ročník nezmiňovat a spoléhat na nadpisy sekcí.

**9. Propozice nekončí akcí.**
- Kde: `app/Views/propositions.php:242-263` — poslední blok je tmavá sekce s odkazem na archivní propozice 2026.
- Proč to vadí: člověk, který se rozhodl jet, na konci stránky dostane odkaz mimo web místo přihlášky.
- Změna: zakončit CTA na `/prihlaska` a archivní odkaz zmenšit a posunout nad něj.

**10. Na mobilu je v seznamu kategorií nejvýraznější cena.**
- Kde: `app/Views/propositions.php:72-87` — název kategorie je `font-semibold`, cena `font-display font-bold`. Značková barva a váha táhnou oko k ceně.
- Proč to vadí: jde proti záměru webu nepůsobit jako ceník a netlačit na ceny.
- Změna: název nechat dominantní, cenu ztlumit (menší, světlejší, ideálně přesunout do druhého řádku k trati a stylu).

**11. Filtry a kotvy nemají signál, že se dají posouvat.**
- Kde: `app/Views/results.php:15-25` (filtr kategorií) a `app/Views/start_list.php:20-28` (kotvy na kategorie), obojí `overflow-x-auto no-scrollbar`.
- Proč to vadí: poslední kategorie na mobilu zmizí za okrajem a nic nenapoví, že je tam ještě něco. Filtr tak vypadá, jako by měl méně možností.
- Změna: na pravém okraji jemný přechod (gradient) nebo `mask-image`, a na konci seznamu odsazení, aby poslední položka nebyla nalepená na okraj.

**12. Odkazy v patičce jsou na dotyk malé.**
- Kde: `app/Views/layout.php:322-352` — seznamy odkazů mají `space-y-2.5` a žádnou minimální výšku; výška řádku je kolem 20 px.
- Proč to vadí: na mobilu se na ně nedá spolehlivě trefit; projekt si jinde 44px minimum drží (mobilní menu v `layout.php:267`).
- Změna: dát odkazům v patičce `min-h-[44px]` (nebo `py-2`) a zachovat stávající vzhled.

**13. Sekce „Novinky" na homepage je mrtvá.**
- Kde: `app/Views/home.php:154-196` čeká na `$novinky`, ale ani `HomeController`, ani žádný jiný kontroler ji nedodá. `app/Models/PostRepository.php` není nikde použitý.
- Proč to vadí: buď jde o nedokončenou funkci, nebo o kód, který se při dalším zásahu do homepage musí znovu luštit. Novinky přitom bývají důvod, proč se lidé vrací.
- Změna: rozhodnout — buď napojit `PostRepository` a sekci rozjet, nebo blok z `home.php` odstranit.

**14. Na kontaktní stránce není e-mail pořadatele závodu.**
- Kde: `app/Views/contact.php:23-38` vypisuje jen jméno a telefon; e-mail má jen chata Bedřichovka (`layout.php:349`, `contact.php:93`).
- Proč to vadí: kdo má dotaz k přihlášce nebo platbě, musí telefonovat nebo jít na cizí web.
- Změna: doplnit do `config/zavod.php` e-mail pořadatele a vypsat ho u kontaktu i v patičce.

### Plán „co opravit první"

1. Termín a ročník na homepage (zjištění 1 + 3) — největší dopad, nejmenší práce.
2. ISO termín pro strukturovaná data (zjištění 2).
3. Počet kategorií a další ručně psaná čísla (zjištění 4).
4. Chybové stavy formuláře (zjištění 6) a mazání `old` hodnot (zjištění 7).
5. Cena vs. název kategorie na mobilu (zjištění 10) a scroll signál u filtrů (zjištění 11).
6. Opakovaný disclaimer v propozicích a uzavírací CTA (zjištění 8 + 9).

---

## 2. Hloubkový audit homepage

### Jak homepage působí jako celek

Kompozičně je homepage slušná: tmavý hero s fotkou, střídání `snow` a `white` sekcí, editorial sazba s `font-display`, odsazení drží rytmus, CTA jsou umístěná rozumně. Problém není vzhled, ale **sdělení a aktuálnost**. Homepage vypráví o ročníku, který proběhl, a neřekne, kdy je ten příští. Návštěvník se dozví, že závod byl 17. ledna 2026, a teprve v posledním tmavém bloku najde „Přihlaste se na další ročník" bez data. To je jádro problému.

### Deset doporučení

**1. Do hero doplnit ročník a termín dalšího závodu.**
- Kde: `app/Views/home.php:28-37` (`dl` s Termínem a Místem), zdroj dat `HomeController.php:10-15`.
- Typ: obsah + layout.
- Přínos: návštěvník hned ví, jestli stíhá.

**2. Změnit druhé hero CTA.**
- Kde: `app/Views/home.php:44-47` — „Startovní listina" vede na archivní listinu 26. ročníku.
- Typ: CTA.
- Přínos: druhá cesta z hero povede na něco, co se týká dalšího ročníku (např. „Jak se přihlásit" nebo „Propozice a trať").

**3. Opravit fakt „Styl: Klasicky i bruslením".**
- Kde: `app/Controllers/HomeController.php:25` vs. rozpis kategorií v `config/zavod.php` (Muži KLASICI zvlášť, hlavní trať „volně").
- Typ: text/fakta.
- Přínos: homepage nebude v rozporu s propozicemi.

**4. Přejmenovat sekci „Ročník 2026 v číslech" na archivní.**
- Kde: `app/Views/home.php:106`.
- Typ: text/hierarchie.
- Přínos: zmizí dojem, že jde o aktuální ročník.

**5. Sladit obsah sekce „Program závodního dne" s aktuálností.**
- Kde: `app/Views/home.php:127-138` a data `HomeController.php:38-44`.
- Typ: text.
- Přínos: buď doplnit větu „Program dalšího ročníku zveřejníme v propozicích", nebo blok označit jako loňský.

**6. Doplnit do programu večerní část.**
- Kde: `app/Views/home.php:141-148` — homepage má 5 bodů, propozice 12 (večeře 18:00, promítání 19:00).
- Typ: obsah.
- Přínos: pro vodáky je společenský večer podstatná část závodu; teď na homepage chybí.

**7. Vyřešit mrtvou sekci Novinky.**
- Kde: `app/Views/home.php:154-196`, `app/Models/PostRepository.php`.
- Typ: obsah/struktura.
- Přínos: homepage získá důvod k opakovaným návštěvám, nebo se zbaví kódu, který nic nedělá.

**8. Zjednodušit hlavičku sekce výsledků.**
- Kde: `app/Views/home.php:205-208` — eyebrow „Výsledky 2026", nadpis „Nejrychlejší muži PRO", pod seznamem ještě „Kategorie Muži PRO – volně · 26. ročník, 17. ledna 2026".
- Typ: text/hierarchie.
- Přínos: totéž se řekne třikrát; stačí jednou.

**9. Doplnit do závěrečného CTA termín a co člověk potřebuje.**
- Kde: `app/Views/home.php:244-249`.
- Typ: obsah/CTA.
- Přínos: přihláška je formulář na čtyři pole — když se to řekne, lidé ji podají.

**10. Sjednotit vertikální odsazení sekcí.**
- Kde: `app/Views/home.php:104` (`py-14 sm:py-20`) vs. ostatní sekce (`py-16 sm:py-24`).
- Typ: layout.
- Přínos: rytmus stránky přestane na jednom místě škobrtnout.

### Silná verze homepage

**Co má zůstat:** tmavý hero s fotkou a vrstevnicemi, editorial typografie, sekce „Trať a místo", CTA na přihlášku v hero i na konci, ukázka výsledků.

**Co zjednodušit:** sloučit „Trať a místo" a „Den závodu" do jednoho bloku o dvou sloupcích (vlevo trať, vpravo program), aby stránka neměla tři podobné výčty za sebou. Sekci „V číslech" zkrátit na tři metriky.

**Co zvýraznit:** termín dalšího ročníku (16. ledna 2027) v hero i v závěrečném CTA a fakt, že přihláška je otevřená. To je jediná věc, kterou má homepage prodávat.

---

## 3. Audit propozic

### Hlavní problémy podle důležitosti

**1. Stránka se pětkrát omlouvá, že jde o archiv.**
- Kde: `app/Views/propositions.php:3, 19-26, 69, 199, 253`.
- Úprava: nechat jednu jasnou větu v horním bloku („Níže uvedené údaje popisují 26. ročník z 17. ledna 2026. Termín dalšího ročníku je 16. ledna 2027, propozice k němu zveřejníme.") a všechny ostatní zmínky vypustit.

**2. Kategorie jsou utopené za programem.**
- Kde: program `:41-54`, kategorie až `:64-113`.
- Úprava: prohodit — nejdřív „co pojedu" (kategorie a tratě), pak až harmonogram dne.

**3. Důležitý čas se ztrácí v dvanácti řádcích.**
- Kde: `app/Views/propositions.php:46-53`, data `app/Controllers/RaceController.php:14-27`.
- Úprava: rozdělit program na tři skupiny — děti, dospělí, večer — nebo tučně zvýraznit start hlavního závodu ve 12:00.

**4. Na mobilu je nejvýraznější cena, ne kategorie.**
- Kde: `app/Views/propositions.php:72-87`.
- Úprava: název nadtučnit/nechat dominantní, cenu zmenšit a přesunout na druhý řádek.

**5. Stránka končí archivem, ne přihláškou.**
- Kde: `app/Views/propositions.php:242-263`.
- Úprava: přidat CTA blok „Přihlásit se na 27. ročník" s termínem a odkazem na `/prihlaska`; archivní odkaz na propozice 2026 zmenšit na řádek odkazu na konci sekce Mapy.

**6. Mapa nemá na mobilu žádnou náhradu kromě odkazu.**
- Kde: `app/Views/propositions.php:214-234` — iframe je `hidden ... lg:block`, na mobilu zůstane jen textový odkaz „Otevřít mapu".
- Úprava: přidat statický náhled trati (obrázek) i pro mobil, aby člověk před klepnutím viděl, kam jede.

**7. Startovné je v odrážkách vedle platebních instrukcí a čte se jako dvě nesouvisející věci.**
- Kde: `app/Views/propositions.php:120-154`.
- Úprava: sloučit do jednoho bloku „Startovné a platba" — tabulka cen a pod ní tři věty o platbě.

**8. Pořadatelé jsou v levém sloupci vedle programu a opticky se ztrácejí.**
- Kde: `app/Views/propositions.php:29-37`.
- Úprava: přesunout pořadatele níž, k sekci Trať/bezpečnost/ubytování, kde dávají smysl.

**9. V obecném bloku „Trať" chybí zmínka o tom, co se děje, když není sníh.**
- Kde: `app/Views/propositions.php:161-167` a `:172` („za běžných zimních podmínek").
- Úprava: doplnit konkrétní větu (náhradní termín / zrušení), nebo formulaci zjemnit tak, aby nebyla vágní.

**10. Bezpečnost a ubytování jsou tři stejně vypadající sloupce bez rozdílu důležitosti.**
- Kde: `app/Views/propositions.php:159-186`.
- Úprava: nechat tři bloky, ale bezpečnost zvýraznit (limit trati 17:00 je pro závodníka zásadní).

### Doporučená nová osnova stránky

1. Hlavička stránky + **jeden** jasný blok o ročníku (co je platné dnes, co je archiv).
2. **Kategorie a tratě** (co pojedu).
3. **Startovné a platba** (kolik a jak) s CTA na přihlášku.
4. **Časový program** dne, rozdělený na děti / dospělé / večer.
5. **Trať, bezpečnost, ubytování** (bezpečnost jako první).
6. **Mapy** tratí + řádek s odkazem na archivní propozice 2026.
7. **Uzavírací CTA** na přihlášku.

Čte se to v pořadí, v jakém se člověk rozhoduje: chci jet → co pojedu → kolik to stojí → kdy přijet → kde to je → přihlásit se.

---

## 4. Audit přihlášky a celé registrační cesty

Přihláška je jednoduchý formulář o šesti polích. Z pohledu nového návštěvníka: vyberu kategorii, oddíl, jméno, ročník narození, e-mail, odsouhlasím zpracování údajů, odešlu. Po odeslání dostanu rekapitulaci a platební pokyny. To je srozumitelné.

### Kritické problémy

**K1. Platební instrukce jsou neproveditelné.**
- Kde: `config/zavod.php:38` (`'ucet' => ''`), vykresleno v `app/Views/registration_confirmation.php:72-78`.
- Jak se to projeví: potvrzení vyzve k převodu, dá variabilní symbol a částku, ale číslo účtu chybí. Stránka to sama přiznává („Číslo účtu pořadatel zatím nezveřejnil") — je tedy čestná, ale pro uživatele je to slepá ulička.
- Návrh: dokud účet není, platební blok nezobrazovat a místo něj napsat, že pokyny přijdou e-mailem / telefonicky. Až účet bude, doplnit ho do configu.
- Repro: vyplň a odešli `/prihlaska` na testovacích datech → na `/prihlaska/potvrzeni` uvidíš „Číslo účtu pořadatel zatím nezveřejnil".

**K2. Chyby formuláře nejsou přístupné.**
- Kde: `app/Views/registration.php:24` (`novalidate`), chyby na `:46, 56, 67, 75, 87, 96, 120`.
- Jak se to projeví: chyba je jen červený text pod polem. Odečítač obrazovky ji neohlásí, bez fokusu se k ní nedostaneš, a protože formulář nemá `novalidate` vypnuté nativní hlášky, dostaneš kombinaci obojího.
- Návrh: `aria-invalid` + `aria-describedby` u každého pole, `role="alert"` souhrn nad formulářem, po odeslaní fokus na první chybné pole.
- Repro: odešli formulář bez vyplnění → zobrazí se červené texty, ale žádná hláška nad formulářem.

**K3. Zastaralé předvyplnění.**
- Kde: `config/helpers.php:78-83`.
- Jak se to projeví: hodnoty z neúspěšného odeslání zůstanou v session napořád.
- Návrh: přečíst a vyčistit najednou.
- Repro: odešli neplatný formulář → přejdi na `/` → zpět na `/prihlaska` → pole jsou pořád předvyplněná.

### Zbytečné tření

**T1. Registrace je na 2027, ale ceny a kategorie jsou z 2026.**
- Kde: `app/Views/registration.php:3` (eyebrow „Další ročník · 2027") vs. `app/Controllers/RegistrationController.php:16-22` (ceny z configu ročníku 2026) a sidebar `registration.php:152-166`.
- Návrh: jedna věta u formuláře: „Ceny a kategorie vycházejí z posledního ročníku; propozice k 27. ročníku zveřejníme."

**T2. U vybrané kategorie není vidět cena, dokud se neodešle.**
- Kde: `app/Views/registration.php:38-45` — `option` nese jen název a trať.
- Návrh: doplnit do popisku možnosti i cenu, nebo pod select vypsat cenu živě přes Alpine (Alpine už je na stránce).

**T3. „Oddíl / tým" je povinný pro všechny.**
- Kde: `app/Views/registration.php:54`, validace `RegistrationController.php:61-65`.
- Návrh: povolit „—"/„bez oddílu", nebo pole označit jako nepovinné pro jednotlivce.

**T4. Ročník narození se nekontroluje proti kategorii.**
- Kde: `RegistrationController.php:79-82` kontroluje jen rozsah 1900–aktuální rok.
- Návrh: `config/zavod.php` má `vek_od`/`vek_do` u dětských kategorií. Dát aspoň varování, když ročník narození nesedí do vypsaného rozsahu.

### Kosmetické nedostatky

**Ko1. Rozdílný slovník pro totéž.** Hero „Registrovat do závodu" (`home.php:43`), navigace „Přihlásit se" (`layout.php:241`), propozice „Přejít na přihlášku" (`propositions.php:148`), potvrzení „Podat přihlášku" (`home.php:253`). Sjednotit na jedno.

**Ko2. Chybí označení povinných polí.** Vše je povinné kromě poznámky, ale čte se to jen z hvězdičkového `required` v HTML.

**Ko3. Textarea poznámky nemá počítadlo** (limit 500 znaků v `registration.php:107`).

---

## 5. Mobilní audit

Metoda: posuzovaný markup a responzivní třídy (bez reálného prohlížeče — viz úvod).

### Problémy po stránkách

**Homepage**
- Hero druhé CTA „Startovní listina" vede na archiv → *hierarchie*.
- Sticky hlavička se při scrollu dolů schová (`layout.php:205-212`) včetně CTA „Přihlásit se". Na dlouhé stránce je pak jediná cesta k přihlášce až dole → *hierarchie*.

**Přihláška**
- Chybové stavy bez `aria` a bez souhrnu → *funkční problém* (pro asistivní technologie).
- Sticky sidebar je vypnutý pod `lg` (`registration.php:138`) — správně.

**Propozice**
- Seznam kategorií: nejvýraznější je cena → *čitelnost + hierarchie*.
- Program má 12 řádků bez seskupení → *hierarchie*.
- Mapy: iframe skrytý pod `lg`, zůstává jen textový odkaz → *funkční* (chybí vizuální náhrada).

**Startovní listina**
- Lišta s 10 kotvami, `overflow-x-auto no-scrollbar` (`start_list.php:20`) → *layout* (není poznat, že se posouvá).
- Tabulka má `min-w-[34rem]` a je skrytá pod `md`, nahrazená seznamem → v pořádku.

**Výsledky**
- Filtr s 11 kategoriemi ve stejné liště → *layout*.
- Tabulka `min-w-[42rem]` skrytá pod `md`, nahrazená seznamem → v pořádku.

**Galerie**
- Mozaika `auto-rows-[9rem]` s prvním prvkem `col-span-2 row-span-2` (`gallery.php:80, 83`) → na mobilu zabírá první fotka polovinu šířky přes dva řádky, což je v pořádku, ale `gap-3` je na dotykový web těsné → *layout* (kosmetické).

**Kontakt, 404, 405**
- Bez nálezů kromě patičky (níže).

**Patička (všechny stránky)**
- Odkazy v seznamech mají výšku kolem 20 px → *čitelnost + funkční* (dotykový cíl pod doporučených 44 px).

### Pět úprav s největším dopadem

1. Scroll signál u filtru výsledků a kotev startovní listiny (gradient na okraji).
2. V propozicích ztlumit cenu a zvýraznit název kategorie.
3. Dotykové cíle v patičce na 44 px.
4. Změnit druhé hero CTA z archivní startovní listiny na něco aktuálního.
5. Doplnit k chybám formuláře `aria` a souhrn nad formulářem.

---

## 6. Obsahový audit

### Faktické nejasnosti

- **Homepage: „17. ledna 2026" bez označení, že jde o loňský ročník** (`HomeController.php:13`). Návrh: říct, že jde o poslední ročník, a doplnit termín 2027.
- **Homepage: „10 vypsaných kategorií"** (`HomeController.php:34`) — ve skutečnosti 11 (`config/zavod.php`). Návrh: dopočítat.
- **Homepage: „Styl: Klasicky i bruslením"** (`HomeController.php:25`) je v rozporu s rozpisem kategorií, kde jsou klasici samostatná kategorie. Návrh: formulovat jako „volně i klasicky, podle kategorie".
- **Kontakt: chybí e-mail pořadatele** (`contact.php:23-38`). Návrh: doplnit.
- **Propozice: „Závod se jede za běžných zimních podmínek"** (`propositions.php:172`) — neříká nic. Návrh: doplnit, co se stane při nedostatku sněhu, nebo větu vypustit.

### Tónové problémy

Většina textů je civilní a dobrá — „Běžky místo pádla" (`about.php:20`), „Tahle stránka neexistuje nebo se přesunula." (`404.php:14`), „Kdyby něco nesedělo, napište pořadateli." (`registration_confirmation.php:60`). Tohle držet.

Výjimky:

- **„Vodácké mistrovství ČR v běhu na lyžích"** (`about.php:18`). Zní jako oficiální titul. Pokud ho závod nemá doložený, působí to nafouknutě — a u závodu, kde se platí startovné, je důvěryhodnost všechno. Návrh: buď doložit, nebo zjemnit na popis (např. „závod vodáků na běžkách").
- **„Podmínky posledního zveřejněného ročníku zůstávají dostupné na původním webu závodu."** (`propositions.php:253`). Věta je formální a opakuje, co už stránka řekla. Návrh: zkrátit na „Kompletní propozice 2026" + odkaz.
- **„Další kontakty najdete na webu pořadatelského oddílu."** (`contact.php:41`). Zbytečná věta bez informace. Návrh: vypustit, tlačítko mluví samo.

### Problémy informačního pořadí

- **Propozice:** kategorie jsou až za programem a pořadateli (`propositions.php:41-113`). Návrh: kategorie nahoru — viz osnova v sekci 3.
- **Potvrzení přihlášky:** nejdůležitější (jak zaplatit a co bude dál) je v pravém sloupci vedle rekapitulace (`registration_confirmation.php:65-110`), na mobilu až pod ní. Návrh: na mobilu ji posunout nahoru.
- **Homepage:** výsledky jsou před závěrečným CTA (`home.php:199-235`) — pořadí je v pořádku.

---

## 7. Audit technického dluhu

**1. Obsah závodu je rozdělený mezi kontroler a config.**
- Soubory: `app/Controllers/HomeController.php` (hero, trať, program, podium, čísla), `config/zavod.php`.
- Problém: config vznikl proto, aby se údaje nerozejížděly (viz komentář v `config/zavod.php:3-8`), jenže homepage si polovinu dat drží vlastní. Právě tak vznikla chyba „10 kategorií" a rozpor ve stylu trati.
- Refaktor: přesunout texty a fakta homepage do `config/zavod.php` (nebo nového `config/obsah.php`) a v kontroleru je jen poskládat.
- Velikost: střední.

**2. Termíny a místo jsou na dvou místech.**
- Soubory: `config/seo.php:30-32` vs. `config/zavod.php:13-33`.
- Problém: dvě kopie téhož se už rozešly (SEO posílá 2026, web slibuje 2027).
- Refaktor: `seo.php` ať bere místo a termín z `zavod()`.
- Velikost: malý.

**3. Responzivní tabulky jsou v markupu dvakrát.**
- Soubory: `app/Views/results.php:61-109`, `app/Views/start_list.php:50-85`, `app/Views/propositions.php:72-113`.
- Problém: každý řádek je v DOM dvakrát (mobilní seznam + tabulka). U výsledků to znamená ~198 KB HTML a dvojnásobnou práci při každé úpravě sloupce.
- Refaktor: jeden partial, který dostane data a vykreslí obě varianty, nebo jediný markup zvládnutý přes CSS.
- Velikost: větší.

**4. Mrtvý kód.**
- Soubory: `app/Views/home.php:154-196` (`$novinky`), `app/Models/PostRepository.php`, `app/Models/OddilRepository.php`, `app/Helpers/RateLimit.php`, `app/Helpers/Log.php`.
- Problém: nic z toho veřejný web nevolá. Buď jde o nedokončenou práci z nesloučených větví, nebo o slepou uličku.
- Refaktor: buď napojit, nebo odstranit; rozhodnout podle plánu sloučení větví.
- Velikost: malý.

**5. `old` hodnoty se nikdy nemažou.**
- Soubor: `config/helpers.php:78-83`.
- Problém: viz audit 4 (K3).
- Refaktor: helper, který hodnoty přečte a vyčistí.
- Velikost: malý.

**6. `img_srcset()` staví varianty z názvu souboru.**
- Soubor: `config/helpers.php:155-175`.
- Problém: funkce předpokládá vzor `{klíč}-{šířka}.jpg` a neověřuje, že soubor existuje. Přejmenování fotky tiše vyrobí `srcset` s odkazy na neexistující soubory.
- Refaktor: mapovat varianty explicitně v `config/images.php`, nebo existenci ověřovat.
- Velikost: malý.

**7. Chybí oddělení prostředí.**
- Soubor: `config/seo.php:11` (`'url' => 'http://v30.test'`).
- Problém: produkční adresa, lokální adresa i datumy akce jsou v jednom souboru bez rozlišení prostředí.
- Refaktor: konfigurace podle prostředí (např. `config/seo.local.php`).
- Velikost: střední.

**8. Strukturovaná data jsou tři samostatné bloky.**
- Soubor: `app/Helpers/Seo.php:164-206`.
- Problém: tři `<script type="application/ld+json">` místo jednoho s `@graph`; u `SportsEvent` chybí `url`, `description` a `image`.
- Refaktor: sloučit do `@graph` a doplnit pole.
- Velikost: malý.

**9. Chybná hlavička `Allow` u 405.**
- Soubor: `web/index.php:126`.
- Problém: `implode(', ', array_keys($routes))` vypíše `GET, POST` bez ohledu na to, které metody daná cesta skutečně podporuje.
- Refaktor: sestavit seznam metod, které pro danou cestu v mapě existují.
- Velikost: malý.

**10. Veřejné stránky nemají žádné testy.**
- Soubory: celý `app/`.
- Problém: chyby jako „10 kategorií" nebo rozjeté datumy se odhalí jen ručně.
- Refaktor: několik testů nad HTTP odpověďmi (obsah stránky, kódy, že se čísla berou z configu). Nasadit test-first u dalších úprav.
- Velikost: střední.

### Pořadí refaktorů podle návratnosti

1. Termíny a místo z jednoho zdroje (2) — přímo opraví viditelnou chybu.
2. Obsah homepage do configu (1) — zabrání dalším rozejitým číslům.
3. `old` hodnoty (5) — malé a hned viditelné.
4. Mrtvý kód (4) — uklidí, než se na tom začne stavět.
5. `Allow` hlavička (9) a strukturovaná data (8) — levné, zlepší SEO a korektnost.
6. Dvojité tabulky (3) — větší práce, ale zásadně zjednoduší výsledky a startovní listinu.
7. Prostředí (7), `img_srcset` (6), testy (10).

---

## 8. Akční backlog

### Malé úkoly

**M1. Termín dalšího ročníku do hero homepage.**
- Proč: homepage teď tvrdí, že závod byl 17. ledna 2026.
- Kde: `app/Controllers/HomeController.php`, `app/Views/home.php`.
- Hotovo, když: hero na `/` zobrazuje 27. ročník a 16. ledna 2027 z `config/zavod.php`.

**M2. ISO termín pro strukturovaná data.**
- Proč: `SportsEvent` pro vyhledávače má datum v minulosti.
- Kde: `config/zavod.php`, `config/seo.php`, `app/Helpers/Seo.php`.
- Hotovo, když: JSON-LD na `/` obsahuje `startDate` 2027-01-16 a bere ho z configu závodu.

**M3. Počet kategorií dopočítat.**
- Proč: homepage uvádí 10, config má 11.
- Kde: `app/Controllers/HomeController.php`.
- Hotovo, když: číslo na homepage odpovídá `count(zavod()['kategorie'])`.

**M4. Mazání `old` hodnot formuláře.**
- Proč: předvyplněná data přežívají mezi návštěvami.
- Kde: `config/helpers.php`, `app/Controllers/RegistrationController.php`.
- Hotovo, když: po opuštění a návratu na `/prihlaska` je formulář prázdný.

**M5. `Allow` hlavička u 405.**
- Proč: odpověď tvrdí nesprávné metody.
- Kde: `web/index.php`.
- Hotovo, když: `POST /` vrátí `Allow` obsahující jen metody platné pro danou cestu.

**M6. Dotykové cíle v patičce.**
- Proč: mobilní odkazy jsou pod 44 px.
- Kde: `app/Views/layout.php`.
- Hotovo, když: každý odkaz v patičce má klikatelnou plochu aspoň 44 px vysokou.

**M7. Scroll signál u filtrů.**
- Proč: poslední kategorie mizí za okrajem bez náznaku posunu.
- Kde: `app/Views/results.php`, `app/Views/start_list.php`.
- Hotovo, když: na kraji scrollovatelné lišty je viditelný přechod.

**M8. Ztlumit cenu v mobilním seznamu kategorií.**
- Proč: cena je nejvýraznější prvek řádku.
- Kde: `app/Views/propositions.php`.
- Hotovo, když: dominantní je název kategorie, cena je sekundární.

**M9. Chybové stavy formuláře a souhrn chyb.**
- Proč: chyby nejsou přístupné ani vidět v celku.
- Kde: `app/Views/registration.php`.
- Hotovo, když: pole mají `aria-invalid` a `aria-describedby` a nad formulářem je souhrn s `role="alert"`.

**M10. E-mail pořadatele na kontakt a do patičky.**
- Proč: chybí cesta, jak pořadateli napsat.
- Kde: `config/zavod.php`, `app/Views/contact.php`, `app/Views/layout.php`.
- Hotovo, když: kontaktní stránka vypisuje e-mail pořadatele.

### Střední úkoly

**S1. Obsah homepage do configu.**
- Proč: hero, trať, program a čísla se rozejdou s ostatními stránkami.
- Kde: `app/Controllers/HomeController.php`, `config/zavod.php`.
- Hotovo, když: kontroler neobsahuje ručně psaná fakta o závodě.

**S2. Uklidit opakovaný ročník v propozicích.**
- Proč: stránka se pětkrát omlouvá, že jde o archiv.
- Kde: `app/Views/propositions.php`.
- Hotovo, když: ročník 2026 je zmíněný jen v jednom úvodním bloku.

**S3. Přeuspořádat propozice podle osnovy z auditu 3.**
- Proč: kategorie jsou utopené za programem.
- Kde: `app/Views/propositions.php`.
- Hotovo, když: pořadí bloků odpovídá rozhodovací cestě (kategorie → ceny → program → trať → mapy → přihláška).

**S4. Uzavírací CTA v propozicích.**
- Proč: stránka končí odkazem do archivu.
- Kde: `app/Views/propositions.php`.
- Hotovo, když: poslední blok vede na `/prihlaska` a nese termín 16. ledna 2027.

**S5. Sjednotit slovník CTA.**
- Proč: čtyři různé formulace pro totéž.
- Kde: `home.php`, `layout.php`, `propositions.php`.
- Hotovo, když: web používá jeden tvar („Přihlásit se") a drží ho.

**S6. Doplnit „co bude dál" do potvrzení přihlášky.**
- Proč: po odeslání není jasné, jak proces pokračuje.
- Kde: `app/Views/registration_confirmation.php`.
- Hotovo, když: potvrzení obsahuje krátký blok o ověření platby a dalším postupu.

**S7. Řešit platební blok bez čísla účtu.**
- Proč: vyzývá k platbě, kterou nelze provést.
- Kde: `app/Views/registration_confirmation.php`, `config/zavod.php`.
- Hotovo, když: buď je účet vyplněný, nebo blok místo instrukcí vysvětluje, jak se platba domluví.

**S8. Rozhodnout o sekci Novinky a mrtvém kódu.**
- Proč: v repu je kód, který nic nedělá.
- Kde: `app/Views/home.php`, `app/Models/PostRepository.php`, další nepoužité helpery.
- Hotovo, když: je jasné, co z toho zůstává a co se maže.

### Větší úkoly

**V1. Testy nad veřejnými stránkami.**
- Proč: chyby se dnes odhalí jen ručně.
- Kde: nová testovací sada nad HTTP odpověďmi.
- Hotovo, když: testy hlídají kódy, klíčové texty a to, že se čísla berou z configu.

**V2. Jeden markup pro responzivní tabulky.**
- Proč: výsledky a startovní listina mají každý řádek v DOM dvakrát.
- Kde: `results.php`, `start_list.php`, `propositions.php`.
- Hotovo, když: mobil i desktop vykresluje stejná data z jednoho partialu.

**V3. Konfigurace podle prostředí.**
- Proč: lokální a produkční adresa i termíny akce jsou v jednom souboru.
- Kde: `config/seo.php`.
- Hotovo, když: adresa webu a termíny jdou nastavit bez editace kódu.

### Co má největší přínos pro návštěvníka

Termín a ročník na homepage (M1) a srozumitelný konec registrační cesty (S6, S7). Bez toho web působí jako archiv.

### Co má největší přínos pro správu webu

Jediný zdroj pravdy pro obsah a termíny (S1, M2) a testy (V1). Tím zmizí chyby typu „10 kategorií" a rozjeté datumy.

### Co je dobré odložit

Refaktor dvojitých tabulek (V2), konfigurace prostředí (V3), mapování `srcset` (6) a rozhodnutí o nesloučených větvích (M8/S8) — nemění to, co návštěvník vidí, a počká na sloučení větví `feature/administrace` a `feature/ucty-oddilu`.
