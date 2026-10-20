<?php
/**
 * Formulář příspěvku (nový i úprava).
 * Očekává: $prispevek, $errors.
 */
$jeUprava = ! empty($prispevek['id']);
$akce     = $jeUprava ? '/admin/prispevky/' . (int) $prispevek['id'] : '/admin/prispevky';

$maChyby = $errors !== [];
$hodnota = static function (string $klic, string $vychozi = '') use ($maChyby, $prispevek): string {
    if ($maChyby) {
        return old_get($klic, $vychozi);
    }

    return (string) ($prispevek[$klic] ?? $vychozi);
};

$zverejneno = $maChyby
    ? old_get('zverejneno', (string) ($prispevek['zverejneno'] ?? '1')) === '1'
    : (int) ($prispevek['zverejneno'] ?? 1) === 1;
?>
<div class="flex flex-wrap items-end justify-between gap-4">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Novinky</p>
        <h1 class="mt-3 font-display text-2xl font-extrabold tracking-tight sm:text-3xl">
            <?= $jeUprava ? 'Upravit příspěvek' : 'Nový příspěvek' ?>
        </h1>
    </div>
    <a href="/admin" class="text-sm font-semibold text-slate-500 transition-colors hover:text-slate-800">← Zpět na seznam</a>
</div>

<form method="post" action="<?= e($akce) ?>" class="mt-8 max-w-2xl space-y-6">
    <?= csrf_field() ?>

    <div>
        <label for="nadpis" class="block text-sm font-semibold text-slate-700">Nadpis</label>
        <input type="text" id="nadpis" name="nadpis" maxlength="120" value="<?= e($hodnota('nadpis')) ?>" required
               class="mt-2 block w-full rounded-md border <?= isset($errors['nadpis']) ? 'border-red-400' : 'border-slate-300' ?> bg-white px-3 py-2 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-400/40">
        <?php if (isset($errors['nadpis'])): ?>
            <p class="mt-1.5 text-sm text-red-700"><?= e($errors['nadpis']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="datum" class="block text-sm font-semibold text-slate-700">Datum</label>
        <input type="date" id="datum" name="datum" value="<?= e($hodnota('datum', date('Y-m-d'))) ?>" required
               class="mt-2 block w-full max-w-xs rounded-md border <?= isset($errors['datum']) ? 'border-red-400' : 'border-slate-300' ?> bg-white px-3 py-2 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-400/40">
        <?php if (isset($errors['datum'])): ?>
            <p class="mt-1.5 text-sm text-red-700"><?= e($errors['datum']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="perex" class="block text-sm font-semibold text-slate-700">Perex</label>
        <textarea id="perex" name="perex" rows="3" maxlength="300"
                  class="mt-2 block w-full rounded-md border <?= isset($errors['perex']) ? 'border-red-400' : 'border-slate-300' ?> bg-white px-3 py-2 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-400/40"><?= e($hodnota('perex')) ?></textarea>
        <p class="mt-1.5 text-xs text-slate-400">Krátké shrnutí zobrazené na úvodní stránce (nejvýše 300 znaků).</p>
        <?php if (isset($errors['perex'])): ?>
            <p class="mt-1.5 text-sm text-red-700"><?= e($errors['perex']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="text" class="block text-sm font-semibold text-slate-700">Text</label>
        <textarea id="text" name="text" rows="8"
                  class="mt-2 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-400/40"><?= e($hodnota('text')) ?></textarea>
        <p class="mt-1.5 text-xs text-slate-400">Nepovinné. Delší text příspěvku.</p>
    </div>

    <div class="flex items-center gap-3 rounded-md border border-slate-200 bg-white px-4 py-3">
        <input type="checkbox" id="zverejneno" name="zverejneno" value="1" <?= $zverejneno ? 'checked' : '' ?>
               class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-400">
        <label for="zverejneno" class="text-sm font-medium text-slate-700">Zveřejnit na úvodní stránce</label>
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit"
                class="inline-flex items-center rounded-md bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-500">
            <?= $jeUprava ? 'Uložit změny' : 'Vytvořit příspěvek' ?>
        </button>
        <a href="/admin" class="text-sm font-medium text-slate-500 transition-colors hover:text-slate-800">Zrušit</a>
    </div>
</form>
