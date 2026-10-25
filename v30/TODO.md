# TODO prompty pro AI audit a zlepšení webu

Tento soubor slouží jako zásobník konkrétních promptů pro další AI agenty. Každý prompt je samostatný úkol. Prompt stačí zkopírovat a poslat agentovi v tomto projektu.

## Důležité před spuštěním

- Pracuj z project rootu `c:\Users\onvin\Documents\WEB\v30\v30`.
- Pokud agent používá browser testování, nejdřív si zjisti běžící lokální URL aplikace a nahraď ji za `<BASE_URL>`.
- Reálné routy webu:
  - `/`
  - `/zavod`
  - `/propozice`
  - `/prihlaska`
  - `/prihlaska/potvrzeni`
  - `/startovni-listina`
  - `/vysledky`
  - `/galerie`
  - `/kontakt`
- U tohoto projektu dává smysl hlavně použít skills `frontend-skill`, `diagnosing-bugs`, `code-review`, `tdd`, `codebase-design`, `improve-codebase-architecture` a `writing-for-agents`.
- Výstup má být vždy konkrétní, bez marketingové omáčky a bez obecných frází typu „zlepšit UX“. Každé doporučení musí říct co je špatně, kde se to projevuje a co přesně změnit.

---

## 1. Celkový audit webu napříč všemi stránkami

```text
Použij skills `frontend-skill` a `code-review`. Pokud je k dispozici browser testování, použij ho nad běžící lokální URL `<BASE_URL>`.

Cíl:
Projdi celý veřejný web a zjisti, co působí slabě, nejasně, zastarale, nedůvěryhodně nebo vizuálně rozpadle. Chci audit skutečného webu, ne obecné rady.

Projdi tyto stránky:
- <BASE_URL>/
- <BASE_URL>/zavod
- <BASE_URL>/propozice
- <BASE_URL>/prihlaska
- <BASE_URL>/startovni-listina
- <BASE_URL>/vysledky
- <BASE_URL>/galerie
- <BASE_URL>/kontakt

Co přesně sleduj:
1. Jestli web jako celek drží jednotný vizuální jazyk.
2. Jestli homepage jasně vysvětluje, co je Vodácká 30, kdy se koná a co má uživatel udělat dál.
3. Jestli jsou CTA prvky dobře umístěné a nepůsobí rušivě.
4. Jestli některé sekce vypadají zbytečně prázdně, nataženě nebo naopak namačkaně.
5. Jestli některé stránky působí výrazně slaběji než ostatní.
6. Jestli někde nesedí tón textu, informační hierarchie nebo důvěryhodnost obsahu.
7. Jestli na některých stránkách chybí jasný další krok.

Výstup:
- Nejprve napiš 8 až 15 konkrétních zjištění seřazených podle dopadu.
- U každého zjištění uveď:
  - stránku nebo komponentu,
  - co je problém,
  - proč to vadí z pohledu návštěvníka,
  - přesný návrh změny.
- Potom napiš krátký plán „co opravit jako první tento týden“.

Nechci:
- obecné poučky o UX,
- vágní formulace bez konkrétní stránky,
- návrhy, které by šly proti stylu tohoto projektu (editorial, čistý, civilní, bez agresivního tlačení cen).
```

---

## 2. Hloubkový audit homepage

```text
Použij `frontend-skill`. Projdi detailně homepage na `<BASE_URL>/`.

Cíl:
Zhodnoť pouze úvodní stránku a najdi místa, kde ztrácí sílu, rytmus nebo srozumitelnost. Chci posoudit, jestli homepage opravdu funguje jako kvalitní vstup do webu závodu.

Zaměř se na:
- hero sekci,
- blok „Trať a místo“,
- blok „Ročník 2026 v číslech“,
- program závodního dne,
- novinky,
- výsledky,
- spodní CTA bloky a přechody mezi sekcemi.

Posuzuj:
1. První dojem během prvních 5 sekund.
2. Jasnost hlavního sdělení.
3. Kvalitu hierarchie mezi titulkem, podtitulkem, fakty a CTA.
4. Vertikální rytmus mezi sekcemi.
5. Jestli homepage vede k registraci, nebo jen informuje.
6. Jestli některé sekce působí jako výplň místo skutečné hodnoty.
7. Jestli je rozumný poměr mezi emocí, informací a navigací.

Výstup:
- 1 krátké shrnutí, jak homepage působí jako celek.
- 10 konkrétních doporučení pro homepage.
- U každého doporučení napiš:
  - co přesně upravit,
  - kde na stránce to je,
  - jestli jde spíš o text, layout, hierarchii, CTA nebo obrazový materiál,
  - očekávaný přínos po úpravě.
- Nakonec navrhni jednu „silnou verzi homepage“, tedy co by mělo zůstat, co zjednodušit a co naopak zvýraznit.
```

