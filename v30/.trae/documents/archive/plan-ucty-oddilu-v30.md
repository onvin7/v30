> **ARCHIV – NEPLATÍ.** Zóna oddílu je hotová ve větvi `feature/ucty-oddilu`. Aktuální stav je v `PROJEKT.md` a `TECHNICKA_DOKUMENTACE.md` v kořeni repozitáře.

# Plán: uživatelské účty pro oddíly (Vodácká 30)

## 1. Shrnutí

Zavést **účty výhradně pro oddíly**. Vedoucí oddílu se sám zaregistruje, pořadatel
jeho účet v administraci schválí, a teprve pak se může vedoucí přihlásit, spravovat
soupisku svých závodníků a přihlásit je hromadně na ročník.

Jednotlivci a rodiče **zůstávají u anonymního formuláře** `/prihlaska` jako dnes —
účet nezakládají a nic se pro ně nemění.

Web i nadále běží **bez databáze**: účty oddílů i přihlášky se ukládají do JSON
souborů v chráněné `storage/`, stejnou technikou jako příspěvky
([PostRepository.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Models/PostRepository.php)
— `flock` + atomický zápis přes dočasný soubor a `rename`).

Respektovat `AGENTS.md`: čisté PHP MVC bez závislostí, bez DB, kód a texty česky,
escapovat přes `e()`, formuláře přes CSRF + honeypot, PRG přes `redirect()`,
nevytvářet zbytečné abstrakce.

## 2. Rozhodnutí, ze kterých plán vychází

| Otázka | Rozhodnutí |
|---|---|
| Kdo dostane účet | **Jen oddíly.** Jednotlivci a rodiče bez účtu, jako dnes. |
| Vznik účtu | **Samoobslužná registrace** vedoucího + **schválení pořadatelem**. Do schválení se nelze přihlásit. |
| Platba oddílu | **Jeden variabilní symbol za celý oddíl**, v potvrzení rozpis po závodnících. |
| Platba jednotlivce | Beze změny — vlastní variabilní symbol jako dnes. |
| Soupisky oddílů | **Seednuté z 26. ročníku** (reálná jména a oddíly z oficiální listiny). |

## 3. Nalezené rozpory ve stávajícím kódu

Před implementací je nutné srovnat tato místa, jinak by účty oddílů stály na
nekonzistentních datech.

### 3.1 Kategorie existují dvakrát a liší se

Dva nezávislé seznamy kategorií:

