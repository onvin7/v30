<?php
/**
 * Hromadná přihláška oddílu.
 * Očekává: $radky, $kategorie, $rok, $errors, $volby.
 */
$inputClass = 'block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-400/40';
$errorClass = 'mt-1.5 text-sm text-red-600';
?>
<div class="flex flex-wrap items-end justify-between gap-4">
    <div class="min-w-0">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Zóna oddílu</p>
        <h1 class="mt-3 font-display text-2xl font-extrabold tracking-tight sm:text-3xl">Přihláška do ročníku <?= (int) $rok ?></h1>
        <p class="mt-2 text-sm text-slate-600">
            Zaškrtněte závodníky, kteří pojedou, a u každého zkontrolujte kategorii. Startovné se platí za oddíl jedním převodem.
        </p>
    </div>
    <a href="/oddil/zavodnici"
       class="inline-flex items-center rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-900 transition-colors hover:border-slate-400 hover:bg-white">
        Upravit soupisku
    </a>
</div>

<?php if (isset($errors['obecne'])): ?>
    <p class="mt-6 rounded-md border-l-2 border-red-600 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert"><?= e($errors['obecne']) ?></p>
<?php endif; ?>

<?php if (empty($radky)): ?>
    <div class="mt-8 rounded-md border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
        <p class="text-sm text-slate-500">V soupisce zatím nejsou žádní závodníci.</p>
        <a href="/oddil/zavodnici" class="mt-3 inline-block text-sm font-semibold text-brand-700 hover:text-brand-500">
            Přidat závodníky →
        </a>
    </div>
<?php else: ?>
    <form method="post" action="/oddil/prihlaska" class="mt-8" novalidate>
        <?= csrf_field() ?>

        <ul class="space-y-px overflow-hidden rounded-md border border-slate-200 bg-slate-200">
            <?php foreach ($radky as $radek): ?>
                <?php
                $zavodnik = $radek['zavodnik'];
                $zid      = (int) ($zavodnik['id'] ?? 0);
                $jmeno    = (string) ($zavodnik['jmeno'] ?? '');
                $rocnik   = (int) ($zavodnik['rocnik_narozeni'] ?? 0);

                $vybrano = isset($volby['jede'][$zid]) && (string) $volby['jede'][$zid] === '1';
                $vybranaKat = isset($volby['kategorie'][$zid])
                    ? (string) $volby['kategorie'][$zid]
                    : (string) $radek['predvyplnena'];
                $vybranaRocnik = isset($volby['rocnik_narozeni'][$zid])
                    ? (string) $volby['rocnik_narozeni'][$zid]
                    : ($rocnik > 0 ? (string) $rocnik : '');

                $chybaKat    = $errors['kategorie_' . $zid] ?? null;
                $chybaRocnik = $errors['rocnik_' . $zid] ?? null;
                ?>
                <li class="bg-white px-5 py-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-12 sm:items-end">

                        <div class="sm:col-span-5">
                            <label for="jede-<?= $zid ?>" class="flex items-start gap-3">
                                <input type="checkbox" id="jede-<?= $zid ?>" name="jede[<?= $zid ?>]" value="1"
                                       data-cena="<?= (int) ($kategorie[$vybranaKat]['cena'] ?? 0) ?>"
                                       <?= $vybrano ? 'checked' : '' ?>
                                       class="js-jede mt-0.5 h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-600">
                                <span class="min-w-0">
                                    <span class="block text-sm font-semibold text-slate-900"><?= e($jmeno) ?></span>
                                    <?php if (! empty($radek['jizPrihlasen'])): ?>
                                        <span class="mt-0.5 block text-xs text-slate-400">Už je v tomto ročníku přihlášený</span>
                                    <?php elseif ((int) ($zavodnik['aktivni'] ?? 1) !== 1): ?>
                                        <span class="mt-0.5 block text-xs text-slate-400">V soupisce označen jako „nejede“</span>
                                    <?php endif; ?>
                                </span>
                            </label>
                        </div>

                        <div class="sm:col-span-4">
                            <label for="kategorie-<?= $zid ?>" class="block text-xs font-medium uppercase tracking-wide text-slate-500">Kategorie</label>
                            <select id="kategorie-<?= $zid ?>" name="kategorie[<?= $zid ?>]"
                                    class="js-kategorie mt-1.5 <?= $inputClass ?>">
                                <option value="">Vyberte kategorii</option>
                                <?php foreach ($kategorie as $kod => $kat): ?>
                                    <option value="<?= e($kod) ?>" data-cena="<?= (int) $kat['cena'] ?>"
                                            <?= $vybranaKat === $kod ? 'selected' : '' ?>>
                                        <?= e($kat['nazev']) ?> (<?= e($kat['trat']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($chybaKat !== null): ?>
                                <p class="<?= $errorClass ?>"><?= e($chybaKat) ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="rocnik-<?= $zid ?>" class="block text-xs font-medium uppercase tracking-wide text-slate-500">Ročník narození</label>
                            <input type="text" id="rocnik-<?= $zid ?>" name="rocnik_narozeni[<?= $zid ?>]"
                                   value="<?= e($vybranaRocnik) ?>"
                                   inputmode="numeric" maxlength="4" autocomplete="off" placeholder="např. 2013"
                                   class="mt-1.5 <?= $inputClass ?>">
                            <?php if ($chybaRocnik !== null): ?>
                                <p class="<?= $errorClass ?>"><?= e($chybaRocnik) ?></p>
                            <?php endif; ?>
                        </div>

                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="mt-6 flex flex-wrap items-center justify-between gap-4 rounded-md border border-slate-200 bg-white px-5 py-4">
            <p class="text-sm text-slate-600">
                Vybraných závodníků: <span class="font-semibold tabular-nums text-slate-900" id="souhrn-pocet">0</span>
            </p>
            <p class="font-display text-lg font-extrabold tabular-nums tracking-tight">
                Startovné celkem: <span id="souhrn-castka">0</span> Kč
            </p>
        </div>

        <div class="mt-6 flex flex-wrap items-center gap-4">
            <button type="submit"
                    class="inline-flex items-center rounded-md bg-brand-600 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-brand-500">
                Odeslat přihlášku
            </button>
            <p class="text-xs text-slate-500">
                U kategorií bez vypsaného věkového rozsahu se věk nekontroluje – kategorii volí vedoucí oddílu.
            </p>
        </div>
    </form>

    <script>
        (function () {
            var radky = Array.prototype.slice.call(document.querySelectorAll('.js-jede'));

            function prepocitej() {
                var pocet = 0;
                var castka = 0;

                radky.forEach(function (check) {
                    if (!check.checked) {
                        return;
                    }

                    var select = check.closest('li').querySelector('.js-kategorie');
                    var volba = select.options[select.selectedIndex];

                    if (select.value === '') {
                        return;
                    }

                    pocet++;
                    castka += parseInt((volba && volba.dataset.cena) || '0', 10);
                });

                document.getElementById('souhrn-pocet').textContent = String(pocet);
                document.getElementById('souhrn-castka').textContent = String(castka);
            }

            radky.forEach(function (check) {
                var select = check.closest('li').querySelector('.js-kategorie');

                check.addEventListener('change', prepocitej);
                select.addEventListener('change', function () {
                    if (select.value !== '' && !check.checked) {
                        check.checked = true;
                    }
                    prepocitej();
                });
            });

            prepocitej();
        })();
    </script>
<?php endif; ?>
