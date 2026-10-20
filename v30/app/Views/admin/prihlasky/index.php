<?php
/**
 * Přehled přihlášek v administraci.
 * Očekává: $prihlasky, $rok, $filtr, $celkemZavodniku, $celkemCastka.
 */
$filtry = [
    ''           => 'Vše',
    'nova'       => 'Nezaplaceno',
    'zaplaceno'  => 'Zaplaceno',
    'stornovano' => 'Storno',
];

$stavPopis = [
    'nova'       => ['label' => 'Nezaplaceno', 'class' => 'bg-amber-100 text-amber-800'],
    'zaplaceno'  => ['label' => 'Zaplaceno',   'class' => 'bg-emerald-100 text-emerald-800'],
    'stornovano' => ['label' => 'Stornováno',  'class' => 'bg-slate-100 text-slate-500'],
];

$tlacitko = 'rounded-md border border-slate-300 px-3 py-1.5 text-sm font-semibold text-slate-700 transition-colors hover:border-slate-400 hover:bg-slate-50';
?>
<div class="flex flex-wrap items-end justify-between gap-4">
    <div class="min-w-0">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Administrace</p>
        <h1 class="mt-3 font-display text-2xl font-extrabold tracking-tight sm:text-3xl">Přihlášky <?= (int) $rok ?></h1>
        <p class="mt-2 text-sm text-slate-600">
            <?= (int) $celkemZavodniku ?> závodníků v <?= count($prihlasky) ?> přihláškách.
        </p>
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="/admin/prihlasky/export" class="<?= $tlacitko ?>">Export do CSV</a>
    </div>
</div>

<div class="mt-6 flex flex-wrap items-center gap-px overflow-hidden rounded-md border border-slate-200 bg-slate-200 sm:inline-flex">
    <div class="bg-white px-5 py-4 sm:min-w-[12rem]">
        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Nezaplaceno (bez storna)</p>
        <p class="mt-1.5 font-display text-2xl font-extrabold tabular-nums tracking-tight"><?= (int) $celkemCastka ?> Kč</p>
    </div>
    <div class="bg-white px-5 py-4 sm:min-w-[10rem]">
        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Závodníků</p>
        <p class="mt-1.5 font-display text-2xl font-extrabold tabular-nums tracking-tight"><?= (int) $celkemZavodniku ?></p>
    </div>
</div>

<div class="mt-8 flex flex-wrap gap-1 border-b border-slate-200">
    <?php foreach ($filtry as $klic => $label): ?>
        <?php $cil = $klic === '' ? '/admin/prihlasky' : '/admin/prihlasky?stav=' . urlencode($klic); ?>
        <a href="<?= e($cil) ?>"
           class="-mb-px border-b-2 px-3 py-2 text-sm font-semibold transition-colors <?= $filtr === $klic
               ? 'border-brand-600 text-brand-700'
               : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-800' ?>">
            <?= e($label) ?>
        </a>
    <?php endforeach; ?>
</div>

<?php if (empty($prihlasky)): ?>
    <div class="mt-8 rounded-md border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
        <p class="text-sm text-slate-500">V tomto filtru nejsou žádné přihlášky.</p>
    </div>
<?php else: ?>
    <ul class="mt-8 space-y-px overflow-hidden rounded-md border border-slate-200 bg-slate-200">
        <?php foreach ($prihlasky as $prihlaska): ?>
            <?php
            $pid      = (string) ($prihlaska['id'] ?? '');
            $stav     = (string) ($prihlaska['stav'] ?? 'nova');
            $popis    = $stavPopis[$stav] ?? ['label' => $stav, 'class' => 'bg-slate-100 text-slate-500'];
            $zavodnici = (array) ($prihlaska['zavodnici'] ?? []);
            $jeOddil  = ($prihlaska['typ'] ?? '') === 'oddil';
            ?>
            <li class="bg-white px-5 py-5">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-display text-base font-extrabold tabular-nums tracking-tight"><?= e($pid) ?></span>
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold <?= e($popis['class']) ?>"><?= e($popis['label']) ?></span>
                            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                                <?= $jeOddil ? 'oddíl' : 'jednotlivec' ?>
                            </span>
                        </div>
                        <p class="mt-1.5 text-sm font-semibold text-slate-800">
                            <?= e($jeOddil ? ($prihlaska['oddil_nazev'] ?? '') : ($zavodnici[0]['jmeno'] ?? '')) ?>
                        </p>
                        <p class="text-sm text-slate-500"><?= e($prihlaska['email'] ?? '') ?></p>
                        <p class="mt-1.5 text-xs text-slate-400">
                            VS <?= e($prihlaska['variabilni_symbol'] ?? '') ?> &middot;
                            <?= count($zavodnici) ?> závodníků &middot;
                            <?= e($prihlaska['vytvoreno'] ?? '') ?>
                        </p>
                    </div>
                    <p class="shrink-0 font-display text-xl font-extrabold tabular-nums tracking-tight"><?= (int) ($prihlaska['castka'] ?? 0) ?> Kč</p>
                </div>

                <details class="mt-4">
                    <summary class="cursor-pointer text-sm font-semibold text-brand-700 hover:text-brand-500">
                        Rozpis závodníků
                    </summary>
                    <div class="mt-3 divide-y divide-slate-100 rounded-md border border-slate-200">
                        <?php foreach ($zavodnici as $zavodnik): ?>
                            <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1 px-4 py-2.5">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-slate-800"><?= e($zavodnik['jmeno'] ?? '') ?></p>
                                    <p class="text-xs text-slate-500">
                                        <?= e($zavodnik['kategorie_nazev'] ?? ($zavodnik['kategorie'] ?? '')) ?>
                                        <?php if (! empty($zavodnik['rocnik_narozeni'])): ?>&middot; ročník <?= (int) $zavodnik['rocnik_narozeni'] ?><?php endif; ?>
                                    </p>
                                </div>
                                <p class="shrink-0 text-sm tabular-nums text-slate-600"><?= (int) ($zavodnik['cena'] ?? 0) ?> Kč</p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </details>

                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <?php if ($stav !== 'zaplaceno'): ?>
                        <form method="post" action="/admin/prihlasky/<?= e($pid) ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="stav" value="zaplaceno">
                            <button type="submit"
                                    class="rounded-md bg-emerald-600 px-3 py-1.5 text-sm font-semibold text-white transition-colors hover:bg-emerald-500">
                                Označit jako zaplaceno
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if ($stav !== 'nova'): ?>
                        <form method="post" action="/admin/prihlasky/<?= e($pid) ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="stav" value="nova">
                            <button type="submit" class="<?= $tlacitko ?>">Vrátit na nezaplaceno</button>
                        </form>
                    <?php endif; ?>

                    <?php if ($stav !== 'stornovano'): ?>
                        <form method="post" action="/admin/prihlasky/<?= e($pid) ?>"
                              onsubmit="return confirm('Stornovat přihlášku <?= e($pid) ?>?');">
                            <?= csrf_field() ?>
                            <input type="hidden" name="stav" value="stornovano">
                            <button type="submit"
                                    class="rounded-md border border-red-200 px-3 py-1.5 text-sm font-semibold text-red-700 transition-colors hover:border-red-300 hover:bg-red-50">
                                Stornovat
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
