<?php
/**
 * Soupiska závodníků oddílu.
 * Očekává: $soupiska, $errors.
 */
$inputClass = 'block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-400/40';
$errorClass = 'mt-1.5 text-sm text-red-600';
?>
<div class="flex flex-wrap items-end justify-between gap-4">
    <div class="min-w-0">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Zóna oddílu</p>
        <h1 class="mt-3 font-display text-2xl font-extrabold tracking-tight sm:text-3xl">Soupiska</h1>
        <p class="mt-2 text-sm text-slate-600">
            Závodníky vedete jednou a příští ročník je přihlásíte jedním krokem.
        </p>
    </div>
    <a href="/oddil/prihlaska"
       class="inline-flex items-center rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-brand-500">
        Přihlásit závodníky
    </a>
</div>

<?php if (isset($errors['obecne'])): ?>
    <p class="mt-6 <?= $errorClass ?>"><?= e($errors['obecne']) ?></p>
<?php endif; ?>

<!-- Přidání závodníka -->
<form method="post" action="/oddil/zavodnici" class="mt-8 rounded-md border border-slate-200 bg-white p-5 sm:p-6">
    <?= csrf_field() ?>
    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Přidat závodníka</p>

    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-12">
        <div class="sm:col-span-6">
            <label for="jmeno" class="block text-sm font-medium text-slate-900">Jméno a příjmení</label>
            <input type="text" id="jmeno" name="jmeno" value="<?= e(old_get('jmeno')) ?>" required maxlength="100"
                   autocomplete="off" enterkeyhint="next" class="mt-2 <?= $inputClass ?>">
            <?php if (isset($errors['jmeno'])): ?>
                <p class="<?= $errorClass ?>"><?= e($errors['jmeno']) ?></p>
            <?php endif; ?>
        </div>
        <div class="sm:col-span-3">
            <label for="rocnik_narozeni" class="block text-sm font-medium text-slate-900">
                Ročník narození <span class="font-normal text-slate-400">(nepovinné)</span>
            </label>
            <input type="text" id="rocnik_narozeni" name="rocnik_narozeni" value="<?= e(old_get('rocnik_narozeni')) ?>"
                   inputmode="numeric" maxlength="4" autocomplete="off" enterkeyhint="done" class="mt-2 <?= $inputClass ?>">
            <?php if (isset($errors['rocnik_narozeni'])): ?>
                <p class="<?= $errorClass ?>"><?= e($errors['rocnik_narozeni']) ?></p>
            <?php endif; ?>
        </div>
        <div class="flex items-end sm:col-span-3">
            <button type="submit"
                    class="inline-flex w-full items-center justify-center rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-brand-500">
                Přidat
            </button>
        </div>
    </div>
</form>

<!-- Soupiska -->
<?php if (empty($soupiska)): ?>
    <div class="mt-8 rounded-md border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
        <p class="text-sm text-slate-500">Soupiska je zatím prázdná.</p>
    </div>
<?php else: ?>
    <ul class="mt-8 space-y-px overflow-hidden rounded-md border border-slate-200 bg-slate-200">
        <?php foreach ($soupiska as $zavodnik): ?>
            <?php $zid = (int) ($zavodnik['id'] ?? 0); ?>
            <li class="bg-white px-5 py-4">
                <form method="post" action="/oddil/zavodnici/<?= $zid ?>"
                      class="grid grid-cols-1 gap-4 sm:grid-cols-12 sm:items-end">
                    <?= csrf_field() ?>

                    <div class="sm:col-span-6">
                        <label for="jmeno-<?= $zid ?>" class="block text-xs font-medium uppercase tracking-wide text-slate-500">Jméno</label>
                        <input type="text" id="jmeno-<?= $zid ?>" name="jmeno" value="<?= e($zavodnik['jmeno'] ?? '') ?>"
                               required maxlength="100" autocomplete="off" class="mt-1.5 <?= $inputClass ?>">
                    </div>

                    <div class="sm:col-span-3">
                        <label for="rocnik-<?= $zid ?>" class="block text-xs font-medium uppercase tracking-wide text-slate-500">Ročník narození</label>
                        <input type="text" id="rocnik-<?= $zid ?>" name="rocnik_narozeni"
                               value="<?= e($zavodnik['rocnik_narozeni'] ?? '') ?>"
                               inputmode="numeric" maxlength="4" autocomplete="off"
                               placeholder="např. 2013" class="mt-1.5 <?= $inputClass ?>">
                    </div>

                    <div class="sm:col-span-3">
                        <label class="flex items-center gap-2.5 text-sm text-slate-700">
                            <input type="checkbox" name="aktivni" value="1" <?= (int) ($zavodnik['aktivni'] ?? 1) === 1 ? 'checked' : '' ?>
                                   class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-600">
                            Jede i letos
                        </label>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 sm:col-span-12">
                        <button type="submit"
                                class="rounded-md border border-slate-300 px-3 py-1.5 text-sm font-semibold text-slate-700 transition-colors hover:border-slate-400 hover:bg-slate-50">
                            Uložit
                        </button>
                        <?php if (! empty($zavodnik['kategorie_2026'])): ?>
                            <span class="text-xs text-slate-400">
                                V ročníku 2026: <?= e(zavod()['kategorie'][$zavodnik['kategorie_2026']]['nazev'] ?? $zavodnik['kategorie_2026']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </form>

                <form method="post" action="/oddil/zavodnici/<?= $zid ?>/smazat"
                      class="mt-3 border-t border-slate-100 pt-3"
                      onsubmit="return confirm('Opravdu odebrat závodníka ze soupisky?');">
                    <?= csrf_field() ?>
                    <button type="submit"
                            class="text-sm font-semibold text-red-700 transition-colors hover:text-red-600">
                        Odebrat ze soupisky
                    </button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
