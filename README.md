# Vodácká 30

Projektové dokumenty jsou v kořeni workspace. Laravel aplikace je v `application/`.

## Lokální vývoj

- Laravel Framework 13.35.0, PHP 8.3.33, Composer 2.10.2 a Node.js 22.23.2 přes Laragon.
- Lokální `.env` je v `application/.env`, je ignorovaný Gitem a obsahuje lokální konfiguraci s nově vygenerovaným `APP_KEY`; neobsahuje produkční tajemství.
- `application/database/database.sqlite` je izolovaná lokální vývojová DB, ignorovaná Gitem. Obsahuje pouze veřejná, neosobní fakta závodu 2026 a plánovaný ročník 2027.
- Lokální SQLite vytvoříš z `application/` příkazy `php artisan migrate --database=sqlite` a `php artisan db:seed --database=sqlite --class=Vodacka30Seeder`.
- Tyto příkazy používej jen s lokálním SQLite. Neznámou, legacy ani produkční DB nepřipojuj ani nemigruj před read-only auditem a výslovným rozhodnutím o cílovém schématu.
- Pro lokální Laravel server spusť z `application/` `php artisan serve --host=127.0.0.1 --port=8000`; URL je `http://127.0.0.1:8000`.
- `http://v30.test` funguje přes Laragon Apache vhost v `C:/laragon/etc/apache2/sites-enabled/v30.test.conf`.

Podrobnosti, rozhodnutí a akceptační brány jsou v [AGENTS.md](AGENTS.md) a [realizačním plánu](PLAN_REALIZACE_V30.md).