- [RegistrationController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/RegistrationController.php#L13-L28)
  — 11 kategorií, ceny jako `int` (200 / 300 Kč), **obsahuje `veterans`**.
- [RaceController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/RaceController.php#L32-L43)
  — 10 kategorií, ceny jako text (`'300 / 400 Kč'`), **`veterans` neobsahuje**.

Ověřeno proti zveřejněným propozicím
([vodacka30.cz/?menu=2](https://vodacka30.cz/?menu=2)): **kategorie `veterans` existuje**
— „Veteráni/veteránky – v této kategorii nebudeme rozlišovat nic“. Chybuje tedy
webová stránka propozic, ne přihláška. Zdroj pravdy musí být jeden.

### 3.2 Startovné

Zveřejněné propozice ([vodacka30.cz/?menu=2](https://vodacka30.cz/?menu=2)):

| Startovné za osobu | |
|---|---|
| 200 Kč | dětské kategorie na 3 a 7 km |
| 300 Kč | bezhotovostní platba (přihláška a platba do 15. ledna) |
| 400 Kč | na místě startu |

Rozhodující je datum přihlášení a platba na účet před startem. Přihláška dnes
účtuje paušálně 300 Kč a potvrzení lhůtu pro včasnou platbu **vůbec nezmiňuje**
([RegistrationController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/RegistrationController.php#L116-L129)).
U hromadné přihlášky oddílu je to o to citlivější — jde o násobek.

### 3.3 Bankovní údaje jsou mock a skutečné nejsou veřejné

V potvrzení je napevno `'ucet' => '123456789/0300'`
([RegistrationController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/RegistrationController.php#L155-L161)).
**Skutečné bankovní spojení se na vodacka30.cz dohledat nepodařilo** — propozice
mluví jen o „platbě na účet“, číslo účtu ani IBAN zveřejněné nejsou a kontaktní
stránka [vodacka30.cz/?menu=5](https://vodacka30.cz/?menu=5) obsahuje pouze telefony
na ředitele závodu a hlavního rozhodčího. Číslo účtu musí dodat pořadatel; do té doby
zůstane v `config/zavod.php` prázdné a potvrzení na něj musí upozornit, aby oddíly
neposílaly peníze na neexistující účet.

### 3.4 Přihlášky se nikam neukládají

Žijí jen v session a ztrácejí se. Pro účty oddílů je persistence podmínkou — oddíl
se musí ke své přihlášce vrátit i po odhlášení.

### 3.5 Chybí zdroj ročníku narození

Oficiální listina 26. ročníku obsahuje jen jméno, oddíl a kategorii. Seed soupisek
tedy **nemůže** naplnit ročník narození — viz 8.2.

### 3.6 Ověřená data z vodacka30.cz

Dohledáno na oficiálním webu (stav k 20. 10. 2026, webem stále běží 26. ročník):

| Údaj | Hodnota | Zdroj |
|---|---|---|
| **27. ročník** | **16. ledna 2027** | aktuality, [vodacka30.cz](https://vodacka30.cz/) |
| Uzávěrka včasné platby | 15. ledna (lhůta z propozic) | [menu=2](https://vodacka30.cz/?menu=2) |
| Startovné děti (3 a 7 km) | 200 Kč | [menu=2](https://vodacka30.cz/?menu=2) |
| Startovné ostatní — bezhotovostně | 300 Kč | [menu=2](https://vodacka30.cz/?menu=2) |
| Startovné ostatní — na místě | 400 Kč | [menu=2](https://vodacka30.cz/?menu=2) |
| Děti: do 6 let včetně | 3 km **klasicky** | [menu=2](https://vodacka30.cz/?menu=2) |
| Děti: 7–10, 11–14, PRO 7–14 | 7 km **volně** | [menu=2](https://vodacka30.cz/?menu=2) |
| Junioři | od této kategorie 30 km | [menu=2](https://vodacka30.cz/?menu=2) |
| PRO 7–14 | „členové běžkařských oddílů, závodníci atp.“ | [menu=2](https://vodacka30.cz/?menu=2) |
| Veteráni/veteránky | **kategorie existuje** | [menu=2](https://vodacka30.cz/?menu=2) |
| Trať | start/cíl chata Bedřichovka, občerstvení Pěticestí | [menu=2](https://vodacka30.cz/?menu=2) |
| Časový limit | 17:00 | [menu=2](https://vodacka30.cz/?menu=2) |

**Kategorie, které web propozic vynechává:** `veterans` na webu chybí, ale v propozicích
na vodacka30.cz je uvedená. Doplnit.

**Stále nedohledáno — musí dodat pořadatel:**

1. **Bankovní spojení** pro platbu startovného (na webu není nikde).
2. **Věkové hranice** juniorské a dospělých kategorií — propozice je neuvádějí,
   takže se u nich věk ověřovat nebude (`vek_od`/`vek_do = null`).

**Legacy past:** zveřejněná přihláška na [vodacka30.cz/?menu=6](https://vodacka30.cz/?menu=6)
je **zastaralá** — nabízí kategorie z dob, kdy šlo o vodácký závod
(„60 km – C2 – turistického typu“, „12 km – C2 – Dětský maraton“, „60 km – K1 – sea kajak“),
které s dnešním během na lyžích nemají nic společného. Náš formulář je tedy jediný
platný zdroj kategorií a je potřeba držet ho v souladu s config souborem.

## 4. Jednotný zdroj pravdy: `config/zavod.php`

**Nový soubor** nahradí oba duplikované seznamy kategorií a zároveň ponese časové
údaje ročníku. Styl jako `config/images.php` (vrací pole).

```php
return [
    'rok'                 => 2027,
    'rocnik'              => 27,
    'datum'               => '16. ledna 2027',
    'misto'               => 'chata Bedřichovka, Orlické Záhoří',
    'obcerstveni'         => 'Pěticestí (Horská služba)',
    'casovy_limit'        => '17:00',

    // platby — potvrzeno z propozic na vodacka30.cz
    'uzavierka'           => '15. ledna 2027',
    'lhuta_vcasne_platby' => '15. ledna 2027',
    'ucet'                => '',   // CHYBÍ — musí dodat pořadatel, viz 3.3

    // kod => [nazev, trat, styl, cena, cena_na_miste, vek_od, vek_do]
    'kategorie' => [
        'children-under-6'  => ['nazev' => 'Chlapci a dívky do 6 let',           'trat' => '3 km',  'styl' => 'klasicky', 'cena' => 200, 'cena_na_miste' => 200, 'vek_od' => 0,    'vek_do' => 6],
        'children-7-10'     => ['nazev' => 'Chlapci a dívky od 7 do 10 let',     'trat' => '7 km',  'styl' => 'volně',    'cena' => 200, 'cena_na_miste' => 200, 'vek_od' => 7,    'vek_do' => 10],
        'children-11-14'    => ['nazev' => 'Chlapci a dívky od 11 do 14 let',    'trat' => '7 km',  'styl' => 'volně',    'cena' => 200, 'cena_na_miste' => 200, 'vek_od' => 11,   'vek_do' => 14],
        'children-pro-7-14' => ['nazev' => 'Chlapci a dívky PRO od 7 do 14 let', 'trat' => '7 km',  'styl' => 'volně',    'cena' => 200, 'cena_na_miste' => 200, 'vek_od' => 7,    'vek_do' => 14],
        'juniors'           => ['nazev' => 'Junioři',                           'trat' => '30 km', 'styl' => 'volně',    'cena' => 300, 'cena_na_miste' => 400, 'vek_od' => null, 'vek_do' => null],
        'men-pro'           => ['nazev' => 'Muži PRO',                          'trat' => '30 km', 'styl' => 'volně',    'cena' => 300, 'cena_na_miste' => 400, 'vek_od' => null, 'vek_do' => null],
        'women-pro'         => ['nazev' => 'Ženy PRO',                          'trat' => '30 km', 'styl' => 'volně',    'cena' => 300, 'cena_na_miste' => 400, 'vek_od' => null, 'vek_do' => null],
        'men-classic'       => ['nazev' => 'Muži KLASICI',                      'trat' => '30 km', 'styl' => 'klasicky', 'cena' => 300, 'cena_na_miste' => 400, 'vek_od' => null, 'vek_do' => null],
        'women-classic'     => ['nazev' => 'Ženy KLASIČKY',                     'trat' => '30 km', 'styl' => 'klasicky', 'cena' => 300, 'cena_na_miste' => 400, 'vek_od' => null, 'vek_do' => null],
        'pro-for-fun'       => ['nazev' => 'Muži a ženy PRO RADOST',             'trat' => '30 km', 'styl' => 'volně',    'cena' => 300, 'cena_na_miste' => 400, 'vek_od' => null, 'vek_do' => null],
        'veterans'          => ['nazev' => 'Veteráni / veteránky',               'trat' => '30 km', 'styl' => 'volně',    'cena' => 300, 'cena_na_miste' => 400, 'vek_od' => null, 'vek_do' => null],
    ],
];
```

Sloupec `vek_od` / `vek_do` je vyplněný jen tam, kde ho propozice výslovně uvádějí
(`do 6 let`, `7–10`, `11–14`, `PRO 7–14`) — potvrzeno z vodacka30.cz. U juniorské
a dospělých kategorií rozsah uvedený není, proto `null` = věk se nekontroluje,
ne že by se domýšlel.

Načítání zůstane stejně jako u obrázků: helper `zavod()` v `config/helpers.php`
s `static` cache, aby se soubor čítal jednou za požadavek.

**Co to znamená pro stávající kód:**

- `RegistrationController::kategorie()` se nahradí voláním `zavod()['kategorie']`,
  částka se bere z `cena`.
- `RaceController::propositions()` sestaví tabulku kategorií z téhož pole (název,
  trať, styl, cena) + doplní `cena_na_miste` jako samostatný sloupec.
- `RaceController` musí doplnit `veterans` — v propozicích na vodacka30.cz je
  uvedená, ve webovém výpisu kategorií ale chybí (3.1).

## 5. Datový model

### 5.1 `storage/oddily.json`

```json
{
  "next_id": 8,
  "oddily": [
    {
      "id": 1,
      "nazev": "Vodáci Choceň",
      "email": "vedouci@example.cz",
      "heslo_hash": "$2y$10$…",
      "kontakt_jmeno": "Jan Novák",
      "telefon": "",
      "stav": "schvaleny",
      "token_zadosti": "9f3c…",
      "vytvoreno": "2026-10-19 22:00",
      "schvaleno": "2026-10-20 09:00",
      "zavodnici": [
        {
          "id": 1,
          "jmeno": "Karlík Tobiáš",
          "kategorie_2026": "juniors",
          "rocnik_narozeni": null,
          "aktivni": 1
        }
      ]
    }
  ]
}
```

- `stav`: `cekajici` | `schvaleny` | `zamitnuty`.
- `email` je zároveň **přihlašovací jméno** — není potřeba zavádět `slug()` ani
  další pole.
- `token_zadosti`: náhodný řetězec — přístupový klíč k textovým oznámením (viz 7.3).
  Je to tentýž token, který se později vloží do e-mailu.
- `zavodnici` = soupiska oddílu. Drží se u účtu oddílu, ne u závodníka.

### 5.2 `storage/prihlasky.json`

```json
{
  "next_id": 12,
  "next_vs": 1000000001,
  "prihlasky": [
    {
      "id": "V30-2027-0001",
      "rok": 2027,
      "typ": "oddil",
      "oddil_id": 1,
      "oddil_nazev": "Vodáci Choceň",
      "email": "vedouci@example.cz",
      "variabilni_symbol": "1000000001",
      "castka": 900,
      "zavodnici": [
        {
          "jmeno": "Karlík Tobiáš",
          "rocnik_narozeni": 2012,
          "kategorie": "juniors",
          "kategorie_nazev": "Junioři",
          "trat": "30 km",
          "cena": 300
        }
      ],
      "poznamka": "",
      "stav": "nova",
      "vytvoreno": "2026-11-02 18:12"
    }
  ]
}
```

- `typ`: `oddil` | `jednotlivec`.
- `stav`: `nova` | `zaplaceno` | `stornovano` — mění pořadatel v adminu ručně.
- `oddil_nazev` i `kategorie_nazev` se **kopírují**, aby přihláška zůstala čitelná
  i po přejmenování oddílu nebo kategorie.
- `cena` se ukládá v okamžiku přihlášky — pozdější změna ceníku nesmí zpětně měnit
  vystavené přihlášky.

### 5.3 Repozitáře

**Nový `app/Models/OddilRepository.php`**:

| Metoda | Popis |
|---|---|
| `all(): array` | všichni oddíly (pro admin) |
| `cekajici(): array` | jen se stavem `cekajici` |
| `pocetCekajicich(): int` | pro badge v adminu |
| `find(int $id): ?array` | podle ID |
| `findByEmail(string $email): ?array` | přihlášení, case-insensitive |
| `findByToken(string $token): ?array` | stav žádosti |
| `create(array $data): int` | založí účet ve stavu `cekajici`, vrátí ID |
| `setStav(int $id, string $stav): bool` | schválit / zamítnout |
| `setHeslo(int $id, string $heslo): bool` | `password_hash`; používá admin i sám oddíl |
| `ulozZavodnika(int $id, array $zavodnik): int` | přidá / upraví položku soupisky |
| `smazZavodnika(int $id, int $zavodnikId): bool` | odstraní položku soupisky |
| `nastavSoupisku(int $id, array $zavodnici): bool` | hromadný zápis při importu |
| `smaz(int $id): bool` | smazání účtu i soupisky (GDPR) |

**Nový `app/Models/RegistrationRepository.php`**:

| Metoda | Popis |
|---|---|
| `create(array $prihlaska): array` | přiřadí `id` a `variabilni_symbol`, uloží |
| `proOddil(int $oddilId, int $rok): array` | přihlášky daného oddílu pro daný rok |
| `proRok(int $rok): array` | všechny přihlášky ročníku (admin, startovní listina) |
| `find(string $id): ?array` | detail |
| `setStav(string $id, string $stav): bool` | označení platby (admin) |

Číslování `id` i `variabilni_symbol` monotónně roste (`next_id`, `next_vs`),
aby se VS neopakoval. Uložení je vždy pod `flock(LOCK_EX)`.

## 6. Autentizace oddílů

**Nový `app/Middleware/OddilAuth.php`** — obdoba
[AdminAuth.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Middleware/AdminAuth.php),
ale s více účty z repozitáře:

- `check(): bool`, `guard(): void`, `user(): ?array`, `attempt(string $email, string $heslo): string`, `logout(): void`.
- `attempt()` vrací **výsledek jako řetězec**, aby šlo odlišit stavy:
  - `ok` — přihlášeno (`session_regenerate_id(true)`),
  - `cekajici` — heslo správné, ale účet ještě neschválen,
  - `zamitnuty` — žádost byla zamítnuta,
  - `spatne` — neexistující e-mail nebo špatné heslo.
- Session klíče oddělené od admina: `oddil_id`, `oddil_email`, `oddil_last`. Vedoucí
  a administrátor se navzájem neovlivní a obě role mohou být přihlášené současně.
- Timeout nečinnosti shodný s adminem (30 minut), logika přesunutá ze `AdminAuth`.

Změna v [web/index.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/web/index.php#L124-L127):
dosud platí `if (! empty($options['auth'])) AdminAuth::guard();`. Rozšíří se na dva režimy:

```php
if (($options['auth'] ?? null) === 'oddil') {
    OddilAuth::guard();
} elseif (! empty($options['auth'])) {
    AdminAuth::guard();
}
```

## 7. Veřejná část — registrace oddílu

**`app/Controllers/OddilAuthController.php`**

| Metoda | Routa | Popis |
|---|---|---|
| `showLogin()` | GET `/oddil/prihlaseni` | přihlašovací formulář |
| `login()` | POST `/oddil/prihlaseni` | rate limit → CSRF + honeypot → `OddilAuth::attempt()` |
| `showRegister()` | GET `/oddil/registrace` | formulář žádosti o účet oddílu |
| `register()` | POST `/oddil/registrace` | validace → `OddilRepository::create()` → stav `cekajici` → PRG na oznámení |
| `oznameni(string $typ, string $token)` | GET `/oddil/oznameni/([a-z-]+)/([a-f0-9]{32})` | prostý text oznámení místo e-mailu (7.3) |
| `logout()` | POST `/oddil/odhlaseni` | odhlášení |

### 7.1 Formulář registrace

Pole: název oddílu, kontaktní jméno, e-mail, telefon (nepovinný), heslo, heslo znovu,
souhlas se zpracováním údajů.

Serverová validace ve stylu
[RegistrationController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/RegistrationController.php#L68-L109):

- název oddílu povinný, 3–100 znaků, **nesmí kolidovat** s existujícím oddílem
  (case-insensitive, bez diakritiky),
- e-mail povinný, `FILTER_VALIDATE_EMAIL`, nesmí už být použitý,
- heslo min. 8 znaků a musí se rovnat kontrole,
- souhlas `1`.

Při chybě se hodnoty vrací přes `flash_set('old', …)`, **heslo se nevrací**.

### 7.2 Rate limiting

Nasazení stávajícího
[RateLimit.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Helpers/RateLimit.php):

- registrace: max. 3 pokusy / hodinu na IP, klíč `oddil-registrace:<ip>`,
- přihlášení: max. 5 pokusů / 15 minut na IP, klíč `oddil-login:<ip>`.

Logování přes [Log.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Helpers/Log.php)
s prefixem `oddil:` do `storage/login.log`, neoprávněný přístup do `storage/security.log`.

### 7.3 Oznámení místo e-mailu — zatím jen text na stránce

Napojení na e-maily bude potřeba později (registrace oddílu, schválení účtu, reset
hesla). **Zatím se nic neodesílá** — jen se **simuluje doručený e-mail**: obsah,
který se bude jednou posílat, se vypíše jako **prostý text na vlastní stránce**.
Žádné `target="_blank"` a žádné nové okno — chová se to jako e-mail otevřený
v běžném okně. Až se přidá SMTP, pošle se přesně ten samý text, jen jinam.

- Jedna routa `GET /oddil/oznameni/<typ>/<token>`, jedna šablona
  `app/Views/oddil/oznameni.php` — `<pre>` s textem v minimálním layoutu bez navigace.
- `token` je **přístupový klíč** (32 znaků z `random_bytes`, uložený jako
  `token_zadosti`); bez platného tokenu stránka vrací 404. Je to tentýž token,
  který se později vloží do e-mailu.
- Stránka má `robots: noindex, nofollow`.

| `typ` | Obsah textu (přesně to, co bude jednou v e-mailu) |
|---|---|
| `zadost-prijata` | „Žádost o účet oddílu <název> byla přijata a čeká na schválení pořadatelem.“ + odkaz na stav žádosti |
| `stav-zadosti` | aktuální stav: `čeká na schválení` / `schváleno` + odkaz na přihlášení / `zamítnuto` |
| `ucet-schvalen` | „Účet oddílu <název> byl schválen, můžete se přihlásit.“ + odkaz na přihlášení |

- Po odeslání registrace se **PRG** přesměruje rovnou na
  `/oddil/oznameni/zadost-prijata/<token>` (stejný vzor jako
  [confirmation()](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/RegistrationController.php#L146-L167)).
- Na oznámení se odkazuje z `/oddil/prihlaseni`, ze zóny `/oddil` i z administrace
  normálním odkazem — **bez** `target="_blank"`.
- Text obsahuje **absolutní URL** (odkaz na stav žádosti i na přihlášení), aby byly
  použitelné hned a po napojení e-mailů se nemusely měnit. Odkaz na stav žádosti
  si vedoucí uloží do záložek — přesně jako by to dělal s odkazem v e-mailu.
- Text sestavuje **jediná metoda** `OddilAuthController::textOznameni(string $typ, array $oddil): string`,
  aby se při napojení e-mailů neopisoval.
- **Nové heslo po resetu** (admin) se na samostatnou stránku nedává — zobrazí se
  jako text přímo v administraci s poznámkou, že se později pošle e-mailem.

Administrace má navíc počítadlo čekajících (9.1), takže pořadatel o nové žádosti
ví, aniž by mu chodil e-mail.

### 7.4 Odkaz na veřejné přihlášce

Na [registration.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/registration.php)
přibude odstavec: „Jste vedoucí oddílu a přihlašujete více závodníků? Přihlaste se
nebo si založte účet oddílu.“

## 8. Zóna `/oddil`

Vše za `OddilAuth::guard()`, vlastní layout bez veřejné navigace (obdoba
[admin/layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/admin/layout.php)).

| Routa | Kontroler::metoda | Obsah |
|---|---|---|
| GET `/oddil` | `OddilController::index` | nástěnka: název, stav, soupiska, přihlášky do ročníku, celková částka |
| GET `/oddil/zavodnici` | `OddilController::zavodnici` | soupiska + formulář na přidání závodníka |
| POST `/oddil/zavodnici` | `OddilController::pridejZavodnika` | přidání závodníka do soupisky |
| POST `/oddil/zavodnici/(\d+)` | `OddilController::upravZavodnika` | úprava (jméno, ročník narození, aktivní) |
| POST `/oddil/zavodnici/(\d+)/smazat` | `OddilController::smazZavodnika` | odstranění ze soupisky |
| GET `/oddil/prihlaska` | `OddilRegistrationController::create` | hromadná přihláška na ročník |
| POST `/oddil/prihlaska` | `OddilRegistrationController::store` | zpracování a uložení |
| GET `/oddil/prihlaska/potvrzeni` | `OddilRegistrationController::confirmation` | potvrzení s rozpisem a jedním VS |
| GET `/oddil/prihlaska/(V30-\d{4}-\d{4})` | `OddilRegistrationController::show` | detail dřívější přihlášky |

### 8.1 Formulář hromadné přihlášky

Pro každého závodníka soupisky zaškrtávací políčko „jede“ + select kategorie.
Pořadí: nejdřív aktivní závodníci, kteří ještě nejsou přihlášení v tomto ročníku.

**Předvyplnění kategorie** dvěma vodítky, v tomto pořadí:

1. **Ročník narození** → kategorie se dopočítá z `vek_od` / `vek_do`
   (např. narozen 2013, ročník 2027 → 14 let → `children-11-14`).
   U kategorií s `vek_od = null` se nevybírá nic, vedoucí zvolí ručně.
2. **`kategorie_2026`** ze soupisky → použije se, když ročník narození chybí.

Předvyplnění je jen návrh, vedoucí má vždy poslední slovo.

**Uložení:** jeden záznam v `prihlasky.json` s `typ = oddil`, jedním
`variabilni_symbol` a `castka` = součet `cena` zvolených kategorií. Podmínky:
alespoň jeden závodník, každý se zvolenou kategorií z allowlistu, ročník narození
vyplněný a validní, a u kategorií s `vek_od`/`vek_do` věk odpovídající.

**Soukromí:** oddíl vidí **jen svoje** závodníky a svoje přihlášky. Všechny dotazy
filtrují přes `oddil_id` ze session, nikdy přes parametr z URL — pokus
o `/oddil/prihlaska/<cizí id>` musí skončit 404, ne zobrazením cizích dat.

### 8.2 Soupiska a chybějící ročník narození

Seed z 26. ročníku naplní jen jméno a `kategorie_2026`; ročník narození zdrojová
listina neobsahuje. Vedoucí ho doplní při první přihlášce.

Aby to nebylo otročením u dvaceti dětí, formulář přihlášky u závodníků bez ročníku
narození zobrazí inline pole „rok narození“ a po odeslání se hodnota **uloží
i do soupisky** pro příště. Hodnoty se nedomýšlejí ani neodvozují z kategorie.

## 9. Administrace

### 9.1 Sekce „Oddíly“

**Nový `app/Controllers/Admin/OddilAdminController.php`**

| Routa | Metoda | Popis |
|---|---|---|
| GET `/admin/oddily` | `index` | čekající nahoře, pak schválené; počty, celková částka |
| POST `/admin/oddily/(\d+)/schvalit` | `schvalit` | `stav = schvaleny` |
| POST `/admin/oddily/(\d+)/zamitnout` | `zamitnout` | `stav = zamitnuty` |
| POST `/admin/oddily/(\d+)/heslo` | `resetHeslo` | vygeneruje nové heslo, jednorázově zobrazí |
| POST `/admin/oddily/(\d+)/smazat` | `smazat` | smazání účtu i soupisky |
| POST `/admin/oddily/import` | `import` | import soupisek z 26. ročníku (10) |

Všechny změny se logují přes `Log::admin()`. V
[admin/layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/admin/layout.php)
přibude položka „Oddíly“ s **badge počtem čekajících** přes `pocetCekajicich()`.

### 9.2 Sekce „Přihlášky“

Dosud administrace přihlášky vůbec nevidí — pořadatel nemá z čeho sestavit startovní
listinu. Proto:

| Routa | Metoda | Popis |
|---|---|---|
| GET `/admin/prihlasky` | `index` | všechny přihlášky ročníku, filtr oddíl / jednotlivec / stav |
| POST `/admin/prihlasky/(V30-\d{4}-\d{4})/stav` | `setStav` | `zaplaceno` / `stornovano` / `nova` |
| GET `/admin/prihlasky/export.csv` | `export` | CSV všech přihlášek ročníku |

CSV je pro pořadatele klíčové — z něj se staví startovní listina a předává se
časoměřičům. Sloupce: `prihlaska_id, typ, oddil, variabilni_symbol, castka,
jmeno, rocnik_narozeni, kategorie, kategorie_nazev, trat, cena, stav, vytvoreno`.
Oddělené středníkem, kódování UTF-8 s BOM (kvůli Excelu), `Content-Disposition: attachment`.

### 9.3 Rozhodnutí k potvrzení (mimo původní zadání)

Přihlášky jednotlivců se dnes ukládají **jen do session** a ztrácejí se. Soubor
`prihlasky.json` ale vzniká pro oddíly, takže se nabízí ukládat do něj i jednotlivce
(`typ = jednotlivec`) — pořadatel tím získá jeden přehled a CSV pro všechny závodníky.

**Je to nad rámec původního zadání**, proto to plán nechává jako samostatnou položku:

- **Varianta A** — doplnit hned (drobná úprava `store()` v `RegistrationController`).
- **Varianta B** — jednotlivci zůstanou v session a uloží se později.

Doporučuji A: bez toho je sekce „Přihlášky“ i CSV poloviční a část dat dál mizí.

## 10. Import soupisek z 26. ročníku

**Nový `config/oddily-2026.php`** — jediné místo se zdrojovými daty, odvozenými
z oficiální listiny 26. ročníku (stejná data, ze kterých čte
[StartListController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/StartListController.php)
a [ResultsController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/ResultsController.php)):

```php
return [
    'Vodáci Choceň' => [
        ['jmeno' => 'Karlík Tobiáš',  'kategorie' => 'juniors'],
        ['jmeno' => 'Karlík Lukáš',   'kategorie' => 'juniors'],
        ['jmeno' => 'Horký Jakub',    'kategorie' => 'juniors'],
        // … Voženílek Štěpán, Voženílek Martin, Kutlvašr Luděk,
        //   Doskočilová Ela, Kutlvašrová Marie
    ],
    'SKK Vysoké Mýto' => [ /* … */ ],
    // … TJ Sokol Troja, TJ KVT Pardubice, TJ Synthesia Pardubice,
    //    Spartak Letohrad, Kačeři Chrudim, Dračí legie Pardubice
];
```

**Mapování kategorií:** listina používá jiné názvy než přihláška
(„Mládežníci 7 km – dívky“, „Junioři“, „Ženy PRO – volně“). Mapování na klíče
z `config/zavod.php` bude součástí seed souboru, aby se nepřekládalo za běhu.

**Průběh importu:** přiřadí soupisky **existujícím** účtům oddílů podle shody názvu
(case-insensitive, tolerantně k diakritice). Účty sám nezakládá — ty vznikají
samoobslužnou registrací. Závodníky bez oddílu (Klekar Theodor, Klekar Viktor,
Klekarová Martina, Smítalová Martina) import přeskakuje, stejně jako prázdný oddíl.

Import je **idempotentní** — opakované spuštění nesmí duplikovat závodníky
(porovnává se jméno v rámci oddílu) ani přepsat už vyplněný ročník narození.

## 11. Změny stávajícího kódu

| Soubor | Změna |
|---|---|
| [web/index.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/web/index.php) | načtení `OddilAuth` a nových kontrolerů; guard podle role; nové routy; kategorie z configu |
| [config/helpers.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/config/helpers.php) | helper `zavod()` se `static` cache |
| [RegistrationController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/RegistrationController.php) | kategorie z configu; účet z configu místo mocku; potvrzení zmíní lhůtu včasné platby; volitelně 9.3 |
| [RaceController.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Controllers/RaceController.php) | tabulka kategorií z configu + sloupec platby na místě |
| [registration.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/registration.php) | odkaz na přihlášení / registraci oddílu |
| [registration_confirmation.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/registration_confirmation.php) | lhůta včasné platby a skutečný účet z configu |
| [admin/layout.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Views/admin/layout.php) | položka „Oddíly“ s badge + „Přihlášky“ |
| [AdminAuth.php](file:///c:/Users/onvin/Documents/WEB/v30/v30/app/Middleware/AdminAuth.php) | přesun kontroly nečinnosti do sdílené logiky (aby se neopisovala v `OddilAuth`) |

## 12. Bezpečnost a GDPR

- **Hesla** vždy `password_hash(PASSWORD_DEFAULT)`, nikdy plaintext; ověření
  `password_verify` + `hash_equals` na e-mail.
- **CSRF + honeypot** na všech POST (registrace, login, soupiska, přihláška, admin akce).
- **Rate limiting** na registraci i přihlášení oddílu.
- **Logování** přihlášení, neoprávněných přístupů a admin změn (anonymizovaná IP).
- **Izolace dat:** dotazy na soupisku i přihlášky vždy přes `oddil_id` ze session.
- **GDPR:** soupisku drží pořadatel u účtu oddílu, oddíl vidí jen svoje závodníky.
  Ročník narození se nikde veřejně nezobrazuje. Smazáním účtu (admin) se maží
  i soupiska; přihlášky zůstávají kvůli účetnictví, ale bez vazby na účet.
- **Obsazení názvu cizího oddílu:** samoobslužná registrace to umožňuje. Kryje to
  schválení pořadatelem (odmítnutí / přejmenování) + rate limit. Pro akci tohoto
  rozsahu dostačující; důsledek vědomě přijímáme.
- **Token oznámení** je náhodných 32 znaků z `random_bytes`, neslouží k přihlášení —
  jen ke čtení textu oznámení. Neumožňuje měnit údaje ani nahlížet do soupisky.
  Obcházení: token se nedá uhodnout hrubou silou a nezobrazuje nic citlivějšího než
  stav vlastní žádosti.
- `storage/` je už blokovaná přes
  [storage/.htaccess](file:///c:/Users/onvin/Documents/WEB/v30/v30/storage/.htaccess)
  a kořenový `.htaccess`, takže nové JSON soubory jsou chráněné bez další práce.

## 13. Přehled souborů

**Nové**

- `config/zavod.php` (ročník, kategorie, ceny, účet), `config/oddily-2026.php` (seed)
- `app/Models/OddilRepository.php`, `app/Models/RegistrationRepository.php`
- `app/Middleware/OddilAuth.php`
- `app/Controllers/OddilAuthController.php`, `app/Controllers/OddilController.php`,
  `app/Controllers/OddilRegistrationController.php`
- `app/Controllers/Admin/OddilAdminController.php`, `app/Controllers/Admin/RegistrationAdminController.php`
- `app/Views/oddil/layout.php`, `prihlaseni.php`, `registrace.php`, `oznameni.php`,
  `index.php`, `zavodnici.php`, `prihlaska.php`, `potvrzeni.php`, `detail.php`
- `app/Views/admin/oddily/index.php`, `app/Views/admin/prihlasky/index.php`
- `storage/oddily.json`, `storage/prihlasky.json` (vzniknou za běhu)

**Upravené** — viz tabulka v sekci 11.

## 14. Otevřené otázky k potvrzení

1. **Bankovní spojení** pro platbu startovného — na vodacka30.cz zveřejněné není,
   musí ho dodat pořadatel (3.3). Do té doby zůstane `ucet` v configu prázdný.
2. **Přihlášky jednotlivců do `prihlasky.json`** (9.3 — varianta A/B). Doporučuji A.
3. **Odesílací cesta pro budoucí e-maily** — SMTP pořadatele, nebo služba typu
   Mailgun / SendGrid? Bez SPF a DKIM na doméně budou potvrzení padat do spamu,
   což je u přihlášek na závod horší než neposílat nic.

Ostatní původní rozpory jsou vyřešené z vodacka30.cz (3.6): ročník se jede
**16. ledna 2027**, uzávěrka je **15. ledna**, startovné **200 / 300 / 400 Kč**,
`veterans` je platná kategorie a věkové hranice dětských kategorií odpovídají
propozicím. U juniorské a dospělých kategorií propozice věk neuvádějí, proto se
u nich věk neověřuje.

## 15. Ověření

1. **Syntaxe** všech nových/upravených PHP: `php -l <soubor>`.
2. **Registrace oddílu:** vyplněný formulář → 302, záznam v `storage/oddily.json`
   se `stav = cekajici`; duplicitní název nebo e-mail → chyba, hodnoty zůstanou,
   heslo se nevrací.
3. **Neschválený účet:** přihlášení správným heslem → hláška „čeká na schválení“,
   ne „špatné heslo“; přístup na `/oddil` → 302 na login.
4. **Oznámení:** `/oddil/oznameni/stav-zadosti/<token>` vrátí prostý text se správným
   stavem; neplatný token → 404; stránka je `noindex` a **nikde není `target="_blank"`**.
5. **Schválení:** admin schválí → `stav = schvaleny`, badge čekajících klesne;
   přihlášení nyní projde na `/oddil`.
6. **Import soupisek:** po importu má „Vodáci Choceň“ reálná jména (Karlík Tobiáš,
   Karlík Lukáš, Horký Jakub …) s `rocnik_narozeni = null`; druhé spuštění importu
   nic nezduplikuje ani nepřepíše.
7. **Předvyplnění kategorie:** závodník narozený 2013 → nabídnuta `children-11-14`;
   u závodníka bez ročníku narození → podle `kategorie_2026`.
8. **Hromadná přihláška:** výběr 3 závodníků → jediný záznam v `prihlasky.json`,
   jeden variabilní symbol, `castka` = součet cen, potvrzení zobrazí rozpis.
9. **Izolace dat:** oddíl A nevidí závodníky ani přihlášky oddílu B
   (`/oddil/prihlaska/<cizí id>` → 404).
10. **CSV export:** obsahuje všechny přihlášky ročníku, otevírá se v Excelu se
    správnou diakritikou, částky sedí na součet položek.
11. **CSRF / rate limit:** POST bez tokenu odmítnut; 4. registrace z jedné IP
    v hodině odmítnuta; 6. pokus o login v 15 minutách odmítnut bez ověření hesla.
12. **Admin akce:** reset hesla → nové funguje, staré ne; smazání oddílu odstraní
    i soupisku; vše zapsané v `storage/admin.log`.
13. **Regrese veřejných rout:** `/`, `/zavod`, `/propozice`, `/prihlaska`,
    `/startovni-listina`, `/vysledky`, `/galerie`, `/kontakt` → 200; `/admin*` dál
    chrání admin login; neznámá cesta 404, špatná metoda 405.
14. **Konzistence kategorií:** propozice i přihláška nabízejí přesně stejnou sadu
    kategorií a stejné ceny — ověřit porovnáním obou stránek.
15. Testovat na Apache `http://v30.test`.

## 16. Mimo rozsah

- **Odesílání e-mailů (SMTP)** — potvrzení registrace, informace o schválení, reset
  hesla. **Zatím nahrazeno prostým textem na stránce** (`/oddil/oznameni/…`, viz 7.3).
  Až se bude doplňovat, patří do jediného místa: nový `App\Helpers\Mail::send()`
  + konfigurace odesílatele, volaný ze tří míst (`register`, `schvalit`, `resetHeslo`),
  a **text se neopisuje** — bere se z `textOznameni()`.
- **Účty pro jednotlivce a rodiče** — vědomě se nezavádějí.
- **Online platba / QR kód** — platbu i nadále ověřuje pořadatel ručně.
- **Zveřejňování soupisek a výsledků oddílů** — ven se publikují jen oficiální
  listiny, jako dosud.
- **Samoobslužná změna zapomenutého hesla** — bez e-mailů řeší pořadatel ručně
  v adminu (reset hesla).
