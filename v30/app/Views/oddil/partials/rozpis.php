<?php
/**
 * Společný rozpis přihlášky a platební údaje.
 * Očekává: $prihlaska, $platba a (nepovinně) $zobrazStav.
 */
$zavodnici   = (array) ($prihlaska['zavodnici'] ?? []);
$zobrazStav  = $zobrazStav ?? false;
$stav        = (string) ($prihlaska['stav'] ?? 'nova');
?>
<div class="overflow-hidden rounded-md border border-slate-200 bg-white">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 bg-snow px-5 py-3">
        <p class="text-sm font-semibold tabular-nums text-slate-900"><?= e($prihlaska['id'] ?? '') ?></p>
        <div class="flex items-center gap-3">
            <?php if ($zobrazStav): ?>
                <?php if ($stav === 'zaplaceno'): ?>
                    <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Zaplaceno</span>
                <?php elseif ($stav === 'stornovano'): ?>
                    <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500">Stornováno</span>
                <?php else: ?>
                    <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">Nezaplaceno</span>
                <?php endif; ?>
            <?php endif; ?>
            <p class="text-xs text-slate-500"><?= e($prihlaska['vytvoreno'] ?? '') ?></p>
        </div>
    </div>

    <div class="divide-y divide-slate-100">
        <?php foreach ($zavodnici as $zavodnik): ?>
            <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1 px-5 py-3">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-900"><?= e($zavodnik['jmeno'] ?? '') ?></p>
                    <p class="text-xs text-slate-500">
                        <?= e($zavodnik['kategorie_nazev'] ?? '') ?>
                        <?php if (! empty($zavodnik['trat'])): ?>&middot; <?= e($zavodnik['trat']) ?><?php endif; ?>
                        <?php if (! empty($zavodnik['rocnik_narozeni'])): ?>&middot; ročník <?= (int) $zavodnik['rocnik_narozeni'] ?><?php endif; ?>
                    </p>
                </div>
                <p class="shrink-0 text-sm tabular-nums text-slate-600"><?= (int) ($zavodnik['cena'] ?? 0) ?> Kč</p>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="flex items-center justify-between border-t border-slate-200 bg-snow px-5 py-4">
        <p class="text-sm font-medium text-slate-600">Startovné celkem</p>
        <p class="font-display text-xl font-extrabold tabular-nums tracking-tight"><?= (int) ($prihlaska['castka'] ?? 0) ?> Kč</p>
    </div>
</div>

<div class="mt-6 overflow-hidden rounded-md border border-slate-200 bg-white">
    <div class="border-b border-slate-200 bg-snow px-5 py-3">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Platební údaje</p>
    </div>
    <dl class="divide-y divide-slate-100">
        <div class="flex items-baseline justify-between gap-4 px-5 py-3">
            <dt class="text-sm text-slate-500">Částka</dt>
            <dd class="text-sm font-semibold tabular-nums text-slate-900"><?= e($platba['castka']) ?></dd>
        </div>
        <div class="flex items-baseline justify-between gap-4 px-5 py-3">
            <dt class="text-sm text-slate-500">Variabilní symbol</dt>
            <dd class="text-sm font-semibold tabular-nums text-slate-900"><?= e($platba['vs']) ?></dd>
        </div>
        <div class="flex items-baseline justify-between gap-4 px-5 py-3">
            <dt class="text-sm text-slate-500">Poznámka pro příjemce</dt>
            <dd class="text-sm font-semibold tabular-nums text-slate-900"><?= e($platba['poznamka']) ?></dd>
        </div>
        <div class="flex items-baseline justify-between gap-4 px-5 py-3">
            <dt class="text-sm text-slate-500">Číslo účtu</dt>
            <dd class="text-sm font-semibold tabular-nums text-slate-900">
                <?= $platba['ucet'] !== '' ? e($platba['ucet']) : '—' ?>
            </dd>
        </div>
    </dl>

    <?php if (($platba['ucet'] ?? '') === ''): ?>
        <div class="border-t border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-900" role="note">
            <p class="font-semibold">Číslo účtu ještě není zveřejněné.</p>
            <p class="mt-1">
                Pořadatel ho doplní – do té doby prosím platbu neposílejte. Až bude účet známý, dáme vám vědět
                e-mailem a přihlášku najdete i v této zóně.
            </p>
        </div>
    <?php endif; ?>

    <div class="border-t border-slate-200 px-5 py-4">
        <p class="text-xs leading-5 text-slate-500">
            Včasná platba do <?= e($platba['uzavierka']) ?> je za zvýhodněnou cenu. Při platbě na místě se startovné zvyšuje.
        </p>
    </div>
</div>
