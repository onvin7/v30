<?php
/**
 * Nástěnka oddílu.
 * Očekává: $oddil, $prihlasky, $soupiska, $celkem.
 */
$rok    = (int) zavod()['rocnik']['rok'];
$stav   = (string) ($oddil['stav'] ?? '');

$stavLbl = [
    'cekajici'  => 'Čeká na schválení',
    'schvaleny' => 'Schváleno',
    'zamitnuty' => 'Zamítnuto',
][$stav] ?? $stav;
?>
<div class="flex flex-wrap items-end justify-between gap-4">
    <div class="min-w-0">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Zóna oddílu</p>
        <h1 class="mt-3 font-display text-2xl font-extrabold tracking-tight sm:text-3xl"><?= e($oddil['nazev'] ?? '') ?></h1>
        <p class="mt-2 text-sm text-slate-600">Přihláška do <?= (int) zavod()['rocnik']['cislo'] ?>. ročníku &middot; <?= e(zavod()['rocnik']['datum']) ?></p>
    </div>
    <?php if ($stav === 'schvaleny'): ?>
        <span class="shrink-0 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800"><?= e($stavLbl) ?></span>
    <?php else: ?>
        <span class="shrink-0 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800"><?= e($stavLbl) ?></span>
    <?php endif; ?>
</div>

<dl class="mt-8 grid grid-cols-1 gap-px overflow-hidden rounded-md border border-slate-200 bg-slate-200 sm:grid-cols-3">
    <div class="bg-white px-5 py-5">
        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Závodníci v soupisce</dt>
        <dd class="mt-2 font-display text-3xl font-extrabold tabular-nums tracking-tight"><?= count($soupiska) ?></dd>
    </div>
    <div class="bg-white px-5 py-5">
        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Přihlášky v ročníku <?= $rok ?></dt>
        <dd class="mt-2 font-display text-3xl font-extrabold tabular-nums tracking-tight"><?= count($prihlasky) ?></dd>
    </div>
    <div class="bg-white px-5 py-5">
        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Startovné celkem</dt>
        <dd class="mt-2 font-display text-3xl font-extrabold tabular-nums tracking-tight"><?= (int) $celkem ?> Kč</dd>
    </div>
</dl>

<div class="mt-10 flex flex-wrap gap-3">
    <a href="/oddil/prihlaska"
       class="inline-flex items-center rounded-md bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-500">
        Přihlásit závodníky
    </a>
    <a href="/oddil/zavodnici"
       class="inline-flex items-center rounded-md border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-900 transition-colors hover:border-slate-400 hover:bg-white">
        Upravit soupisku
    </a>
</div>

<div class="mt-12">
    <h2 class="font-display text-lg font-extrabold tracking-tight">Přihlášky v ročníku <?= $rok ?></h2>

    <?php if (empty($prihlasky)): ?>
        <div class="mt-4 rounded-md border border-dashed border-slate-300 bg-white px-6 py-10 text-center">
            <p class="text-sm text-slate-500">Zatím jste nikoho nepřihlásili.</p>
            <a href="/oddil/prihlaska" class="mt-3 inline-block text-sm font-semibold text-brand-700 hover:text-brand-500">
                Přihlásit závodníky →
            </a>
        </div>
    <?php else: ?>
        <ul class="mt-4 divide-y divide-slate-200 overflow-hidden rounded-md border border-slate-200 bg-white">
            <?php foreach ($prihlasky as $prihlaska): ?>
                <li class="flex flex-wrap items-center justify-between gap-4 px-5 py-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="/oddil/prihlaska/<?= e($prihlaska['id'] ?? '') ?>"
                               class="font-semibold tabular-nums text-slate-900 transition-colors hover:text-brand-600">
                                <?= e($prihlaska['id'] ?? '') ?>
                            </a>
                            <?php $ps = (string) ($prihlaska['stav'] ?? 'nova'); ?>
                            <?php if ($ps === 'zaplaceno'): ?>
                                <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800">Zaplaceno</span>
                            <?php elseif ($ps === 'stornovano'): ?>
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-500">Stornováno</span>
                            <?php else: ?>
                                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">Nezaplaceno</span>
                            <?php endif; ?>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">
                            <?= count((array) ($prihlaska['zavodnici'] ?? [])) ?> závodníků &middot;
                            VS <?= e($prihlaska['variabilni_symbol'] ?? '') ?> &middot;
                            <?= e($prihlaska['vytvoreno'] ?? '') ?>
                        </p>
                    </div>
                    <p class="shrink-0 font-display text-lg font-extrabold tabular-nums tracking-tight"><?= (int) ($prihlaska['castka'] ?? 0) ?> Kč</p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
