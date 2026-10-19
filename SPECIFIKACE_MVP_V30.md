# Specifikace MVP: web Vodácké 30

**Verze:** pracovní návrh
**Stav:** k potvrzení pořadateli
**Rozsah:** první produkční verze veřejného webu

Tento dokument převádí [hlavní plán projektu](PLAN_PROJEKTU_V30.md) a [strukturu projektu](STRUKTURA_PROJEKTU_V30.md) na konkrétní rozsah, chování důležitých procesů a ověřitelná kritéria.

## 1. Účel MVP

První verze má umožnit najít informace o závodě, bezpečně odeslat přihlášku a zobrazit startovní listinu, výsledky a základní obsah webu. Technicky jde o **čisté PHP MVC bez frameworku a bez databáze**: data jsou ukázková (mock) v PHP kontrolerech a přihláška se ukládá pouze do session prohlížeče.

## 2. Rozsah událostí

MVP se soustředí na **běžkařskou Vodáckou 30 v Orlických horách**. Lodní kategorie C2, K1 a K2 z původního formuláře `?menu=6` patří letnímu Východočeskému vodáckému maratonu a **do tohoto webu ani přihlášky se nepřebírají**. Žádná data maratonu se nesmí zobrazit ani přijmout v kontextu Vodácké 30.

## 3. Stránky

| Cesta | Obsah |
|---|---|
| `/` | Úvod: název, termín a místo, hlavní akce, trať a místo |
| `/zavod` | O závodu: charakter závodu, fakta ročníku 2026 |
| `/propozice` | Pořadatelé, program, kategorie a tratě, startovné, platba, trať, mapy, ubytování, kontakt |
| `/prihlaska` | Přihlašovací formulář |
| `/prihlaska/potvrzeni` | Rekapitulace přihlášky a platební instrukce |
| `/startovni-listina` | Startovní listina podle kategorií (veřejná pole) |
| `/vysledky` | Výsledky podle kategorií s filtrem `?kategorie=` + archiv ročníků |
| `/galerie` | Alba podle ročníků a fotografie z ročníku 2026 |
| `/kontakt` | Kontakty na pořadatele |

Společná navigace (Úvod, O závodu, Propozice a trať, Přihlášky, Startovní listina, Výsledky a archiv, Galerie, Kontakt) a tlačítko **Přihlásit se**.

## 4. Přihláška – pole a validace

### Pole formuláře

| Pole | Význam | Typ |
|---|---|---|
| `kategorie` | Kategorie závodu | výběr (allowlist) |
| `oddil` | Oddíl / tým | text |
| `jmeno` | Jméno | text |
| `prijmeni` | Příjmení | text |
| `rocnik_narozeni` | Ročník narození | text (4 číslice) |
| `email` | E-mailová adresa | e-mail |
| `poznamka` | Poznámka | textarea (nepovinné) |
| `souhlas` | Souhlas se zpracováním údajů | zaškrtávací pole |
| `_csrf` | CSRF token | skryté |
| `website` | Honeypot proti spamu | skryté (musí zůstat prázdné) |

### Serverová validace

- `kategorie` – povinná, musí být z povoleného seznamu kategorií.
- `oddil` – povinný, nejvýše 100 znaků.
- `jmeno` / `prijmeni` – povinné, nejvýše 60 znaků.
- `rocnik_narozeni` – povinný, čtyřmístné číslo v rozsahu 1900 až aktuální rok.
- `email` – povinný a platný formát.
- `souhlas` – musí být zaškrtnutý.
- CSRF token musí být platný a honeypot `website` musí zůstat prázdný (jinak se přihláška tiše odmítne).

### Chování

- Při chybě validace se uživatel vrátí na formulář s chybovými zprávami u polí a se zachovanými hodnotami.
- Při úspěchu se zobrazí rekapitulace přihlášky s identifikátorem (`V30-<rok>-XXXX`) a platebními instrukcemi.
- **Stav implementace:** uložení je simulované do `$_SESSION['registrations']`; nejde o závaznou registraci. Odeslání potvrzení e-mailem je plánováno do produkční verze.

## 5. Funkce

### Must have (nutné pro první spuštění)

- Responzivní veřejný web v češtině s přístupnou navigací a jasným tlačítkem k přihlášce.
- Úvodní stránka s termínem a místem ročníku a hlavními odkazy.
- Strukturované propozice, informace o trati a mapě, harmonogram a kontakt.
- Online přihláška s validací, CSRF ochranou a honeypotem.
- Veřejná startovní listina a výsledky jen se schválenými veřejnými údaji.
- Výsledky jako přístupná tabulka s filtrem kategorie a odkazem na oficiální PDF.
- Archiv ročníků s vyznačením ověřených a neověřených ročníků.
- Chybové stránky 404 a 405.

### Should have (doplnit, pokud se vejde do rozsahu)

- Vyhledávání ve výsledcích podle jména a oddílu.
- Mapy dětských tratí.
- Ověření a doplnění starších ročníků do archivu.
- Přesměrování původních adres `?menu=2`, `?menu=3`, `?menu=5` na nové stránky.

### Later (mimo rozsah prvního spuštění)

- Databáze a napojení na reálná data (persistence).
- Administrace pro pořadatele a správa obsahu bez zásahu do kódu.
- Stavy přihlášek a ruční evidence plateb.
- Odesílání potvrzovacích e-mailů.
- Živé výsledky, napojení na časomíru a online platby (QR, platební brána).

## 6. Soukromí

- Ve veřejné startovní listině a ve výsledcích se zveřejňují pouze **startovní číslo, jméno, oddíl a kategorie**.
- **E-mail, telefon, ročník (datum) narození, poznámka ani stav platby se nikdy nezobrazují** – ani ve filtrech, ani v exportech.
- Osobní údaje z přihlášky slouží výhradně pro účely závodu; historická osobní data se nepřenášejí do databáze bez ověření účelu, právního titulu, retence a přístupových práv.
- U nezletilých je politika zveřejnění věcí pořadatele.

## 7. Akceptační kritéria

1. Všechny stránky z oddílu 3 vracejí 200 a odpovídající nadpis; neznámá cesta vrací 404, existující cesta s nesprávnou metodou 405.
2. Formulář nabízí jen povolené kategorie; neplatný vstup vrátí chybu a zachová vyplněné hodnoty.
3. Platný `POST /prihlaska` vede na `/prihlaska/potvrzeni` s identifikátorem a platebními instrukcemi.
4. Neplatný CSRF token nebo vyplněný honeypot přihlášku odmítne bez uložení.
5. Veřejná startovní listina a výsledky neobsahují e-mail, telefon, ročník narození, poznámku ani platební stav.
6. Výsledky jsou filtrovatelné podle kategorie a odpovídají schválenému zdroji.
7. Web je použitelný na mobilu i počítači a ovladatelný klávesnicí; formuláře mají popisky a stavové zprávy.
8. Mimo session se při běžném provozu nic neukládá (žádné soubory ani databáze).