---

## 3. Audit propozic: důvěryhodnost, čitelnost a práce s informací

```text
Použij `frontend-skill` a `code-review`. Projdi stránku `<BASE_URL>/propozice`.

Cíl:
Zjistit, jestli stránka propozic působí jako důvěryhodný a dobře čitelný informační zdroj pro závodníka, který chce rychle pochopit podmínky závodu.

Zkontroluj hlavně:
- úvodní vysvětlení, že jde o poslední zveřejněný ročník,
- pořadatele,
- časový program,
- kategorie a tratě,
- startovné a přihlášku,
- trať / bezpečnost / ubytování,
- mapy a odkazy na mapové podklady,
- závěrečný archivní blok.

Hledej:
1. Kde je příliš mnoho informací najednou.
2. Kde je naopak důležitá informace utopená.
3. Jestli ceny nejsou zobrazené rušivě nebo odrazujícím způsobem.
4. Jestli je jasně oddělené „co je potvrzené“ a „co je z minulého ročníku“.
5. Jestli mapy, tabulky a texty fungují stejně dobře na mobilu i desktopu.
6. Jestli stránka vede člověka k dalšímu kroku, když se rozhodne závod jet.

Výstup:
- Seznam hlavních problémů v pořadí od nejdůležitějších.
- U každého problému navrhni konkrétní úpravu sekce.
- Nakonec napiš doporučenou novou osnovu stránky propozic v pořadí bloků tak, aby se četla přirozeněji.
```

---

## 4. Audit přihlášky a celé registrační cesty

```text
Použij `frontend-skill`, `diagnosing-bugs` a podle potřeby `tdd`. Projdi `<BASE_URL>/prihlaska` a `<BASE_URL>/prihlaska/potvrzeni`.

Cíl:
Ověřit, jestli je registrační proces srozumitelný, důvěryhodný a bez zbytečných třecích ploch. Nejde jen o validaci formuláře, ale o celý pocit z přihlášení.

Postup:
1. Otevři stránku přihlášky jako nový návštěvník bez znalosti projektu.
2. Zkus popsat, co má uživatel udělat, jaké informace musí dodat a co dostane po odeslání.
3. Pokud to jde, otestuj reálné vyplnění formuláře nanečisto na bezpečných testovacích údajích.
4. Projdi i potvrzovací stránku a zhodnoť, jestli působí jako dobré dokončení akce.

Sleduj:
- jasnost formuláře,
- délku a členění formuláře,
- srozumitelnost popisků,
- chování chybových stavů,
- důvěryhodnost potvrzení,
- to, jestli uživatel po odeslání ví, co se bude dít dál,
- případná místa, kde by se dal člověk snadno zarazit nebo znejistit.

Výstup:
- rozděl nálezy na:
  - kritické problémy,
  - zbytečné tření,
  - kosmetické nedostatky.
- u každého nálezu napiš:
  - přesné místo,
  - jak se problém projeví,
  - návrh opravy.
- pokud najdeš bug nebo podezřelé chování, napiš i přesný repro postup.
```

---

## 5. Mobilní audit celého webu

