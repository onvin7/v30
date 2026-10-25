# Vodácká 30

Web tradičního **běžkařského** závodu Vodácká 30, který pořádá **TJ KVT Pardubice** v Orlických horách. Vedle prezentace závodu slouží i jako nástroj pro pořadatele: přihlášky, startovní listina a výsledky.

Nejbližší (pořadatelem potvrzený) **27. ročník** se pojede **16. ledna 2027**. Naposledy odjetý **26. ročník** byl **17. ledna 2026**. Start a cíl jsou u chaty Bedřichovka v Orlickém Záhoří, kontrolní bod na Pěticestí.

## Kde je co

Repozitář drží dvě věci odděleně:

```text
v30/                kořen repozitáře – jen dokumentace (tyto soubory)
`-- v30/            vlastní aplikace (PHP, šablony, konfigurace, storage/)
```

- **Hledáš kód?** Všechno je v podsložce `v30/v30`.
- **Hledáš, jak to funguje a proč?** Čti dokumenty níže.

## Dokumentace

| Dokument | Co v něm najdeš |
|---|---|
| [PROJEKT.md](PROJEKT.md) | Zadání a kontext: co je závod, odkud se berou data, co které stránky obsahují, jaký je rozsah funkcí, co ještě chybí a jaké otázky čekají na pořadatele. |
| [TECHNICKA_DOKUMENTACE.md](TECHNICKA_DOKUMENTACE.md) | Jak je aplikace postavená: struktura souborů, tok požadavku, seznam adres (rout), ukládání dat, bezpečnost, konvence kódu a jak změny ověřit. |
| [DESIGN.md](DESIGN.md) | Vizuální pravidla: paleta, typografie, komponenty, co se záměrně nepoužívá. |

Aktuální stav projektu shrnuje tato stránka, plány a úkoly jsou v [PROJEKT.md](PROJEKT.md#co-zbývá). Hotové a neaktuální plány z dřívějška jsou v `.trae/documents/archive/`.

Průběžný stav práce – co je hotové v kódu, co právě běží a co je rozpracované – drží živý plán [`v30/PLAN.md`](v30/PLAN.md). Vychází z auditu veřejného webu [`v30/.trae/documents/audit-webu-v30.md`](v30/.trae/documents/audit-webu-v30.md); zadání pro další práci jsou v [`v30/TODO.md`](v30/TODO.md).

## Co je hotové

Veřejný web je hotový a naplněný daty:

- Úvod, O závodu, Propozice a trať (včetně map všech tří tratí), Startovní listina, Výsledky s archivem ročníků, Galerie, Kontakt.
- Fotky se servírují z repozitáře (`v30/v30/assets/img/`, 63 souborů ve více šířkách), web už neodkazuje na Zoneramu.
- Chybové stránky 404 a 405, mapa stránek `/sitemap.xml` a `/robots.txt`.
- Přihláška jednotlivce: serverová validace, CSRF ochrana, honeypot. Přihláška se uloží a zobrazí se rekapitulace s platebními instrukcemi. Potvrzovací e-mail se zatím neposílá, text se zobrazí přímo na stránce. Limit pokusů je hotový v `App\Helpers\RateLimit`, ale přihláška ho zatím nevolá.

## Co je rozpracované

Dvě části jsou hotové ve větvích, které **nejsou sloučené do `main`**:

| Větev | Co přidává |
|---|---|
| `feature/ucty-oddilu` | Zóna oddílu `/oddil` – registrace oddílu se schválením, soupiska závodníků, hromadná přihláška. |
| `feature/administrace` | Administrace `/admin` – příspěvky na úvod, schvalování oddílů, přehled přihlášek a export do CSV. |

Obě větve přidávají routy do `web/index.php`, takže při sloučení vznikne v mapě rout konflikt. Na `main` proto zóna oddílu ani administrace neběží a odkaz `/oddil/registrace` na stránce přihlášky vrací 404. Sloučení větví je první bod v seznamu [co zbývá](PROJEKT.md#co-zbývá).

## Jak to spustit

Aplikace potřebuje PHP (bez databáze a bez instalace závislostí). Složka `v30/v30/storage/` musí být zapisovatelná, ukládají se do ní přihlášky a logy.

**Apache (doporučeno).** Vhost `http://v30.test` s document rootem do `v30/v30`, kde leží `.htaccess`. Ten přepíše všechny cesty, které nemíří na existující soubor, na `index.php`. Bez `.htaccess` se neprojeví blokace interních složek ani přesměrování.

**Vestavěný server PHP.** Z kořene aplikace spusť:

```powershell
php -S 127.0.0.1:8000 index.php
```

a otevři `http://127.0.0.1:8000`. Vestavěný server `.htaccess` nerespektuje, takže routy fungují, ale přepis adres a bezpečnostní hlavičky ne.

Podrobnosti (routy, ukládání dat, jak ověřit změnu) jsou v [TECHNICKA_DOKUMENTACE.md](TECHNICKA_DOKUMENTACE.md).

## Kontakty na pořadatele

- **Pořadatel:** TJ KVT Pardubice
- **Ředitel závodu:** Aleš Křivka – 724 308 469
- **Hlavní rozhodčí:** Jiří Roleček – 775 125 883
