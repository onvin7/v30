# Projekt: web Vodácké 30

Tento dokument popisuje zadání a kontext – co web je, odkud se berou data, co který obsah znamená a co ještě chybí. Technické řešení je v [TECHNICKA_DOKUMENTACE.md](TECHNICKA_DOKUMENTACE.md), vizuální pravidla v [DESIGN.md](DESIGN.md), rychlý přehled v [README.md](README.md).

## 1. Co je cílem

Vodácká 30 je běžkařský závod v Orlických horách; název má z pořadatelského klubu (vodáci), ale jede se na běžkách. Web má zachovat identitu závodu, dobře fungovat na mobilu a pomoct návštěvníkovi rychle najít to podstatné: kdy se závod koná, kde se přihlásit, jaká platí pravidla, kudy vede trať a kde jsou výsledky.

Hlavní zásada: obsah a pravidla patří ke **konkrétnímu ročníku závodu**. Údaj o kategorii, termínu, ceně nebo trati se nemá ručně kopírovat mezi stránkami – drží se na jednom místě (`config/zavod.php`) a stránky si ho čtou.

## 2. Původní web a co z něj web přebírá

Obsahová reference je původní web [vodacka30.cz](https://vodacka30.cz/). Přebíráme z něj:

- **Úvodní stránku a aktuality** – organizační zprávy a odkazy na registraci, startovní listinu a trať.
- **Propozice** – pořadatel, ředitel závodu, hlavní rozhodčí, místo startu a cíle, časový program, tratě, kategorie, startovné, ubytování a časový limit.
- **Výsledky** – výběr ročníků, rozdělení po kategoriích, tabulky s pořadím, startovním číslem, závodníkem, oddílem, časem a ztrátou, k ročníku odkaz na oficiální PDF.
- **Přihlášky** – formulář s výběrem kategorie, oddílem, jménem, ročníkem narození, e-mailem a poznámkou.
- **Fotogalerii** – alba podle ročníků, novější fotky odkazované do externí galerie pořadatele.
- **Kontakt** – organizační výbor, ředitel závodu a hlavní rozhodčí.

Původní web přenášel informace přes číselné parametry v adrese (např. `?menu=2`), používal pevné HTML tabulky a měl chyby ve starém JavaScriptu. Proto má smysl navigaci, obsah i jeho správu uspořádat znovu.

### Pozor: lodní kategorie sem nepatří

Původní přihlašovací formulář (`?menu=6`) nabízel lodní kategorie C2, K1 a K2 na 60 nebo 12 km. Ty patří k **letnímu Východočeskému vodáckému maratonu**, ne k běžkařské Vodácké 30. Tento web je určen **jen pro běžkařskou Vodáckou 30** a lodní kategorie nepřebírá. Žádná data maratonu se nesmí zobrazit ani přijmout v kontextu Vodácké 30.

## 3. Zásady návrhu

- Nejdřív mobilní použití – lidé hledají pokyny na cestě nebo přímo v místě závodu.
- Každá stránka má jednoznačný účel a viditelný nadpis.
- Úřední informace, výsledky a registrace mají přednost před dekorací.
- Ročník je samostatný celek se svým datem, propozicemi a daty.
- Veřejná prezentace účastníků je řízená pravidly pořadatele.
- Neexistující funkce (živé měření času, online platby) se neslibují, dokud je pořadatel nepotvrdí.

## 4. Navigace a obsah stránek

Hlavní navigace je krátká: Úvod (`/`), O závodu (`/zavod`), Propozice a trať (`/propozice`), Přihlášky (`/prihlaska`), Startovní listina (`/startovni-listina`), Výsledky a archiv (`/vysledky`), Galerie (`/galerie`), Kontakt (`/kontakt`). Na mobilu se schová do menu, tlačítko **Přihlásit se** zůstává po ruce. V patičce je pořadatel, kontakt a odkazy na hlavní stránky.

Mimo navigaci existují dvě chráněné zóny: **zóna oddílu** `/oddil` (registrace oddílu, soupiska, hromadná přihláška) a **administrace** `/admin` (příspěvky, schvalování oddílů, přehled přihlášek a export do CSV). Obě jsou zatím ve větvích.

| Stránka | Co obsahuje |
|---|---|
| Úvod | Rozcestník závodu: název, aktuální ročník s termínem a místem, hlavní akce (přihláška, propozice, startovní listina), stručný přehled trati a místa. |
| O závodu | Charakter závodu (běžky v Orlických horách, pořadatel TJ KVT Pardubice) a přehled faktů ročníku. Odkazy na propozice a výsledky. |
| Propozice a trať | Pořadatelé, časový program, kategorie a tratě, startovné, platba a přihlášení, trať a bezpečnost, ubytování, kontakt, trasy s mapami. U každé informace je zřejmé, ke kterému ročníku patří. |
| Přihláška | Formulář a po odeslání rekapitulace s identifikátorem přihlášky a platebními instrukcemi (bankovní převod). |
| Startovní listina | Jen povolené údaje: jméno, oddíl, kategorie a startovní číslo. |
| Výsledky a archiv | Filtr podle kategorie, tabulka s pořadím, startovním číslem, jménem, oddílem, časem a ztrátou, odkaz na oficiální PDF. Archiv ročníků rozlišuje ověřené a zatím neověřené. Pořadí se nepřepočítává bez schválených pravidel pořadatele. |
| Galerie | Alba podle ročníků, kompletní alba zůstávají u externí galerie pořadatele. |
| Kontakt | Role a aktuální údaje pořadatele. |

## 5. Odkud se berou data

Web stojí na dvou ročnících. **27. ročník (16. ledna 2027)** je pořadatelem potvrzený termín a podle něj se zobrazuje aktuální ročník; **26. ročník (17. ledna 2026)** je archivní a jsou z něj konkrétní data:

- **Hlavní trať:** 30 km, start a cíl u chaty Bedřichovka v Orlickém Záhoří, kontrolní bod Pěticestí.
- **Dětské tratě:** 3 km klasicky (do 6 let) a 7 km volně (7–10, 11–14 let a děti PRO).
- **Startovné:** 200 Kč dětské kategorie, 300 Kč dospělí při bezhotovostní platbě do 15. ledna, 400 Kč na místě.
- **Časový limit:** závod končí v 17:00.
- **Mapy:** všechny tři tratě mají vlastní mapu (odkazy na `mapy.com`).
- **Pořadatel:** TJ KVT Pardubice, ředitel závodu Aleš Křivka, hlavní rozhodčí Jiří Roleček.
- **Kategorie:** 11 vypsaných kategorií (od dětí po veterány), každá s tratí, stylem a cenou.

Vše je na jednom místě v `config/zavod.php` a čte se přes `zavod()`. Startovní listina a výsledky ročníku 2026 vycházejí z oficiálních listin původního webu. Starší ročníky (v evidenci 2015–2026, ročník 2021 se nejel) jsou vedené jako neověřené, dokud je pořadatel nepotvrdí. **Číslo účtu pro platbu zveřejněné není** – doplní ho pořadatel, do té doby na to potvrzení přihlášky upozorňuje, aby nikdo neposílal peníze na neexistující účet.

Údaje o ročníku 2026 jsou archivní: na další ročník se automaticky nevztahují a musí je potvrdit pořadatel.

## 6. Rozsah funkcí

**Hotové (první spuštění veřejného webu):** responzivní web v češtině s přístupnou navigací, úvod s termínem a místem, strukturované propozice včetně trati a map, online přihláška s validací, CSRF ochranou a honeypotem, veřejná startovní listina a výsledky jen se schválenými údaji, filtr výsledků podle kategorie, archiv ročníků, chybové stránky 404 a 405, `/sitemap.xml` a `/robots.txt`. Fotky se servírují z repozitáře, ne přes hotlinking.

**Hotové nad původní plán, ale zatím ve větvích:** zóna oddílu se schválením účtu a hromadnou přihláškou, administrace pro pořadatele, ukládání přihlášek, oddílů a příspěvků do JSON souborů.

**Zbývá doplnit:** mapy dětských tratí jsou hotové, ale chybí oficiální PDF propozic k aktuálnímu ročníku a potvrzená data ročníku 2027. Dále se počítá s přesměrováním původních adres `?menu=2`, `?menu=3` a `?menu=5` na nové stránky a s vyhledáváním ve výsledcích podle jména a oddílu.

**Mimo rozsah prvního spuštění:** databáze místo JSON souborů, odesílání potvrzovacích e-mailů, živé výsledky z časomíry a online platby (QR, platební brána). Platba zůstává bankovním převodem bez QR – zobrazení platebních instrukcí neznamená zaplaceno.

## 7. Soukromí

- Ve veřejné startovní listině a ve výsledcích se zveřejňují pouze **startovní číslo, jméno, oddíl a kategorie**.
- **E-mail, telefon, ročník (datum) narození, poznámka ani stav platby se nikdy nezobrazují** – ani ve filtrech, ani v exportech.
- Osobní údaje z přihlášky slouží výhradně pro účely závodu a ukládají se jen do `storage/` (mimo Git).
- Historická osobní data se nepřenášejí do úložiště bez ověření účelu, právního titulu, doby uchování a přístupových práv.
- Politika zveřejňování u nezletilých je věcí pořadatele.

## 8. Kdy je web připravený ke spuštění

- Propozice, kategorie a termín jsou potvrzené pořadatelem.
- Všechny stránky fungují na telefonu i na počítači a navigace má srozumitelné názvy.
- Přihlášku lze bezpečně odeslat a zobrazí se rekapitulace s platebními instrukcemi.
- Startovní listina a výsledky neobsahují neveřejné osobní údaje.
- Výsledky odpovídají schválenému zdroji a jsou filtrovatelné podle kategorie.
- Neznámá cesta vrací 404, existující cesta s jinou metodou 405.
- Je jasné, kdo web spravuje a jak se aktualizuje obsah.

## 9. Otázky k rozhodnutí s pořadateli

1. Jaké jsou oficiální kategorie, tratě, ceny a termíny pro ročník 2027?
2. Kdo schvaluje termín a kdo dodá finální propozice, ceník, mapy, fotografie a podklady pro výsledky?
3. Jak se dnes potvrzují platby a kdo smí přihlášky opravovat?
4. Jaké číslo účtu se má zveřejnit?
5. Mají být jména účastníků a výsledky veřejné? Jaká pravidla platí pro děti?
6. Zůstane galerie u externí služby, nebo se fotky budou nahrávat na web?
7. Kdo bude web dlouhodobě spravovat a na jakém hostingu poběží?

## Co zbývá

Pořadí odpovídá tomu, co je potřeba udělat dřív. Průběžně aktualizovaný stav (co už je hotové v kódu, co běží a co je rozpracované) je v živém plánu `v30/PLAN.md`; nálezy z auditu veřejného webu `.trae/documents/audit-webu-v30.md` jsou do něj rozdané.

1. **Sloučit větve.** `feature/administrace` a `feature/ucty-oddilu` do `main` a vyřešit konflikt v mapě rout `web/index.php` (obě větve přidávají routy pro `/admin` a `/oddil`). Pak ověřit, že odkaz `/oddil/registrace` na stránce přihlášky vede na funkční cíl.
2. **Napojit reálná data ročníku 2027.** Termín je potvrzený, obsah (kategorie, ceny, kapacity, uzávěrky, kontakty, číslo účtu) musí doplnit pořadatel. Ověřené starší ročníky doplnit do archivu.
3. **Doplnit propozice a PDF** k aktuálnímu ročníku.
4. **Napojit odesílání e-mailů.** Dnes se potvrzovací text zobrazí přímo na stránce.
5. **Doladit responzivitu.** Zbytek seznamu z auditu: sjednotit breakpoint hlavičky (sticky se vypíná na 768 px, ale hamburger mizí až na 1280 px), drobnější úpravy (tracking popisků, mezery sekcí, výška odkazů v mobilním menu, CTA tlačítka na malých šířkách). Podrobný seznam je v `.trae/documents/archive/plan-responzivita-v30.md`.
6. **Volitelně build Tailwindu.** Styl se dnes načítá z CDN, což na mobilní síti způsobuje probliknutí nenastylované stránky. Řešením je standalone Tailwind CLI bez Node.js.
7. **Rozšířit správu obsahu.** Aby pořadatel spravoval ročník, kategorie, propozice a výsledky bez zásahu do kódu. Databáze místo JSON souborů má smysl teprve tehdy, až objem dat přestane stačit.
