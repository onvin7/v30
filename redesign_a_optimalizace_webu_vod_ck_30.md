# Plán redesignu a optimalizace webu Vodácká 30 (V30)

Tento dokument shrnuje konkrétní kroky a doporučení pro dokončení návrhu a vývoje nového webu `vodacka30.cz`. Reflektuje specifika jednorázového komunitního závodu (akce 1× ročně), eliminuje zbytečnou komplexitu a zaměřuje se na perfektní UX, mobilní použití a viditelnost partnerů závodu.

---

## 1. Co vyřadit a zjednodušit (Overkill na 1× roční závod)

### 1.1 Zrušení uživatelských účtů a loginů oddílů
* **Problém:** Nutit trenéry nebo závodníky pamatovat si hesla a přihlašovat se jednou za 365 dní vede k opuštění registrací a přetížení pořadatelů dotazy.
* **Řešení:** Registrace bez přihlášení (Guest checkout styl).
* **Hromadná přihláška oddílu:** 
  * Formulář na `registration.php` doplnit o dynamické tlačítko `+ Přidat dalšího závodníka`.
  * Jeden zástupce/trenér vyplní své kontaktní údaje (jméno, e-mail, telefon) a následně přidává řádky se jmény, ročníky a kategoriemi dětí/členů.
  * Systém sečte startovné a vygeneruje **jeden společný QR kód** pro celou platbu s jedním variabilním symbolem.

### 1.2 Zjednodušení správy aktualit
* **Problém:** Plánovat redakční systém, CMS editor a CRUD databázi na 4 novinky za celou sezónu je zbytečná režie.
* **Řešení:** Ponechat správu aktualit jako strukturované pole v `config/zavod.php` (nebo v lehkém `news.json`):
  ```php
  'aktuality' => [
      [
          'datum' => '15. 1. 2027',
          'nadpis' => 'Mazací stůl na Bedřichovce',
          'text' => 'K dispozici bude kopyto i žehlička pro finální parafínování.',
          'dulezite' => false,
      ],
  ],
  ```

---

## 2. Dopracování sponzorů a partnerů (Kritická část)

Vodácký závod tohoto typu stojí a padá na podpoře partnerů (Wiegel, Vella Trutnov, Fischer Sports, Pinguin, Kutlíci, SWIX, CHKO Orlické hory, Horská služba ČR atd.). Na starém webu byla loga utopená v nepřehledném seznamu.

### 2.1 Koncept víceúrovňového zobrazení partnerů (Tiering)
Rozdělit sponzory v konfiguraci `config/zavod.php` do 3 úrovní:
1. **Generální / Hlavní partneři** (1–2 subjekty): Větší logo, krátký claim, odkaz.
2. **Partneři a dodavatelé cen** (materiální podpora, vosky, outdoor vybavení): Střední grid.
3. **Institucionální podpora** (CHKO Orlické hory, Horská služba, obec Orlické Záhoří): Menší loga.

### 2.2 Kde a jak sponzory umístit, aby byli skutečně vidět:
* **Globální sekce nad patičkou (Pre-footer bar):**
  * Na každé stránce těsně nad patičkou mít čistý, vizuálně oddělený grid s logy partnerů na světlém nebo neutrálním podkladu (aby nezanikala v tmavém tématu).
* **Kontextové umístění na podstránkách:**
  * **U propozic a cen vítězů:** Zmínka partnerů u věcných cen (např. *„Ceny do závodu věnovaly firmy Fischer a Pinguin“*).
  * **U servisních informací:** Zmínka partnera pro mazání (např. *„Servisní zóna SWIX“*).
  * **V potvrzení registrace a e-mailu:** Logo hlavního partnera přímo u instrukcí k platbě a startovních pokynů.

---

## 3. Klíčové funkční a designové doplňky

### 3.1 Krizový operační pruh (Race Day Alert)
V den závodu nebo těsně před ním dochází k operativním změnám (stav sněhu, zkrácení trasy na bunkrovce, posun startu mládeže na 10:00, kapacita parkoviště na Bedřichovce).
* **Implementace:** Podmíněný pruh v `app/Views/layout.php` řízený konfiguračním souborem:
  ```php
  'upozorneni' => [
      'aktivni' => true,
      'typ' => 'warning', // warning (žlutá), alert (červená), info (modrá)
      'text' => 'Z důvodu sněhových podmínek je trať zkrácena na 22 km. Dětské kategorie startují v 10:00!',
      'odkaz' => '#propozice',
  ],
  ```

### 3.2 Live vyhledávání a filtrace ve startovní listině (`start_list.php`)
Při počtu 150–300 závodníků je nutné umožnit okamžité nalezení jména na mobilu:
* Textový input pro okamžité filtrování jména / klubu (bez nutnosti reloadu stránky).
* Tlačítka pro rychlý výběr kategorie (`Vše`, `Muži 30 km`, `Ženy 30 km`, `Děti`, `Turistická`).
* Realizace pomocí lehkého nativního JS (skrývání řádků tabulky dle shody).

### 3.3 Trasa a profil v propozicích (`propositions.php`)
* Doplnit přímé odkazy na stažení:
  * `[Stáhnout GPX]` (pro hodinky/cyklonavigace).
  * `[Zobrazit na Mapy.cz]`.
* Výškový profil trati s vyznačenými kontrolními body (Start Bedřichovka, Bufet Pěticestí, Bunkrovka, Korunka, Cíl).

### 3.4 Mobilní ergonomie
* Všechny časy a startovní čísla stylovat monospace fontem (`font-mono` v Tailwindu), aby tabulky držely vertikální zarovnání na úzkých displejích.
* Rychlá akční tlačítka v hlavičce na mobilu (`[Startovka]`, `[Trasa]`, `[Registrace]`).

---

## 4. Akční kontrolní seznam pro další vývoj

- [ ] **Sponzoři:** Definovat strukturu partnerů v `config/zavod.php` a vytvořit jednotnou šablonu `partials/sponsors.php` vkládanou před footer.
- [ ] **Krizový alert:** Vložit alert komponentu do `layout.php` s možností snadného zapnutí/vypnutí v configu.
- [ ] **Registrace:** Upravit `registration.php` na možnost přidání více osob pod jeden kontakt a jednu souhrnnou QR platbu.
- [ ] **Startovka:** Přidat JS vyhledávání a filtrování kategorií do `start_list.php`.
- [ ] **Architektura:** Odstranit návrhy na účty oddílů a komplexní blogový modul, soustředit se na finální UX jednostránkového flow a podstránek propozic/výsledků.