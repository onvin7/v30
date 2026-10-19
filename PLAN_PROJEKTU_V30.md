# Plán projektu: web Vodácké 30

**Stav dokumentu:** návrh k upřesnění s pořadateli
**Původní web (obsahová reference):** [vodacka30.cz](https://vodacka30.cz/)

## 1. Záměr projektu

Cílem je vytvořit přehledný, důvěryhodný a snadno udržovatelný web běžkařského závodu **Vodácká 30** v Orlických horách. Název vychází z pořadatelského klubu (vodáci), hlavní závod se ale jede na běžkách. Web má zachovat identitu závodu, dobře fungovat na mobilu a pomoci návštěvníkovi rychle najít to nejdůležitější: kdy se závod koná, kde se přihlásit, jaká platí pravidla, kudy vede trať a kde jsou výsledky.

Hlavní zásada: obsah a pravidla se vztahují ke **konkrétnímu ročníku závodu**. Údaj o kategorii, termínu, ceně nebo trati se nemá ručně kopírovat mezi stránkami, ale na jednom místě.

## 2. Výchozí web a co z něj přebíráme

Původní web `vodacka30.cz` obsahuje části, které je třeba zachovat nebo nahradit lepším řešením:

- **Úvodní stránka a aktuality:** organizační zprávy a odkazy na registraci, startovní listinu a trasu.
- **Propozice:** pořadatel, ředitel závodu, hlavní rozhodčí, místo startu a cíle, časový program, tratě, kategorie, startovné, ubytování a časový limit. Pro ročník 2026 šlo o běh na lyžích v Orlických horách s dětskými tratěmi 3 a 7 km a dospělou tratí na 30 km.
- **Výsledky:** výběr ročníků, rozdělení na kategorie, tabulky s pořadím, startovním číslem, závodníkem, oddílem, časem a ztrátou; k ročníku je dostupné oficiální PDF.
- **Přihlášky:** formulář s výběrem kategorie, oddílem, jménem, ročníkem narození, e-mailem a poznámkou.
- **Fotogalerie:** alba podle ročníků, novější fotografie odkazované do externí galerie.
- **Kontakt:** organizační výbor, ředitel závodu a hlavní rozhodčí.

Původní web přenášel informace přes staré číselné parametry v adrese (např. `menu=2`), používal pevné HTML tabulky a měl chyby ve starém JavaScriptu. Proto má smysl navigaci, obsah i jeho správu znovu uspořádat.

### Důležitý rozpor

Původní přihlašovací formulář (`?menu=6`) nabízel lodní kategorie C2, K1 a K2 na 60 nebo 12 km. Ty patří k **letnímu Východočeskému vodáckému maratonu**, nikoli k běžkařské Vodácké 30. Nový web je určen **pouze pro běžkařskou Vodáckou 30** a lodní kategorie z tohoto formuláře nepřebírá. Kategorie se sestavují z potvrzených běžkařských podkladů.

## 3. Cíle a zásady

### Cíle

1. Návštěvník najde termín, místo, přihlášku a výsledky bez zbytečného hledání.
2. Přihlášku lze bezpečně odeslat a získat rekapitulaci s platebními instrukcemi.
3. Výsledky jsou čitelné i na telefonu a je možné je filtrovat podle kategorie.
4. Informace o účastnících a dětech se zpracovávají bezpečně a nezveřejňují se omylem.
5. Archiv ročníků zůstává dohledatelný a doplňuje se o ověřené podklady.
6. Web je přístupný, rychlý a indexovatelný vyhledávači.

### Zásady návrhu

- Nejdříve mobilní použití – návštěvníci často hledají pokyny na cestě nebo přímo v místě závodu.
- Každá stránka má jednoznačný účel a viditelný nadpis.
- Úřední informace, výsledky a registrace mají přednost před dekorací.
- Ročník závodu je samostatný celek se svým datem, propozicemi a daty.
- Veřejná prezentace účastníků je řízena výslovnou volbou a pravidly pořadatele.
- Neexistující funkce (živé měření času, online platby) se neslibují, dokud je pořadatel nepotvrdí.

## 4. Návrh obsahu a navigace

Hlavní navigace je krátká a srozumitelná:

1. **Úvod** (logo odkazuje na `/`)
2. **O závodu** (`/zavod`)
3. **Propozice a trať** (`/propozice`)
4. **Přihlášky** (`/prihlaska`)
5. **Startovní listina** (`/startovni-listina`)
6. **Výsledky a archiv** (`/vysledky`)
7. **Galerie** (`/galerie`)
8. **Kontakt** (`/kontakt`)

Na mobilu se navigace zjednoduší do menu; tlačítko **Přihlásit se** zůstává snadno dostupné. V patičce je pořadatel, kontakt a odkazy na hlavní stránky.

### Úvodní stránka

Slouží jako rozcestník závodu. Zobrazí název, aktuální ročník a jeho termín a místo, hlavní akce (přihláška, propozice, startovní listina) a stručný přehled trati a místa.

### O závodu

Představí charakter závodu (běžky v Orlických horách, pořadatel TJ KVT Pardubice) a přehled faktů ročníku 2026. Odkazuje na propozice a výsledky.

### Propozice a trať

Strukturované propozice: pořadatelé, časový program, kategorie a tratě (tabulka), startovné, platba a přihlášení, trať a bezpečnost, ubytování a kontakt, trasy s mapou. Odkaz na oficiální propozice na původním webu. U každé informace je zřejmé, ke kterému ročníku se vztahuje.

### Přihláška a startovní listina

Přihláška obsahuje výběr kategorie a údaje o závodníkovi (oddíl, jméno, příjmení, ročník narození, e-mail, poznámka, souhlas). Po odeslání následuje rekapitulace s identifikátorem přihlášky a platebními instrukcemi (bankovní převod).

Veřejná startovní listina zobrazuje jen povolené údaje: **jméno, oddíl, kategorii a startovní číslo**. Datum narození, e-mail, telefon, poznámka ani platební stav se veřejně nezobrazí. U nezletilých je třeba zvlášť ověřit právní titul a nastavení souhlasů.

### Výsledky a archiv

Výsledková stránka nabízí filtr podle kategorie a tabulku s pořadím, startovním číslem, závodníkem, oddílem, časem a ztrátou. Ke zveřejněným výsledkům patří odkaz na oficiální PDF. Archiv ročníků rozlišuje ročníky ověřené a zatím neověřené. Pořadí se nepřepočítává bez schválených pravidel pořadatele.

### Galerie, aktuality, partneři a kontakt

- Fotogalerie je členěna podle ročníků; kompletní alba mohou zůstat u externí galerie a web na ně odkazuje.
- Aktuality mají titulek, datum, obsah a případný příznak důležitosti; důležité oznámení (změna času, zrušení) má být viditelné.
- Partneři a kontakty se spravují podle ročníku.
- Kontaktní stránka uvádí role a aktuální údaje pořadatele.

## 5. Stav naplnění reálnými daty (ročník 2026)

Aktuální web je naplněn údaji **26. ročníku (17. ledna 2026)**:

- **Hlavní trať:** 30 km, start a cíl u chaty Bedřichovka v Orlickém Záhoří, kontrolní bod Pěticestí.
- **Dětské tratě:** 3 km klasicky (děti do 6 let) a 7 km volně (7–10, 11–14 let a děti PRO).
- **Startovné:** 200 Kč dětské kategorie; 300 Kč dospělí při bezhotovostní platbě do 15. ledna 2026; 400 Kč na místě.
- **Časový limit:** závod končí v 17:00.
- **Mapa hlavní trati:** `https://mapy.com/s/calefedoho`.
- **Pořadatel:** TJ KVT Pardubice · ředitel závodu Aleš Křivka · hlavní rozhodčí Jiří Roleček.

Startovní listina a výsledky ročníku 2026 vycházejí z oficiálních listin na původním webu. Starší ročníky (v evidenci 2015–2026, ročník 2021 se nejel) jsou vedeny jako neověřené, dokud je pořadatel nepotvrdí.

Údaje o ročníku 2026 jsou archivní – na další ročník se automaticky nevztahují a čekají na potvrzení pořadatelem.

## 6. Technické řešení: čisté PHP MVC

### Použitý stack

- **PHP** bez frameworku – vlastní MVC: router, kontrolery a šablony.
- **Bez správce závislostí** – žádné externí PHP závislosti.
- **Bez build kroku** – žádný Node.js, žádné sestavování assetů.
- **Tailwind CSS přes CDN** – stylování utility třídami.
- **Alpine.js přes CDN** – jen drobná interaktivita (mobilní menu).
- **Bez databáze** – data jsou zatím ukázková (mock) v kontrolerech; přihláška se ukládá pouze do session.

### Rozdělení odpovědností

- **Router** (`web/index.php`) – mapa rout, hledání shody podle HTTP metody, 404/405.
- **Kontrolery** (`app/Controllers/`) – připraví data a zvolí šablonu.
- **Šablony** (`app/Views/`) – vykreslení výstupu přes společný `layout.php`.
- **Pomocné funkce** (`config/helpers.php`) – escapování, CSRF, flash zprávy, přesměrování.
- **Vlastní adresy** – krátké a stabilní (např. `/propozice`, `/prihlaska`). Původní adresy `?menu=2`, `?menu=3`, `?menu=5` se případně namapují přesměrováním na nové stránky.

### Plánovaný rozvoj dat

Zavedení **databáze a administrace** je plánované, nikoli implementované. Cílem je, aby pořadatel spravoval ročník, kategorie, propozice a výsledky bez zásahu do kódu. Do té doby platí, že data jsou v kontrolerech a web je statický (kromě funkční přihlášky). Podrobnosti v [PLAN_REALIZACE_V30.md](PLAN_REALIZACE_V30.md).

## 7. Bezpečnost, soukromí a dostupnost

- **Soukromí:** veřejná rozhraní (startovní listina, výsledky) zobrazují pouze schválená pole; e-mail, telefon, ročník narození, poznámka a platební stav se nezveřejňují.
- **Souhlasy:** u přihlášky se eviduje souhlas se zpracováním údajů; konkrétní právní základ, dobu uchování a podobu souhlasů ověří pořadatel.
- **Formuláře:** serverová validace, CSRF ochrana a honeypot proti spamu.
- **Cookies:** nezbytné cookies fungují bez souhlasu v nutném rozsahu; analytické skripty se před souhlasem nenačítají.
- **Přístupnost:** sémantické HTML, popisky formulářů, ovládání klávesnicí, čitelný kontrast a stavové zprávy přístupné čtečkám; cílem je WCAG 2.2 AA v rozsahu odpovídajícím veřejnému webu.
- **Nasazení:** HTTPS, pravidelné zálohy a zálohovaný postup obnovy.

## 8. Ověření kvality a kritéria dokončení

Web je připraven ke spuštění, když:

- propozice, kategorie a termín jsou potvrzené pořadatelem;
- všechny stránky fungují na telefonu i počítači a navigace má srozumitelné názvy;
- přihlášku lze bezpečně odeslat a zobrazí se rekapitulace s platebními instrukcemi;
- veřejná startovní listina a výsledky neobsahují neveřejné osobní údaje;
- výsledky odpovídají schválenému zdroji a jsou filtrovatelné podle kategorie;
- chybové stránky 404 a 405 fungují a důležité staré odkazy se přesměrují nebo mají náhradní stránku;
- existuje jasné určení, kdo web spravuje a jak se aktualizuje obsah.

## 9. Otázky k rozhodnutí s pořadateli

1. Jaké jsou oficiální kategorie, tratě, ceny a termíny pro příští ročník?
2. Je termín dalšího ročníku potvrzený a kdo ho schvaluje?
3. Kdo dodá finální propozice, ceník, mapu, fotografie a podklady pro výsledky?
4. Jak se dnes potvrzují platby a kdo smí přihlášky opravovat?
5. Mají být jména účastníků a výsledky veřejné? Jaká pravidla platí pro děti?
6. Zůstane galerie u externí služby, nebo se budou fotografie nahrávat na web?
7. Kdo bude web dlouhodobě spravovat a na jakém hostingu poběží?

## Shrnutí

Nový web má spojit moderní prezentaci s praktickým nástrojem pro organizaci Vodácké 30. Je určen pouze pro tento běžkařský závod; lodní kategorie z původního formuláře `?menu=6` se nepřebírají. Web je postaven jako čisté PHP MVC bez frameworku a zatím bez databáze – s mock daty ročníku 2026 a funkční přihláškou. Dalším krokem je potvrzení ročníku pořadatelem a postupné zavedení datové vrstvy a administrace.