```text
Použij `frontend-skill`. Projdi web výhradně z pohledu mobilu.

Cíl:
Zjistit, kde web na mobilu ztrácí kvalitu. Chci audit skutečného mobilního používání, ne jen vizuální komentář.

Projdi stejné veřejné stránky jako v celkovém auditu, ale sleduj hlavně:
- sticky hlavičku,
- čitelnost textů,
- zalamování dlouhých řádků,
- mezery a rytmus mezi bloky,
- velikost klikacích ploch,
- přetečení tabulek nebo seznamů,
- mapy a bloky s více informacemi vedle sebe,
- jestli některé CTA nejsou moc nízko, moc vysoko nebo opticky slabé.

Výstup:
- vypiš konkrétní mobilní problémy po stránkách,
- označ, co je:
  - funkční problém,
  - layout problém,
  - problém informační hierarchie,
  - problém čitelnosti.
- nakonec napiš 5 mobilních úprav s největším dopadem.
```

---

## 6. Obsahový audit: co je nejasné, zastaralé nebo zní nepřirozeně

```text
Použij `code-review`. Projdi veřejné stránky webu a soustřeď se jen na text a informační poctivost.

Cíl:
Zjistit, kde texty znějí příliš strojeně, kde nejsou dost konkrétní, kde se opakují, kde neříkají dost a kde může návštěvník špatně pochopit aktuálnost informací.

Zaměř se na:
- homepage,
- stránku závodu,
- propozice,
- přihlášku,
- kontakt.

Posuzuj:
1. Jestli text zní lidsky a civilně.
2. Jestli není zbytečně generický.
3. Jestli je zřejmé, co patří k ročníku 2026 a co je už jen archivní informace.
4. Jestli někde nechybí konkrétní fakta.
5. Jestli někde text nepůsobí zbytečně dlouze nebo opakuje už řečené.

Výstup:
- napiš seznam problematických textových míst,
- u každého uveď původní smysl problému,
- navrhni přesné přeformulování nebo směr přepisu,
- odděl:
  - faktické nejasnosti,
  - tónové problémy,
  - problémy informačního pořadí.
```

---

## 7. Audit technického dluhu z pohledu webu

```text
Použij `codebase-design` a `improve-codebase-architecture`. Neřeš jen vzhled webu, ale hledej, co v kódu nebo struktuře brání dalšímu zlepšování veřejných stránek.

Cíl:
Najít architektonická nebo strukturální místa, kvůli kterým je těžké web dál rozvíjet, udržovat konzistentní obsah nebo dělat bezpečné úpravy UI.

Projdi hlavně:
- `app/Controllers`
- `app/Views`
- `config/zavod.php`
- `config/images.php`
- `web/index.php`

Hledej:
1. Kde je logika nebo obsah zbytečně roztříštěný.
2. Kde chybí jasná odpovědnost mezi kontrolerem, konfigurací a view.
3. Kde se stejná rozhodnutí opakují na více místech.
4. Které části veřejného webu půjdou při další změně snadno rozbít.
5. Jaké refaktory by zjednodušily další práci na homepage, propozicích a přihlášce.

Výstup:
- 5 až 10 konkrétních technických doporučení,
- u každého napiš:
  - kterých souborů se týká,
  - jaký problém dnes způsobuje,
  - co by byl rozumný refaktor,
  - jestli je to malý, střední nebo větší zásah.
- Nakonec navrhni pořadí refaktorů podle návratnosti.
```

---

## 8. Přetavení auditu do konkrétního akčního backlogu

```text
Použij `code-review` a `writing-for-agents`. Vezmi zjištění z předchozích auditů a převeď je do malého, praktického backlogu pro tento projekt.

Cíl:
Nevypsat další obecný seznam nápadů, ale vytvořit přehled konkrétních úkolů, které se dají opravdu udělat.

Pravidla:
- Každý úkol musí být samostatně proveditelný.
- Každý úkol musí mít jasný cíl a jasně pojmenované soubory nebo stránky.
- Úkoly rozděl na malé, střední a větší.
- Upřednostni změny s vysokým dopadem a malým nebo středním úsilím.

Výstup:
- backlog ve formátu:
  - název úkolu,
  - proč se má dělat,
  - kde se projeví,
  - jak poznáme, že je hotový.
- Na konci napiš:
  - co má největší přínos pro návštěvníka,
  - co má největší přínos pro správu webu,
  - co je dobré odložit na později.
```
